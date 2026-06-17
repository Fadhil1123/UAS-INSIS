<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppService
{
    private function normalizePhoneNumber(?string $phoneNumber): ?string
    {
        $phoneNumber = preg_replace('/\D+/', '', (string) $phoneNumber);

        if ($phoneNumber === '') {
            return null;
        }

        if (str_starts_with($phoneNumber, '0')) {
            return '62' . substr($phoneNumber, 1);
        }

        if (str_starts_with($phoneNumber, '8')) {
            return '62' . $phoneNumber;
        }

        return $phoneNumber;
    }

    /**
     * Fungsi inti mengirim pesan WA via HTTP client Laravel.
     */
    public function sendMessage($targetPhoneNumber, $message)
    {
        $url = config('services.whatsapp.gateway_url');
        $token = config('services.whatsapp.token');
        $targetPhoneNumber = $this->normalizePhoneNumber($targetPhoneNumber);

        if (empty($url) || empty($token)) {
            Log::error('WhatsApp gateway belum dikonfigurasi di services.whatsapp.');
            return false;
        }

        if (empty($targetPhoneNumber)) {
            Log::warning('WhatsApp target number kosong, pesan tidak dikirim.');
            return false;
        }

        try {
            // Mengirim request POST ke API Gateway WA.
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->timeout(10)->post($url, [
                'target' => $targetPhoneNumber,
                'message' => $message,
            ]);

            if ($response->failed()) {
                Log::error(
                    "WhatsApp Gateway gagal kirim ke {$targetPhoneNumber}. Status: " .
                    $response->status()
                );
                return false;
            }

            Log::info("WhatsApp berhasil dikirim ke {$targetPhoneNumber}");
            return true;

        } catch (Exception $e) {
            // Error handling jika koneksi internet server putus / gateway down.
            Log::critical('Gagal terhubung ke WhatsApp Gateway: ' . $e->getMessage());
            return false;
        }
    }
}