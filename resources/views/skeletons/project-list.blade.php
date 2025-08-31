<div class="space-y-6">
    <!-- Header Skeleton -->
    <div class="flex justify-between items-center">
        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/4 animate-pulse"></div>
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded w-32 animate-pulse"></div>
    </div>

    <!-- Search Skeleton -->
    <div class="w-full max-w-md">
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
    </div>

    <!-- Projects Grid Skeleton -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @for ($i = 0; $i < 6; $i++)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <!-- Project Header -->
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2 animate-pulse"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full animate-pulse"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3 mt-1 animate-pulse"></div>
                    </div>
                    <div class="h-8 w-8 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                </div>

                <!-- Project Meta -->
                <div class="space-y-2 mb-4">
                    <div class="flex gap-2">
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/3 animate-pulse"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/4 animate-pulse"></div>
                    </div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 animate-pulse"></div>
                </div>

                <!-- Project Owners -->
                <div class="mb-4">
                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/4 mb-2 animate-pulse"></div>
                    <div class="flex flex-wrap gap-1">
                        @for ($j = 0; $j < 2; $j++)
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-16 animate-pulse"></div>
                        @endfor
                    </div>
                </div>
            </div>
        @endfor
    </div>

    <!-- Pagination Skeleton -->
    <div class="mt-6 flex justify-center">
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded w-64 animate-pulse"></div>
    </div>
</div>
