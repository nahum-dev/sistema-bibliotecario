import { Routes, Route, Navigate } from 'react-router-dom';
import { useAuth } from './context/AuthContext';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import Usuarios from './pages/Usuarios';
import Libros from './pages/Libros';
import Prestamos from './pages/Prestamos';
import Layout from './components/Layout';

function RutaProtegida({ children }) {
    const { usuario, cargando } = useAuth();
    if (cargando) return <div className="flex items-center justify-center h-screen">Cargando...</div>;
    return usuario ? children : <Navigate to="/login" />;
}

export default function App() {
    return (
        <Routes>
            <Route path="/login" element={<Login />} />
            <Route path="/" element={
                <RutaProtegida>
                    <Layout />
                </RutaProtegida>
            }>
                <Route index element={<Dashboard />} />
                <Route path="usuarios" element={<Usuarios />} />
                <Route path="libros" element={<Libros />} />
                <Route path="prestamos" element={<Prestamos />} />
            </Route>
        </Routes>
    );
}