import { useEffect, useState } from 'react';
import api from '../api/axios';

export default function Dashboard() {
    const [stats, setStats] = useState({ libros: 0, prestamos: 0, usuarios: 0, vencidos: 0 });

    useEffect(() => {
        const cargar = async () => {
            try {
                const [libros, prestamos, usuarios, vencidos] = await Promise.all([
                    api.get('/libros'),
                    api.get('/prestamos'),
                    api.get('/usuarios'),
                    api.get('/prestamos-vencidos'),
                ]);
                setStats({
                    libros: libros.data.data.length,
                    prestamos: prestamos.data.data.filter(p => p.estado === 'activo').length,
                    usuarios: usuarios.data.data.length,
                    vencidos: vencidos.data.data.length,
                });
            } catch (err) {
                console.error(err);
            }
        };
        cargar();
    }, []);

    const tarjetas = [
        { label: 'Total Libros', valor: stats.libros, color: 'bg-blue-500', icono: '📖' },
        { label: 'Préstamos Activos', valor: stats.prestamos, color: 'bg-green-500', icono: '📋' },
        { label: 'Usuarios', valor: stats.usuarios, color: 'bg-purple-500', icono: '👥' },
        { label: 'Préstamos Vencidos', valor: stats.vencidos, color: 'bg-red-500', icono: '⚠️' },
    ];

    return (
        <div>
            <h2 className="text-2xl font-bold text-gray-800 mb-6">Dashboard</h2>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {tarjetas.map((t) => (
                    <div key={t.label} className={`${t.color} text-white rounded-xl p-6 shadow`}>
                        <div className="text-4xl mb-2">{t.icono}</div>
                        <div className="text-3xl font-bold">{t.valor}</div>
                        <div className="text-sm opacity-90 mt-1">{t.label}</div>
                    </div>
                ))}
            </div>
        </div>
    );
}