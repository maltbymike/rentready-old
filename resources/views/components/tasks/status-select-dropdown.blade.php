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

            <div x-data="{ showAddNewStatus: false }" class="contents">
            
                <button class="bg-white text-primary border border-primary rounded-lg py-1 px-3 flex gap-3 items-center hover:bg-primary hover:text-white active:bg-darkblue justify-center"
                    @click.stop="showAddNewStatus = true"
                    x-show="! showAddNewStatus">
                    
                    {{ __('Add New Status') }}
                
                </button>

                <div x-show="showAddNewStatus"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    style="display: none"
                    @click.stop>
                
                    <div class="flex items-center border rounded-lg focus-within:border-primary">
                        <input type="text"
                            name="newStatusName" 
                            wire:model="list.newStatusName"
                            class="grow py-1 px-3 rounded-l-lg border-r-0 border-gray-200 focus:border-none focus:ring-0 focus:ring-offset-0 w-3/4" 
                            placeholder="New Status" />

                            <input type="color" 
                                name="newStatusColor"
                                wire:model="list.newStatusColor"
                                class="h-6 w-6 p-0 bg-transparent"
                                 />

                        <button type="submit"
                            class="group grow-0 active:scale-125 disabled:scale-100"
                            {{ strlen($this->list->newStatusName) >= 3 ? '' : 'disabled' }}>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-6 w-6 p-1 mx-1 rounded-full border fill-green-300 hover:fill-green-600 hover:border-green-600 group-disabled:fill-gray-200 group-disabled:border-gray-200"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                        </button>
                    </div>
            
                </div>
            
            </div>

        </div>

    </x-slot>

</x-dropdown>