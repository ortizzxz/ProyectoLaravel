<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePonenteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255|min:7',
            'descripcion' => 'nullable|string|max:1000',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Solo imgs, max 2MB
            'areas_experiencia' => 'nullable|array', // debe ser un array
            'areas_experiencia.*' => 'string|max:255', // debe ser solo strings
            'redes_sociales' => 'nullable|string|max:500|url', // url valida
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del ponente es obligatorio.',
            'nombre.min' => 'El nombre debe tener como mínimo 7 caracteres.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres.',
            'foto.image' => 'La foto debe ser una imagen válida.',
            'foto.mimes' => 'La foto debe ser un archivo de tipo jpg, jpeg o png.',
            'foto.max' => 'La foto no puede superar los 2MB.',
            'areas_experiencia.array' => 'Las áreas de experiencia deben ser un arreglo.',
            'areas_experiencia.*.string' => 'Cada área de experiencia debe ser una cadena de texto.',
            'areas_experiencia.*.max' => 'Cada área de experiencia no puede tener más de 255 caracteres.',
            'redes_sociales.string' => 'Las redes sociales deben ser una cadena de texto.',
            'redes_sociales.max' => 'Las redes sociales no pueden tener más de 500 caracteres.',
            'redes_sociales.url' => 'Las redes sociales deben ser una URL válida.',
        ];
    }
}