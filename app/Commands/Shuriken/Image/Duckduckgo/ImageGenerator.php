<?php

namespace App\Commands\Shuriken\Image\Duckduckgo;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Post;
use App\Traits\PostTrait;
use App\Traits\HookTrait;
use Buchin\SentenceFinder\SentenceFinder;
use Jenssegers\Blade\Blade;
use Carbon\Carbon;
use Buchin\GoogleSuggest\GoogleSuggest;
use ReflectionClass;


class ImageGenerator extends Command
{
    use PostTrait, HookTrait;

    public $source = [
        0 => 'keywords.txt',
        'p19' => 'Google Trends Indonesia',
        'p40' => 'Google Trends Afrika Selatan',
        'p1' => 'Google Trends Amerika Serikat',
        'p36' => 'Google Trends Arab Saudi',
        'p30' => 'Google Trends Argentina',
        'p8' => 'Google Trends Australia',
        'p44' => 'Google Trends Austria',
        'p17' => 'Google Trends Belanda',
        'p41' => 'Google Trends Belgia',
        'p18' => 'Google Trends Brasil',
        'p38' => 'Google Trends Cile',
        'p49' => 'Google Trends Denmark',
        'p25' => 'Google Trends Filipina',
        'p50' => 'Google Trends Finlandia',
        'p10' => 'Google Trends Hong Kong',
        'p45' => 'Google Trends Hungaria',
        'p3' => 'Google Trends India',
        'p9' => 'Google Trends Inggris',
        'p6' => 'Google Trends Israel',
        'p27' => 'Google Trends Italia',
        'p4' => 'Google Trends Jepang',
        'p15' => 'Google Trends Jerman',
        'p13' => 'Google Trends Kanada',
        'p37' => 'Google Trends Kenya',
        'p32' => 'Google Trends Kolombia',
        'p23' => 'Google Trends Korea Selatan',
        'p34' => 'Google Trends Malaysia',
        'p21' => 'Google Trends Meksiko',
        'p29' => 'Google Trends Mesir',
        'p52' => 'Google Trends Nigeria',
        'p51' => 'Google Trends Norwegia',
        'p31' => 'Google Trends Polandia',
        'p47' => 'Google Trends Portugal',
        'p16' => 'Google Trends Prancis',
        'p43' => 'Google Trends Republik Cheska',
        'p39' => 'Google Trends Rumania',
        'p14' => 'Google Trends Rusia',
        'p5' => 'Google Trends Singapura',
        'p26' => 'Google Trends Spanyol',
        'p42' => 'Google Trends Swedia',
        'p46' => 'Google Trends Swiss',
        'p12' => 'Google Trends Taiwan',
        'p33' => 'Google Trends Thailand',
        'p24' => 'Google Trends Turki',
        'p35' => 'Google Trends Ukraina',
        'p28' => 'Google Trends Vietnam',
        'p48' => 'Google Trends Yunani',
    ];

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'import:image:duckduckgo
    {--omakase : Pasrah ke chef, run with default options, without interactivity }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate content using Duckduckgo image';

    public function path()
    {
        $class_info = new ReflectionClass($this);
        return dirname($class_info->getFileName());
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        if($this->option('omakase')){
            $options = $this->getDefaultOptions();
        }
        else{
            $options = $this->getOptions();
        }


        $keywords = $this->getKeywords($options);
        
        $this->clearData($options);

        $this->massInsertKeywords($keywords);

        $this->line('');

        while($post = Post::whereNull('content')->where('banned', false)->first()){
            $post->category = $options['category'];
            
            $post->templates = [
                'title' => 'image',
                'content' => 'image',
                'json_ld' => 'image',
            ];

            $post_count = Post::count();
            $keyword = $post->keyword;

            $this->info('🍱 Processing #' . $post->id . ': "' . $keyword . '" of total ' . $post_count . ' keywords');

            if($post_count < $options['max_posts']){
                $this->scrapeRelatedKeywords($post);
            }

            $this->setPublishDate($post, $options);

            if($post->ingredients = $this->getIngredients($post)){
                $post->save();

                $this->generateTemplates($post);
                
                $this->slugify($post);
                
                $this->after_create($post);
                $this->after_post($post);
            }
            else{
                $this->task('⚔️  Deleting post because of insufficient ingredients', function() use ($post){
                    $post->banned = true;
                    $post->save();
                    return true;
                });
            }

            $this->line('');

            sleep(1);
        }

        $this->task('Generating export data', function(){
            $this->call('export:html', ['--omakase' => true]);
            $this->call('export:wordpress');
            $this->call('export:blogger');
        });

        $this->info('☕️ Alhamdulillah. It\'s Finished');
    }

    function getIngredients($post)
    {
        $ingredients = $post->ingredients;

        $ingredients['images'] = $this->scrapeImages($post->keyword);
        $ingredients['sentences'] = $this->scrapeSentences($post->keyword);

        if(count($ingredients['images']) && count($ingredients['sentences'])){
            return $ingredients;
        }

        return false;
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->daily();
    }

    public function getToken($query)
    {
        $url = 'https://duckduckgo.com/?'.http_build_query(array(
            'q'=> $query,
            't'=> 'h_',
            'iax'=> 'images',
            'ia'=> 'images'
        ));

        $html = http()->get($url)->body();

        // vqd='3-142216309694420347760037715005911496568-220342727435306949816443430106535095457';
        $vqd_token = '';
        if(!preg_match("/vqd\s*\=\s*\'(?<vqd_token>[^\']*)/",$html,$matches)){
            throw new \Exception('Error: Banned IP. We will rest for a bit');
        }

        $vqd_token=$matches['vqd_token'];

        return $vqd_token;
    }

    public function getImages($query)
    {
        $vqd_token = $this->getToken($query);
        $url = 'https://duckduckgo.com/i.js?l=wt-wt&o=json&q=' . urlencode($query) .  '&vqd=' . $vqd_token . '&f=,,,&p=1&v7exp=a&sltexp=b';

        $html = file_get_contents($url);
        // "results":[{"title":"Nintendo 64 controller

        if(!preg_match('/"results":(?<images_json>.+?\}\])/m',$html,$matches)){
            throw new \Exception('Error: unable to extract images json...');
        }

        $images_json=$matches["images_json"];
        $images=json_decode($images_json,true);

        foreach ($images as $key => $image) {
            $images[$key]['url'] = $image['image'];
        }

        return $images;
    }

    public function scrapeImages($keyword, $tries = 0)
    {
        try{
            $images = [];
            $this->task('🥢 Scraping images', function() use ($keyword, &$images){
                $images = $this->getImages($keyword);

                return count($images);
            });

            return $images;
        }
        catch(\Exception $e){
            $this->error('🌊 ' . $e->getMessage());
            if($tries > 3){
                return [];
            }

            $tries++;
            sleep(15*$tries);
            return $this->scrapeImages($keyword, $tries);
        }

    }
}
