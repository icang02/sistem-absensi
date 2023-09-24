<?php

namespace App\Console;

use App\Models\Absen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use function PHPUnit\Framework\isNull;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            $user = User::select('id')->get(); // [1, 2]

            foreach ($user as $item) {
                $tanggalSekarang = Carbon::now()->subDay()->format('Y-m-d');
                $cek = Absen::where('user_id', $item->id)->where('tanggal', $tanggalSekarang)->get()->first();

                if ($cek == null) {
                    Absen::create([
                        'user_id' => $item->id,
                        'waktu_absen' => '-',
                        'tanggal' => $tanggalSekarang,
                        'keterangan' => 'Tidak Hadir',
                    ]);
                }
            }
        })->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
