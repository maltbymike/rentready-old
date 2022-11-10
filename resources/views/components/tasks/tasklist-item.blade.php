@props([
    'listItem'
])

<div {{ $attributes->merge(['class' => 'p-3 text-sm leading-4 font-medium text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 transition' ]) }}>
    {{ $listItem['name'] }}
</div>