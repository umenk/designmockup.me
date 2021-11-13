<?php

namespace App\Commands\Shuriken;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Surprise extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'surprise';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Pasrah ke chef, run with default options, without interactivity';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('start', ['--omakase' => true]);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
