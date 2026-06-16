<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Training :') }}  {{ $training->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">

                    <form action="{{ route('trainingCRUD.update', ['trainingCRUD' => $training->id ]) }}" method="post">

                        @csrf
                        @method('PUT') 


                        <div class="mb-3">
                            <label for="day">Dia</label>
                            <input type="date" class="mt-1 block w-full" style="@error('day') border-color:RED; @enderror" name="day" value="{{ $training->day }}">
                            @error('day')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hour">Hora</label>
                            <input type="time" class="mt-1 block w-full" style="@error('hour') border-color:RED; @enderror" name="hour" value="{{ substr($training->hour, 0, 5) }}"> {{-- Aqui le fuerzo a que me lo ponga en time, sin segundos --}}
                            @error('hour')
                                <div>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="activity_id">Actividad</label>
                            <select name="activity_id" class="mt-1 block w-full" style="@error('activity_id') border-color:RED; @enderror">
                                @foreach ($activities as $activity)
                                    @if ($training->activity->id == $activity->id)
                                        <option selected value="{{$activity->id}}">{{$activity->code}} {{$activity->name}} | Dur: {{$activity->duration}} PerLimi: {{$activity->peopleMax}}</option>  
                                    @else
                                        <option value="{{$activity->id}}">{{$activity->code}} {{$activity->name}} | Dur: {{$activity->duration}} PerLimi: {{$activity->peopleMax}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('activity_id')
                                <div>{{$message}}</div>
                            @enderror
                        </div>    

                        <div class="mb-3">
                            <label for="user_id">Monitor</label>
                            <select name="user_id" class="mt-1 block w-full" style="@error('user_id') border-color:RED; @enderror">
                                @foreach ($users as $user)
                                    @if ($training->user->id == $user->id)
                                        <option selected value="{{$user->id}}">{{$user->name}} {{$user->lastname}}</option> 
                                    @else
                                        <option value="{{$user->id}}">{{$user->name}} {{$user->lastname}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('user_id')
                                <div>{{$message}}</div>
                            @enderror
                        </div>           
                        
                        <div class="mb-3">
                            <label for="court_id">Pista</label>
                            <select name="court_id" class="mt-1 block w-full" style="@error('court_id') border-color:RED; @enderror">
                                @foreach ($courts as $court)
                                    @if ($training->court->id == $court->id)
                                        <option selected value="{{$court->id}}">Pista nº{{$court->courtNum}} · {{$court->zoneType->name}} · {{$court->courtType->name}}</option>
                                    @else
                                        <option value="{{$court->id}}">Pista nº{{$court->courtNum}} · {{$court->zoneType->name}} · {{$court->courtType->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('court_id')
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