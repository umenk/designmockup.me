<?php

namespace App\Traits;
use Storage;
use Jenssegers\Blade\Blade;
use App\Post;
use Carbon\Carbon;
use Buchin\GoogleSuggest\GoogleSuggest;
use Buchin\Badwords\Badwords;
use Buchin\SentenceFinder\SentenceFinder;
use Cocur\Slugify\Slugify;

trait PostTrait
{
    public function setPublishDate($post, $options)
    {
        $post->published_at = Carbon::createFromTimestamp(rand($options['end_date']->timestamp, $options['start_date']->timestamp));
        $post->save();
    }

    public function scrapeRelatedKeywords($post)
    {
        $keywords = $this->scrapeRelatedKeyword($post->keyword);

        foreach ($keywords as $keyword) {
            Post::firstOrCreate(
                ['keyword' => $keyword],
                ['parent' => $post->keyword]
            );
        }
    }

    public function scrapeRelatedKeyword($keyword, $tries = 0)
    {
        $keywords = [];

        try {
            $this->task('🍡 Scraping related keywords: ' . $keyword, function() use($keyword, &$keywords){
                $new_keywords = GoogleSuggest::grab($keyword,'', '', 'i');
                $keywords = [];

                if(is_null($new_keywords)){
                    return false;
                }

                foreach($new_keywords as $key => $new_keyword){
                    if(is_null(Post::where('keyword', $new_keyword)->first()) && Badwords::isDirty($new_keyword) === false){
                        $keywords[] = $new_keyword;
                    }
                }

                $keywords = array_values(array_filter($keywords));

                return count($new_keywords);
            });

        } catch (\Exception $e) {
            $this->error('🌊 ' . $e->getMessage());
            if($tries > 3){
                return [];
            }

            $tries++;
            sleep(15*$tries);
            return $this->scrapeRelatedKeyword($keyword, $tries);
        }

        return $keywords;
    }

    public function massInsertKeywords($keywords, $post = null){
        $keywords = collect($keywords)->unique();

        foreach($keywords as $keyword){
            $attributes = is_null($post) ? ['keyword' => $keyword] : ['keyword' => $keyword, 'parent' => $post->keyword];

            Post::firstOrCreate($attributes);
        }
    }

    public function getKeywords($options)
    {
        $source = $options['keyword_source'];
        $keywords = [];

        if($source == '0'){
            $keywords = $this->getKeywordsTxt();
        }
        else{
            $url = 'https://tools.dojo.cc/api/trends/' . $source . '/keywords';

            $keywords = json_decode(file_get_contents($url));
        }

        $this->info(count($keywords) . ' keywords found');

        $keywords = collect($keywords)->map(function($item){
            return mb_strtolower($item, 'UTF-8');
        })->filter(function($item){
            return !Badwords::isDirty($item);
        })->unique()
        ->map(function($item){
            return mb_convert_encoding($item, 'UTF-8');
        })->toArray();

        return $keywords;
    }

    public function clearData($options)
    {
        if($options['clear'] == 'Yes'){
            $this->call('clean', ['--omakase' => true]);
        }
    }

    public function getKeywordsTxt()
    {
        $content = trim(Storage::get('keywords.txt'));
        $content = explode("\n", $content);
        $content = array_values(array_filter($content));

        return $content;
    }

    public function spintax($text)
    {
        return preg_replace_callback(
            '/\[(((?>[^\[\]]+)|(?R))*)\]/x',
            array($this, 'spintaxReplace'),
            $text
        );
    }

    public function spintaxReplace($text)
    {
        $text = $this->spintax($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }

    public function generateTemplates($post)
    {
        foreach ($post->templates as $type => $template) {

            $this->generate($type, $template, $post);
        }
    }

    public function generate($name, $template, $post){
        $this->task('🍣 Generating post ' . $name, function() use ($name, $template, $post){
            $blade = new Blade(base_path('templates'), base_path('cache'));

            $content = $blade->render($name . '.' . $template, ['post' => $post]);
            $content = $this->spintax($content);

            $post->{$name} = trim($content);
            $post->save();
            return true;
        });
    }

    public function showHeader()
    {
        $headers = ['Name', 'Description'];

        $commands = [
            ['name' => $this->signature, 'description' => $this->description]
        ];

        $this->table($headers, $commands);

        $this->ask('Press any key to continue...');
    }

    public function getDefaultOptions()
    {
        return [
            'max_posts' => 1000,
            'category' => 'Uncategorized',
            'clear' => 'Yes',
            'start_date' => Carbon::now()->subYears(1),
            'end_date' => Carbon::now(),
            'keyword_source' => collect($this->source)->filter(function($item, $key){
                return is_string($key);
            })->keys()->random()
        ];
    }

    public function getOptions()
    {
        $options = [];

        $options['max_posts'] = (int)$this->ask('How many posts do you want?', 1000);

        $options['clear'] = $this->choice('Clear previous posts data before running?', ['Yes', 'No'], 'Yes');

        $options['start_date'] = $this->ask('Start date (yyyy-mm-dd)', Carbon::now()->subYears(1)->format('Y-m-d'));
        $options['start_date'] = empty($options['start_date']) ? Carbon::now()->subYears(1) : Carbon::parse($options['start_date']);

        $options['end_date'] = $this->ask('End date (yyyy-mm-dd)', Carbon::now()->format('Y-m-d'));
        $options['end_date'] = empty($options['end_date']) ? Carbon::now() : Carbon::parse($options['end_date']);

        $options['keyword_source'] = $this->choice('Please select keyword source', $this->source, 0);
        $options['category'] = $this->ask('Category', 'Uncategorized');

        return $options;
    }

    public function scrapeSentences($keyword, $tries = 0)
    {
        try{
            $sentences = [];


            $this->task('🥢 Scraping sentences', function() use ($keyword, &$sentences){
                $sentences = (new SentenceFinder)->findSentence($keyword);

                return count($sentences);
            });

            $sentences = collect($sentences)->filter(function($item){
                return !Badwords::isDirty($item);
            })->map(function($item, $key){
                return mb_convert_encoding($item, 'UTF-8', 'UTF-8');
            })->toArray();

            return $sentences;
        }
        catch(\Exception $e){
            $this->error('🌊 ' . $e->getMessage());
            if($tries > 3){
                return [];
            }

            $tries++;
            sleep(15*$tries);
            return $this->scrapeSentences($keyword, $tries);
        }
    }

    public function slugify($post)
    {
        $slugify = new Slugify();
        $post->slug = $slugify->slugify($post->title);
        $post->save();
    }
}
