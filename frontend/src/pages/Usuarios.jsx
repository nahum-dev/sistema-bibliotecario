import { useEffect, useState } from 'react';
import api from '../api/axios';

export default function Usuarios() {
    const [usuarios, setUsuarios] = useState([]);
    const [cargando, setCargando] = useState(true);

    const cargar = async () => {
        try {
            const res = await api.get('/usuarios');
            setUsuarios(res.data.data);
        } catch (err) {
            console.error(err);
        } finally {
            setCargando(false);
        }
    };

    useEffect(() => { cargar(); }, []);

    const rolColor = (rol) => {
        if (rol === 'administrador') return 'bg-red-100 text-red-700';
        if (rol === 'bibliotecario') return 'bg-blue-100 text-blue-700';
        return 'bg-gray-100 text-gray-700';
    };

    return (
        <div>
            <h2 className="text-2xl font-bold text-gray-800 mb-6">👥 Usuarios</h2>
            {cargando ? (
                <p className="text-gray-500">Cargando usuarios...</p>
            ) : (
                <div className="bg-white rounded-xl shadow overflow-hidden">
                    <table className="w-full text-sm">
                        <thead className="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th className="px-6 py-3 text-left">Nombre</th>
                                <th className="px-6 py-3 text-left">Correo</th>
                                <th className="px-6 py-3 text-center">Rol</th>
                                <th className="px-6 py-3 text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {usuarios.map(u => (
                                <tr key={u.id} className="hover:bg-gray-50">
                                    <td className="px-6 py-4 font-medium">{u.nombre}</td>
                                    <td className="px-6 py-4 text-gray-500">{u.correo_electronico}</td>
                                    <td className="px-6 py-4 text-center">
                                        <span className={`px-2 py-1 rounded-full text-xs font-medium ${rolColor(u.rol)}`}>
                                            {u.rol}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-center">
                                        <span className={`px-2 py-1 rounded-full text-xs font-medium ${
                                            u.activo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'
                                        }`}>
                                            {u.activo ? 'Activo' : 'Inactivo'}
                                        </span>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}
        </div>
    );
}