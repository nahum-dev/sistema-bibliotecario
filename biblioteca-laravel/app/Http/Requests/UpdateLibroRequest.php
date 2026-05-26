<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLibroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $libroId = $this->route('libro')->id_libro;

        return [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'isbn' => 'required|string|unique:libros,isbn,' . $libroId . ',id_libro|max:20',
            'anio_publicacion' => 'nullable|integer|min:1000|max:' . date('Y'),
            'editorial' => 'nullable|string|max:255',
            'id_categoria' => 'required|integer|exists:categorias,id_categoria',
            'cantidad_total' => 'required|integer|min:1',
            'cantidad_disponible' => 'required|integer|min:0',
            'estado' => 'required|in:disponible,prestado,dañado,perdido',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'isbn.unique' => 'El ISBN ya existe.',
            'id_categoria.required' => 'Debes seleccionar una categoría.',
            'id_categoria.exists' => 'La categoría no existe.',
        ];
    }
}