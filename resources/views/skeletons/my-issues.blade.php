<div class="space-y-6">
    {{-- Header Skeleton --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/3 mb-2 animate-pulse"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 animate-pulse"></div>
        </div>
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded w-32 animate-pulse"></div>
    </div>

    {{-- Search Skeleton --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
    </div>

    {{-- My Issues List Skeleton --}}
    <div class="space-y-4">
        @for ($i = 0; $i < 5; $i++)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2 animate-pulse"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full animate-pulse"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3 mt-1 animate-pulse"></div>

                        {{-- Meta --}}
                        <div class="mt-3 flex flex-wrap gap-2">
                            <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-16 animate-pulse"></div>
                            <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-20 animate-pulse"></div>
                            <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-24 animate-pulse"></div>
                        </div>

                        {{-- Tags --}}
                        <div class="mt-2 flex flex-wrap gap-1">
                            @for ($j = 0; $j < 3; $j++)
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-12 animate-pulse"></div>
                            @endfor
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-16 animate-pulse"></div>
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20 animate-pulse"></div>
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
