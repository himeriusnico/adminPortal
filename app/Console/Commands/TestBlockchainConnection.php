<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestBlockchainConnection extends Command
{
    protected $signature = 'blockchain:test';
    protected $description = 'Test connection from Laravel to Hyperledger Fabric REST API';

    public function handle()
    {
        $url = config('blockchain.api_url') . '/testblockchainconnection';

        $this->info("Testing connection to: $url");

        try {
            $response = Http::timeout(5)->get($url);

            if ($response->successful()) {
                $this->info("✔ SUCCESS — server.js reachable!");
                $this->line("Response: " . $response->body());
            } else {
                $this->error("✖ FAILED — Received non-200 response");
                $this->line("Status: " . $response->status());
                $this->line($response->body());
            }
        } catch (\Throwable $e) {
            $this->error("❌ ERROR — cannot connect to server.js");
            $this->line("Reason: " . $e->getMessage());
        }
    }
}
