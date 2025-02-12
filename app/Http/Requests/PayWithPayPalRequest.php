<?php
namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;

class PayWithPayPalRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'evento_id' => 'required|exists:events,id', // asegurarse que el evento existe
            'tipo_inscripcion' => 'required|in:virtual,presencial,gratuita', // debe ser uno de esos
        ];
    }

    public function messages()
    {
        return [
            'evento_id.required' => 'El ID del evento es obligatorio.',
            'evento_id.exists' => 'El evento seleccionado no existe.',
            'tipo_inscripcion.required' => 'El tipo de inscripción es obligatorio.',
            'tipo_inscripcion.in' => 'El tipo de inscripción debe ser virtual, presencial o gratuita.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $evento = Event::find($this->evento_id);

            if (!$evento) {
                $validator->errors()->add('evento_id', 'El evento no existe.');
                return;
            }

            if ($evento->cupo_maximo <= 0) {
                $validator->errors()->add('evento_id', 'El evento ya no tiene cupo disponible.');
            }
        });
    }
}