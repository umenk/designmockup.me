<?php

namespace App\Commands\Shuriken\Export\Sitemap;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Post;
use Illuminate\Support\Facades\File;

class SitemapExporter extends Command
{
    public $export_path;
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'export:sitemap';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Export all post slug to sitemap.xml';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $export_dir = base_path('exports/' . date('Y-m-d'));
        $this->export_path = $export_dir . '/sitemap-' . time() . '.xml';

        if(!File::isDirectory($export_dir)){
            File::makeDirectory($export_dir, 0755, true);
        }

        $this->task('🗂  Exporting sitemap.xml', function(){
            $site_url = $this->ask('Site URL (Start with http, end with /, i.e: https://domain.com', 'https://domain.com/');
            $content = blade(dirname(__FILE__))->render('sitemap', ['site_url' => $site_url]);

            File::put($this->export_path, $content);
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
