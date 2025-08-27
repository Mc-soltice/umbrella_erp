<?php

namespace App\Services\Users;

use App\Repositories\Users\AttendanceRepository;
use Carbon\Carbon;

class AttendanceService
{
    protected $repository;

    public function __construct(AttendanceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Début du pointage
     */
    public function startSession(int $userId)
    {
        return $this->repository->startSession($userId);
    }

    /**
     * Fin du pointage
     */
    public function endSession(int $userId)
    {
        return $this->repository->endSession($userId);
    }

    /**
     * Rapport global pour tous les utilisateurs
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return array
     */
    public function getReport(Carbon $start, Carbon $end): array
    {
        $attendances = $this->repository->getByPeriod($start, $end);

        // Regroupe par utilisateur
        return $attendances->groupBy('user_id')->map(function ($sessions) {
            return [
                'total_hours' => round($sessions->sum('worked_hours'), 2),
                'sessions' => $sessions,
            ];
        })->toArray();
    }

    /**
     * Rapport pour un utilisateur spécifique
     *
     * @param int $userId
     * @param Carbon $start
     * @param Carbon $end
     * @return array
     */
    public function getUserReport(int $userId, Carbon $start, Carbon $end): array
    {
        $sessions = $this->repository->getUserByPeriod($userId, $start, $end);

        return [
            'total_hours' => round($sessions->sum('worked_hours'), 2),
            'sessions' => $sessions,
        ];
    }
}
