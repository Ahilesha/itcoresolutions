<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public function sendMessage(?string $chatId, string $text): bool
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        if (!$token) {
            Log::warning('Telegram not sent: TELEGRAM_BOT_TOKEN missing');
            return false;
        }

        if (!$chatId) {
            Log::warning('Telegram not sent: chat_id missing');
            return false;
        }

        try {
            $response = Http::timeout(15)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            if (!$response->successful()) {
                Log::error('Telegram sendMessage failed', [
                    'chat_id' => $chatId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            Log::info('Telegram sendMessage success', [
                'chat_id' => $chatId,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Telegram sendMessage exception', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function sendDocument(?string $chatId, string $fileAbsolutePath, string $caption = ''): bool
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        if (!$token) {
            Log::warning('Telegram document not sent: TELEGRAM_BOT_TOKEN missing');
            return false;
        }

        if (!$chatId) {
            Log::warning('Telegram document not sent: chat_id missing');
            return false;
        }

        if (!is_file($fileAbsolutePath)) {
            Log::warning('Telegram document not sent: file missing', [
                'path' => $fileAbsolutePath,
            ]);
            return false;
        }

        try {
            $response = Http::timeout(20)
                ->attach('document', file_get_contents($fileAbsolutePath), basename($fileAbsolutePath))
                ->post("https://api.telegram.org/bot{$token}/sendDocument", [
                    'chat_id' => $chatId,
                    'caption' => $caption,
                    'parse_mode' => 'HTML',
                ]);

            if (!$response->successful()) {
                Log::error('Telegram sendDocument failed', [
                    'chat_id' => $chatId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            Log::info('Telegram sendDocument success', [
                'chat_id' => $chatId,
                'file' => basename($fileAbsolutePath),
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Telegram sendDocument exception', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
