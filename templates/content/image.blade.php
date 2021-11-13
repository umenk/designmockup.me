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

@for($i = 0; $i < 1; $i++)
<p><strong>Free {{ $post->title }} Mockups File</strong> {{ $sentences->shuffle()->take(4)->implode(' ') }}</p>
     <p>
        @php $image = collect($images)->shuffle()->first(); @endphp
        @if($image)
        <p style="text-align: center;">        
<a href="https://yellowimages.com/all/objects/apparel-mockups?yi=363287"><img src="{{ $image['image'] }}" alt="Free {{ $sentences->shuffle()->take(1)->implode(' ') }} {{ $image['title'] }} Free SVG" width="466" height="580" /></a> Free {{ $sentences->shuffle()->take(1)->implode(' ') }} {{ $image['title'] }} SVG Cut File     
</p>       @endif
        {{ $sentences->shuffle()->take(3)->implode(' ') }}        
    </p>
 <!--more-->
@endfor

<h2 style="text-align: center;">{{ $post->title }} Free Mockups</h2>
<p><strong>Download {{ $post->title }} Mockups File</strong>, {{ $sentences->shuffle()->take(3)->implode(' ') }}</p>
<p><strong>Download {{ $post->title }} Mockups for Branding</strong>, {{ $sentences->shuffle()->take(1)->implode(' ') }}</p>
<p><strong>Download {{ $post->title }} PSD Mockups Template</strong>, {{ $sentences->shuffle()->take(4)->implode(' ') }}</p>
@foreach(collect($images)->shuffle()->take(2) as $image)
<p style="text-align: center;"> 
<img src="{{ $image['image'] }}" alt="{{ $sentences->shuffle()->take(1)->implode(' ') }} {{ $image['title'] }}" width="466" height="580" />
</p>       <p>{{ $sentences->shuffle()->take(3)->implode(' ') }}</p>
@endforeach


<h3><strong>5 {{ $post->title }} Mockups PSD</strong></h3>
@foreach(collect($images)->shuffle()->take(5) as $image)
<p style="text-align: center;"> 
<img src="{{ $image['image'] }}" alt="{{ $sentences->shuffle()->take(1)->implode(' ') }} {{ $image['title'] }}" width="466" height="580" />
</p>       <p>{{ $sentences->shuffle()->take(2)->implode(' ') }}</p>
@endforeach

<h4>13 {{ $post->title }} Mockups File</h4>
@foreach(collect($images)->shuffle()->take(13) as $image)
<p style="text-align: center;"> 
<img src="{{ $image['image'] }}" alt="{{ $sentences->shuffle()->take(1)->implode(' ') }} {{ $image['title'] }}" width="466" height="580" />
</p>       <p>{{ $sentences->shuffle()->take(4)->implode(' ') }}</p>
@endforeach