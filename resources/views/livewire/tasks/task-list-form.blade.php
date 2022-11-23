<x-dialog-modal 
    wire:model="state.showListForm" 
    maxWidth="7xl" 
    titleClass="bg-gray-100"
    contentClass="lg:overflow-y-hidden"
>
    <x-slot name="title">{{ __('Task List Settings') }}</x-slot>

    <x-slot name="content">

        <div class="grid grid-cols-6 gap-3 py-3">
            
            <input type="hidden" name="id" wire:model.defer="state.id" />

            <div class="mb-3 col-span-6">
                <x-forms.form-group>
                    <x-forms.input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" autofocus />
                    <x-forms.label for="name">{{ __('List Name') }}</x-forms.label>
                    <x-forms.input-error for="name" class="mt-2" />
                </x-forms.form-group>
            </div>

            <x-tasks.assign-statuses-to-task-lists class="mb-3 col-span-3" />
        
        </div>
    
    </x-slot>

    <x-slot name="footer">
        
        <div class="flex gap-3 justify-center lg:justify-end w-full">
                        
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

</x-dialog-modal>