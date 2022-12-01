@props([
    'disabled' => false,    
])

<div class="flex items-center border-y group
    hover:border hover:bg-gray-50 hover:shadow-lg
    {{ $disabled ? 'opacity-50' : '' }}">

    @isset($start)
        <div class="grow-0">{{ $start }}</div>
    @endisset
    
    <div class="grow flex items-center">

        <button {{ $attributes->merge(['class' => 'flex items-center gap-3
            p-3 text-sm text-left text-gray-500 leading-4 font-bold 
            group-hover:text-primary
            active:bg-gray-900 active:text-white transition' ]) }}>

            {{ $slot }}

        </button>

    </div>

    @isset($end)
        <div class="grow-0">{{ $end }}</div>
    @endisset

</div>