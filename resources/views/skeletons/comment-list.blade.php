<div class="space-y-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-1/4 animate-pulse"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-16 animate-pulse"></div>
        </div>
        
        {{-- Comments Skeleton --}}
        <div class="space-y-4">
            @for ($i = 0; $i < 3; $i++)
                <div class="flex space-x-3">
                    <div class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full animate-pulse"></div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24 animate-pulse"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-16 animate-pulse"></div>
                        </div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full animate-pulse"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mt-1 animate-pulse"></div>
                    </div>
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-8 animate-pulse"></div>
                </div>
            @endfor
        </div>

        {{-- Add Comment Form Skeleton --}}
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <div class="h-20 bg-gray-200 dark:bg-gray-700 rounded mb-3 animate-pulse"></div>
            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20 animate-pulse"></div>
        </div>

        {{-- Pagination Skeleton --}}
        <div class="mt-4 flex justify-center">
            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-32 animate-pulse"></div>
        </div>
    </div>
</div>
