<?php

namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Solicitacao\Entities\Motivo;
use Illuminate\Support\Facades\DB;



class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('notificacao:solicitacao')->dailyAt('13:00');
        $schedule->command('importar:usuario')->dailyAt('06:00');
        //$schedule->command('importar:usuario')->dailyAt('14:00');
        // $schedule->command('usuario:acesso')->dailyAt('07:00');
        $schedule->command('importar:cargo_funcao')->dailyAt('01:00');
        $schedule->command('carga:pccr')->dailyAt('00:00');
        $schedule->command('carga:trilha')->dailyAt('00:00');
        $schedule->command('carga:qualificacao')->dailyAt('00:00');
        $schedule->command('carga:certificacao')->dailyAt('00:00');
        $schedule->command('carga:escolaridade')->dailyAt('00:00');
        $schedule->command('carga:niveis-escolaridade')->dailyAt('00:00');

        // Modulo BRB In home nao esta mais sendo utilizado 
        //Schedule - Semanalmente
        //$schedule->command('atividade-ponto:notifica-email',['0'])->cron('0 0 * * 1');
        //Schedule - Mensalmente
        //$schedule->command('atividade-ponto:notifica-email',['1'])->cron('0 0 1 * *');
        //Schedule - Semestral
        //$schedule->command('atividade-ponto:notifica-email',['2'])->cron('0 0 * 6 1');
        //Schedule - Quinzenal - 1
        //$schedule->command('atividade-ponto:notifica-email',['3'])->cron('0 0 15 * *');
        //Schedule - Quinzenal - 2
        //$schedule->command('atividade-ponto:notifica-email',['3'])->cron('0 0 1 * *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}
