import { useEffect, useState } from 'react';
import api from '../api/axios';

export default function Libros() {
    const [libros, setLibros] = useState([]);
    const [buscar, setBuscar] = useState('');
    const [cargando, setCargando] = useState(true);

    const cargar = async () => {
        setCargando(true);
        try {
            const res = await api.get('/libros', { params: buscar ? { buscar } : {} });
            setLibros(res.data.data);
        } catch (err) {
            console.error(err);
        } finally {
            setCargando(false);
        }
    };

    useEffect(() => { cargar(); }, []);

    return (
        <div>
            <div className="flex justify-between items-center mb-6">
                <h2 className="text-2xl font-bold text-gray-800">📖 Libros</h2>
                <input
                    type="text"
                    placeholder="Buscar por título..."
                    value={buscar}
                    onChange={e => setBuscar(e.target.value)}
                    onKeyDown={e => e.key === 'Enter' && cargar()}
                    className="border border-gray-300 rounded-lg px-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            {cargando ? (
                <p className="text-gray-500">Cargando libros...</p>
            ) : (
                <div className="bg-white rounded-xl shadow overflow-hidden">
                    <table className="w-full text-sm">
                        <thead className="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th className="px-6 py-3 text-left">Título</th>
                                <th className="px-6 py-3 text-left">Categoría</th>
                                <th className="px-6 py-3 text-left">Autores</th>
                                <th className="px-6 py-3 text-center">Disponibles</th>
                                <th className="px-6 py-3 text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {libros.map(libro => (
                                <tr key={libro.id} className="hover:bg-gray-50">
                                    <td className="px-6 py-4 font-medium">{libro.titulo}</td>
                                    <td className="px-6 py-4 text-gray-500">{libro.categoria?.nombre}</td>
                                    <td className="px-6 py-4 text-gray-500">
                                        {libro.autores?.map(a => `${a.nombre} ${a.apellido}`).join(', ')}
                                    </td>
                                    <td className="px-6 py-4 text-center">
                                        <span className={`px-2 py-1 rounded-full text-xs font-medium ${
                                            libro.cantidad_disponible > 0
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-700'
                                        }`}>
                                            {libro.cantidad_disponible}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-center text-gray-500">{libro.cantidad_total}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                    {libros.length === 0 && (
                        <p className="text-center text-gray-400 py-8">No se encontraron libros</p>
                    )}
                </div>
            )}
        </div>
    );
}