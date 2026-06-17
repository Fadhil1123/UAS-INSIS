<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppService
{
    /**
     * Fungsi Inti Mengirim Pesan WA via HTTP Client Laravel
     */
    public function sendMessage($targetPhoneNumber, $message)
    {
        $url = env('WA_GATEWAY_URL');
        $token = env('WA_GATEWAY_TOKEN');

        try {
            // Mengirim request POST ke API Gateway WA
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->timeout(10)->post($url, [
                'target' => $targetPhoneNumber,
                'message' => $message,
            ]);

            // Cek jika API gateway merespons dengan error
            if ($response->failed()) {
                Log::error("WhatsApp Gateway Gagal Kirim ke {$targetPhoneNumber}. Status: " . $response->status());
                return false;
            }

            Log::info("WhatsApp Berhasil Dikirim ke {$targetPhoneNumber}");
            return true;

        } catch (Exception $e) {
            // Error Handling jika koneksi internet server putus / gateway down
            Log::critical("Gagal terhubung ke WhatsApp Gateway: " . $e->getMessage());
            return false;
        }
    }
}