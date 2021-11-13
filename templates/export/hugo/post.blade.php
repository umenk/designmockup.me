---
title: "{{ $post->title }}"
date: {{ $post->published_at->format('Y-m-d') }}
# post thumb
image: "{{ collect($post->ingredients['images'])->shuffle()->random()['image'] }}"
#author
author: "{{ fake()->name }}"
# description
description: "{{ collect($post->ingredients['sentences'])->shuffle()->random() }}"
# Taxonomies
categories: ["{{ $post->category }}"]
tags: ["{{ collect(['mockups for books', 'free mockups', 'free mockups online', 'free mockups psd', 'mockups download', 'mockups apparel', 'mockup apps', 'mockups book', 'mockups branding', 'mockups commercial use', 'mockups clothinge'])->random() }}"]
draft: false
---

{!! $post->content !!}

