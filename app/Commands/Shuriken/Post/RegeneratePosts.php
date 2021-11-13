<?php

namespace App\Commands\Shuriken\Post;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Post;
use App\Traits\PostTrait;

class RegeneratePosts extends Command
{
    use PostTrait;
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'regenerate:posts';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate ulang semua post sehingga scrape sekali bisa digunakan banyak kali';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->task('🏭 Regenerating posts', function(){
            $post_count = Post::whereNotNull('content')->count();

            foreach(Post::whereNotNull('content')->cursor() as $post){
                $this->generateTemplates($post);
                $this->info('🍱 Processing #' . $post->id . ': "' . $post->title . '" of total ' . $post_count . ' keywords');
                $this->line('');
            }
        });

        $this->info('All posts regenerated, you may export now');
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
