<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">
        <h5 class="mb-2 text-xl font-medium leading-tight">Nombre/Apellido: {{$user->name}} {{$user->lastname}}</h5>
        @if ($user->dni != null)
            <p class="mb-2 text-sm">DNI: {{$user->dni}}</p>
        @endif
        <p class="mb-2 text-sm">Email: {{$user->email}}</p>
        @if ($user->phone != null)
            <p class="mb-2 text-sm">Teléfono: {{$user->phone}}</p>
        @endif
        <p class="mb-4 text-sm">Rol: {{$user->role->name}}</p>
        <p class="mb-2 text-sm">created at: {{ $user->created_at }}</p>
        <p class="mb-4 text-sm">updated at: {{ $user->updated_at }}</p>
        @if ($show)
            @if ($user->role->name === 'cliente')
                <hr class="mb-2">
                <div class="mb-4">
                    <h6 class="mb-3 text-lg font-medium">Reservas de pistas:</h6>
                    @forelse ($user->courts as $court)
                        <div class="mb-3 rounded">
                            <p class="text-sm"><strong>Pista:</strong> Pista nº{{ $court->courtNum }} · {{ $court->courtType->name}} · {{ $court->zoneType->name}}</p>
                            <p class="text-sm"><strong>Día:</strong> {{ $court->pivot->day }}</p>
                            <p class="text-sm"><strong>Hora:</strong> {{ substr($court->pivot->hour, 0, 5) }}</p>
                            <p class="text-sm"><strong>Duración:</strong> {{ $court->pivot->duration*60 }}min</p>
                            <p class="text-sm"><strong>Precio:</strong> {{ $court->pivot->price }}€</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No tiene reservas</p>
                    @endforelse
                    
                    <h6 class="mb-3 mt-5 text-lg font-medium">Clases:</h6>
                    @forelse ($user->trainings_nm as $training)
                        <div class="mb-3 rounded">
                            <p class="text-sm"><strong>Código:</strong> {{ $training->activity->code }}</p>
                            <p class="text-sm"><strong>Nombre:</strong> {{ $training->activity->name }}</p>
                            @if ($training->activity->description != null)
                                <p class="text-sm"><strong>Descripción:</strong> {{ $training->activity->description }}</p>
                            @endif
                            <p class="text-sm"><strong>Duración:</strong> {{ $training->activity->duration*60 }}min</p>
                            <p class="text-sm"><strong>Precio:</strong> {{ $training->activity->price }}€</p>
                            <p class="text-sm"><strong>Monitor:</strong> {{ $training->user->name }} {{ $training->user->lastname }} </p>
                            <p class="text-sm"><strong>Período de inscripción:</strong> {{ $training->dateIni }} al {{ $training->dateEnd }}</p>
                            <p class="text-sm"><strong>Dia:</strong> {{ $training->day }}</p>
                            <p class="text-sm"><strong>Hora:</strong> {{ substr($training->hour, 0, 5) }}</p>
                            <p class="text-sm"><strong>Pista:</strong> Pista nº{{ $training->court->courtNum }} · {{ $training->court->zoneType->name }} · {{ $training->court->courtType->name }}</p>
                            <p class="text-sm"><strong>Personas incritas:</strong> {{ $training->users_nm->count() }} / {{ $training->activity->peopleMax }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 mb-3">No ha reservado ningúna clase</p>
                    @endforelse
                </div>
            @elseif ($user->role->name === 'monitor')
                <hr class="mb-2">
                <div class="mt-4">
                    <h6 class="mb-3 text-lg font-medium">Clases</h6>
                    @forelse ($user->trainings as $training)
                        <div class="mb-3 rounded">
                            <p class="text-sm"><strong>Código:</strong> {{ $training->activity->code }}</p>
                            <p class="text-sm"><strong>Nombre:</strong> {{ $training->activity->name }}</p>
                            @if ($training->activity->description != null)
                                <p class="text-sm"><strong>Descripción:</strong> {{ $training->activity->description }}</p>
                            @endif
                            <p class="text-sm"><strong>Duración:</strong> {{ $training->activity->duration*60 }}min</p>
                            <p class="text-sm"><strong>Precio:</strong> {{ $training->activity->price }}€</p>
                            <p class="text-sm"><strong>Período de inscripción:</strong> {{ $training->dateIni }} al {{ $training->dateEnd }}</p>
                            <p class="text-sm"><strong>Dia:</strong> {{ $training->day }}</p>
                            <p class="text-sm"><strong>Hora:</strong> {{ substr($training->hour, 0, 5) }}</p>
                            <p class="text-sm"><strong>Pista:</strong> Pista nº{{ $training->court->courtNum }} · {{ $training->court->zoneType->name }} · {{ $training->court->courtType->name }}</p>
                            <p class="text-sm"><strong>Personas incritas:</strong> {{ $training->users_nm->count() }} / {{ $training->activity->peopleMax }}</p>
                        </div>
                    @empty
                        <p class="text-sm mb-5 text-gray-500 mb-3">Sin clases asignadas</p>
                    @endforelse
                </div>
            @endif
        @endif
        <a href="{{route('userCRUD.show' , ['userCRUD' => $user->id])}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Show</a>
        <a href="{{route('userCRUD.edit' , ['userCRUD' => $user->id ])}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        {{-- <form action="{{route('userCRUD.destroy' , ['userCRUD' => $user->id ])}}" method="POST" class="float-right">
           @method('DELETE')
           @csrf
           <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" >Delete</button>

        </form> --}}
    </div>
</div>
