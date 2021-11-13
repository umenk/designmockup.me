<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('keyword')->unique();
            $table->text('templates')->nullable();
            $table->string('category')->nullable();
            $table->boolean('banned')->default(false);
            $table->text('ingredients')->nullable();
            $table->string('title')->nullable();
            $table->text('json_ld')->nullable();
            $table->text('content')->nullable();
            $table->text('content_markdown')->nullable();
            $table->date('published_at')->nullable();
            $table->string('tags')->nullable();
            $table->string('permalink')->nullable();
            $table->string('slug')->nullable();
            $table->integer('parent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
