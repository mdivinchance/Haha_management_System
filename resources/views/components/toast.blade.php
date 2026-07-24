@php
    $toastSuccess = session('success');
    $toastError = session('error');
    $toastInfo = session('info');
    $toastWarning = session('warning');
@endphp

@if($toastSuccess || $toastError || $toastInfo || $toastWarning)
<div x-data="{
        show: true,
        message: '{{ addslashes($toastSuccess ?? $toastError ?? $toastInfo ?? $toastWarning ?? '') }}',
        type: '{{ $toastSuccess ? "success" : ($toastError ? "error" : ($toastInfo ? "info" : "warning")) }}',
        init() {
            setTimeout(() => { this.show = false }, 4000);
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:-translate-x-full"
    x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave-end="opacity-0 translate-y-2 sm:-translate-y-2 sm:translate-x-full"
    class="fixed top-4 right-4 left-4 sm:left-auto sm:w-96 z-[9999] rounded-xl shadow-2xl border backdrop-blur-sm"
    :class="{
        'bg-green-50 dark:bg-green-950/80 border-green-200 dark:border-green-800': type === 'success',
        'bg-red-50 dark:bg-red-950/80 border-red-200 dark:border-red-800': type === 'error',
        'bg-blue-50 dark:bg-blue-950/80 border-blue-200 dark:border-blue-800': type === 'info',
        'bg-amber-50 dark:bg-amber-950/80 border-amber-200 dark:border-amber-800': type === 'warning'
    }"
>
    <div class="flex items-start gap-3 p-4">
        <div class="flex-shrink-0 mt-0.5">
            @if($toastSuccess)
                <svg class="w-5 h-5 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @elseif($toastError)
                <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @elseif($toastInfo)
                <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @else
                <svg class="w-5 h-5 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            @endif
        </div>
        <p class="flex-1 text-sm font-medium"
           :class="{
               'text-green-700 dark:text-green-300': type === 'success',
               'text-red-700 dark:text-red-300': type === 'error',
               'text-blue-700 dark:text-blue-300': type === 'info',
               'text-amber-700 dark:text-amber-300': type === 'warning'
           }" x-text="message"></p>
        <button @click="show = false" class="flex-shrink-0 rounded-lg p-1 transition-colors"
                :class="{
                    'hover:bg-green-100 dark:hover:bg-green-900/50 text-green-500 dark:text-green-400': type === 'success',
                    'hover:bg-red-100 dark:hover:bg-red-900/50 text-red-500 dark:text-red-400': type === 'error',
                    'hover:bg-blue-100 dark:hover:bg-blue-900/50 text-blue-500 dark:text-blue-400': type === 'info',
                    'hover:bg-amber-100 dark:hover:bg-amber-900/50 text-amber-500 dark:text-amber-400': type === 'warning'
                }">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>
@endif
