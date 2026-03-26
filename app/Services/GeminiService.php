<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiUrl, $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$this->apiKey}";
    }

    public function generateChatResponse(array $chatHistory)
    {
        $contents = [];
        foreach ($chatHistory as $msg) {
            $contents[] = [
                "role" => $msg['role'], // 'user' hoặc 'model'
                "parts" => [
                    ["text" => $msg['content']]
                ]
            ];
        }
        $payload = [
            "systemInstruction" => [
                "parts" => [
                    [
                        "text" => "Bạn là một thợ máy chuyên nghiệp và nhân viên chăm sóc khách hàng tại gara xe 'AutoCare'. 
                        \nNhiệm vụ của bạn là:
                        \n1. Đưa ra chẩn đoán ngắn gọn các vấn đề hỏng hóc của xe dựa trên mô tả.
                        \n2. Đưa ra nguyên nhân dự đoán và lời khuyên an toàn.
                        \n3. Mời khách hàng mang xe đến AutoCare để kiểm tra.
                        \n4. Nếu khách muốn đặt lịch, hãy thu thập đủ: Tên, SĐT, ngày giờ.
                        \nLuôn giữ thái độ lịch sự, chuyên nghiệp và ngắn gọn."
                    ]
                ]
            ],
            "contents" => $contents,
            "generationConfig" => [
                "temperature" => 0.7,
                "maxOutputTokens" => 1024,
            ]
        ];

        try {
            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $message = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Xin lỗi, em không thể xử lý yêu cầu lúc này.";
                return $message;
            }

            Log::error('Gemini API Error: ' . $response->body());
            return "Hệ thống AI đang quá tải, vui lòng thử lại sau ít phút.";
        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return "Đã xảy ra lỗi kết nối với máy chủ AI.";
        }
    }
}
