<?php

namespace Database\Seeders;

use App\Models\AiChatSession;
use App\Models\AiMessage;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AiChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');
        $customers = Customer::all();

        foreach ($customers as $customer) {
            // Mỗi customer có 1-3 session
            $sessionCount = rand(1, 3);

            for ($s = 0; $s < $sessionCount; $s++) {
                // Session được tạo từ 60 ngày trước đến 1 ngày trước
                $sessionStartDate = Carbon::now()->subDays(rand(5, 60));

                $session = AiChatSession::create([
                    'customer_id' => $customer->id,
                    'title' => $this->generateSessionTitle(),
                    'created_at' => $sessionStartDate,
                    'updated_at' => $sessionStartDate->copy()->addDays(rand(0, 5)),
                ]);

                // Mỗi session có 4-12 cặp message (user + bot)
                $messageCount = rand(4, 12);
                $messageDate = $sessionStartDate->copy();

                for ($i = 0; $i < $messageCount; $i++) {
                    // User message
                    $userMessage = $this->generateUserMessage();

                    AiMessage::create([
                        'session_id' => $session->id,
                        'sender' => 'user',
                        'content' => $userMessage,
                        'created_at' => $messageDate,
                        'updated_at' => $messageDate,
                    ]);

                    // Bot reply - 1-2 phút sau user message
                    $botReplyDate = $messageDate->copy()->addMinutes(rand(1, 2));

                    AiMessage::create([
                        'session_id' => $session->id,
                        'sender' => 'bot',
                        'content' => $this->generateBotReply($userMessage),
                        'created_at' => $botReplyDate,
                        'updated_at' => $botReplyDate,
                    ]);

                    // Tăng time cho message tiếp theo (3-10 phút)
                    $messageDate = $botReplyDate->copy()->addMinutes(rand(3, 10));
                }
            }
        }
    }

    /**
     * Tạo tiêu đề session thực tế
     */
    private function generateSessionTitle(): string
    {
        $titles = [
            'Hỏi về thay dầu động cơ',
            'Xe bị rung - cần sửa',
            'Đặt lịch bảo dưỡng',
            'Hỏi giá sửa phanh',
            'Vấn đề điều hòa không mát',
            'Hỏi về bảo hành',
            'Kiểm tra check engine',
            'Thay lốp xe',
            'Sửa chữa hộp số',
            'Bảo dưỡng định kỳ 10k',
            'Vệ sinh khoang máy',
            'Thay lọc gió',
            'Kiểm tra hệ thống phanh',
            'Hỏi về giá phụ tùng',
            'Sửa chữa điện oto',
        ];

        return $titles[array_rand($titles)];
    }

    /**
     * Tạo repository của user messages theo category
     */
    private function generateUserMessage(): string
    {
        $messageTypes = [
            // Technical Issues
            'technical' => [
                'Xe tôi bị rung khi chạy tốc độ cao, có sao không?',
                'Phanh kêu kêu là bị gì vậy? Có nguy hiểm không?',
                'Xe khó nổ vào buổi sáng, cần sửa gì?',
                'Động cơ có tiếng kêu lạ lùng, sao vậy?',
                'Xe bị hao xăng bất thường, nguyên nhân là gì?',
                'Điều hòa không mát bình thường nữa',
                'Xe bị lệch lái khi chạy, cần cân chỉnh',
                'Xe bị giật khi tăng ga, chuyện gì vậy?',
                'Đèn check engine sáng liên tục',
                'Tay lái có cảm giác nặng khi chạy chậm',
                'Xe phát hành xanh từ ống xả',
                'Cảm thấy có rò rỉ nước dưới gầm',
            ],
            // Service Booking
            'booking' => [
                'Tôi muốn đặt lịch bảo dưỡng xe tuần tới',
                'Có thể sửa xe vào chiều mai được không?',
                'Xe tôi cần kiểm tra toàn bộ, mấy ngày xong?',
                'Muốn thay dầu, từ mấy giờ có sẵn?',
                'Có thể sửa tại nhà tôi được không?',
                'Đặt lịch thay lốp cho ngày cuối tuần',
                'Bao lâu thì hoàn thành sửa xe?',
                'Liệu có thể sửa cấp tốc được không?',
            ],
            // Pricing
            'pricing' => [
                'Sửa phanh khoảng bao nhiêu tiền vậy?',
                'Giá thay dầu động cơ bao nhiêu?',
                'Bảo dưỡng toàn bộ hết bao nhiêu?',
                'Máy phát điện hỏng, sửa bao nhiêu tiền?',
                'Giá sơn lại mẻ ngoài vỏ bao nhiêu?',
                'Thay ắc quy mới giá chuẩn bao nhiêu?',
                'Sửa điều hòa khoảng bao nhiêu tiền?',
            ],
            // Parts & Products
            'parts' => [
                'Cần tìm lốp xe ô tô chất lượng, có gợi ý không?',
                'Loại dầu động cơ nào tốt cho xe tôi?',
                'Bugi của xe tôi bị hỏng, thay loại nào?',
                'Có nên dùng phụ tùng chính hãng không?',
                'Giá lọc gió bao nhiêu? Có bảo hành không?',
                'Tấm phanh nào tốt và an toàn nhất?',
                'Ắc quy loại nào phù hợp với xe tôi?',
            ],
            // Maintenance Tips
            'maintenance' => [
                'Nên thay dầu định kỳ bao lâu?',
                'Cách bảo quản xe khi không dùng lâu?',
                'Nên kiểm tra túi khí định kỳ bao giờ?',
                'Làm sao để giảm hao xăng xe?',
                'Bảo dưỡng hệ thống treo bao lâu một lần?',
                'Cần rửa xe bao lâu để bảo vệ sơn?',
                'Có nên bảo dưỡng máy lạnh mùa đông không?',
            ],
        ];

        $type = array_rand($messageTypes);
        $messages = $messageTypes[$type];

        return $messages[array_rand($messages)];
    }

    /**
     * Tạo bot replies phù hợp từng loại câu hỏi
     */
    private function generateBotReply($userMessage): string
    {
        // Phân loại message dựa trên keywords
        $message_lower = strtolower($userMessage);

        // Technical replies
        if ($this->containsAnyKeyword($message_lower, ['rung', 'kêu', 'lạ', 'check engine', 'hỏng', 'sao'])) {
            $replies = [
                'Tình trạng này có thể do hệ thống treo hoặc bánh xe. Tôi khuyến nghị đưa xe tới kiểm tra ngay để tránh ảnh hưởng đến an toàn.',
                'Nguyên nhân có thể do bugi, lọc gió bị bẩn, hoặc hệ thống nhiên liệu. Hãy để kỹ thuật viên kiểm tra chi tiết.',
                'Đây có thể là tín hiệu của vấn đề động cơ. Bạn nên sử dụng máy chẩn đoán để xác định lỗi chính xác.',
                'Hiện tượng này thường liên quan đến hệ thống phanh, treo hoặc bánh xe. Vui lòng mang xe đến kiểm tra sớm.',
                'Có thể liên quan đến dầu động cơ, bugi, hoặc bộ lọc. Tôi đề xuất thực hiện kiểm tra toàn bộ hệ thống.',
            ];
        }
        // Booking replies
        elseif ($this->containsAnyKeyword($message_lower, ['đặt lịch', 'sửa', 'ngày', 'giờ', 'thời gian', 'xong', 'bao lâu'])) {
            $replies = [
                'Vâng, tôi có thể giúp bạn đặt lịch. Bạn muốn loại dịch vụ nào? Chi tiết liên hệ của bạn là gì?',
                'Tất nhiên! Bạn vui lòng cho biết dịch vụ cần và thời gian phù hợp. Chi nhánh nào gần nhất với bạn?',
                'Được rồi, tôi sẽ ghi nhận yêu cầu của bạn. Dịch vụ này thường mất khoảng 2-4 giờ tùy tình trạng xe.',
                'Chúng tôi có lịch trống. Bạn vui lòng cung cấp các thông tin chi tiết để tôi xác nhận lịch hẹn.',
                'Tôi sẽ chuyển yêu cầu này tới chi nhánh gần nhất. Thường sửa chữa cơ bản hoàn thành trong 1-2 ngày.',
            ];
        }
        // Pricing replies
        elseif ($this->containsAnyKeyword($message_lower, ['bao nhiêu', 'giá', 'tiền', 'chi phí', 'giá cả'])) {
            $replies = [
                'Giá sẽ tùy thuộc vào loại xe và tình trạng hiện tại. Tôi cần biết chi tiết xe của bạn để báo giá chính xác.',
                'Có thể cho tôi biết model xe của bạn không? Vậy tôi sẽ tính giá dịch vụ này cho bạn.',
                'Giá các dịch vụ dao động trong khoảng khá rộng. Bạn vui lòng mô tả chi tiết để tôi báo giá.',
                'Giá sẽ rẻ hơn nếu bạn chọn phụ tùng không chính hãng. Nên dùng chính hãng để bảo hành tốt hơn.',
                'Tôi không thể báo giá chính xác qua tin nhắn. Hãy đưa xe tới để kỹ thuật viên kiểm tra và báo giá.',
            ];
        }
        // Parts/Products replies
        elseif ($this->containsAnyKeyword($message_lower, ['phụ tùng', 'lốp', 'dầu', 'ắc quy', 'bugi', 'lọc'])) {
            $replies = [
                'Chúng tôi có sẵn nhiều loại phụ tùng chất lượng. Bạn muốn dùng hãng nào? Chính hãng hay phụ kiện?',
                'Các loại phụ tùng này có sẵn ở cửa hàng. Tôi có thể giới thiệu những sản phẩm tốt và giá hợp lý.',
                'Phụ tùng chính hãng sẽ đắt hơn nhưng có tính bền và bảo hành tốt. Phụ kiện rẻ hơn nhưng chất lượng có hạn.',
                'Tôi khuyên bạn nên chọn hãng uy tín để đảm bảo chất lượng và tuổi thọ phụ tùng.',
                'Có rất nhiều lựa chọn. Tùy vào nhu cầu và ngân sách của bạn. Bạn muốn được tư vấn thêm?',
            ];
        }
        // Maintenance tips replies
        elseif ($this->containsAnyKeyword($message_lower, ['bảo dưỡng', 'định kỳ', 'cách', 'bao lâu', 'nên'])) {
            $replies = [
                'Bảo dưỡng định kỳ rất quan trọng! Thông thường nên bảo dưỡng cứ 5.000-10.000km hoặc 3-6 tháng.',
                'Để xe kéo dài tuổi thọ, bạn nên bảo dưỡng đều đặn theo hướng dẫn của hãng sản xuất.',
                'Bảo dưỡng định kỳ sẽ tiết kiệm chi phí sửa chữa về sau. Tôi khuyên bạn không nên bỏ qua.',
                'Nên kiểm tra dầu, lọc gió, lốp, và phanh định kỳ. Điều này sẽ giúp xe chạy an toàn hơn.',
                'Bảo dưỡng đúng cách sẽ giảm hao xăng và giảm tiếng ồn của động cơ, cải thiện hiệu năng.',
            ];
        }
        // Default replies
        else {
            $replies = [
                'Tôi hiểu vấn đề của bạn. Bạn muốn được hỗ trợ gì? Tôi sẽ cố gắng giúp.',
                'Cảm ơn câu hỏi của bạn. Để hỗ trợ tốt hơn, bạn có thể cho biết thêm chi tiết không?',
                'Vâng, tôi đã ghi nhận. Hãy cho tôi biết thêm để tôi cung cấp giải pháp tốt nhất.',
                'Tôi sẽ tìm cách giúp bạn. Bạn muốn nói chuyện với kỹ thuật viên không?',
                'Biết rồi! Bạn vui lòng đợi tôi tìm thông tin chi tiết để trả lời bạn.',
            ];
        }

        return $replies[array_rand($replies)];
    }

    /**
     * Helper function: kiểm tra nếu string chứa bất kỳ keyword nào trong array
     */
    private function containsAnyKeyword(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }
        return false;
    }
}
