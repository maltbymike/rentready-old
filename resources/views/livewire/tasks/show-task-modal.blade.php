@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
    <style>
        trix-toolbar .trix-button-row {
            flex-wrap: wrap;
            column-gap: 1.5vw;
            font-size: 0.75rem;
        }
        trix-toolbar .trix-button-group:not(:first-child) {
            margin-left: 0;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
    <script src="https://raw.githack.com/SortableJS/Sortable/master/Sortable.js"></script>
    <x-sortable.scripts/>

    <script> 
        document.addEventListener('livewire:load', () => { 
            window.livewire.on('set-focus-on-new-subtask', id => { 
                document.getElementById("subtask." + id + ".name").focus(); 
            }) 
        }); 
    </script>
@endpush

<x-dialog-modal 
    wire:model="showTaskModal" 
    maxWidth="7xl" 
    titleClass="bg-gray-100"
    contentClass="lg:overflow-y-hidden"
>

    <x-slot name="title">
        
        <div class="wrapper bg-white p-3 -m-3 mb-0">
        
            <div 
                x-data="{ showDates : false, maybeShow() { this.showDates = window.innerWidth > 640 } }"
                @resize.window="maybeShow()"
                x-init="maybeShow()"
                class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3 mb-3"
            >

                <!-- Task Lists -->
                <div class="scale-75 text-gray-500 text-sm origin-[0] -mb-3">Lists</div>
                <div class="mb-3 col-span-full flex items-center gap-2">

                    @empty($currentTask['lists'])
                        <div class="text-sm text-gray-300">
                            {{ __('Assign Task to List') }}
                        </div>
                    @else                            
                        <div class="text-xs flex gap-3">
                            @foreach($currentTask['lists'] as $list)
                                <div class="flex items-center gap-2 border rounded p-1 @if ($loop->first) -ml-1 @endif max-w-[10rem] hover:shadow-md hover:max-w-full">
                                    <span class="grow whitespace-nowrap overflow-hidden text-ellipsis hover:overflow-visible">{{ $list['name'] }}</span>
                                    <button class="grow-0 hover:fill-primary" wire:click.prevent="removeTaskFromList({{ $list['id'] }})" title="{{ __('Remove task from ') . $list['name']}}">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endempty

                    <x-jet-dropdown width="w-max">
                        <x-slot name="trigger">
                            <button class="hover:fill-primary -m-1 p-1 border rounded hover:shadow-md" title="{{ __('Assign task to new list') }}">
                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/></svg>
                            </button>
                        </x-slot>
                        
                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Select Task List') }}
                            </div>

                            @foreach($taskLists as $taskList)
                                <x-jet-dropdown-link wire:click.prevent="assignTaskToList({{ $taskList['id'] }})" href="#">
                                    {{ $taskList['name'] }}
                                </x-jet-dropdown-link>
                            @endforeach
                        </x-slot>
                    </x-jet-dropdown>
                                    
                </div>

                <!-- Task Status -->
                <div class="mb-3 col-span-2 sm:col-span-1">

                    <div class="w-full flex gap-3">
                        
                        <!-- Task Status Dropdown -->
                        <x-forms.form-group class="grow">
                            <x-forms.select 
                                id="task_status_id" 
                                name="task_status_id" 
                                wire:model="currentTask.task_status_id"
                            >
                                @foreach ($taskStatuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                 @endforeach
                            </x-forms.select>
                            <x-forms.label for="task_status_id">{{ __('Status') }}</x-forms.label>
                        </x-forms.form-group>
                                                            
                        <!-- Task Status Checkbox -->
                            <button 
                                wire:click="updateTaskStatusClosed({{ isset($currentTask['id']) ? $currentTask['id'] : '' }})" 
                                class="grow-0 flex justify-items-center items-center" 
                                aria-label="Mark task closed"
                            >
                                <svg 
                                    @empty ($currentTask['closed_at'])
                                        class="h-6 w-6 border-2 rounded border-gray-300 fill-gray-200 hover:border-green-300 hover:fill-green-600"
                                    @else
                                        class="h-6 w-6 border-2 rounded hover:border-gray-300 hover:fill-gray-200 border-green-300 fill-green-600"
                                    @endempty
                                    xmlns="http://www.w3.org/2000/svg" 
                                    viewBox="0 0 512 512"
                                >
                                    <!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                    <path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
                                </svg>
                            </button>
                        
                    </div>

                    @error ('currentTask.statusId') 
                        <p id="task_status_id_error_help" class="mt-2 text-xs w-full text-red-600 basis-full">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dates Toggle Button -->
                <button 
                    @click="showDates = !showDates" 
                    class="mb-3 justify-self-end block text-gray-700 sm:invisible rounded hover:border focus:border focus:bg-gray-100"
                >
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M152 64H296V24C296 10.75 306.7 0 320 0C333.3 0 344 10.75 344 24V64H384C419.3 64 448 92.65 448 128V448C448 483.3 419.3 512 384 512H64C28.65 512 0 483.3 0 448V128C0 92.65 28.65 64 64 64H104V24C104 10.75 114.7 0 128 0C141.3 0 152 10.75 152 24V64zM48 248H128V192H48V248zM48 296V360H128V296H48zM176 296V360H272V296H176zM320 296V360H400V296H320zM400 192H320V248H400V192zM400 408H320V464H384C392.8 464 400 456.8 400 448V408zM272 408H176V464H272V408zM128 408H48V448C48 456.8 55.16 464 64 464H128V408zM272 192H176V248H272V192z"/></svg>    
                </button>

                <!-- Task Repeats -->
                <div class="mb-3 lg:col-start-4" x-show="showDates" x-transition.duration.300ms>
                    <x-forms.form-group>
                        <x-forms.select id="task_repeats" name="task_repeats" wire:model="currentTask.repeats">
                            <option value="">{{ __('Does Not Repeat') }}</option>
                            <option value="daily">{{ __('Daily') }}</option>
                            <option value="weekly">{{ __('Weekly') }}</option>
                            <option value="monthly">{{ __('Monthly') }}</option>
                            <option value="yearly">{{ __('Yearly') }}</option>
                            <option value="weekdays">{{ __('Every Weekday') }}</option>
                            <option value="custom">{{ __('Custom') }}</option>
                        </x-forms.select>
                        <x-forms.label for="task_repeats">{{ __('Task Repeats') }}</x-forms.label>
                    </x-forms.form-group>
                </div>
                
                <!-- Start Date -->
                <div class="mb-3" x-show="showDates" x-transition.duration.300ms>
                    <x-forms.form-group>
                        <x-forms.input 
                            id="date_start" 
                            name="date_start" 
                            type="date" 
                            max="{{ isset($currentTask['date_due']) ? $currentTask['date_due'] : '' }}"
                            wire:model.lazy="currentTask.date_start" />
                        <x-forms.label for="date_start">{{ __('Start Date') }}</x-forms.label>
                    </x-forms.form-group>
                    @error ('currentTask.date_start')
                        <p id="date_start_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Due Date -->
                <div class="mb-3" x-show="showDates" x-transition.duration.300ms>
                    <x-forms.form-group>
                        <x-forms.input 
                            id="date_due" 
                            name="date_due" 
                            type="date" 
                            min="{{ isset($currentTask['date_start']) ? $currentTask['date_start'] : '' }}"
                            wire:model.lazy="currentTask.date_due" />
                        <x-forms.label for="date_due">{{ __('Due Date') }}</x-forms.label>
                    </x-forms.form-group>
                    @error ('currentTask.date_due') 
                        <p id="date_due_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            
            </div>

            <!-- Task Name -->
            <div class="col-span-2 md:col-span-6">
                <x-forms.form-group>
                    <x-forms.input type="text" id="name" name="name" wire:model.lazy="currentTask.name" />
                    <x-forms.label for="name">{{ __('Task Name') }}</x-forms.label>
                </x-forms.form-group>
                @error ('currentTask.name')
                    <p id="name_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        
        </div>
        
    </x-slot>
    
    <x-slot name="content">

        <div class="grid grid-cols-2 gap-x-3 gap-y-8 lg:h-full">

            <section class="mb-3 mt-3 pr-2 col-span-2 lg:col-span-1 lg:overflow-y-auto overflow-x-hidden" tabindex="0">
                <h2 class="mb-3">{{ __('Details') }}</h2>
                <x-forms.form-group>
                    <div x-data="{ currentTask: @entangle('currentTask').defer }">
                        <input 
                            id="details" 
                            name="details" 
                            type="hidden" 
                            x-model="currentTask.details" 
                        />
                        <div wire:ignore>
                            <trix-editor 
                                tabindex="0" 
                                class="trix-editor trix-content text-xs" 
                                x-model.debounce.300ms="currentTask.details" 
                                wire:model.debounce.5s="currentTask.details" 
                                title="Task Details">
                            </trix-editor>
                        </div>
                        @error ('currentTask.details') 
                            <p id="details_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </x-forms.form-group>
            </section>

            <section class="-mx-6 lg:-mt-2 lg:mb-3 lg:-ml-2 lg:-mr-6 p-2 pl-6 lg:pl-4 lg:pt-4 col-span-2 lg:col-span-1 lg:overflow-y-auto overflow-x-hidden bg-gray-100" tabindex="0">
                <h2 class="mb-3 grow">{{ __('Subtasks') }}</h2>

                @isset($currentTask['children'])
                    <x-laravel-blade-sortable::sortable
                        class="col-span-12 grid grid-cols-12 gap-x-3 p-0"
                        drag-handle="drag-handle"
                        wire:onSortOrderChange="handleSortOrderChange"
                    >
                    @foreach ($currentTask['children'] as $key => $value)
                        <x-laravel-blade-sortable::sortable-item 
                            sort-key="{{ $value['id'] }}" 
                            wire:key="subtask-{{ $value['id'] }}"
                            wire:loading.class="opacity-25"
                            wire:target="showTask({{ $value['id'] }})"
                            class="col-span-12 flex gap-x-3 items-center hover:bg-gray-100 p-2"
                        >
                            <div class="drag-handle">
                                <svg class="w-5 h-5 fill-gray-200 hover:fill-black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path d="M17.5 40q-1.45 0-2.475-1.025Q14 37.95 14 36.5q0-1.45 1.025-2.475Q16.05 33 17.5 33q1.45 0 2.475 1.025Q21 35.05 21 36.5q0 1.45-1.025 2.475Q18.95 40 17.5 40Zm13 0q-1.45 0-2.475-1.025Q27 37.95 27 36.5q0-1.45 1.025-2.475Q29.05 33 30.5 33q1.45 0 2.475 1.025Q34 35.05 34 36.5q0 1.45-1.025 2.475Q31.95 40 30.5 40Zm-13-12.5q-1.45 0-2.475-1.025Q14 25.45 14 24q0-1.45 1.025-2.475Q16.05 20.5 17.5 20.5q1.45 0 2.475 1.025Q21 22.55 21 24q0 1.45-1.025 2.475Q18.95 27.5 17.5 27.5Zm13 0q-1.45 0-2.475-1.025Q27 25.45 27 24q0-1.45 1.025-2.475Q29.05 20.5 30.5 20.5q1.45 0 2.475 1.025Q34 22.55 34 24q0 1.45-1.025 2.475Q31.95 27.5 30.5 27.5ZM17.5 15q-1.45 0-2.475-1.025Q14 12.95 14 11.5q0-1.45 1.025-2.475Q16.05 8 17.5 8q1.45 0 2.475 1.025Q21 10.05 21 11.5q0 1.45-1.025 2.475Q18.95 15 17.5 15Zm13 0q-1.45 0-2.475-1.025Q27 12.95 27 11.5q0-1.45 1.025-2.475Q29.05 8 30.5 8q1.45 0 2.475 1.025Q34 10.05 34 11.5q0 1.45-1.025 2.475Q31.95 15 30.5 15Z"/></svg>
                            </div>
                            <button 
                                wire:click.prevent="updateTaskStatusClosed({{ $value['id'] }})"
                                wire:key="updateSubtaskButton-{{ $value['id'] }}"
                                wire:loading.class="opacity-25"
                                wire:target="updateTaskStatusClosed({{ $value['id'] }})"
                                class="grow-0 flex justify-items-center items-center h-4 w-4 border-2 border-gray-300 rounded bg-white {{ $value['closed_at'] != null ? 'fill-green-600' : 'fill-white' }} hover:border-green-300 hover:fill-green-600" 
                                aria-label="Mark task closed"
                            >
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                            </button>
                            <x-forms.form-group class="grow">
                                <x-forms.input 
                                    type="text" 
                                    id="currentTask.children.{{ $key }}.name" 
                                    class="{{ $value['closed_at'] != null ? 'line-through' : '' }}"
                                    wire:model.lazy="currentTask.children.{{ $key }}.name"
                                    disabled
                                    aria-label="{{ __('Subtask Name') }}"
                                />
                            </x-forms.form-group>
                            <button 
                                wire:click.prevent="showTask({{ $value['id'] }})" 
                                class="flex justify-items-center items-center h-4 w-4 fill-gray-300 hover:fill-green-600 active:fill-slate-900"
                                aria-label="Edit Subtask Details"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.8 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
                            </button>
                            @error ('currentTask.children.{{ $value }}.name')
                                <p id="name_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </x-laravel-blade-sortable::sortable-item>
                    @endforeach
                    </x-laravel-blade-sortable::sortable>
                @endisset

                <div class="col-span-12 flex px-2 py-3">
                    <x-forms.form-group class="grow">
                        <x-forms.input 
                            type="text" 
                            id="currentTask.subtasknew.name" 
                            name="currentTask.subtasknew.name" 
                            wire:model.lazy="currentTask.subtasknew.name"
                            wire:keydown.enter="saveSubtask({{ isset($currentTask['id']) ? $currentTask['id'] : '' }})"
                        />
                        <x-forms.label for="currentTask.subtasknew.name">{{ __('New Subtask') }}</x-forms.label>
                    </x-forms.form-group>
                    <button 
                        wire:click.prevent="saveSubtask({{ isset($currentTask['id']) ? $currentTask['id'] : '' }})" 
                        class="flex justify-items-center items-center h-3 w-3 m-1 fill-gray-300 hover:fill-green-600"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                    </button>
                    @error ('currentTask.subtasknew.{{ $value }}.name')
                        <p id="name_error_help" class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </section>
        </div>
    </x-slot>

    <x-slot name="footer">
        <div class="col-span-12 flex justify-center lg:justify-end w-full gap-3">

            <x-button-danger wire:click.prevent="resetFormFields">
                {{ __('Cancel') }}
            </x-button-danger>

            <x-button wire:click.prevent="save({{ isset($currentTask['id']) ? $currentTask['id'] : '' }})" class="">
                @empty($updateMode)
                    {{ __('Save') }}
                @else
                    {{ __('Update') }}
                @endempty
            </x-button>

            <x-button-outline wire:click.prevent="save({{ isset($currentTask['id']) ? $currentTask['id'] : 'null' }}, true)" class="">
                @empty($updateMode)
                    {{ __('Save and Close') }}
                @else
                    {{ __('Update and Close') }}
                @endempty
            </x-button>
        </div>
    </x-slot>

</x-dialog-modal>