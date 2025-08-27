<?php

namespace App\Http\Controllers\Users;

use App\Services\Users\AttendanceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\AttendanceRequest;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected $service;
    protected $attendanceRequest;

    public function __construct(AttendanceService $service, AttendanceRequest $attendanceRequest)
    {
        $this->service = $service;
    }

    /**
     * Rapport global pour tous les utilisateurs sur une période
     *
     * @param AttendanceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function report(AttendanceRequest $request)
    {
        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();

        $report = $this->service->getReport($start, $end);

        return response()->json($report);
    }

    /**
     * Rapport pour un utilisateur spécifique sur une période
     *
     * @param AttendanceRequest $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function userReport(AttendanceRequest $request, int $userId)
    {
        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();

        $report = $this->service->getUserReport($userId, $start, $end);

        return response()->json($report);
    }
}
