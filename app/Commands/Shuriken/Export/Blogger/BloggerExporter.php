<?php

namespace App\Commands\Shuriken\Export\Blogger;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\File;
use Jenssegers\Blade\Blade;
use Illuminate\Support\Str;

class BloggerExporter extends Command
{
    public $export_path;
    public $blade;
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'export:blogger';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Export posts to blogger format';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->export_path = base_path('exports/' . date('Y-m-d') . '/');
        $this->blade = new Blade(base_path('templates/export/blogger'), base_path('cache'));

        if(!File::isDirectory($this->export_path)){
            File::makeDirectory($this->export_path, 0755, true);
        }

        $this->task('Exporting to Blogger XML format', function(){
            $content = $this->blade->render('blogger');

            File::put($this->export_path . 'blogger-' . time() . '.xml', $content);
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
