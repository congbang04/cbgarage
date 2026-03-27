<?php

namespace App\Services;

use App\Models\AiChatSession;
use App\Models\AiMessage;
use Carbon\Carbon;

class AiLogService
{
    /**
     * Lấy danh sách Sessions có kèm tìm kiếm và lọc
     */
    public function getSessions($search = null, $date = null)
    {
        $query = AiChatSession::with('customer')->orderBy('updated_at', 'desc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
            });
            $query->limit(50);
        } else {
            $filterDate = $date ? Carbon::parse($date) : Carbon::today();
            $query->whereDate('updated_at', $filterDate);
        }

        return $query->get()->map(function ($session) {

            if ($session->updated_at->isToday()) {
                $timeDisplay = $session->updated_at->format('H:i'); // Hôm nay thì chỉ hiện giờ
            } elseif ($session->updated_at->isYesterday()) {
                $timeDisplay = 'Hôm qua ' . $session->updated_at->format('H:i');
            } else {
                $timeDisplay = $session->updated_at->format('d/m/Y');
            }

            $userName = $session->customer ? $session->customer->name : 'Khách hàng';

            return [
                'id' => $session->id,
                'title' => $session->title,
                'user_name' => $userName,
                'time' => $timeDisplay,
            ];
        });
    }

    /**
     * Lấy nội dung chi tiết tin nhắn của 1 Session
     */
    public function getSessionMessages($sessionId)
    {
        $session = AiChatSession::with('customer')->findOrFail($sessionId);

        $messages = AiMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        $userName = $session->customer ? $session->customer->name : 'Khách hàng';

        return [
            'session_info' => [
                'id' => $session->id,
                'name' => $userName,
                'title' => $session->title
            ],
            'messages' => $messages
        ];
    }

    /**
     * Xóa Session
     */
    public function deleteSession($sessionId)
    {
        $session = AiChatSession::findOrFail($sessionId);
        return $session->delete();
    }
}
