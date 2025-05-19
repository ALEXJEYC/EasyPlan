@props(['class' => ''])

<div {{ $attributes->merge(['class' => "bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md $class"]) }}>
    {{ $slot }}
</div>