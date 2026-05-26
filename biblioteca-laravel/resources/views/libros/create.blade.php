@extends('layouts.app')
@section('title', 'Crear Libro - Sistema de Biblioteca')
@section('content')
    <div class="header">
        <h1>➕ Crear Nuevo Libro</h1>
        <p>Agrega un nuevo libro al catálogo de la biblioteca</p>
    </div>
    <div class="card">
        <form action="/libros" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="titulo">Título *</label>
                    <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                    @error('titulo')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN *</label>
                    <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}" required>
                    @error('isbn')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion">{{ old('descripcion') }}</textarea>
                @error('descripcion')<div class="error-message">{{ $message }}</div>@enderror
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="anio_publicacion">Año de Publicación</label>
                    <input type="number" id="anio_publicacion" name="anio_publicacion" value="{{ old('anio_publicacion') }}" min="1000" max="{{ date('Y') }}">
                    @error('anio_publicacion')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="editorial">Editorial</label>
                    <input type="text" id="editorial" name="editorial" value="{{ old('editorial') }}">
                    @error('editorial')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="id_categoria">Categoría *</label>
                    <select id="id_categoria" name="id_categoria" required>
                        <option value="">-- Selecciona una categoría --</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria') == $categoria->id_categoria ? 'selected' : '' }}>{{ $categoria->nombre_categoria }}</option>
                        @endforeach
                    </select>
                    @error('id_categoria')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="estado">Estado *</label>
                    <select id="estado" name="estado" required>
                        <option value="disponible" {{ old('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="prestado" {{ old('estado') == 'prestado' ? 'selected' : '' }}>Prestado</option>
                        <option value="dañado" {{ old('estado') == 'dañado' ? 'selected' : '' }}>Dañado</option>
                        <option value="perdido" {{ old('estado') == 'perdido' ? 'selected' : '' }}>Perdido</option>
                    </select>
                    @error('estado')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="cantidad_total">Cantidad Total *</label>
                    <input type="number" id="cantidad_total" name="cantidad_total" value="{{ old('cantidad_total', 1) }}" min="1" required>
                    @error('cantidad_total')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="cantidad_disponible">Cantidad Disponible *</label>
                    <input type="number" id="cantidad_disponible" name="cantidad_disponible" value="{{ old('cantidad_disponible', 1) }}" min="0" required>
                    @error('cantidad_disponible')<div class="error-message">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label for="autores">Autores</label>
                <select id="autores" name="autores[]" multiple>
                    @foreach($autores as $autor)
                        <option value="{{ $autor->id_autor }}" {{ in_array($autor->id_autor, old('autores', [])) ? 'selected' : '' }}>{{ $autor->nombre_autor }}</option>
                    @endforeach
                </select>
                <p class="text-muted">Mantén presionado Ctrl (Cmd en Mac) para seleccionar múltiples autores</p>
                @error('autores')<div class="error-message">{{ $message }}</div>@enderror
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-success">✅ Guardar Libro</button>
                <a href="/libros" class="btn btn-secondary">❌ Cancelar</a>
            </div>
        </form>
    </div>
@endsection
