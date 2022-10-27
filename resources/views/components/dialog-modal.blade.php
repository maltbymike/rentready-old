@props([
    'id' => null, 
    'maxWidth' => null, 
    'titleClass' => null,
    'contentClass' => null,
    'footerClass' => null,
    ])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="p-6 h-[94vh] flex flex-col {{ $titleClass }}">
        <div class="text-lg bg-white">
            {{ $title }}
        </div>

        <div class="mt-4 -mr-6 pr-2 flex-1 overflow-y-auto {{ $contentClass }}">
            {{ $content }}
        </div>

        <div class="pt-6 text-right {{ $footerClass }}">
            {{ $footer }}
        </div>
    </div>
</x-modal>
