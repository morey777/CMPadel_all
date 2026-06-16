<?php

namespace App\Http\Requests;

use App\Models\Training;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\DB;

class ActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:150',
            'description' => 'nullable|min:4|max:200',
            'price' => 'required',
            'peopleMax' => 'required',
            'duration' => 'required|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "Se tiene que poner el nombre",
            'name.min' => 'Nombre mínimo son 3 carateres',
            'name.max' => 'Nombre máximo son 150 caracters',
            'description.min' => "Descripción mínima son 4 carateres",
            'description.max' => "Descripción máxima son 150 caracters",
            'price.required' => "Debes poner el precio",
            'peopleMax.required' => "Debes poner el número máximo de personas para esta actividad",
            'duration.required' => "Debes poner la duración",
            'duration.min' => "Debes poner la duración mínimo 1hora",
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            // POST -> para insertar. Al crear una nueva actividad, inicialmente no existe ningún conflicto
            if ($this->method() === 'POST') return;

            $nuevoMax  = $this->peopleMax;
            $duracionA = (int) $this->duration;

            // Buscamos todos los trainings de esta actividad
            $trainings = Training::where('activity_id', $this->route('activityCRUD')->id)
                ->with('users_nm', 'activity')
                ->get();

            foreach ($trainings as $t) {

                $horaIni  = substr($t->hour, 0, 5);
                $horaFin = Carbon::parse($horaIni)->addHours($duracionA)->format('H:i');

                $diaSemana   = Carbon::parse($t->day)->dayOfWeek;
                $esFinSemana = ($diaSemana === 0 || $diaSemana === 6);

                if ($esFinSemana) {
                    $cierra = '20:00';
                } else {
                    $cierra = '21:00';
                }

                $inscritos = $t->users_nm->count();

                // Comprueba que el nuevo límite de plazas no sea inferior al número de inscritos en el training actual
                if ($inscritos > $nuevoMax) {
                    $validator->errors()->add('peopleMax', "No se puede reducir a {$nuevoMax} persona/s, el training del {$t->day} a las {$horaIni} ya tiene {$inscritos} inscritos.");
                    break;
                }

                // Comprueba que la nueva duración puesta en la actividad no supere los trainings en la hora del cierre
                if ($horaFin > $cierra) {
                    $validator->errors()->add('duration', "No se puede extender la duración, el training del {$t->day} a las {$horaIni} terminaría a las {$horaFin} y el club cierra a las {$cierra}.");
                    break;
                }

                // Conflicto con una reserva de pista
                $reservas = DB::table('court_user')
                    ->where('court_id', $t->court_id)
                    ->where('day', $t->day)
                    ->get();

                foreach ($reservas as $r) {
                    $horaIniReserva = substr($r->hour, 0, 5);
                    $horaFinReserva = Carbon::parse($horaIniReserva)->addHours($r->duration)->format('H:i');

                    if ($horaIni < $horaFinReserva && $horaFin > $horaIniReserva) {
                        $validator->errors()->add('duration', "No se puede extender la duración, el training del {$t->day} a las {$horaIni} chocaría con una reserva de {$horaIniReserva} a {$horaFinReserva} en la misma pista.");
                        break 2;
                    }
                }

                // Conflicto con otro training en la misma pista, y dia
                $otrosTrainings = Training::where('court_id', $t->court_id)
                    ->where('day', $t->day)
                    ->where('id', '!=', $t->id) // excluimos el training actual
                    ->with('activity')
                    ->get();

                foreach ($otrosTrainings as $otro) {
                    $horaIniO = substr($otro->hour, 0, 5);
                    $horaFinO  = Carbon::parse($horaIniO)->addHours($otro->activity->duration)->format('H:i');

                    if ($horaIni < $horaFinO && $horaFin > $horaIniO) {
                        $validator->errors()->add('duration', "No se puede extender la duración, el training del {$t->day} a las {$horaIni} chocaría con otro training de {$horaIniO} a {$horaFinO} en la misma pista.");
                        break 2;
                    }
                }
            }
        });
    }
}
