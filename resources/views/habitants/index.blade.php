<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Habitants') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex items-center justify-between mb-4">
                        <form method="GET" action="{{ route('habitants.index') }}" class="flex gap-2">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Rechercher par nom, prénom ou email"
                                class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <input
                                type="text"
                                name="quartier"
                                value="{{ request('quartier') }}"
                                placeholder="Quartier"
                                class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <x-primary-button>
                                Rechercher
                            </x-primary-button>
                        </form>

                        <a
                            href="{{ route('habitants.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Nouvel habitant
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nom
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Téléphone
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quartier
                                    </th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($habitants as $habitant)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <a
                                                href="{{ route('habitants.show', $habitant) }}"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                {{ $habitant->nom_complet }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            {{ $habitant->email }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            {{ $habitant->telephone }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            {{ $habitant->quartier }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a
                                                href="{{ route('habitants.edit', $habitant) }}"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Modifier
                                            </a>
                                            <form
                                                action="{{ route('habitants.destroy', $habitant) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Supprimer cet habitant ?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Aucun habitant trouvé.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $habitants->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

