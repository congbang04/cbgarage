<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AiLogService;

class AiLogController extends Controller
{
    protected $aiLogService;

    public function __construct(AiLogService $aiLogService)
    {
        $this->aiLogService = $aiLogService;
    }

    public function index()
    {
        return view('admin.settings.ai-logs');
    }

    public function getSessions(Request $request)
    {
        $sessions = $this->aiLogService->getSessions($request->search, $request->date);

        return response()->json(['sessions' => $sessions]);
    }

    public function getMessages($sessionId)
    {
        $data = $this->aiLogService->getSessionMessages($sessionId);

        return response()->json($data);
    }

    public function destroy($id)
    {
        try {
            $this->aiLogService->deleteSession($id);
            return response()->json(['status' => 'success', 'message' => 'Đã xóa log hội thoại']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể xóa log này.'], 500);
        }
    }
}
