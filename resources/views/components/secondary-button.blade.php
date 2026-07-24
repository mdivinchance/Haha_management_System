<button {{ $attributes->merge(['type' => 'button', 'class' => 'rounded-lg border border-gray-300 bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-400 focus:ring-offset-white dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:ring-neutral-500 dark:focus:ring-offset-neutral-900 px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
