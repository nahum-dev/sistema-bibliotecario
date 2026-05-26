@extends('layouts.app')
@section('title', 'Libros - Sistema de Biblioteca')
@section('content')
    <div class="header">
        <h1> Gestión de Libros</h1>
        <p>Vista general de todos los libros disponibles en la biblioteca</p>
    </div>
    <div class="btn-group">
        <a href="/libros/create" class="btn btn-primary">➕ Agregar Nuevo Libro</a>
    </div>
    @if($libros->count() > 0)
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Título</th><th>ISBN</th><th>Categoría</th><th>Autores</th><th>Cantidad</th><th>Estado</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($libros as $libro)
                        <tr>
                            <td><strong>{{ $libro->titulo }}</strong>@if($libro->anio_publicacion)<br><span class="text-muted">({{ $libro->anio_publicacion }})</span>@endif</td>
                            <td>{{ $libro->isbn }}</td>
                            <td>{{ $libro->categoria->nombre_categoria }}</td>
                            <td>@if($libro->autores->count() > 0)@foreach($libro->autores as $autor)<div class="text-muted">{{ $autor->nombre_autor }}</div>@endforeach @else<span class="text-muted">Sin autores</span>@endif</td>
                            <td><strong>{{ $libro->cantidad_disponible }}</strong>/{{ $libro->cantidad_total }}</td>
                            <td><span class="status-badge status-{{ $libro->estado }}">{{ ucfirst($libro->estado) }}</span></td>
                            <td><div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="/libros/{{ $libro->id_libro }}" class="btn btn-primary btn-sm">Ver</a>
                                <a href="/libros/{{ $libro->id_libro }}/edit" class="btn btn-secondary btn-sm">Editar</a>
                                <form action="/libros/{{ $libro->id_libro }}" method="POST" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                                </form>
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem;">
            {{ $libros->links() }}
        </div>
    @else
        <div class="card">
            <p style="text-align: center; color: #7f8c8d; padding: 2rem;">No hay libros registrados. <a href="/libros/create">¡Agrega el primero ahora!</a></p>
        </div>
    @endif
@endsection
