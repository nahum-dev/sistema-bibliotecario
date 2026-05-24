<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // ── PRÉSTAMOS ─────────────────────────────────────────
        DB::table('prestamos')->insert([
            [
                'id_prestamo'              => 4,
                'id_usuario'               => 6,
                'id_libro'                 => 6,
                'fecha_salida'             => '2026-04-01',
                'fecha_devolucion_prevista'=> '2026-04-15',
                'fecha_entrega_real'       => null,
                'estado'                   => 'vencido',
                'activo'                   => 1,
                'multa'                    => 0,
            ],
            [
                'id_prestamo'              => 5,
                'id_usuario'               => 7,
                'id_libro'                 => 7,
                'fecha_salida'             => '2026-04-10',
                'fecha_devolucion_prevista'=> '2026-04-24',
                'fecha_entrega_real'       => null,
                'estado'                   => 'vencido',
                'activo'                   => 1,
                'multa'                    => 0,
            ],
            [
                'id_prestamo'              => 6,
                'id_usuario'               => 6,
                'id_libro'                 => 8,
                'fecha_salida'             => '2026-03-20',
                'fecha_devolucion_prevista'=> '2026-04-03',
                'fecha_entrega_real'       => null,
                'estado'                   => 'vencido',
                'activo'                   => 1,
                'multa'                    => 0,
            ],
            [
                'id_prestamo'              => 7,
                'id_usuario'               => 2,
                'id_libro'                 => 6,
                'fecha_salida'             => '2026-03-01',
                'fecha_devolucion_prevista'=> '2026-03-15',
                'fecha_entrega_real'       => '2026-03-14',
                'estado'                   => 'devuelto',
                'activo'                   => 1,
                'multa'                    => 0,
            ],
            [
                'id_prestamo'              => 8,
                'id_usuario'               => 3,
                'id_libro'                 => 7,
                'fecha_salida'             => '2026-03-05',
                'fecha_devolucion_prevista'=> '2026-03-19',
                'fecha_entrega_real'       => '2026-03-18',
                'estado'                   => 'devuelto',
                'activo'                   => 1,
                'multa'                    => 0,
            ],
            [
                'id_prestamo'              => 9,
                'id_usuario'               => 7,
                'id_libro'                 => 8,
                'fecha_salida'             => '2026-03-10',
                'fecha_devolucion_prevista'=> '2026-03-24',
                'fecha_entrega_real'       => '2026-03-22',
                'estado'                   => 'devuelto',
                'activo'                   => 1,
                'multa'                    => 0,
            ],
            [
                'id_prestamo'              => 10,
                'id_usuario'               => 6,
                'id_libro'                 => 7,
                'fecha_salida'             => '2026-05-10',
                'fecha_devolucion_prevista'=> '2026-06-10',
                'fecha_entrega_real'       => null,
                'estado'                   => 'activo',
                'activo'                   => 1,
                'multa'                    => 0,
            ],
            [
                'id_prestamo'              => 11,
                'id_usuario'               => 7,
                'id_libro'                 => 6,
                'fecha_salida'             => '2026-05-15',
                'fecha_devolucion_prevista'=> '2026-06-15',
                'fecha_entrega_real'       => null,
                'estado'                   => 'activo',
                'activo'                   => 1,
                'multa'                    => 0,
            ],
            [
                'id_prestamo'              => 12,
                'id_usuario'               => 2,
                'id_libro'                 => 8,
                'fecha_salida'             => '2026-05-20',
                'fecha_devolucion_prevista'=> '2026-06-20',
                'fecha_entrega_real'       => null,
                'estado'                   => 'activo',
                'activo'                   => 1,
                'multa'                    => 0,
            ],
        ]);

        // ── RESERVAS ──────────────────────────────────────────
        DB::table('reservas')->insert([
            [
                'id_reserva'       => 3,
                'id_usuario'       => 6,
                'id_libro'         => 8,
                'fecha_reserva'    => '2026-05-20',
                'fecha_expiracion' => '2026-05-27',
                'estado'           => 'pendiente',
            ],
            [
                'id_reserva'       => 4,
                'id_usuario'       => 7,
                'id_libro'         => 6,
                'fecha_reserva'    => '2026-05-18',
                'fecha_expiracion' => '2026-05-25',
                'estado'           => 'confirmada',
            ],
            [
                'id_reserva'       => 5,
                'id_usuario'       => 2,
                'id_libro'         => 7,
                'fecha_reserva'    => '2026-05-10',
                'fecha_expiracion' => '2026-05-17',
                'estado'           => 'cancelada',
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}