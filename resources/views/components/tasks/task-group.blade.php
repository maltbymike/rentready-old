<h2 class="col-span-2 mt-3 px-3 py-2 pr-0 font-bold text-sm bg-orange-500 text-slate-900 border-t border-orange-500">{{ $slot }}</h2>
<div class="text-center text-sm mt-3 p-2 font-bold text-orange-500 border-t border-orange-500">{{ __('Due Date') }}</div>
<div class="text-center text-sm mt-3 p-2 font-bold text-orange-500 border-t border-orange-500">{{ __('Status') }}</div>

@foreach ($tasks as $taskInList)

    <button 
        wire:click="updateTaskStatusClosed({{ $taskInList->id }})" 
        class="px-3" 
        aria-label="Mark task with task name: {{ $taskInList->name }} Closed"
    >
        <svg class="h-5 w-5 border-2 border-gray-300 rounded {{ $taskInList->status->id == $closed ? 'fill-green-600' : 'fill-transparent' }} fill-gray-500 hover:border-green-300 hover:fill-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" /></svg>                        
    </button>         
    
    <button 
        x-on:click="$wire.edit({{ $taskInList->id }})" 
        wire:key="todayTask-{{ $taskInList->id }}" 
        class="w-full flex rounded hover:text-orange-500 hover:font-bold hover:bg-slate-500 text-left"
    >
        <div class="grow my-auto p-3 {{ $taskInList->status->id == $closed ? 'line-through' : '' }}">{{ $taskInList->name }}</div>
    </button>

    <div class="text-center">
        {{ $taskInList->date_due }}
    </div>

    <div class="px-3 text-center">
        {{ $taskInList->status ? $taskInList->status->name : '' }}
    </div>


@endforeach
