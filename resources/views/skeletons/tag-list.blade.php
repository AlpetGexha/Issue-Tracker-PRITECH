<div class="space-y-6">
    {{-- Header Skeleton --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/3 mb-2 animate-pulse"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 animate-pulse"></div>
        </div>
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded w-24 animate-pulse"></div>
    </div>

    {{-- Search Skeleton --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
    </div>

    {{-- Tags Table Skeleton --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-4 gap-4">
                @for ($i = 0; $i < 4; $i++)
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                @endfor
            </div>
        </div>
        @for ($i = 0; $i < 5; $i++)
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                <div class="grid grid-cols-4 gap-4 items-center">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-16 animate-pulse"></div>
                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-20 animate-pulse"></div>
                    <div class="flex gap-2">
                        <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-8 animate-pulse"></div>
                        <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-8 animate-pulse"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>

    {{-- Pagination Skeleton --}}
    <div class="mt-6 flex justify-center">
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded w-64 animate-pulse"></div>
    </div>
</div>
