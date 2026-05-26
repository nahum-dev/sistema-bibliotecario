@extends('layouts.app')
@section('title', $libro->titulo . ' - Sistema de Biblioteca')
@section('content')
    <div class="header">
        <h1>📖 {{ $libro->titulo }}</h1>
        <p>Información detallada del libro</p>
    </div>
    <div class="card">
        <div class="detail-row">
            <div class="detail-label">ISBN:</div>
            <div class="detail-value">{{ $libro->isbn }}</div>
        </div>
        @if($libro->editorial)
        <div class="detail-row">
            <div class="detail-label">Editorial:</div>
            <div class="detail-value">{{ $libro->editorial }}</div>
        </div>
        @endif
        @if($libro->anio_publicacion)
        <div class="detail-row">
            <div class="detail-label">Año de Publicación:</div>
            <div class="detail-value">{{ $libro->anio_publicacion }}</div>
        </div>
        @endif
        <div class="detail-row">
            <div class="detail-label">Categoría:</div>
            <div class="detail-value">{{ $libro->categoria->nombre_categoria }}</div>
        </div>
        @if($libro->autores->count() > 0)
        <div class="detail-row">
            <div class="detail-label">Autores:</div>
            <div class="detail-value">
                @foreach($libro->autores as $autor)
                    <div>{{ $autor->nombre_autor }}</div>
                @endforeach
            </div>
        </div>
        @endif
        <div class="detail-row">
            <div class="detail-label">Cantidad Total:</div>
            <div class="detail-value">{{ $libro->cantidad_total }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Cantidad Disponible:</div>
            <div class="detail-value">{{ $libro->cantidad_disponible }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Estado:</div>
            <div class="detail-value">
                <span class="status-badge status-{{ $libro->estado }}">{{ ucfirst($libro->estado) }}</span>
            </div>
        </div>
        @if($libro->descripcion)
        <div class="detail-row">
            <div class="detail-label">Descripción:</div>
            <div class="detail-value">{{ $libro->descripcion }}</div>
        </div>
        @endif
        <div class="detail-row">
            <div class="detail-label">Creado:</div>
            <div class="detail-value">{{ $libro->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Última Actualización:</div>
            <div class="detail-value">{{ $libro->updated_at->format('d/m/Y H:i') }}</div>
        </div>
    </div>
    @if($libro->prestamos->count() > 0)
    <div class="card">
        <h2>Historial de Préstamos</h2>
        <table style="margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Usuario</th><th>Fecha Salida</th><th>Fecha Devolución Prevista</th><th>Fecha Entrega Real</th><th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($libro->prestamos as $prestamo)
                <tr>
                    <td>{{ $prestamo->usuario->nombres }} {{ $prestamo->usuario->apellidos }}</td>
                    <td>{{ $prestamo->fecha_salida->format('d/m/Y') }}</td>
                    <td>{{ $prestamo->fecha_devolución_prevista->format('d/m/Y') }}</td>
                    <td>@if($prestamo->fecha_entrega_real) {{ $prestamo->fecha_entrega_real->format('d/m/Y') }} @else <span class="text-muted">Pendiente</span> @endif</td>
                    <td><span class="status-badge status-{{ $prestamo->estado }}">{{ ucfirst($prestamo->estado) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    <div class="btn-group">
        <a href="/libros/{{ $libro->id_libro }}/edit" class="btn btn-secondary">✏️ Editar</a>
        <a href="/libros" class="btn btn-primary">📚 Volver al Listado</a>
        <form action="/libros/{{ $libro->id_libro }}" method="POST" style="display: inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro?')">🗑️ Eliminar Libro</button>
        </form>
    </div>
@endsection
