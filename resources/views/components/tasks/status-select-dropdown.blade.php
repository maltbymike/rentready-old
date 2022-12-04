@props([
    'current',
    'statuses',
])

<x-dropdown dropdownClasses="-translate-y-1/2 -translate-x-7 opacity-100" align="top right" width="w-max">
    
    <x-slot name="trigger">

        <button type="button" 
            class="border-2 focus:ring-0 bg-none rounded-lg py-0 px-2 text-sm w-5 h-5"
            style="background-color: {{ $statuses->find($current)->pivot->color ?? '#fff' }}; 
                color: {{ $statuses->find($current)->pivot->color ?? '#fff' }}"
            title="{{ $statuses->find($current)->name ?? '' }}">
        </button>
    
    </x-slot>
 
    <x-slot name="content">
    
        <div class="p-3 flex flex-col gap-2">
            @foreach($statuses as $status)
                
                <label class="bg-white py-1 px-3 flex gap-3 items-center border rounded-lg hover:border-primary hover:border-1 active:bg-primary" 
                        style="color: {{ $status->pivot->color ?? '#000' }}">

                    <input type="radio" 
                        name="status"
                        value="{{ $status->id }}"
                        class="text-primary focus:ring-primary appearance-none"
                        {{ $attributes }}>

                    <span>{{ $status->name }}</span>
                    
                </label>
                
            @endforeach

        </div>

    </x-slot>

</x-dropdown>