import { Routes, Route, Navigate } from 'react-router-dom';
import { useAuth } from './context/AuthContext';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import Usuarios from './pages/Usuarios';
import Libros from './pages/Libros';
import Prestamos from './pages/Prestamos';
import Reservas from './pages/Reservas';
import Layout from './components/Layout';

function Spinner() {
  return (
    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', height: '100vh', background: '#f8fafc' }}>
      <div style={{ textAlign: 'center' }}>
        <svg style={{ animation: 'spin 1s linear infinite', margin: '0 auto 12px' }} width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#3b82f6" strokeWidth="2">
          <path strokeLinecap="round" strokeLinejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
        </svg>
        <p style={{ color: '#6b7280', fontSize: '14px', fontFamily: 'system-ui' }}>Cargando...</p>
      </div>
      <style>{`@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }`}</style>
    </div>
  );
}

function RutaProtegida({ children }) {
  const { usuario, cargando } = useAuth();
  if (cargando) return <Spinner />;
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
        <Route path="reservas" element={<Reservas />} />
      </Route>
    </Routes>
  );
}
