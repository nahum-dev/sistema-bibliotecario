# Sistema Bibliotecario — Universidad Don Bosco

Sistema web para la gestión de biblioteca universitaria desarrollado con Laravel 12 y React 18.

## Integrantes

| Nombre | Carnet |
|--------|--------|
| Anderson Portillo | PA250105 |
| Nahum Flores | FG250084 |
| Ana Ruth López | LL250088 |
| Diana Rivera | RN250387 |
| Tiffany Benítez | BR250073 |

**Curso:** DSS404 — Desarrollo de Software  
**Universidad:** Don Bosco  
**Ciclo:** I 2025

---

## Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 12 + PHP 8.2 |
| Frontend | React 18 + Vite |
| Base de datos | MySQL 8 |
| Autenticación | Laravel Sanctum |
| Estilos | CSS personalizado con variables |

---

## Requisitos previos

- PHP 8.2 o superior
- Composer
- Node.js 18 o superior
- MySQL 8 (o XAMPP)
- Git

---

## Instalación con XAMPP

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd sistema-bibliotecario
```

### 2. Configurar el backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Editar `backend/.env` con los datos de conexión:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblioteca_db
DB_USERNAME=root
DB_PASSWORD=
SANCTUM_STATEFUL_DOMAINS=localhost:5173,localhost:5174
```

Crear las tablas:

```bash
php artisan migrate
```

### 3. Cargar datos de demostración

Abrir phpMyAdmin, seleccionar `biblioteca_db`, ir a la pestaña SQL y ejecutar primero esto para limpiar:

```sql
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE libro_autor;
TRUNCATE TABLE reservas;
TRUNCATE TABLE prestamos;
TRUNCATE TABLE libros;
TRUNCATE TABLE autores;
TRUNCATE TABLE categorias;
TRUNCATE TABLE personal_access_tokens;
TRUNCATE TABLE usuarios;
SET FOREIGN_KEY_CHECKS = 1;
```

Luego pegar y ejecutar el contenido completo de `database/datos_demo.sql`.

### 4. Actualizar el hash de las contrasenas

Las contrasenas estan hasheadas con bcrypt. Si el login falla con credenciales correctas, es porque el hash del SQL no coincide con la version de bcrypt de tu computadora. Para regenerarlo:

```bash
php artisan tinker
Hash::make('password123');
```

Copiar el hash generado y ejecutar en phpMyAdmin:

```sql
UPDATE usuarios SET password_hash = 'HASH_GENERADO_AQUI';
```

### 5. Configurar el frontend

```bash
cd frontend
npm install
```

Crear el archivo `frontend/.env`:

```env
VITE_API_URL=http://localhost:8000/api/v1
```

### 6. Iniciar el sistema

Terminal 1 — Backend:

```bash
cd backend
php artisan serve
```

Terminal 2 — Frontend:

```bash
cd frontend
npm run dev
```

Abrir `http://localhost:5173` en el navegador.

---

## Instalacion con Docker

### Requisitos

- Docker Desktop instalado y corriendo

### Pasos

1. Obtener el APP_KEY en la computadora donde ya funciona el proyecto:

```bash
cd backend
php artisan key:generate --show
```

2. Editar `docker-compose.yml` y reemplazar `TU_APP_KEY_AQUI` con el valor obtenido.

3. Editar `frontend/Dockerfile` y reemplazar la IP:

```dockerfile
ENV VITE_API_URL=http://TU_IP_LOCAL:8000/api/v1
```

Para obtener tu IP local en Windows:

```powershell
ipconfig
```

4. Levantar los contenedores:

```bash
docker-compose up --build
```

5. Cargar los datos de demostración:

```bash
docker exec -i biblioteca_mysql mysql -u biblioteca -pbiblioteca123 biblioteca_db < database/datos_demo.sql
```

6. Abrir `http://localhost:5173` o desde otro dispositivo en la misma red: `http://TU_IP_LOCAL:5173`

---

## Acceso desde otros dispositivos en la misma red

Para que el sistema sea accesible desde telefonos u otras computadoras en la misma red WiFi:

**Backend:**

```bash
cd backend
php artisan serve --host=0.0.0.0 --port=8000
```

**Frontend:**

```bash
cd frontend
npm run dev -- --host
```

Cambiar `frontend/.env` con la IP de la computadora que corre el backend:

```env
VITE_API_URL=http://192.168.X.X:8000/api/v1
```

Obtener la IP en Windows:

```powershell
ipconfig
```

Buscar la linea que dice `Direccion IPv4` bajo el adaptador de red activo.

Cualquier dispositivo conectado al mismo WiFi puede acceder desde: http://192.168.X.X:5173

---

