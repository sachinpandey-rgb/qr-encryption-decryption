<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Helpers\QrEncryption;
use App\Services\LegacyQrSignatureVerifier;
use App\Services\QrTokenService;
use App\Services\QrVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as Generator;
use Illuminate\Support\Facades\Storage;

class QrController extends Controller
{
    public function __construct(
        private QrVerificationService $verificationService,
        private QrTokenService $tokenService,
        private LegacyQrSignatureVerifier $legacyVerifier,
    ) {}

    public function index()
    {
        $qrs = QrCode::with(['activeToken'])
            ->latest()
            ->get();

        return view('qrcode.index', compact('qrs'));
    }

    public function show(string $uniqueId)
    {
        $qr = QrCode::with('activeToken')
            ->where('unique_id', $uniqueId)
            ->firstOrFail();

        $scanUrl = $this->buildScanUrl($qr);

        return view('qrcode.show', compact('qr', 'scanUrl'));
    }

    public function create()
    {
        return view('qrcode.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_form' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'reward_points' => 'required|integer|min:0',
        ]);

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

        $qrToken = $this->tokenService->generateForProduct($uniqueId);

        $fileName = $uniqueId.'.svg';

        $scanUrl = $this->tokenService->buildVerifyUrl($qrToken->token);

        $svgContent = Generator::format('svg')
                ->size(300)
                ->generate($scanUrl);

        $path = 'qrcodes/'.$fileName;

        Storage::disk('public')->put($path, $svgContent);

        QrCode::create([
            'unique_id' => $uniqueId,
            'encrypted_payload' => $encryptedPayload,
            'qr_image' => 'qrcodes/'.$fileName,
            'expires_at' => now()->addDays(config('qr.expiry_days')),
        ]);

        return redirect()->route('qrcode.show', $uniqueId);
    }

    public function scan(Request $request)
    {
        $productUuid = $this->resolveProductUuidFromRequest($request);

        $result = $this->verificationService->verify($productUuid);

        return view('qrcode.scan', $result);
    }

    public function verify(Request $request)
    {
        $productUuid = $this->resolveProductUuidFromRequest($request);

        if (! $productUuid) {
            return response()->json([
                'success' => false,
                'status' => 'invalid',
                'message' => 'Invalid verification request.',
                'data' => null,
            ], 422);
        }

        $result = $this->verificationService->verify($productUuid);

        return response()->json([
            'success' => $result['status'] === 'verified',
            'status' => $result['status'],
            'message' => $result['message'],
            'data' => $result['status'] === 'verified' ? $result['payload'] : null,
            'scan_count' => $result['record']?->scan_count,
            'first_scanned_at' => $result['record']?->first_scanned_at?->toDateTimeString(),
            'last_scanned_at' => $result['record']?->last_scanned_at?->toDateTimeString(),
            'expires_at' => $result['record']?->expires_at?->toDateTimeString(),
        ], $result['status'] === 'verified' ? 200 : 422);
    }

    private function buildScanUrl(QrCode $qr): string
    {
        if ($qr->activeToken) {
            return $this->tokenService->buildVerifyUrl($qr->activeToken->token);
        }

        return url('/scan?str='.$qr->unique_id);
    }

    private function resolveProductUuidFromRequest(Request $request): ?string
    {
        if ($request->filled('token')) {
            return $this->tokenService->resolveProductUuid($request->query('token'));
        }

        if ($request->filled('u') && $request->filled('s')) {
            $format = (int) $request->query('f', config('qr.legacy_format', 4));

            if (! $this->legacyVerifier->verify(
                $request->query('u'),
                $request->query('s'),
                $format
            )) {
                return null;
            }

            return $request->query('u');
        }

        if ($request->filled('str')) {
            return $request->query('str');
        }

        return null;
    }
}
