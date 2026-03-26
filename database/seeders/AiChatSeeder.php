<?php

namespace Database\Seeders;

use App\Models\AiChatSession;
use App\Models\AiMessage;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class AiChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();

        foreach ($customers as $customer) {

            // Mỗi customer có 1-3 session
            $sessionCount = rand(1, 3);

            for ($s = 0; $s < $sessionCount; $s++) {

                $session = AiChatSession::create([
                    'customer_id' => $customer->id,
                    'title' => fake()->sentence(5),
                    'created_at' => fake()->dateTimeBetween('-1 month', '-1 day'),
                    'updated_at' => now(),
                ]);

                // Mỗi session có 5-15 message
                $messageCount = rand(5, 15);

                for ($i = 0; $i < $messageCount; $i++) {

                    // User message
                    $userMessage = $this->fakeUserMessage();

                    AiMessage::create([
                        'session_id' => $session->id,
                        'sender' => 'user',
                        'content' => $userMessage,
                        'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
                        'updated_at' => now(),
                    ]);

                    // Bot reply
                    AiMessage::create([
                        'session_id' => $session->id,
                        'sender' => 'bot',
                        'content' => $this->fakeBotReply($userMessage),
                        'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function fakeUserMessage()
    {
        $messages = [
            'Xe tôi bị rung khi chạy tốc độ cao',
            'Phanh kêu két két là bị gì?',
            'Xe khó nổ vào buổi sáng',
            'Động cơ có tiếng kêu lạ',
            'Xe bị hao xăng bất thường',
            'Điều hòa không mát',
            'Xe bị lệch lái',
            'Có nên thay nhớt định kỳ bao lâu?',
            'Xe bị giật khi tăng ga',
            'Đèn check engine sáng'
        ];

        return $messages[array_rand($messages)];
    }

    private function fakeBotReply($userMessage)
    {
        $replies = [
            'Có thể xe bạn đang gặp vấn đề về hệ thống lốp hoặc cân bằng động. Bạn nên kiểm tra sớm.',
            'Nguyên nhân có thể do má phanh bị mòn hoặc dính bụi. Bạn nên mang xe đến gara để kiểm tra.',
            'Hiện tượng này thường do bugi hoặc hệ thống nhiên liệu. Nên kiểm tra kỹ.',
            'Đây có thể là dấu hiệu của hỏng hóc động cơ. Bạn nên đến AutoCare để kiểm tra chi tiết.',
            'Xe có thể đang tiêu hao nhiên liệu không hiệu quả. Kiểm tra kim phun và lọc gió.',
            'Bạn nên kiểm tra hệ thống điều hòa và gas lạnh.',
            'Có thể hệ thống lái hoặc lốp có vấn đề. Nên kiểm tra ngay.',
            'Bạn nên bảo dưỡng định kỳ mỗi 5.000 - 10.000 km.',
            'Hiện tượng này có thể do hộp số hoặc bugi.',
            'Đèn check engine có thể liên quan đến nhiều lỗi. Nên dùng máy chẩn đoán.'
        ];

        return $replies[array_rand($replies)];
    }
}
