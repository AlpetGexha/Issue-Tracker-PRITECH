<x-layouts.app :title="__('Dashboard')">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400">Welcome back! Here's what's happening with your projects.</p>
        </div>

        {{-- Quick Stats --}}
        <div class="grid gap-6 md:grid-cols-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/20">
                        <flux:icon icon="briefcase" class="size-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Projects</h3>
                        <p class="text-gray-600 dark:text-gray-400">Manage your projects</p>
                    </div>
                </div>
                <div class="mt-4">
                    <flux:button href="{{ route('project.index') }}" variant="subtle" size="sm" wire:navigate>
                        View All Projects
                    </flux:button>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/20">
                        <flux:icon icon="document-text" class="size-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Issues</h3>
                        <p class="text-gray-600 dark:text-gray-400">Issues assigned to you</p>
                    </div>
                </div>
                <div class="mt-4">
                    <flux:button href="{{ route('issues.my') }}" variant="subtle" size="sm" wire:navigate>
                        View My Issues
                    </flux:button>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/20">
                        <flux:icon icon="tag" class="size-6 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tags</h3>
                        <p class="text-gray-600 dark:text-gray-400">Organize with tags</p>
                    </div>
                </div>
                <div class="mt-4">
                    <flux:button href="{{ route('tags.index') }}" variant="subtle" size="sm" wire:navigate>
                        Manage Tags
                    </flux:button>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h2>
            </div>
            <div class="p-6">
                <div class="text-center py-8">
                    <flux:icon icon="clock" class="size-12 text-gray-400 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No recent activity</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Recent project and issue activities will appear here.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
