<?php

namespace App\Http\Requests;

use App\Models\Activity;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Training;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB;

class TrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'day'         => 'required|date|after_or_equal:today',
            'hour'        => 'required',
            'activity_id' => 'required',
            'user_id'     => 'required',
            'court_id'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'day.required'          => 'Se tiene que poner el día.',
            'day.after_or_equal'    => 'El día no puede ser en el pasado.',
            'hour.required'         => 'Se tiene que poner la hora.',
            'activity_id.required'  => 'Se tiene que seleccionar una actividad.',
            'user_id.required'      => 'Se tiene que seleccionar un monitor.',
            'court_id.required'     => 'Se tiene que seleccionar una pista.',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            // Comprobacion de que no ponga algo que no sea ni :30 ni :00
            if (substr($this->hour, 3, 5) != 30 && substr($this->hour, 3, 5) != 00) {
                $validator->errors()->add('hour', "Se debe de poner :00 o bien :30");
            }

            $dia        = $this->day;
            $horaIni       = substr($this->hour, 0, 5);
            $monitorId  = $this->user_id;
            $courtId    = $this->court_id;
            $activityId = $this->activity_id;

            $duracion = Activity::find($activityId)->duration;

            $horaFin = Carbon::parse($horaIni)->addHours($duracion)->format('H:i');

            // Horario del club
            // 0 = Domingo, 1 = Lunes...,  5 = Viernes, 6 = Sábado
            $diaSemana   = Carbon::parse($dia)->dayOfWeek;
            $esFinSemana = ($diaSemana === 0 || $diaSemana === 6); // true si Sáb o Dom

            if ($esFinSemana) {
                $abre   = '09:00';
                $cierra = '20:00';
            } else {
                $abre   = '08:00';
                $cierra = '21:00';
            }

            // Comprobamos que a la hora que se selecciona este en el rango de la apertura
            if ($horaIni < $abre || $horaIni > $cierra) {
                $validator->errors()->add('hour', "El club abre a las {$abre} y cierra a las {$cierra}.");
                return;
            }

            // Comprobamos que el training no finalice fuera del horario de apertura del club
            if ($horaFin > $cierra) {
                $horaLimite = Carbon::parse($cierra)->subHours($duracion)->format('H:i'); // Calculamos la última hora posible restando la duración al cierre
                $validator->errors()->add('hour', "Con duración de {$duracion}h la última hora posible es {$horaLimite} (el club cierra a las {$cierra}).");
                return;
            }

            // Si el método es no es un POST (insertar), la variable para coger el id, sera null, pero no afecta al where, pero si el método es un POST, cogerá el id del training actual, para que no le afecte al where
            $trainingActualId = $this->method() === 'POST' ? null : $this->route('trainingCRUD')?->id;


            // Conflicto con el monitor (actual)
            $trainingsMonitor = Training::where('user_id', $monitorId)
                ->where('day', $dia)
                ->when($trainingActualId, fn($q) => $q->where('id', '!=', $trainingActualId)) // excluye el actual en update
                ->with('activity')
                ->get();

            foreach ($trainingsMonitor as $t) {
                $horaIniMoni = substr($t->hour, 0, 5);
                $horaFinMoni  = Carbon::parse($horaIniMoni)->addHours($t->activity->duration)->format('H:i');
                
                // Comprueba que el monitor no tenga otro training en el mismo horario antes de asignarlo
                if ($horaIni < $horaFinMoni && $horaFin > $horaIniMoni) {
                    $validator->errors()->add('user_id', "Este monitor ya tiene clase de {$horaIniMoni} a {$horaFinMoni}.");
                    break;
                }
            }

            // Conflicto con una reserva de pista (si hay un Training)
            $trainingsPista = Training::where('court_id', $courtId)
                ->where('day', $dia)
                ->when($trainingActualId, fn($q) => $q->where('id', '!=', $trainingActualId)) // excluye el actual en update
                ->with('activity')
                ->get();

            foreach ($trainingsPista as $t) {
                $horaIniT = substr($t->hour, 0, 5);
                $horaFinT  = Carbon::parse($horaIniT)->addHours($t->activity->duration)->format('H:i');

                if ($horaIni < $horaFinT && $horaFin > $horaIniT) {
                    $validator->errors()->add('court_id', "La pista ya está ocupada de {$horaIniT} a {$horaFinT}. (Por un Training)");
                    break;
                }
            }

            // Conflicto con una reserva de pista (si hay un usuario)
            $reservas = DB::table('court_user')
                ->where('court_id', $courtId)
                ->where('day', $dia)
                ->get();

            foreach ($reservas as $r) {
                $horaIniR = substr($r->hour, 0, 5);
                $horaFinR  = Carbon::parse($horaIniR)->addHours($r->duration)->format('H:i');

                if ($horaIni < $horaFinR && $horaFin > $horaIniR) {
                    $validator->errors()->add('court_id', "La pista ya está reservada de {$horaIniR} a {$horaFinR}. (Por un usuario)");
                    break;
                }
            }
        });
    }
}
