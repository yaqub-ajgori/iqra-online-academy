<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>ইকরা অনলাইন একাডেমি - ব্লগ</title>
        <description>ইসলামিক শিক্ষা এবং কুরআন-হাদিসের জ্ঞান নিয়ে আমাদের সর্বশেষ পোস্ট</description>
        <link>{{ url('/blog') }}</link>
        <atom:link href="{{ url('/blog/feed') }}" rel="self" type="application/rss+xml"/>
        <language>bn</language>
        <lastBuildDate>{{ now()->toRSSString() }}</lastBuildDate>
        <generator>ইকরা অনলাইন একাডেমি</generator>

        @foreach($posts as $post)
        <item>
            <title><![CDATA[{{ $post->title }}]]></title>
            <description><![CDATA[{{ $post->excerpt ?: strip_tags(Str::limit($post->content, 300)) }}]]></description>
            <link>{{ route('frontend.blog.show', $post->slug) }}</link>
            <guid>{{ route('frontend.blog.show', $post->slug) }}</guid>
            <pubDate>{{ $post->published_at->toRSSString() }}</pubDate>
            <author>{{ $post->author->email ?? 'noreply@iqraacademy.com' }} ({{ $post->author->name }})</author>
            @if($post->category)
            <category>{{ $post->category->name }}</category>
            @endif
        </item>
        @endforeach
    </channel>
</rss>