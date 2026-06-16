<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">
        <h5 class="mb-2 text-xl font-medium leading-tight">Nombre: {{$activity->name}}</h5>
        <p class="mb-2 text-sm">Código: {{$activity->code}}</p>
        @if ($activity->description != null)
            <p class="mb-2 text-sm">Descripción: {{$activity->description}}</p>
        @endif
        <p class="mb-2 text-sm">Personas Max.: {{$activity->peopleMax}}</p>
        <p class="mb-2 text-sm">Duración: {{$activity->duration*60}}min</p>
        <p class="mb-4 text-sm">Precio: {{$activity->price}}€</p>
        <p class="mb-2 text-sm">created at: {{ $activity->created_at }}</p>
        <p class="mb-4 text-sm">updated at: {{ $activity->updated_at }}</p>

        @if ($show)

            <hr class="mb-2">
            <div class="mb-4">
                <h6 class="mb-3 text-lg font-medium">Sus Trainings:</h6>
                @forelse ($activity->trainings as $training)
                    <div class="mb-3 rounded">
                        <p class="text-sm"><strong>DateIni:</strong> {{$training->dateIni}} </p>
                        @if ($training->dateEnd != null)
                            <p class="text-sm"><strong>DateEnd:</strong> {{$training->dateEnd}}</p>
                        @endif
                        <p class="text-sm"><strong>Dia:</strong> {{$training->day}}</p>
                        <p class="text-sm"><strong>Monitor/a:</strong> {{$training->user->name}} {{$training->user->lastname}} </p>
                        <p class="text-sm"><strong>Pista:</strong> Pista nº{{$training->court->courtNum}} · {{ $training->court->zoneType->name }} · {{ $training->court->courtType->name }}</p>
                        <p class="text-sm"><strong>Personas incritas:</strong> {{ $training->users_nm->count() }} / {{ $activity->peopleMax }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 mb-3">Este Activity no tiene ningún Training</p>
                @endforelse
                
            </div>

        @endif

        <a href="{{route('activityCRUD.show' , ['activityCRUD' => $activity->id])}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Show</a>
        <a href="{{route('activityCRUD.edit' , ['activityCRUD' => $activity->id ])}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        <form action="{{route('activityCRUD.destroy' , ['activityCRUD' => $activity->id ])}}" method="POST" class="float-right">
           @method('DELETE')
           @csrf
           <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" >Delete</button>

        </form>
    </div>
</div>
