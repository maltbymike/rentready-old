<div class="flex
            items-center 
            border-y 
            group
            hover:border
            hover:bg-gray-50 
            hover:drop-shadow-lg">
    
    <div class="grow flex items-center">

        <button 
            {{ $attributes->merge(['class' => '
                p-3 
                text-sm 
                text-left 
                text-gray-500 
                leading-4 
                font-bold 
                flex
                items-center
                gap-3
                group-hover:text-primary
                active:bg-gray-900 
                active:text-white 
                transition' 
            ]) }}
        >

        {{ $slot }}

        </button>

    </div>

    @isset($end)
        <div class="grow-0">{{ $end }}</div>
    @endisset    
</div>