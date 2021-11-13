@extends('layout')

@section('content')
<section>
    @foreach($posts as $post)
        <aside>
            @php
            $sentences = collect($post->ingredients['sentences']);
            $images = collect($post->ingredients['images']);

            $images->transform(function($item, $key){
                $item['source'] =  parse_url($item['image'], PHP_URL_HOST);
                $item['image'] = str_contains($item['image'], '.wp.com') ?
                    $item['image'] :
                    'https://i' . rand(0,1) . '.wp.com/' . str_replace(['https://', 'http://'], '', $item['image']);

                $item['thumbnail'] = str_contains($item['thumbnail'], '.wp.com') ?
                    $item['thumbnail'] :
                    'https://i' . rand(0,1) . '.wp.com/' . str_replace(['https://', 'http://'], '', $item['thumbnail']);

                return $item;
            });

            @endphp
            @php
            $image = $images->random();
            @endphp
            <a href="{{ $image['image'] }}" target="_blank"><img alt="{{ $image['title'] }}" src="{{ $image['thumbnail'] }}" width="100%" onerror="this.onerror=null;this.src='{{ $image['image'] }}';"></a>
            <h3><a href="{{ $post->slug }}.html">{{ $post->title }}</a></h3>
        </aside>
    @endforeach
</section>
@endsection
