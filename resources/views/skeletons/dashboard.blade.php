<div class="space-y-6">
    <div>
        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/4 animate-pulse"></div>
        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mt-2 animate-pulse"></div>
    </div>

    {{-- Quick Stats Skeleton --}}
    <div class="grid gap-6 md:grid-cols-3">
        @for ($i = 0; $i < 3; $i++)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gray-200 dark:bg-gray-700 animate-pulse">
                        <div class="w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded animate-pulse"></div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-3/4 animate-pulse"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mt-2 animate-pulse"></div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/3 animate-pulse"></div>
                </div>
            </div>
        @endfor
    </div>

    {{-- Recent Activity Skeleton --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-1/4 animate-pulse"></div>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                {{-- Recent Issues Skeleton --}}
                <div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/6 mb-3 animate-pulse"></div>
                    <div class="space-y-3">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="flex items-start space-x-3">
                                <div class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 animate-pulse">
                                    <div class="w-4 h-4 bg-gray-300 dark:bg-gray-600 rounded animate-pulse"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 animate-pulse"></div>
                                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mt-1 animate-pulse"></div>
                                </div>
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-16 animate-pulse"></div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Recent Projects Skeleton --}}
                <div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/6 mb-3 animate-pulse"></div>
                    <div class="space-y-3">
                        @for ($i = 0; $i < 2; $i++)
                            <div class="flex items-start space-x-3">
                                <div class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 animate-pulse">
                                    <div class="w-4 h-4 bg-gray-300 dark:bg-gray-600 rounded animate-pulse"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3 animate-pulse"></div>
                                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-full mt-1 animate-pulse"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
