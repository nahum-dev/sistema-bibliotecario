<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Categoria;
use App\Models\Autor;
use App\Http\Requests\StoreLibroRequest;
use App\Http\Requests\UpdateLibroRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LibroController extends Controller
{
    public function index(): View
    {
        $libros = Libro::with(['categoria', 'autores'])->orderBy('titulo', 'asc')->paginate(10);
        return view('libros.index', compact('libros'));
    }

    public function create(): View
    {
        $categorias = Categoria::orderBy('nombre_categoria', 'asc')->get();
        $autores = Autor::orderBy('nombre_autor', 'asc')->get();
        return view('libros.create', compact('categorias', 'autores'));
    }

    public function store(StoreLibroRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $libro = Libro::create($validated);
        if ($request->filled('autores')) {
            $libro->autores()->sync($request->input('autores'));
        }
        return redirect()->route('libros.show', $libro)->with('success', 'Libro creado exitosamente.');
    }

    public function show(Libro $libro): View
    {
        $libro->load(['categoria', 'autores', 'prestamos']);
        return view('libros.show', compact('libro'));
    }

    public function edit(Libro $libro): View
    {
        $categorias = Categoria::orderBy('nombre_categoria', 'asc')->get();
        $autores = Autor::orderBy('nombre_autor', 'asc')->get();
        // CORREGIDO: usar get() en lugar de pluck() directamente
        $autoresSeleccionados = $libro->autores()->get()->pluck('id_autor')->toArray();
        return view('libros.edit', compact('libro', 'categorias', 'autores', 'autoresSeleccionados'));
    }

    public function update(UpdateLibroRequest $request, Libro $libro): RedirectResponse
    {
        $validated = $request->validated();
        $libro->update($validated);
        if ($request->filled('autores')) {
            $libro->autores()->sync($request->input('autores'));
        } else {
            $libro->autores()->detach();
        }
        return redirect()->route('libros.show', $libro)->with('success', 'Libro actualizado exitosamente.');
    }

    public function destroy(Libro $libro): RedirectResponse
    {
        $titulo = $libro->titulo;
        $libro->delete();
        return redirect()->route('libros.index')->with('success', "El libro '{$titulo}' ha sido eliminado exitosamente.");
    }
}