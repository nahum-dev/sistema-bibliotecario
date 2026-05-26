<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Biblioteca')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; color: #333; }
        nav { background-color: #2c3e50; padding: 1rem 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        nav a { color: white; text-decoration: none; margin-right: 2rem; font-weight: 500; transition: color 0.3s; }
        nav a:hover { color: #3498db; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .header { background: white; padding: 2rem; margin-bottom: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header h1 { color: #2c3e50; margin-bottom: 0.5rem; }
        .btn { display: inline-block; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; border: none; cursor: pointer; font-size: 1rem; transition: all 0.3s; }
        .btn-primary { background-color: #3498db; color: white; }
        .btn-primary:hover { background-color: #2980b9; }
        .btn-success { background-color: #27ae60; color: white; }
        .btn-success:hover { background-color: #229954; }
        .btn-danger { background-color: #e74c3c; color: white; }
        .btn-danger:hover { background-color: #c0392b; }
        .btn-secondary { background-color: #95a5a6; color: white; }
        .btn-secondary:hover { background-color: #7f8c8d; }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.875rem; }
        .btn-group { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .alert { padding: 1rem; margin-bottom: 1.5rem; border-radius: 4px; border-left: 4px solid; }
        .alert-success { background-color: #d4edda; color: #155724; border-left-color: #28a745; }
        .alert-error { background-color: #f8d7da; color: #721c24; border-left-color: #f5c6cb; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; }
        input[type="text"], input[type="email"], input[type="number"], input[type="date"], textarea, select { width: 100%; padding: 0.75rem; border: 1px solid #bdc3c7; border-radius: 4px; font-size: 1rem; font-family: inherit; transition: border-color 0.3s; }
        input:focus, textarea:focus, select:focus { outline: none; border-color: #3498db; box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1); }
        textarea { resize: vertical; min-height: 120px; }
        .error-message { color: #e74c3c; font-size: 0.875rem; margin-top: 0.5rem; }
        .errors { background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; padding: 1rem; margin-bottom: 1.5rem; color: #721c24; }
        .errors ul { list-style-position: inside; margin: 0; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        thead { background-color: #34495e; color: white; }
        th { padding: 1rem; text-align: left; font-weight: 600; }
        td { padding: 1rem; border-bottom: 1px solid #ecf0f1; }
        tbody tr:hover { background-color: #f8f9fa; }
        .status-badge { display: inline-block; padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600; }
        .status-disponible { background-color: #d4edda; color: #155724; }
        .status-prestado { background-color: #fff3cd; color: #856404; }
        .status-dañado { background-color: #f8d7da; color: #721c24; }
        .status-perdido { background-color: #e2e3e5; color: #383d41; }
        .card { background: white; border-radius: 8px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .text-muted { color: #7f8c8d; font-size: 0.875rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } nav a { display: block; margin-bottom: 0.5rem; } }
        .detail-row { display: grid; grid-template-columns: 200px 1fr; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #ecf0f1; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 600; color: #2c3e50; }
        .detail-value { color: #555; }
    </style>
</head>
<body>
    <nav>
        <a href="/libros"> Biblioteca</a>
        <a href="/libros/create">➕ Nuevo Libro</a>
    </nav>
    <div class="container">
        @if ($message = session('success'))
            <div class="alert alert-success">{{ $message }}</div>
        @endif
        @if ($errors->any())
            <div class="errors">
                <strong>Por favor revisa los errores:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>
