<x-app-layout>
    <x-slot name="header">
        <x-project-detail-menu :project="$project" active="roles"/>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3">
                <p class="text-sm leading-6 font-medium text-gray-900 dark:text-white">
                    Total : {{ $count }}
                </p>
                <div class="flex space-x-4 items-center">
                    @if(viewContent($project, 'Roles', 'Archives'))
                        <form action="{{route('roles.archives',$project)}}" method="GET">
                            <button title="Archives" class="mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30" height="30" class="fill-blue-400 dark:fill-yellow-400">
                                    <path d="M32 32l448 0c17.7 0 32 14.3 32 32l0 32c0 17.7-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96L0 64C0 46.3 14.3 32 32 32zm0 128l448 0 0 256c0 35.3-28.7 64-64 64L96 480c-35.3 0-64-28.7-64-64l0-256zm128 80c0 8.8 7.2 16 16 16l160 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-160 0c-8.8 0-16 7.2-16 16z"/>
                                </svg>
                            </button>
                        </form>
                    @endif
                    @if(viewContent($project, 'Roles', 'Create'))
                        <form action="{{ route('roles.create',$project) }}" method="GET">
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 dark:bg-orange-500 hover:bg-orange-700 dark:hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="24" width="24" class="mr-2 fill-current">
                                    <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                                </svg>
                                New Role
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 shadow overflow-x-auto sm:rounded-lg">
                <!-- Table -->
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Role
                            </th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Description
                            </th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Created At
                            </th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($roles as $role)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800">
                                <td class="px-4 py-4 max-w-xs text-sm font-medium text-gray-900 dark:text-white text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-4 py-4 max-w-xs text-sm font-medium text-gray-900 dark:text-white text-center">
                                    {{ $role->role_name }}
                                </td>
                                <td class="px-4 py-4 max-w-xs text-sm text-gray-500 dark:text-gray-300 text-center break-words">
                                    {{ $role->description }}
                                </td>
                                <td class="px-4 py-4 max-w-xs text-sm text-gray-500 dark:text-gray-300 text-center">
                                    {{ $role->created_at->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-4 py-4 max-w-xs text-sm text-gray-500 dark:text-gray-300 text-center">
                                    @if(viewContent($project, 'Roles', 'GrantPermission'))
                                        <div class="inline-block">
                                            <form action="{{route('permissions',[ 'project' => $project, 'role' => $role ])}}" method="GET">
                                                <button title="Permissions">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="22" viewBox="0 0 448 512">
                                                        <path fill="#f59e0b" d="M224 64c-44.2 0-80 35.8-80 80l0 48 240 0c35.3 0 64 28.7 64 64l0 192c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 256c0-35.3 28.7-64 64-64l16 0 0-48C80 64.5 144.5 0 224 0c57.5 0 107 33.7 130.1 82.3c7.6 16 .8 35.1-15.2 42.6s-35.1 .8-42.6-15.2C283.4 82.6 255.9 64 224 64zm32 320c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l64 0z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                        <span class="ml-2 mr-2">|</span>
                                    @endif
                                    @if(viewContent($project, 'Roles', 'Update'))
                                        <div class="inline-block">
                                            <form action="{{route('roles.edit',[ 'project'=>$project, 'role'=>$role ])}}" method="GET">
                                                <button title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 512 512">
                                                        <path fill="#22C55E" d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-96c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 64z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                    @if(viewContent($project, 'Roles', 'Delete'))
                                        <span class="ml-2 mr-2">|</span>
                                        <div class="inline-block">
                                            <form action="{{route('roles.delete',[ 'project'=>$project, 'role'=>$role ])}}" method="POST" onsubmit="return confirm('Move this role to Archives?');">
                                                @csrf
                                                @method('DELETE')
                                                <button title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 448 512">
                                                        <path fill="#EF4444" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Table Ends -->
            </div>
        </div>
    </div>
</x-app-layout>