## Credenciales de prueba

| Rol | Correo | Contrasena |
|-----|--------|-----------|
| Administrador | admin@biblioteca.edu | password123 |
| Lector | anderson@universidad.edu | password123 |
| Lector | ana@universidad.edu | password123 |
| Lector | carlos.martinez@udb.edu.sv | password123 |
| Lector | maria.gonzalez@udb.edu.sv | password123 |

---

## API REST

### Autenticacion

| Metodo | Endpoint | Descripcion | Auth |
|--------|----------|-------------|------|
| POST | /api/v1/auth/login | Iniciar sesion | No |
| POST | /api/v1/auth/register | Registrar usuario | No |
| POST | /api/v1/auth/logout | Cerrar sesion | Si |
| GET | /api/v1/auth/me | Usuario autenticado | Si |

### Usuarios

| Metodo | Endpoint | Descripcion | Rol |
|--------|----------|-------------|-----|
| GET | /api/v1/usuarios | Listar usuarios | Administrador |
| GET | /api/v1/usuarios/{id} | Ver usuario | Administrador |
| POST | /api/v1/usuarios | Crear usuario | Administrador |
| PUT | /api/v1/usuarios/{id} | Actualizar usuario | Administrador |
| DELETE | /api/v1/usuarios/{id} | Eliminar usuario | Administrador |

### Libros

| Metodo | Endpoint | Descripcion | Rol |
|--------|----------|-------------|-----|
| GET | /api/v1/libros | Listar libros | Todos |
| GET | /api/v1/libros/{id} | Ver libro | Todos |
| POST | /api/v1/libros | Crear libro | Administrador |
| PUT | /api/v1/libros/{id} | Actualizar libro | Administrador |
| DELETE | /api/v1/libros/{id} | Eliminar libro | Administrador |

### Prestamos

| Metodo | Endpoint | Descripcion | Rol |
|--------|----------|-------------|-----|
| GET | /api/v1/prestamos | Listar prestamos | Todos |
| GET | /api/v1/prestamos/{id} | Ver prestamo | Todos |
| POST | /api/v1/prestamos | Crear prestamo | Todos |
| PUT | /api/v1/prestamos/{id}/devolver | Registrar devolucion | Administrador |
| GET | /api/v1/prestamos-vencidos | Prestamos vencidos | Administrador |

### Reservas

| Metodo | Endpoint | Descripcion | Rol |
|--------|----------|-------------|-----|
| GET | /api/v1/reservas | Listar reservas | Todos |
| GET | /api/v1/reservas/{id} | Ver reserva | Todos |
| POST | /api/v1/reservas | Crear reserva | Todos |
| PUT | /api/v1/reservas/{id}/cancelar | Cancelar reserva | Todos |
| PUT | /api/v1/reservas/{id}/completar | Confirmar reserva | Administrador |

### Catalogos

| Metodo | Endpoint | Descripcion | Rol |
|--------|----------|-------------|-----|
| GET | /api/v1/categorias | Listar categorias | Todos |
| GET | /api/v1/autores | Listar autores | Todos |

---

## Estructura de la Base de Datos

| Tabla | Descripcion |
|-------|-------------|
| usuarios | Cuentas del sistema con roles |
| categorias | Categorias de libros |
| autores | Autores de libros |
| libros | Catalogo con control de stock |
| libro_autor | Relacion muchos a muchos libros-autores |
| prestamos | Registro de prestamos |
| reservas | Reservas de libros |
| personal_access_tokens | Tokens de autenticacion Sanctum |

---

## Estructura del Proyecto

sistema-bibliotecario/
├── backend/                          Laravel 12
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── UsuarioController.php
│   │   │   │   ├── LibroController.php
│   │   │   │   ├── PrestamoController.php
│   │   │   │   └── ReservaController.php
│   │   │   └── Middleware/
│   │   │       └── CheckRole.php
│   │   └── Models/
│   │       ├── Usuario.php
│   │       ├── Libro.php
│   │       ├── Categoria.php
│   │       ├── Autor.php
│   │       ├── Prestamo.php
│   │       └── Reserva.php
│   ├── database/
│   │   ├── migrations/
│   │   ├── seeders/
│   │   │   └── DemoSeeder.php
│   │   └── datos_demo.sql
│   └── routes/
│       └── api.php
└── frontend/                         React 18 + Vite
└── src/
├── api/
│   └── axios.js
├── context/
│   └── AuthContext.jsx
├── components/
│   └── Layout.jsx
├── index.css
└── pages/
├── Login.jsx
├── Dashboard.jsx
├── Libros.jsx
├── Usuarios.jsx
├── Prestamos.jsx
└── Reservas.jsx