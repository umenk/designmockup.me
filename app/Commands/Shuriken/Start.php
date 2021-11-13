<?php

namespace App\Commands\Shuriken;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Artisan;
use Illuminate\Support\Str;

class Start extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'start
    {--omakase : Pasrah ke chef, run with default options, without interactivity }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start shuriken cli';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('
_____________               ___________                   _____ __
__  ___/__  /_____  ___________(_)__  /_____________      __  // /
_____ \__  __ \  / / /_  ___/_  /__  //_/  _ \_  __ \     _  // /_
____/ /_  / / / /_/ /_  /   _  / _  ,<  /  __/  / / /     /__  __/
/____/ /_/ /_/\__,_/ /_/    /_/  /_/|_| \___//_/ /_/        /_/   
                                                                  ');
        $this->line(app('git.version') . ' by Internet Marketing Dojo (www.dojo.cc)');

        $commands = collect(Artisan::all())->filter(function($value, $key){
            return Str::contains(get_class($value), 'Shuriken') && $key !== 'start';
        });

        $keys = $commands->keys();

        if($this->option('omakase')){
            $action = $keys->filter(function($item, $key){
                return Str::contains($item, 'import');
            })->random();

            $this->call($action, ['--omakase' => true]);
        }
        else{
            $action = $this->choice('What do you want to do?', $keys->toArray(), 0);
            $this->call($action);
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
