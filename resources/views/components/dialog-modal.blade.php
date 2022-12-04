@props([
    'id' => null, 
    'maxWidth' => null, 
    'titleClass' => null,
    'contentClass' => null,
    'footerClass' => null,
    'formAction' => null,
    ])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>

    <div class="h-[94vh] flex flex-col overflow-y-auto">
        <div class="p-6 pb-2 text-lg bg-white {{ $titleClass }}">
            {{ $title }}
        </div>

        @isset($formAction)
            <form wire:submit.prevent="{{ $formAction }}">
        @endisset

            <div class="px-6 py-2 flex-1 overflow-y-auto {{ $contentClass }}">
                {{ $content }}
            </div>

            <div class="p-6 pt-2 text-right {{ $footerClass }}">
                {{ $footer }}
            </div>

        @isset($formAction)
            </form>
        @endisset

    </div>

</x-modal>
