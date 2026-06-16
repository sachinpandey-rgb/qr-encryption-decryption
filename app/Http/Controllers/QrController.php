<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Helpers\QrEncryption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as Generator;
use Illuminate\Support\Facades\Storage;

class QrController extends Controller
{
    public function index()
    {
        $qrs = QrCode::latest()->get();

        return view('qrcode.index', compact('qrs'));
    }

    public function create()
    {
        return view('qrcode.create');
    }

    public function store(Request $request)
    {
        $uniqueId = Str::uuid()->toString();

        $payload = [
            'qr_uid' => 'QR'.str_pad(rand(1,999999),8,'0',STR_PAD_LEFT),

            'raw_token' => hash('sha256', Str::random(50)),

            'product' => [
                'id' => 5,
                'name' => $request->name,
                'company_form' => $request->company_form,
                'company_name' => $request->company_name,
                'price' => $request->price,
                'reward_points' => $request->reward_points,
            ],

            'issued_at' => now()
                ->format('Y-m-d H:i:s')
        ];

        $encryptedPayload =
            QrEncryption::encrypt($payload);


        $fileName = $uniqueId.'.svg';

        $scanUrl = url('/scan?str='.$uniqueId);

        $svgContent = Generator::format('svg')
                ->size(300)
                ->generate($scanUrl);

        $path = 'qrcodes/'.$fileName;

        Storage::put($path, $svgContent);

        QrCode::create([
            'unique_id' => $uniqueId,
            'encrypted_payload' => $encryptedPayload,
            'qr_image' => 'qrcodes/'.$fileName
        ]);

        return redirect('/');
    }

    public function scan(Request $request)
    {
        $record = QrCode::where('unique_id',$request->str)->firstOrFail();

        $payload = QrEncryption::decrypt($record->encrypted_payload);

        return view('qrcode.scan', compact('payload'));
    }
}