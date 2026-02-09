<?php

namespace Database\Seeders;

use App\Models\DtrLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TestDtrLogsSeeder extends Seeder
{
    public function run(): void
    {
        $dayUser = User::where('email', 'christy@example.com')->first();
        $nightUser = User::where('email', 'jerwin@example.com')->first();

        // 1. PERFECT DAY (Feb 01)
        $this->seedScenarios($dayUser, $nightUser, '2026-02-01', [
            'day' => ['08:00', '12:00', '13:00', '17:00'],
            'night' => ['20:00', '2026-02-02 00:00', '2026-02-02 01:00', '2026-02-02 05:00'],
        ]);

        //  2. LATE DAY (Feb 02) -
        $this->seedScenarios($dayUser, $nightUser, '2026-02-02', [
            'day' => ['08:10', '12:00', '13:10', '17:00'],
            'night' => ['20:10', '2026-02-03 00:00', '2026-02-03 01:10', '2026-02-03 05:00'],
        ]);

        // 3. INCOMPLETE DAY - Only Morning (Feb 03)
        $this->seedScenarios($dayUser, $nightUser, '2026-02-03', [
            'day' => ['08:00', '12:00'],
            'night' => ['20:00', '2026-02-04 00:00'],
        ]);

        // 4. SHORT SESSION TEST (Feb 04) - duty one hour in morning
        $this->seedScenarios($dayUser, $nightUser, '2026-02-04', [
            'day' => ['08:00', '09:00'],
            'night' => ['20:00', '21:00'],
        ]);
    }

    private function seedScenarios($dayUser, $nightUser, $workDate, $times)
    {
        if ($dayUser) {
            $this->createLogs($dayUser->id, $workDate, $times['day']);
        }
        if ($nightUser) {
            $this->createLogs($nightUser->id, $workDate, $times['night']);
        }
    }

    private function createLogs($userId, $workDate, $times)
    {
        foreach ($times as $index => $time) {
            $recordedAt = (strlen($time) > 5) ? Carbon::parse($time) : Carbon::parse("$workDate $time");

            DtrLog::create([
                'user_id' => $userId,
                'work_date' => $workDate,
                'recorded_at' => $recordedAt,
                'type' => ($index % 2 === 0) ? 1 : 2,
            ]);
        }
    }
}
