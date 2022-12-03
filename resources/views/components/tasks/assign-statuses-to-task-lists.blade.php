<table {{ $attributes->merge(['class' => 'table-fixed text-center w-full']) }}>

    <thead>
        <tr class="bg-lightblue text-white">
            <th class='text-left p-1 w-1/2'>{{ __('Active Statuses') }}</th>
            <th class='text-center w-1/6'>{{ __('Colour') }}</th>
            <th class='text-center w-1/6'>{{ __('Default') }}</th>
            <th class='text-center w-1/6'>{{ __('Closed') }}</th>
        </tr>
        <tr>
            <td>
                <x-forms.input-error for="add_status" class="px-1 text-left" />
            </td>
            <td>
                <!-- Intentionally Blank -->
            </td>
            <td>
                <x-forms.input-error for="default_status" class="px-2" />
            </td>
            <td>
                <x-forms.input-error for="closed_status" class="px-2" />
            </td>
        </tr>
    </thead>

    <tbody class="">
        @foreach($statuses as $status)
            <tr class="border-y align-middle
                        text-gray-500 hover:text-primary
                        even:bg-gray-50 hover:bg-gray-100 
                        hover:fill-primary hover:drop-shadow-lg hover:border"
            >
                <th class='p-1 text-left flex flex-wrap gap-2'>
                    
                    <input type="checkbox" 
                        id="status-{{ $status->id }}" 
                        name="add_status[{{ $status->id }}]"
                        wire:model="state.add_status.{{ $status->id }}"
                        value="{{ $status->id }}"
                        class="m-auto grow-0 rounded"
                    />
                    <label for="status-{{ $status->id }}" class="grow">{{ $status->name }}</label>
                    
                    <x-forms.input-error for="add_status.{{ $status->id }}" class="px-2 w-full" />
                </th>

                <td>
                    <x-forms.input-color id="color-{{ $status->id }}" name="color-{{ $status->id }}" wire:model="state.color.{{ $status->id }}" />
                    <x-forms.input-error for="add_status.{{ $status->id }}" class="px-2 w-full" />          
                </td>

                <td>
                    <input type="radio" 
                            id="default-{{ $status->id }}" 
                            name="default_status" 
                            wire:model="state.default_status"
                            value="{{ $status->id }}" 
                            class="m-auto"
                            aria-label="{{ __('Mark') }} {{ $status->name }} {{ __('as default status') }}"
                    />
                </td>

                <td>
                    <input type="radio" 
                            id="closed-{{ $status->id }}" 
                            name="closed_status"
                            wire:model="state.closed_status"
                            value="{{ $status->id }}"
                            class="m-auto"
                            aria-label="{{ ('Mark') }} {{ $status->name }} {{ ('as closed status') }}"
                    />
                </td>
        
            </tr>
        
        @endforeach
    </tbody>
</table>