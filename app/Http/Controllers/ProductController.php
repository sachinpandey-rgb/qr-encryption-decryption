<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();

        return view(
            'products.index',
            compact('products')
        );
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $uniqueId = Str::uuid()->toString();

        $productData = [
            'product_name' => $request->product_name,
            'sku' => $request->sku,
            'price' => $request->price,
            'description' => $request->description,
        ];

        $encryptedData = Crypt::encryptString(
            json_encode($productData)
        );

        $scanUrl = url('/scan?str='.$uniqueId);
      
        $fileName = $uniqueId.'.svg';

        $svgContent = QrCode::format('svg')
                ->size(300)
                ->generate($scanUrl);

        $path = 'qrcodes/'.$fileName;

        Storage::put($path, $svgContent);
        

        Product::create([
            'unique_id' => $uniqueId,
            'product_name' => $request->product_name,
            'sku' => $request->sku,
            'price' => $request->price,
            'description' => $request->description,
            'encrypted_data' => $encryptedData,
            'qr_image' => $path
        ]);

        return redirect('/');
    }
}
