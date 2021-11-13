<?php

namespace App\Commands\Shuriken;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\File;

class Clean extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'clean
    {--omakase : Pasrah ke chef, run with default options, without interactivity }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Clear database and export files';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->option('omakase')){
            $confirmation = 'Yes';
        }
        else{
            $confirmation = $this->ask('This will wipe out all data including database and export files, are you sure?', 'Yes');
        }

        if($confirmation == 'Yes'){

            $this->task('Clearing database', function(){
                $this->call('db:wipe', ['--force' => true]);
                $this->call('migrate:refresh', ['--force' => true]);
            });

            $this->task('Clearing exports', function(){
                File::cleanDirectory(base_path('exports/'));
            });

            $this->info('👍 All data has been cleared');
        }
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
