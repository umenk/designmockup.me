{!! '<' . '?' . "xml version='1.0' encoding='UTF-8'?>" !!}
<ns0:feed xmlns:ns0="http://www.w3.org/2005/Atom">
<ns0:title type="html">wpan.com</ns0:title>
<ns0:generator>Blogger</ns0:generator>
<ns0:link href="http://localhost/wpan" rel="self" type="application/atom+xml" />
<ns0:link href="http://localhost/wpan" rel="alternate" type="text/html" />
<ns0:updated>2016-06-10T04:33:36Z</ns0:updated>

@foreach(App\Post::whereNotNull('content')->cursor() as $post)
	<ns0:entry>
		@foreach($post->whereNotNull('content')->where('parent', $post->keyword)->get() as $related_post)
		<ns0:category scheme="http://www.blogger.com/atom/ns#" term="{{ $related_post->keyword }}" />
		@endforeach
		<ns0:category scheme="http://schemas.google.com/g/2005#kind" term="http://schemas.google.com/blogger/2008/kind#post" />
		<ns0:id>post-{{ $post->id }}</ns0:id>
		<ns0:author>
		<ns0:name>admin</ns0:name>
		</ns0:author>
		<ns0:content type="html">{{ str_replace("\n", ' ', $post->content) }}
		</ns0:content>
		<ns0:published>{{ $post->published_at->format('Y-m-d') }}T{{ $post->published_at->format('H:i:s') }}Z</ns0:published>
		<ns0:title type="html">{{ $post->title }}</ns0:title>
		<ns0:link href="http://localhost/wpan/{{ $post->id }}/" rel="self" type="application/atom+xml" />
		<ns0:link href="http://localhost/wpan/{{ $post->id }}/" rel="alternate" type="text/html" />
	</ns0:entry>
@endforeach

</ns0:feed>