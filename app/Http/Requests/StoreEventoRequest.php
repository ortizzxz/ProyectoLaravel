<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Event;
use Carbon\Carbon;

class StoreEventoRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Or implement your authorization logic
    }

    public function rules()
    {
        return [
            'titulo' => 'required|string|max:255|min:5',
            'tipo' => 'required|in:conferencia,taller',
            'fecha' => ['required', 'date', 'after_or_equal:today', function ($attribute, $value, $fail) {
                $date = Carbon::parse($value);
                if (!in_array($date->dayOfWeek, [4, 5]) || !in_array($date->format('Y-m-d'), ['2025-02-20', '2025-02-21'])) {
                    $fail('Solo se permiten eventos el jueves 20 o viernes 21 de febrero de 2025.');
                }
            }],
            'hora_inicio' => 'required|date_format:H:i',
            'ponente_id' => 'required|exists:ponentes,id',
        ];
    }

    public function messages()
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.string' => 'El título debe ser una cadena de texto.',
            'tipo.in' => 'El tipo debe ser conferencia o taller.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.after_or_equal' => 'No se pueden crear eventos en fechas pasadas.',
            'hora_inicio.date_format' => 'Formato de hora inválido.',
            'ponente_id.exists' => 'El ponente seleccionado no es válido.',
            'titulo.min' => 'El título debe tener como mínimo 5 carácteres.'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateEventOverlap($validator);
            $this->validateSpeakerAvailability($validator);
            $this->validateBreakOverlap($validator);
        });
    }

    protected function validateEventOverlap($validator)
    {
        $fecha = $this->fecha;
        $tipo = $this->tipo;
        $hora_inicio = $this->hora_inicio;
        $hora_fin = Carbon::createFromFormat('H:i', $hora_inicio)->addMinutes(55)->format('H:i');

        $eventoSuperpuestoTipo = Event::where('fecha', $fecha)
            ->where('tipo', $tipo)
            ->where(function ($query) use ($hora_inicio, $hora_fin) {
                $query->whereBetween('hora_inicio', [$hora_inicio, $hora_fin])
                    ->orWhereBetween('hora_fin', [$hora_inicio, $hora_fin])
                    ->orWhere(function ($query) use ($hora_inicio, $hora_fin) {
                        $query->where('hora_inicio', '<', $hora_inicio)
                            ->where('hora_fin', '>', $hora_fin);
                    });
            })
            ->exists();

        if ($eventoSuperpuestoTipo) {
            $validator->errors()->add('hora_inicio', 'Ya existe otra ' . $tipo . ' en este horario.');
        }
    }

    protected function validateSpeakerAvailability($validator)
    {
        $fecha = $this->fecha;
        $ponente_id = $this->ponente_id;
        $hora_inicio = $this->hora_inicio;
        $hora_fin = Carbon::createFromFormat('H:i', $hora_inicio)->addMinutes(55)->format('H:i');

        $eventoSuperpuestoPonente = Event::where('fecha', $fecha)
            ->where('ponente_id', $ponente_id)
            ->where(function ($query) use ($hora_inicio, $hora_fin) {
                $query->whereBetween('hora_inicio', [$hora_inicio, $hora_fin])
                    ->orWhereBetween('hora_fin', [$hora_inicio, $hora_fin])
                    ->orWhere(function ($query) use ($hora_inicio, $hora_fin) {
                        $query->where('hora_inicio', '<', $hora_inicio)
                            ->where('hora_fin', '>', $hora_fin);
                    });
            })
            ->exists();

        if ($eventoSuperpuestoPonente) {
            $validator->errors()->add('ponente_id', 'El ponente ya tiene otro evento en este horario.');
        }
    }

    protected function validateBreakOverlap($validator)
    {
        $fecha = $this->fecha;
        $hora_inicio = $this->hora_inicio;
        $hora_fin = Carbon::createFromFormat('H:i', $hora_inicio)->addMinutes(55)->format('H:i');
        $tipo = $this->tipo;

        if ($mensaje = Event::validarSuperposicionConDescanso($fecha, $hora_inicio, $hora_fin, $tipo)) {
            $validator->errors()->add('hora_inicio', $mensaje);
        }
    }
}