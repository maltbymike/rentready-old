<div class="pt-5 md:py-5 md:col-span-1 flex justify-between">
    <div class="">
        <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>

        <p class="mt-1 text-sm text-gray-600">
            {{ $description }}
        </p>
    </div>

    <div class="">
        {{ $aside ?? '' }}
    </div>
</div>
