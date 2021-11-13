<?xml version='1.0' encoding='UTF-8'?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach(\App\Post::whereNotNull('content')->get() as $post)
<url>
    <loc>{{ $site_url }}{{ $post->slug }}.html</loc>
    <lastmod>{{ $post->published_at->toISOString() }}</lastmod>
</url>
@endforeach
</urlset>
