@extends('layouts.app')
@section('title', 'Editar Libro - Sistema de Biblioteca')
@section('content')
    <div class="header">
        <h1>✏️ Editar Libro</h1>
        <p>Modifica la información del libro: {{ $libro->titulo }}</p>
    </div>
    <div class="card">
        <form action="/libros/{{ $libro->id_libro }}" method="POST">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label for="titulo">Título *</label>
                    <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $libro->titulo) }}" required>
                    @error('titulo')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN *</label>
                    <input type="text" id="isbn" name="isbn" value="{{ old('isbn', $libro->isbn) }}" required>
                    @error('isbn')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion">{{ old('descripcion', $libro->descripcion) }}</textarea>
                @error('descripcion')<div class="error-message">{{ $message }}</div>@enderror
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="anio_publicacion">Año de Publicación</label>
                    <input type="number" id="anio_publicacion" name="anio_publicacion" value="{{ old('anio_publicacion', $libro->anio_publicacion) }}" min="1000" max="{{ date('Y') }}">
                    @error('anio_publicacion')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="editorial">Editorial</label>
                    <input type="text" id="editorial" name="editorial" value="{{ old('editorial', $libro->editorial) }}">
                    @error('editorial')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="id_categoria">Categoría *</label>
                    <select id="id_categoria" name="id_categoria" required>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $libro->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>{{ $categoria->nombre_categoria }}</option>
                        @endforeach
                    </select>
                    @error('id_categoria')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="estado">Estado *</label>
                    <select id="estado" name="estado" required>
                        <option value="disponible" {{ old('estado', $libro->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="prestado" {{ old('estado', $libro->estado) == 'prestado' ? 'selected' : '' }}>Prestado</option>
                        <option value="dañado" {{ old('estado', $libro->estado) == 'dañado' ? 'selected' : '' }}>Dañado</option>
                        <option value="perdido" {{ old('estado', $libro->estado) == 'perdido' ? 'selected' : '' }}>Perdido</option>
                    </select>
                    @error('estado')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="cantidad_total">Cantidad Total *</label>
                    <input type="number" id="cantidad_total" name="cantidad_total" value="{{ old('cantidad_total', $libro->cantidad_total) }}" min="1" required>
                    @error('cantidad_total')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="cantidad_disponible">Cantidad Disponible *</label>
                    <input type="number" id="cantidad_disponible" name="cantidad_disponible" value="{{ old('cantidad_disponible', $libro->cantidad_disponible) }}" min="0" required>
                    @error('cantidad_disponible')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label for="autores">Autores</label>
                <select id="autores" name="autores[]" multiple>
                    @foreach($autores as $autor)
                        <option value="{{ $autor->id_autor }}" {{ in_array($autor->id_autor, $autoresSeleccionados) ? 'selected' : '' }}>{{ $autor->nombre_autor }}</option>
                    @endforeach
                </select>
                <p class="text-muted">Mantén presionado Ctrl (Cmd en Mac) para seleccionar múltiples autores</p>
                @error('autores')<div class="error-message">{{ $message }}</div>@enderror
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-success">✅ Actualizar Libro</button>
                <a href="/libros/{{ $libro->id_libro }}" class="btn btn-secondary">❌ Cancelar</a>
            </div>
        </form>
    </div>
@endsection
