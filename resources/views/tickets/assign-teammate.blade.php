<x-app-layout>
    <x-slot name="header">
        <x-project-detail-menu :project="$project" active="sprints"/>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-black overflow">
                <livewire:assign-teammate :ticket="$ticket" :project="$project"/>
            </div>
        </div>
    </div>
</x-app-layout>
