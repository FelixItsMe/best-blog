@props([
    'image',
    'title',
    'date',
    'tags',
    'preview_text',
    'link',
])

<div>
    <img class="aspect-4/3 object-cover" src="{{ $image }}" alt="" srcset="">
    <div class="bg-white w-10/12 -translate-y-6 pt-4 pr-2">
        <h2 class="text-2xl font-bold mb-2">{{ $title }}</h2>
        <p class="text-xs mb-2">{{ $date }} &SmallCircle; {{ $tags }}</p>
        <div class="border-b-2 border-pink-500 w-3/12 mb-2"></div>
        <p class="text-sm mb-2">{{ $preview_text }}</p>
        <a href="{{ $link }}" class="text-sm text-pink-500 hover:text-pink-800">Read more &longrightarrow;</a>
    </div>
</div>