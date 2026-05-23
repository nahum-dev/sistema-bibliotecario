import { useEffect, useState } from 'react';
import api from '../api/axios';

export default function Prestamos() {
    const [prestamos, setPrestamos] = useState([]);
    const [cargando, setCargando] = useState(true);
    const [mensaje, setMensaje] = useState('');

    const cargar = async () => {
        try {
            const res = await api.get('/prestamos');
            setPrestamos(res.data.data);
        } catch (err) {
            console.error(err);
        } finally {
            setCargando(false);
        }
    };

    useEffect(() => { cargar(); }, []);

    const devolver = async (id) => {
        try {
            await api.put(`/prestamos/${id}/devolver`);
            setMensaje('✅ Devolución registrada exitosamente');
            cargar();
            setTimeout(() => setMensaje(''), 3000);
        } catch (err) {
            setMensaje('❌ ' + (err.response?.data?.message || 'Error al devolver'));
            setTimeout(() => setMensaje(''), 3000);
        }
    };

    const estadoColor = (estado) => {
        if (estado === 'activo') return 'bg-green-100 text-green-700';
        if (estado === 'devuelto') return 'bg-gray-100 text-gray-500';
        return 'bg-red-100 text-red-700';
    };

    return (
        <div>
            <h2 className="text-2xl font-bold text-gray-800 mb-6">📋 Préstamos</h2>

            {mensaje && (
                <div className="mb-4 px-4 py-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-800">
                    {mensaje}
                </div>
            )}

            {cargando ? (
                <p className="text-gray-500">Cargando préstamos...</p>
            ) : (
                <div className="bg-white rounded-xl shadow overflow-hidden">
                    <table className="w-full text-sm">
                        <thead className="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th className="px-6 py-3 text-left">Usuario</th>
                                <th className="px-6 py-3 text-left">Libro</th>
                                <th className="px-6 py-3 text-left">Fecha préstamo</th>
                                <th className="px-6 py-3 text-left">Devolución esperada</th>
                                <th className="px-6 py-3 text-center">Estado</th>
                                <th className="px-6 py-3 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {prestamos.map(p => (
                                <tr key={p.id} className="hover:bg-gray-50">
                                    <td className="px-6 py-4">{p.usuario?.nombre}</td>
                                    <td className="px-6 py-4">{p.libro?.titulo}</td>
                                    <td className="px-6 py-4 text-gray-500">
                                        {new Date(p.fecha_prestamo).toLocaleDateString('es-SV')}
                                    </td>
                                    <td className="px-6 py-4 text-gray-500">
                                        {new Date(p.fecha_devolucion_esperada).toLocaleDateString('es-SV')}
                                    </td>
                                    <td className="px-6 py-4 text-center">
                                        <span className={`px-2 py-1 rounded-full text-xs font-medium ${estadoColor(p.estado)}`}>
                                            {p.estado}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-center">
                                        {p.estado === 'activo' && (
                                            <button onClick={() => devolver(p.id)}
                                                className="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded-lg transition">
                                                Devolver
                                            </button>
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                    {prestamos.length === 0 && (
                        <p className="text-center text-gray-400 py-8">No hay préstamos registrados</p>
                    )}
                </div>
            )}
        </div>
    );
}