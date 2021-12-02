<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\kota;
use Illuminate\Support\Facades\Validator;
use Response;
class CekOngkir extends Controller
{
    public function getOngkir(Request $request) {
        $credentials = $request->all();
        $validator = Validator::make($credentials, [
            'kota' => 'required',
            'berat' => 'required',
            'kurir' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 400);
        }
        $kotapengirim = 501; // Origin kota Yogyakarta
        $kotatujuan = $request->kota;
        $berat = ($request->berat*1000); // Karena produknya ga punya berat, maka default 1700
        $kurir = $request->kurir; 
        
        $response = Http::asForm()->withHeaders([
            'key' => 'b891c30147a00276f0e7c65836414217'
        ])->post('https://api.rajaongkir.com/starter/cost', [
            'origin' => $kotapengirim,
            'destination' => $kotatujuan,
            'weight' => $berat,
            'courier' => $kurir

        ]);
        
        $cekongkir = $response['rajaongkir']['results'][0]['costs'];
        return $cekongkir;
    }
}
