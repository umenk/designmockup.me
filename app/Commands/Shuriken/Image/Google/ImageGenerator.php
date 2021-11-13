<?php

namespace App\Commands\Shuriken\Image\Google;

use Illuminate\Console\Scheduling\Schedule;
use App\Commands\Shuriken\Image\Duckduckgo\ImageGenerator as Command;
use Buchin\GoogleImageGrabber\GoogleImageGrabber;


class ImageGenerator extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'import:image:google
    {--omakase : Pasrah ke chef, run with default options, without interactivity }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate content using Google image';

    
    function getIngredients($post)
    {
        $ingredients = $post->ingredients;

        $images = GoogleImageGrabber::grab($post->keyword);

        foreach ($images as $key => $image) {
            $images[$key]['image'] = $image['url'];
        }

        $images = convert_from_latin1_to_utf8_recursively($images);

        $ingredients['images'] = $images;
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
        // $schedule->command(static::class)->everyMinute();
    }
}
