<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Activity :') }}  {{ $activity->code }} {{ $activity->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">

                    <form action="{{ route('activityCRUD.update', ['activityCRUD' => $activity->id ]) }}" method="post">

                        @csrf
                        @method('PUT') 

                        <div class="mb-3">
                            <label for="name">Nombre</label>
                            <input type="text" class="mt-1 block w-full" style="@error('name') border-color:RED; @enderror" name="name" value="{{ $activity->name }}"/>
                            @error('name')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description">Descripción</label>
                            <input type="text" class="mt-1 block w-full" style="@error('description') border-color:RED; @enderror" name="description" value="{{ $activity->description }}"/>
                            @error('description')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="regNumber">Personas Max.</label>
                            <input type="number" min="1" step="1" class="mt-1 block w-full" style="@error('peopleMax') border-color:RED; @enderror" name="peopleMax" value="{{ $activity->peopleMax }}"/>
                            @error('peopleMax')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="duration">Duración</label>
                            <input type="number" min="1" step="0.5" class="mt-1 block w-full" style="@error('duration') border-color:RED; @enderror" name="duration" value="{{ $activity->duration }}"/>
                            @error('duration')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price">Precio</label>
                            <input type="number" min="1" step="any" class="mt-1 block w-full" style="@error('price') border-color:RED; @enderror" name="price" value="{{ $activity->price }}"/>
                            @error('price')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>