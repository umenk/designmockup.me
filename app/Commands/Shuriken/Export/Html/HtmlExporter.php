<?php

namespace App\Commands\Shuriken\Export\Html;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Jenssegers\Blade\Blade;
use App\Post;
use Illuminate\Support\Str;

class HtmlExporter extends Command
{
    public $blade;
    public $pages;
    public $export_path;
    public $zip;
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'export:html
    {--omakase : Pasrah ke chef, run with default options, without interactivity }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Export site to complete HTML website';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->blade = new Blade(base_path('templates/export/html'), base_path('cache'));
        $this->pages = ['contact', 'copyright', 'dmca', 'privacy-policy'];
        $this->export_path = base_path('exports/' . date('Y-m-d') . '/');
        $this->zip = new \PhpZip\ZipFile();

        $site = [];

        $post = Post::whereNotNull('content')->first();
        $site['url'] = 'domain.com';

        if($this->option('omakase')){
            $site['name'] = $post->keyword;
            $site['description'] = mb_ucwords(collect($post->ingredients['sentences'])->random());
        }
        else{
            $site['name'] = $this->ask('What is the site name?', 'My Awesome Site');
            $site['description'] = $this->ask('What is the site description?', mb_ucwords(collect($post->ingredients['sentences'])->random()));
        }

        if(!File::isDirectory($this->export_path)){
            File::makeDirectory($this->export_path, 0755, true);
        }

        $this->generatePosts($site);
        $this->generatePages($site);
        $this->generateIndex($site);

        $this->zip->saveAsFile($this->export_path . 'html-' . time() . '.zip');
        $this->zip->close();
    }

    public function generateIndex($site)
    {
        $this->task('🗂  Exporting index', function() use ($site){

            $content = $this->blade->render('index', ['posts' => Post::inRandomOrder()->whereNotNull('content')->take(30)->get(), 'site' => $site]);

            $this->zip->addFromString('index.html', $content);
        });
    }

    public function generatePages($site)
    {
        $this->task('🍡 Exporting pages', function() use($site){
            foreach($this->pages as $page){
                $this->task('🍡 Exporting page: ' . $page, function() use($site, $page){
                    $content = $this->blade->render($page, [
                        'site' => $site,
                        'page' => $page
                    ]);

                    $this->zip->addFromString($page . '.html', $content);
                });
            }
        });
    }

    public function generatePosts($site)
    {
        $this->task('🍣 Exporting single posts', function() use ($site){

            foreach(Post::whereNotNull('content')->cursor() as $post){
                $this->task('🍣 Exporting post: ' . $post->slug . '.html', function() use ($post, $site){

                    $content = $this->blade->render('post', [
                        'post' => $post,
                        'site' => $site
                    ]);

                    $this->zip->addFromString($post->slug . '.html', $content);
                });
            }
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
