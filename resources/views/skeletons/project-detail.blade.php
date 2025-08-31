<div>
    {{-- Project Header Skeleton --}}
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="h-9 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-2 animate-pulse"></div>
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 animate-pulse"></div>
                <div class="mt-4 flex gap-4">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32 animate-pulse"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-28 animate-pulse"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24 animate-pulse"></div>
                </div>
            </div>
            <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded w-32 animate-pulse"></div>
        </div>
    </div>

    {{-- Filters Skeleton --}}
    <div class="mb-6 space-y-4">
        {{-- Search --}}
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>

        {{-- Filters --}}
        <div class="flex flex-wrap gap-4">
            @for ($i = 0; $i < 3; $i++)
                <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded w-32 animate-pulse"></div>
            @endfor
            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-24 animate-pulse"></div>
        </div>
    </div>

    {{-- Issues List Skeleton --}}
    <div class="space-y-4">
        @for ($i = 0; $i < 5; $i++)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2 animate-pulse"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full animate-pulse"></div>

                        {{-- Issue Meta --}}
                        <div class="mt-3 flex flex-wrap gap-2">
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-16 animate-pulse"></div>
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-20 animate-pulse"></div>
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-24 animate-pulse"></div>
                        </div>

                        {{-- Tags --}}
                        <div class="mt-2 flex flex-wrap gap-1">
                            @for ($j = 0; $j < 2; $j++)
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-12 animate-pulse"></div>
                            @endfor
                        </div>

                        {{-- Assigned Users --}}
                        <div class="mt-2 flex flex-wrap gap-1">
                            @for ($j = 0; $j < 2; $j++)
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-20 animate-pulse"></div>
                            @endfor
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="ml-4 flex flex-col gap-2">
                        @for ($j = 0; $j < 4; $j++)
                            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-24 animate-pulse"></div>
                        @endfor
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
