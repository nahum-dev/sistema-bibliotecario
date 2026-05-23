<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    // GET /api/v1/libros
    public function index(Request $request)
{
    $query = Libro::with(['categoria', 'autores']);

    if ($request->has('categoria_id')) {
        $query->where('id_categoria', $request->categoria_id);
    }
    if ($request->has('disponible')) {
        $query->where('cantidad_disponible', '>', 0);
    }
    if ($request->has('buscar')) {
        $query->where('titulo', 'like', '%' . $request->buscar . '%');
    }

    $libros = $query->orderBy('titulo')->get();

    return response()->json(['status' => 'success', 'data' => $libros]);
}

public function show($id)
{
    $libro = Libro::with([
        'categoria',
        'autores'
    ])->find($id);

    if (!$libro) {
        return response()->json(['status' => 'error', 'message' => 'Libro no encontrado'], 404);
    }

    return response()->json(['status' => 'success', 'data' => $libro]);
}

    // POST /api/v1/libros
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'              => 'required|string|max:255',
            'isbn'                => 'nullable|string|unique:libros,isbn',
            'categoria_id' => 'required|exists:categorias,id_categoria',
            'anio_publicacion'    => 'nullable|integer|min:1000|max:' . date('Y'),
            'cantidad_total'      => 'required|integer|min:1',
            'autores'             => 'nullable|array',
            'autores.*'    => 'exists:autores,id_autor',
        ]);

        $libro = Libro::create([
    'titulo'              => $validated['titulo'],
    'isbn'                => $validated['isbn'] ?? null,
    'id_categoria'        => $validated['categoria_id'],
    'anio_publicacion'    => $validated['anio_publicacion'] ?? null,
    'cantidad_total'      => $validated['cantidad_total'],
    'cantidad_disponible' => $validated['cantidad_total'],
]);

        if (!empty($validated['autores'])) {
            $libro->autores()->sync($validated['autores']);
        }

        return response()->json([
    'status'  => 'success',
    'message' => 'Libro creado exitosamente',
    'data'    => $libro->load(['categoria', 'autores'])
], 201);
    }

    // PUT /api/v1/libros/{id}
    public function update(Request $request, $id)
    {
        $libro = Libro::find($id);

        if (!$libro) {
            return response()->json(['status' => 'error', 'message' => 'Libro no encontrado'], 404);
        }

        $validated = $request->validate([
            'titulo'           => 'sometimes|string|max:255',
            'isbn'             => 'sometimes|nullable|string|unique:libros,isbn,' . $id,
            'categoria_id' => 'sometimes|exists:categorias,id_categoria',
            'anio_publicacion' => 'sometimes|nullable|integer|min:1000|max:' . date('Y'),
            'cantidad_total'   => 'sometimes|integer|min:1',
            'autores'          => 'sometimes|array',
            'autores.*'        => 'exists:autores,id_autor',
        ]);

        $libro->update(collect($validated)->except('autores')->toArray());

        if (isset($validated['autores'])) {
            $libro->autores()->sync($validated['autores']);
        }

        return response()->json([
    'status'  => 'success',
    'message' => 'Libro actualizado',
    'data'    => $libro->load(['categoria', 'autores'])
]);
    }

    // DELETE /api/v1/libros/{id}
    public function destroy($id)
    {
        $libro = Libro::find($id);

        if (!$libro) {
            return response()->json(['status' => 'error', 'message' => 'Libro no encontrado'], 404);
        }

        $libro->autores()->detach();
        $libro->delete();

        return response()->json(['status' => 'success', 'message' => 'Libro eliminado']);
    }
}