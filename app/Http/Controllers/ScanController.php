<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Crypt;

class ScanController extends Controller
{
    public function show(Request $request)
    {
        $uniqueId = $request->str;

        $product = Product::where('unique_id', $uniqueId)->first();

        if (!$product) {
            abort(404);
        }

        $data = json_decode(Crypt::decryptString($product->encrypted_data), true);

        return view(
            'scan-result',
            compact('data')
        );
    }
}
