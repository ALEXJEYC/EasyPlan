@props(['src', 'alt', 'size' => '8'])

<img class="h-{{ $size }} w-{{ $size }} rounded-full object-cover" src="{{ $src }}" alt="{{ $alt }}">
