<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Rol Usuario :') }}  {{ $user->name }} @if ($user->lastname != null) {{ $user->lastname }} @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">

                    <form action="{{ route('userCRUD.update', ['userCRUD' => $user->id ]) }}" method="post">

                        @csrf
                        @method('PUT') 
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="mb-3">
                            <label for="name">Nombre</label>
                            <input type="text" class="mt-1 block w-full" style="@error('name') border-color:RED; @enderror" value="{{ $user->name }}" name="name" />
                            @error('name')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="lastname">Apellido</label>
                            <input type="text" class="mt-1 block w-full" style="@error('lastname') border-color:RED; @enderror" value="{{ $user->lastname }}" name="lastname" />
                            @error('lastname')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="dni">DNI</label>
                            <input type="text" class="mt-1 block w-full" style="@error('dni') border-color:RED; @enderror" value="{{ $user->dni }}" name="dni" />
                            @error('dni')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="text" class="mt-1 block w-full" style="@error('email') border-color:RED; @enderror" value="{{ $user->email }}" name="email" />
                            @error('email')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone">Teléfono</label>
                            <input type="text" class="mt-1 block w-full" style="@error('phone') border-color:RED; @enderror" value="{{ $user->phone }}" name="phone" />
                            @error('phone')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role_id">Role</label>
                            <select name="role_id" class="mt-1 block w-full">
                                @foreach ($rols as $rol => $id)
                                    @if ($user->role_id == $id)
                                        <option selected value="{{$id}}">{{$rol}}</option>                                       
                                    @else
                                        <option value="{{$id}}">{{$rol}}</option>  
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <br>
                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>