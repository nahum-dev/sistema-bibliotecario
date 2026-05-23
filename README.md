# 📚 Sistema Bibliotecario — Universidad Don Bosco

Sistema web para la gestión de biblioteca universitaria desarrollado con Laravel 12 y React.

## 👥 Integrantes

| Nombre | Carnet |
|--------|--------|
| Anderson Portillo | PA250105 |
| Nahum Flores | FG250084 |
| Ana Ruth López | LL250088 |
| Diana Rivera | RN250387 |
| Tiffany Benítez | BR250073 |

**Curso:** DSS404 — Desarrollo de Software  
**Universidad:** Don Bosco

---

## 🛠️ Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 12 + PHP 8.2 |
| Frontend | React 18 + Vite |
| Base de datos | MySQL 8 |
| Autenticación | Laravel Sanctum |
| Estilos | Tailwind CSS |

---

## ⚙️ Requisitos previos

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8
- Git

---

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd sistema-bibliotecario
```

### 2. Configurar el Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Editar `.env` con los datos de la base de datos:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblioteca_db
DB_USERNAME=root
DB_PASSWORD=
```

Ejecutar migraciones y seeders:

```bash
php artisan migrate:fresh --seed
php artisan serve --host=0.0.0.0 --port=8000
```

### 3. Configurar el Frontend

```bash
cd ../frontend
npm install
```

Crear archivo `.env`:

```env
VITE_API_URL=http://localhost:8000/api/v1
```

Iniciar el servidor:

```bash
npm run dev
```

---

## 🔑 Credenciales de prueba

| Rol | Correo | Contraseña |
|-----|--------|-----------|
| Administrador | admin@biblioteca.edu | password123 |
| Estudiante | anderson@universidad.edu | password123 |
| Estudiante | ana@universidad.edu | password123 |

---

## 📡 API REST — Endpoints

### Autenticación
| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| POST | /api/v1/auth/login | Iniciar sesión | No |
| POST | /api/v1/auth/register | Registrar usuario | No |
| POST | /api/v1/auth/logout | Cerrar sesión | Sí |
| GET | /api/v1/auth/me | Usuario autenticado | Sí |

### Usuarios
| Método | Endpoint | Descripción | Rol |
|--------|----------|-------------|-----|
| GET | /api/v1/usuarios | Listar usuarios | Administrador |
| GET | /api/v1/usuarios/{id} | Ver usuario | Administrador |
| POST | /api/v1/usuarios | Crear usuario | Administrador |
| PUT | /api/v1/usuarios/{id} | Actualizar usuario | Administrador |
| DELETE | /api/v1/usuarios/{id} | Eliminar usuario | Administrador |

### Libros
| Método | Endpoint | Descripción | Rol |
|--------|----------|-------------|-----|
| GET | /api/v1/libros | Listar libros | Todos |
| GET | /api/v1/libros/{id} | Ver libro | Todos |
| POST | /api/v1/libros | Crear libro | Admin/Bibliotecario |
| PUT | /api/v1/libros/{id} | Actualizar libro | Admin/Bibliotecario |
| DELETE | /api/v1/libros/{id} | Eliminar libro | Admin/Bibliotecario |

### Préstamos
| Método | Endpoint | Descripción | Rol |
|--------|----------|-------------|-----|
| GET | /api/v1/prestamos | Listar préstamos | Admin/Bibliotecario |
| GET | /api/v1/prestamos/{id} | Ver préstamo | Admin/Bibliotecario |
| POST | /api/v1/prestamos | Crear préstamo | Admin/Bibliotecario |
| PUT | /api/v1/prestamos/{id}/devolver | Registrar devolución | Admin/Bibliotecario |
| GET | /api/v1/prestamos-vencidos | Préstamos vencidos | Admin/Bibliotecario |

### Reservas
| Método | Endpoint | Descripción | Rol |
|--------|----------|-------------|-----|
| GET | /api/v1/reservas | Listar reservas | Todos |
| GET | /api/v1/reservas/{id} | Ver reserva | Todos |
| POST | /api/v1/reservas | Crear reserva | Todos |
| PUT | /api/v1/reservas/{id}/cancelar | Cancelar reserva | Todos |
| PUT | /api/v1/reservas/{id}/completar | Completar reserva | Todos |

---

## 🗄️ Estructura de la Base de Datos
usuarios          — Usuarios del sistema (admin, bibliotecario, estudiante)
categorias        — Categorías de libros
autores           — Autores de libros
libros            — Catálogo de libros con stock
libro_autor       — Relación muchos a muchos libros-autores
prestamos         — Registro de préstamos con control de stock
reservas          — Reservas de libros
personal_access_tokens — Tokens de autenticación Sanctum

---
## 📁 Estructura del Proyecto

sistema-bibliotecario/
├── backend/                  # Laravel 12
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
│   │   └── seeders/
│   └── routes/
│       └── api.php
└── frontend/                 # React + Vite
└── src/
├── api/
│   └── axios.js
├── context/
│   └── AuthContext.jsx
├── components/
│   └── Layout.jsx
└── pages/
├── Login.jsx
├── Dashboard.jsx
├── Libros.jsx
├── Usuarios.jsx
└── Prestamos.jsx

