import { Outlet, NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

export default function Layout() {
    const { usuario, logout } = useAuth();
    const navigate = useNavigate();

    const handleLogout = async () => {
        await logout();
        navigate('/login');
    };

    return (
        <div className="min-h-screen bg-gray-100 flex">
            {/* Sidebar */}
            <aside className="w-64 bg-blue-900 text-white flex flex-col">
                <div className="p-6 border-b border-blue-800">
                    <h1 className="text-xl font-bold">📚 Biblioteca</h1>
                    <p className="text-blue-300 text-sm mt-1">UDB Sistema</p>
                </div>

                <nav className="flex-1 p-4 space-y-1">
                    <NavLink to="/" end className={({ isActive }) =>
                        `block px-4 py-2 rounded-lg transition ${isActive ? 'bg-blue-700' : 'hover:bg-blue-800'}`
                    }>🏠 Dashboard</NavLink>

                    <NavLink to="/libros" className={({ isActive }) =>
                        `block px-4 py-2 rounded-lg transition ${isActive ? 'bg-blue-700' : 'hover:bg-blue-800'}`
                    }>📖 Libros</NavLink>

                    <NavLink to="/prestamos" className={({ isActive }) =>
                        `block px-4 py-2 rounded-lg transition ${isActive ? 'bg-blue-700' : 'hover:bg-blue-800'}`
                    }>📋 Préstamos</NavLink>

                    {usuario?.rol === 'administrador' && (
                        <NavLink to="/usuarios" className={({ isActive }) =>
                            `block px-4 py-2 rounded-lg transition ${isActive ? 'bg-blue-700' : 'hover:bg-blue-800'}`
                        }>👥 Usuarios</NavLink>
                    )}
                </nav>

                <div className="p-4 border-t border-blue-800">
                    <p className="text-sm text-blue-300 truncate">{usuario?.nombre}</p>
                    <p className="text-xs text-blue-400 capitalize mb-3">{usuario?.rol}</p>
                    <button onClick={handleLogout}
                        className="w-full bg-red-600 hover:bg-red-700 text-white text-sm py-2 rounded-lg transition">
                        Cerrar sesión
                    </button>
                </div>
            </aside>

            {/* Contenido */}
            <main className="flex-1 p-8 overflow-y-auto">
                <Outlet />
            </main>
        </div>
    );
}