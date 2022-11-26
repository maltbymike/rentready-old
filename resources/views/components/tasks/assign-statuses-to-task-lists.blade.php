<table {{ $attributes->merge(['class' => 'table-fixed text-center']) }}>

    <thead>
        <tr class="bg-lightblue text-white">
            <th class='text-left p-1'>{{ __('Active Statuses') }}</th>
            <th class='text-center'>{{ __('Default') }}</th>
            <th class='text-center'>{{ __('Closed') }}</th>
        </tr>
    </thead>

    <tbody class="">
        @foreach($statuses as $status)
            <tr class="border-y align-middle
                        text-gray-500 hover:text-primary
                        even:bg-gray-50 hover:bg-gray-100 
                        hover:fill-primary hover:drop-shadow-lg hover:border"
            >
                <th class='p-1 text-left flex gap-2'>
                    <input type="checkbox" 
                        id="status-{{ $status->id }}" 
                        name="add_status[]"
                        value="{{ $status->id }}"
                        class="m-auto grow-0 rounded"
                    />
                    <label for="status-{{ $status->id }}" class="grow">{{ $status->name }}</label>
                </th>

                <td>
                    <input type="radio" 
                            id="default-{{ $status->id }}" 
                            name="default_status" 
                            value="{{ $status->id }}" 
                            class="m-auto"
                            aria-label="{{ __('Mark') }} {{ $status->name }} {{ __('as default status') }}"
                    />
                </td>

                <td>
                    <input type="radio" 
                            id="closed-{{ $status->id }}" 
                            name="closed_status" 
                            value="{{ $status->id }}"
                            class="m-auto"
                            aria-label="{{ ('Mark') }} {{ $status->name }} {{ ('as closed status') }}"
                    />
                </td>
        
            </tr>
        
        @endforeach
    </tbody>
</table>