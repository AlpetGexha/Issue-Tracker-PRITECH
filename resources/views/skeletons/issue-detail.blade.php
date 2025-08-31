<div class="space-y-6">
    {{-- Issue Header Skeleton --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2 animate-pulse"></div>
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full animate-pulse"></div>
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3 mt-1 animate-pulse"></div>
            </div>
            <div class="flex gap-2">
                @for ($i = 0; $i < 3; $i++)
                    <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-16 animate-pulse"></div>
                @endfor
            </div>
        </div>

        {{-- Meta Info --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            @for ($i = 0; $i < 4; $i++)
                <div>
                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-1 animate-pulse"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 animate-pulse"></div>
                </div>
            @endfor
        </div>

        {{-- Tags and Users --}}
        <div class="space-y-3">
            <div>
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/6 mb-2 animate-pulse"></div>
                <div class="flex flex-wrap gap-1">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-12 animate-pulse"></div>
                    @endfor
                </div>
            </div>
            <div>
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/6 mb-2 animate-pulse"></div>
                <div class="flex flex-wrap gap-1">
                    @for ($i = 0; $i < 2; $i++)
                        <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-20 animate-pulse"></div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    {{-- Comments Section Skeleton --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-1/4 animate-pulse"></div>
        </div>
        <div class="p-6 space-y-4">
            @for ($i = 0; $i < 3; $i++)
                <div class="flex space-x-3">
                    <div class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full animate-pulse"></div>
                    <div class="flex-1">
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/4 mb-2 animate-pulse"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full animate-pulse"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mt-1 animate-pulse"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/6 mt-2 animate-pulse"></div>
                    </div>
                </div>
            @endfor

            {{-- Add Comment Form Skeleton --}}
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="h-24 bg-gray-200 dark:bg-gray-700 rounded mb-4 animate-pulse"></div>
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-24 animate-pulse"></div>
            </div>
        </div>
    </div>
</div>
