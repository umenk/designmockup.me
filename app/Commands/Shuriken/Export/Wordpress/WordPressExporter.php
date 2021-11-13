<?php

namespace App\Commands\Shuriken\Export\Wordpress;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\File;
use Jenssegers\Blade\Blade;
use Illuminate\Support\Str;


class WordPressExporter extends Command
{
    public $export_path;
    public $blade;
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'export:wordpress';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Export posts to wordpress format';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->export_path = base_path('exports/' . date('Y-m-d') . '/');
        $this->blade = new Blade(base_path('templates/export/wordpress'), base_path('cache'));

        if(!File::isDirectory($this->export_path)){
            File::makeDirectory($this->export_path, 0755, true);
        }

        $this->task('Exporting to WordPress WXR format', function(){
            $content = $this->blade->render('wordpress');

            File::put($this->export_path . 'wordpress-' . time() . '.xml', $content);
        });
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
