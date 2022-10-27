<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border-gray-800 border rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-200 active:border-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>