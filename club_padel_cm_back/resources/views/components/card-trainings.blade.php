<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">
        <p class="mb-2 text-sm">Nombre actividad: {{$training->activity->name}}</p>
        <p class="mb-2 text-sm">Descripcion actividad: {{$training->activity->description}}</p>
        <p class="mb-2 text-sm">DateIni: {{$training->dateIni}}</p>
        @if ($training->dateEnd != null)
            <p class="mb-2 text-sm">DateEnd: {{$training->dateEnd}}</p>
        @endif
        <p class="mb-2 text-sm">Dia: {{$training->day}}</p>
        <p class="mb-2 text-sm">Hora: {{substr($training->hour, 0, 5)}}</p>
        <p class="mb-2 text-sm">Duración: {{$training->activity->duration*60}}min</p>
        <p class="mb-2 text-sm">Monitor/a: {{$training->user->name}} {{$training->user->lastname}} </p>
        <p class="mb-2 text-sm">Personas incritas: {{$training->users_nm->count()}} / {{$training->activity->peopleMax}}</p>
        <p class="mb-4 text-sm">Pista: Pista nº{{$training->court->courtNum}} · {{ $training->court->zoneType->name }} · {{ $training->court->courtType->name }}</p>
        <p class="mb-2 text-sm">created at: {{ $training->created_at }}</p>
        <p class="mb-4 text-sm">updated at: {{ $training->updated_at }}</p>

        @if ($show)

            <hr class="mb-2">
            <div class="mb-4">
                <h6 class="mb-3 text-lg font-medium">Personas inscritas:</h6>
                @forelse ($training->users_nm as $user)
                    <div class="mb-3 rounded">
                        <p class="text-sm"><strong>Nombre/Apellido:</strong> {{$user->name}} {{$user->lastname}} </p>
                        <p class="text-sm"><strong>Email:</strong> {{$user->email}}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 mb-3">En este Training no hay ninguna persona inscrita</p>
                @endforelse
                
            </div>

        @endif

        <a href="{{route('trainingCRUD.show' , ['trainingCRUD' => $training->id])}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Show</a>
        <a href="{{route('trainingCRUD.edit' , ['trainingCRUD' => $training->id ])}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        <form action="{{route('trainingCRUD.destroy' , ['trainingCRUD' => $training->id ])}}" method="POST" class="float-right">
           @method('DELETE')
           @csrf
           <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" >Delete</button>

        </form>
    </div>
</div>
