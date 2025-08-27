<?php


namespace App\Repositories\Users;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceRepository
{
    /**
     * Crée un nouveau pointage (heure d'entrée)
     */
    public function startSession(int $userId): Attendance
    {
        return Attendance::create([
            'user_id' => $userId,
            'start_time' => Carbon::now(),
        ]);
    }


    /**
     * Termine le pointage (heure de sortie) et calcule la durée
     */

    public function endSession(int $userId): ?Attendance
    {
        // Récupère la dernière session ouverte
        $attendance = Attendance::where('user_id', $userId)
            ->whereNull('end_time')
            ->latest('start_time')
            ->first();

        if (!$attendance) {
            return null;
        }

        // Définir l'heure de fin
        $endTime = Carbon::now();
        $attendance->end_time = $endTime;

        // Calcul des minutes
        $minutes = $attendance->start_time->diffInMinutes($endTime);

        // Conversion en heures décimales
        $attendance->worked_hours = round($minutes / 60, 2);

        $attendance->save();

        return $attendance;
    }


    /**
     * Récupère tous les pointages sur une période
     *
     * @param Carbon $start Début de la période
     * @param Carbon $end Fin de la période
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByPeriod(Carbon $start, Carbon $end)
    {
        return Attendance::with('user')
            ->whereBetween('start_time', [$start, $end])
            ->get();
    }

    /**
     * Récupère les pointages d'un utilisateur spécifique sur une période
     *
     * @param int $userId
     * @param Carbon $start
     * @param Carbon $end
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserByPeriod(int $userId, Carbon $start, Carbon $end)
    {
        return Attendance::with('user')
            ->where('user_id', $userId)
            ->whereBetween('start_time', [$start, $end])
            ->get();
    }
}
