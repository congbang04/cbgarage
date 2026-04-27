<?php

namespace App\Services;

use App\Services\PartService;
use App\Services\ServiceService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiUrl, $apiKey;
    protected $partService, $serviceService;

    public function __construct(PartService $partService, ServiceService $serviceService)
    {
        $this->apiKey = config('services.gemini.key');
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$this->apiKey}";

        $this->partService = $partService;
        $this->serviceService = $serviceService;
    }
    private function getPricingContext()
    {
        // Lấy danh sách dịch vụ và phụ tùng
        $services = $this->serviceService->getAll();
        $parts = $this->partService->getAll(100);

        $context = "DANH SÁCH BẢNG GIÁ THAM KHẢO:\n\n";

        $context .= "DỊCH VỤ:\n";
        foreach ($services as $service) {
            $context .= "- {$service->name}: " . number_format($service->price) . " VNĐ\n";
        }

        $context .= "\nPHỤ TÙNG:\n";
        foreach ($parts as $part) {
            $context .= "- {$part->name} (SKU: {$part->sku}): " . number_format($part->price_sell) . " VNĐ\n";
        }

        return $context;
    }
    public function generateChatResponse(array $chatHistory)
    {
        $pricingData = $this->getPricingContext();
        Log::notice($pricingData);
        $contents = [];
        foreach ($chatHistory as $msg) {
            $contents[] = [
                "role" => $msg['role'] === 'bot' ? 'model' : 'user',
                "parts" => [
                    ["text" => $msg['content']]
                ]
            ];
        }
        $payload = [
            "systemInstruction" => [
                "parts" => [[
                    "text" => "Bạn là chuyên gia kỹ thuật và tư vấn viên tại gara 'CBAUTO'.
            
                                NHIỆM VỤ:
                                1. Chẩn đoán hỏng hóc dựa trên mô tả của khách.
                                2. Gợi ý phụ tùng và dịch vụ từ danh sách cung cấp.
                                3. Luôn nhắc nhở: 'Báo giá chỉ mang tính chất tham khảo, cần Kỹ thuật viên kiểm tra trực tiếp'.

                                QUY TẮC ĐỊNH DẠNG (BẮT BUỘC):
                                - Sử dụng Markdown để trình bày câu trả lời.
                                - Khi báo giá, PHẢI sử dụng BẢNG (Table) gồm các cột: Hạng mục, Đơn giá, Ghi chú.
                                - TUYỆT ĐỐI KHÔNG hiển thị mã SKU (ví dụ: P-001, P-002...) trong câu trả lời. Chỉ hiển thị tên phụ tùng/dịch vụ thuần túy.
                                - Đơn giá phải được định dạng có dấu phẩy ngăn cách hàng nghìn và kèm đơn vị VNĐ (ví dụ: 500,000 VNĐ).
                                - Các thuật ngữ kỹ thuật nên viết hoa chữ cái đầu.

                                DANH SÁCH BẢNG GIÁ HỆ THỐNG:
                                {$pricingData}"
                ]]
            ],
            "contents" => $contents,
            "generationConfig" => [
                "temperature" => 0.5,
                "maxOutputTokens" => 4096,
            ]
        ];

        try {
            $response = Http::timeout(90)->withHeaders([
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
