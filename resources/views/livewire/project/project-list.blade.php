<div>
    <input type="search" wire:model.live="search" placeholder="Search projects..." />


    @foreach ($projects as $project)
        <div wire:key="project-{{ $project->id }}">
            <h2>{{ $project->name }}</h2>
            <p>{{ $project->description }}</p>
            <p>Start Date: {{ $project->start_date }}</p>
            <p>Deadline: {{ $project->deadline }}</p>
            <p>Issues: {{ $project->issues_count }}</p>
            @can('delete', $project)
                <button type="submit" wire:click="deleteProject({{ $project->id }})"
                    wire:confirm="Are you sure you want to delete this project?">
                    Delete Project
                </button>
            @endcan

        </div>
        <br> <br>
    @endforeach
    {{ $projects->links() }}
</div>
