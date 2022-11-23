<section {{ $attributes->merge(['class' => 'grid grid-cols-6']) }}>

    <div class="contents">
        <div class='col-span-3 text-left'><!-- Intentionally Blank --></div>
        <div class='text-center'>{{ __('Add') }}</div>
        <div class='text-center'>{{ __('Default') }}</div>
        <div class='text-center'>{{ __('Closed') }}</div>
    </div>

    @foreach($statuses as $status)
        <div class="contents group border-y text-gray-500 hover:bg-white hover:fill-primary hover:text-primary">
            <div class='col-span-3 p-2 text-left border-y group-hover:bg-gray-50'>
                {{ $status->name }}
            </div>

            <div class="border-y flex group-hover:bg-gray-50">
                <input type="checkbox" 
                    id="status-{{ $status->id }}" 
                    name="add_status[]"
                    value="{{ $status->id }}"
                    class="m-auto"
                    aria-label="{{ __('Add') }} {{ $status->name }} {{ __('to task list') }}"
                />
            </div>

            <div class="border-y flex group-hover:bg-gray-50">
                <input type="radio" 
                        id="default-{{ $status->id }}" 
                        name="default_status" 
                        value="{{ $status->id }}" 
                        class="m-auto"
                        aria-label="{{ __('Mark') }} {{ $status->name }} {{ __('as default status') }}"
                />
            </div>

            <div class="border-y flex group-hover:bg-gray-50">
                <input type="radio" 
                        id="closed-{{ $status->id }}" 
                        name="closed_status" 
                        value="{{ $status->id }}"
                        class="m-auto"
                        aria-label="{{ ('Mark') }} {{ $status->name }} {{ ('as closed status') }}"
                />
            </div>
    
        </div>
    
    @endforeach
    
</section>