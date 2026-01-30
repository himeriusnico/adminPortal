<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Institution;

class BlockchainController extends Controller
{
    public function index()
    {
        
        $response = Http::get(
            config('blockchain.api_url') . '/api/blocks'
        );

        if ($response->failed()) {
            return response()->json([
                'error' => 'Gagal mengambil data blockchain'
            ], 500);
        }

        $data = $response->json();

        $mapped = collect($data['blocks'])->map(function ($block) {

            $institution = Institution::find($block['institutionId']);

            return [
                'blockNumber'     => $block['blockNumber'],
                'txId'            => $block['txId'],
                'institutionName' => $institution
                    ? $institution->name
                    : 'Unknown Institution',
                'timestamp'       => $block['timestamp'],
            ];
        });

        
        return response()->json([
            'height' => $data['height'],
            'data'   => $mapped
        ]);
    }
}
