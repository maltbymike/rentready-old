<x-jet-form-section submit="saveTaskList">
    
    <x-slot name="title">{{ __('Task List Settings') }}</x-slot>
    <x-slot name="description"></x-slot>

    <x-slot name="form">

        <input type="hidden" name="id" wire:model.defer="state.id" />

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

        <div class="flex gap-3">
            
            <x-jet-danger-button 
                wire:click.prevent="clear" 
                wire:loading.attr="disabled"
                wire:click.target="clear"
            >
                {{ __('Clear') }}
            </x-jet-danger-button>

            <x-jet-button>
                {{ isset($state['id']) != null ? __('Save') : __('Create') }}
            </x-jet-button>

        </div>

    </x-slot>
</x-jet-form-section>