-- ============================================================
-- BibliotecaUDB — Datos de Demostración
-- DSS404 · Universidad Don Bosco
-- Ejecutar: mysql -u root -p biblioteca_db < datos_demo.sql
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ── LIMPIAR TABLAS ───────────────────────────────────────────
TRUNCATE TABLE libro_autor;
TRUNCATE TABLE reservas;
TRUNCATE TABLE prestamos;
TRUNCATE TABLE libros;
TRUNCATE TABLE autores;
TRUNCATE TABLE categorias;
TRUNCATE TABLE personal_access_tokens;
TRUNCATE TABLE usuarios;

-- ── CATEGORÍAS ───────────────────────────────────────────────
INSERT INTO categorias (id_categoria, nombre_categoria, descripcion) VALUES
(1, 'Literatura',              'Novelas, cuentos y poesía universal'),
(2, 'Historia',                'Historia universal y latinoamericana'),
(3, 'Filosofía',               'Pensamiento filosófico clásico y moderno'),
(4, 'Ciencias Naturales',      'Biología, física, química y astronomía'),
(5, 'Administración',          'Gestión empresarial y liderazgo'),
(6, 'Derecho',                 'Legislación y jurisprudencia'),
(7, 'Ingeniería de Software',  'Programación, algoritmos y tecnología'),
(8, 'Estructuras de Datos',    'Algoritmos, estructuras y matemática discreta');

-- ── AUTORES ──────────────────────────────────────────────────
INSERT INTO autores (id_autor, nombre_autor, nacionalidad) VALUES
(1, 'Gabriel García Márquez', 'Colombiano'),
(2, 'Isabel Allende',         'Chilena'),
(3, 'Mario Vargas Llosa',     'Peruano'),
(4, 'Yuval Noah Harari',      'Israelí'),
(5, 'Friedrich Nietzsche',    'Alemán'),
(6, 'Charles Darwin',         'Británico'),
(7, 'Robert C. Martin',       'Estadounidense'),
(8, 'Thomas H. Cormen',       'Estadounidense'),
(9, 'Bjarne Stroustrup',      'Danés');

-- ── LIBROS ───────────────────────────────────────────────────
INSERT INTO libros (id_libro, id_categoria, titulo, isbn, editorial, anio_publicacion, cantidad_total, cantidad_disponible, activo) VALUES
(1, 1, 'Cien Años de Soledad',          '978-0-06-088328-7', 'Harper Collins',  1967, 5, 3, 1),
(2, 1, 'La Casa de los Espíritus',      '978-0-15-115873-2', 'Atria Books',     1982, 4, 2, 1),
(3, 2, 'Sapiens',                       '978-0-06-231610-4', 'Harper Collins',  2011, 6, 4, 1),
(4, 3, 'Así Habló Zaratustra', '978-0-14-044118-5', 'Penguin Classics', 1901, 3, 3, 1),
(5, 4, 'El Origen de las Especies', '978-0-14-043205-3', 'Penguin Classics', 1901, 2, 1, 1),
(6, 7, 'Código Limpio',                 '978-0-13-235088-4', 'Prentice Hall',   2008, 4, 2, 1),
(7, 7, 'Introducción a los Algoritmos', '978-0-26-204630-5', 'MIT Press',       2009, 3, 1, 1),
(8, 8, 'El Lenguaje de Programación C++','978-0-32-156384-2','Addison-Wesley',  2013, 2, 2, 1);

-- ── LIBRO_AUTOR ──────────────────────────────────────────────
INSERT INTO libro_autor (id_libro, id_autor) VALUES
(1, 1),
(2, 2),
(3, 4),
(4, 5),
(5, 6),
(6, 7),
(7, 8),
(8, 9);

-- ── USUARIOS ─────────────────────────────────────────────────
-- Contraseña de todos: password123
INSERT INTO usuarios (id_usuario, nombres, apellidos, carnet_u_identificacion, correo_electronico, password_hash, rol, activo) VALUES
(1, 'Admin',    'Sistema',    'AD000001', 'admin@biblioteca.edu',        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uJa1V4/4.', 'administrador', 1),
(2, 'Anderson', 'Portillo',   'PA250105', 'anderson@universidad.edu',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uJa1V4/4.', 'lector',         1),
(3, 'Ana Ruth', 'López',      'LL250088', 'ana@universidad.edu',        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uJa1V4/4.', 'lector',         1),
(4, 'Nahum',    'Flores',     'FG250084', 'nahum@universidad.edu',      '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uJa1V4/4.', 'lector',         1),
(5, 'Diana',    'Rivera',     'RN250387', 'diana@universidad.edu',      '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uJa1V4/4.', 'lector',         1),
(6, 'Carlos',   'Martínez',   'MM250201', 'carlos.martinez@udb.edu.sv', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uJa1V4/4.', 'lector',         1),
(7, 'María',    'González',   'GS250305', 'maria.gonzalez@udb.edu.sv',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uJa1V4/4.', 'lector',         1);

-- ── PRÉSTAMOS ────────────────────────────────────────────────
INSERT INTO prestamos (id_prestamo, id_usuario, id_libro, fecha_salida, fecha_devolucion_prevista, fecha_entrega_real, estado, activo, multa) VALUES
-- Vencidos
(1,  6, 6, '2026-04-01', '2026-04-15', NULL,         'vencido',  1, 0),
(2,  7, 7, '2026-04-10', '2026-04-24', NULL,         'vencido',  1, 0),
(3,  6, 8, '2026-03-20', '2026-04-03', NULL,         'vencido',  1, 0),
-- Devueltos
(4,  2, 6, '2026-03-01', '2026-03-15', '2026-03-14', 'devuelto', 1, 0),
(5,  3, 7, '2026-03-05', '2026-03-19', '2026-03-18', 'devuelto', 1, 0),
(6,  7, 5, '2026-03-10', '2026-03-24', '2026-03-22', 'devuelto', 1, 0),
-- Activos
(7,  6, 7, '2026-05-10', '2026-06-10', NULL,         'activo',   1, 0),
(8,  7, 6, '2026-05-15', '2026-06-15', NULL,         'activo',   1, 0),
(9,  2, 1, '2026-05-20', '2026-06-20', NULL,         'activo',   1, 0);

-- ── RESERVAS ─────────────────────────────────────────────────
INSERT INTO reservas (id_reserva, id_usuario, id_libro, fecha_reserva, fecha_expiracion, estado) VALUES
(1, 6, 8, '2026-05-20', '2026-05-27', 'pendiente'),
(2, 7, 6, '2026-05-18', '2026-05-25', 'confirmada'),
(3, 2, 7, '2026-05-10', '2026-05-17', 'cancelada'),
(4, 3, 3, '2026-05-22', '2026-05-29', 'pendiente'),
(5, 4, 4, '2026-05-01', '2026-05-08', 'expirada');

SET FOREIGN_KEY_CHECKS = 1;