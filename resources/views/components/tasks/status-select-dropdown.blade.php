@props([
    'current',
    'statuses',
])

@php
    
@endphp

<select class="border-2 focus:ring-0 bg-none rounded-lg py-0 px-2 text-sm w-5 h-5" 
    style="background-color: {{ $statuses->find($current)->pivot->color ?? '#000' }}; 
           color: {{ $statuses->find($current)->pivot->color ?? '#000' }}"
    title="{{ $statuses->find($current)->name }}"
    >
            @foreach($statuses as $status)
                <option value="{{ $status->id }}" class="bg-white" style="color: {{ $status->pivot->color ?? '#000' }}" {{ $current == $status->id ? 'selected' : '' }}>
                    {{ $status->name }}
                </option>
            @endforeach
</select>