<x-jet-form-section submit="createTaskList">
    <x-slot name="title">
        {{ __('Task List') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Create a new task list to keep things organized.') }}
    </x-slot>

    <x-slot name="form">
        
        <div class="col-span-6">
            <x-jet-label for="name" value="{{ __('List Name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" autofocus />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-2">
            <x-jet-label for="open" value="{{ __('Open Status') }}" />
            <x-jet-input id="open" type="number" class="mt-1 block w-full" wire:model.defer="state.open" />
            <x-jet-input-error for="open" class="mt-2" />
        </div>

        <div class="col-span-2">
            <x-jet-label for="closed" value="{{ __('Closed Status') }}" />
            <x-jet-input id="closed" type="number" class="mt-1 block w-full" wire:model.defer="state.closed" />
            <x-jet-input-error for="closed" class="mt-2" />
        </div>
    
        <x-jet-action-message class="p-3 col-span-6 text-left text-green-700 bg-green-100 rounded" on="created">
            {{ __('Task List Created.') }}
        </x-jet-action-message>

    </x-slot>

    <x-slot name="actions">

        <x-jet-button>
            {{ __('Create') }}
        </x-jet-button>

    </x-slot>
</x-jet-form-section>