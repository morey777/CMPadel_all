<x-app-layout>
    <!-- Header de listado de activities -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Listado de Activities') }}
        </h2>
    </x-slot>

    <!-- Listado de activities -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Comprobamos si tenemos que mostrar un mensaje de status -->
                    <!-- el if es necesario puesto que la primera vez no tendremos status -->
                    @if (session('status'))
                        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                            <span class="font-medium">{{ session('status') }}</span>
                        </div>
                    @endif

                    <!-- Se muestran los elementos en forma de Card -->
                    @foreach($activities as $activity)
                        <x-card-activities :activity="$activity" :show=false />
                    @endforeach

                    {{-- @each('components.card-activities',$activities,'activity', $lala, 'nose') --}}
                    {{ $activities->links() }} <!-- Paginación -->

                </div>
            </div>
        </div>
    </div>

</x-app-layout>