@php
use Buchin\GoogleImageGrabber\GoogleImageGrabber;

$default_author = fake()->name;
$site_url = 'http://example.com/';
@endphp
{!! '<' . '?' . 'xml version="1.0" encoding="UTF-8"' . '?' . '>' !!}
<rss version="2.0"
	xmlns:excerpt="http://wordpress.org/export/1.0/excerpt/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:wp="http://wordpress.org/export/1.0/"
>

<channel>
	<title>My Site</title>
	<link>http://example.com/</link>
	<description></description>
	<pubDate>Thu, 28 May 2009 16:06:40 +0000</pubDate>
	<wp:author><wp:author_id>1</wp:author_id><wp:author_login><![CDATA[admin]]></wp:author_login><wp:author_email><![CDATA[buchin@dropsugar.com]]></wp:author_email><wp:author_display_name><![CDATA[admin]]></wp:author_display_name><wp:author_first_name><![CDATA[]]></wp:author_first_name><wp:author_last_name><![CDATA[]]></wp:author_last_name></wp:author>
	
	<generator>http://wordpress.org/?v=2.7.1</generator>
	<language>en</language>
	<wp:wxr_version>1.0</wp:wxr_version>
	<wp:base_site_url>http://example.com/</wp:base_site_url>
	<wp:base_blog_url>http://example.com/</wp:base_blog_url>

	@foreach(App\Post::whereNotNull('content')->cursor() as $post)
		@php
			$featured_image = collect($post->ingredients['images'])->shuffle()->random();
			$thumbnail_id = 1000000+$post->id;
		@endphp
		<item>
			<title><![CDATA[{{ $post->title }}]]></title>
			
			<link>{{ $site_url }}{{ $post->slug }}/</link>
			<pubDate>{{ $post->published_at->format('D, d M Y H:i:s') }} +0000</pubDate>

			<dc:creator><![CDATA[{{ $default_author }}]]></dc:creator>
			<wp:postmeta>
				<wp:meta_key>_byline</wp:meta_key>
				<wp:meta_value>{{ $default_author }}</wp:meta_value>
			</wp:postmeta>

			<category><![CDATA[{{ $post->category }}]]></category>
			<category domain="category" nicename="{{ slugify($post->category) }}"><![CDATA[{{ $post->category }}]]></category>
			
			@foreach ( $post->whereNotNull('content')->where('parent', $post->keyword)->get() as $related_post )
				<category domain="tag" nicename="{{ slugify($related_post->keyword) }}"><![CDATA[{{ $related_post->keyword }}]]></category>
			@endforeach
			
		
			<guid isPermaLink="false">{{ $site_url }}?p={{ $post->id }}</guid>
			<description></description>
			<content:encoded><![CDATA[{!! $post->content !!}]]></content:encoded>
			<excerpt:encoded><![CDATA[]]></excerpt:encoded>
			<wp:post_id>{{ $post->id }}</wp:post_id>
			<wp:post_date>{{ $post->published_at->format('Y-m-d H:i:s') }}</wp:post_date>
			<wp:post_date_gmt>{{ $post->published_at->format('Y-m-d H:i:s') }}</wp:post_date_gmt>
			<wp:comment_status>open</wp:comment_status>
			<wp:ping_status>closed</wp:ping_status>
			<wp:post_name>{{ $post->slug }}</wp:post_name>

			<wp:status>publish</wp:status>
			<wp:post_parent>0</wp:post_parent>
			<wp:menu_order>0</wp:menu_order>
			<wp:post_type>post</wp:post_type>
			<wp:post_password></wp:post_password>
			
			<wp:postmeta>
				<wp:meta_key>_old_id</wp:meta_key>
				<wp:meta_value>{{ $post->id }}</wp:meta_value>
			</wp:postmeta>
			@if(!empty($post->json_ld))
			<wp:postmeta>
				<wp:meta_key><![CDATA[json_ld]]></wp:meta_key> 
				<wp:meta_value><![CDATA[{!! $post->json_ld !!}]]></wp:meta_value>
			</wp:postmeta>
			@endif

			<wp:postmeta>
				<wp:meta_key><![CDATA[_thumbnail_id]]></wp:meta_key>
				<wp:meta_value><![CDATA[{{ $thumbnail_id }}]]></wp:meta_value>
			</wp:postmeta>
		</item>

		<item>
			<title>{{ $post->keyword }}</title>
			<link>{{ $site_url }}?attachment_id={{ $thumbnail_id }}</link>
			<pubDate>Sun, 27 Sep 2020 19:49:11 +0000</pubDate>
			<dc:creator><![CDATA[admin]]></dc:creator>
			<guid isPermaLink="false">{{ $featured_image['image'] }}</guid>
			<description></description>
			<content:encoded><![CDATA[]]></content:encoded>
			<excerpt:encoded><![CDATA[]]></excerpt:encoded>
			<wp:post_id>{{ $thumbnail_id }}</wp:post_id>
			<wp:post_date><![CDATA[{{ $post->published_at->format('Y-m-d H:i:s') }}]]></wp:post_date>
			<wp:post_date_gmt><![CDATA[{{ $post->published_at->format('Y-m-d H:i:s') }}]]></wp:post_date_gmt>
			<wp:comment_status><![CDATA[open]]></wp:comment_status>
			<wp:ping_status><![CDATA[closed]]></wp:ping_status>
			<wp:post_name><![CDATA[{{ $post->keyword }}]]></wp:post_name>
			<wp:status><![CDATA[inherit]]></wp:status>
			<wp:post_parent>{{ $post->id }}</wp:post_parent>
			<wp:menu_order>0</wp:menu_order>
			<wp:post_type><![CDATA[attachment]]></wp:post_type>
			<wp:post_password><![CDATA[]]></wp:post_password>
			<wp:is_sticky>0</wp:is_sticky>
							<wp:attachment_url><![CDATA[{{ $featured_image['image'] }}]]></wp:attachment_url>
												<wp:postmeta>
			<wp:meta_key><![CDATA[_wp_attached_file]]></wp:meta_key>
			<wp:meta_value><![CDATA[{{ $post->published_at->format('Y/m') }}/{{ slugify($post->keyword) }}.{{ GoogleImageGrabber::getFileType($featured_image['image']) }}]]></wp:meta_value>
			</wp:postmeta>
		</item>
		
	@endforeach

</channel>
</rss>