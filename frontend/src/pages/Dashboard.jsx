import { useEffect, useState } from 'react';
import api from '../api/axios';
import { useAuth } from '../context/AuthContext';

function StatCard({ label, valor, icon, colorClass, subtext }) {
  return (
    <div className="stat-card">
      <div>
        <p className="stat-card-label">{label}</p>
        <p className="stat-card-value">{valor}</p>
        {subtext && <p className="stat-card-sub">{subtext}</p>}
      </div>
      <div className={`stat-card-icon ${colorClass}`}>
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="1.8">
          <path strokeLinecap="round" strokeLinejoin="round" d={icon} />
        </svg>
      </div>
    </div>
  );
}

export default function Dashboard() {
  const { usuario } = useAuth();
  const [stats, setStats] = useState({ libros: 0, prestamos: 0, vencidos: 0, reservas: 0 });
  const [recientes, setRecientes] = useState([]);
  const [cargando, setCargando] = useState(true);

  useEffect(() => {
    const cargar = async () => {
      try {
        const [libros, prestamos, vencidos, reservas] = await Promise.all([
          api.get('/libros'),
          api.get('/prestamos'),
          api.get('/prestamos-vencidos'),
          api.get('/reservas'),
        ]);
        setStats({
          libros: libros.data.data.length,
          prestamos: prestamos.data.data.filter(p => p.estado === 'activo').length,
          vencidos: vencidos.data.data.length,
          reservas: reservas.data.data.filter(r => r.estado === 'pendiente').length,
        });
        setRecientes(prestamos.data.data.slice(0, 6));
      } catch (err) {
        console.error(err);
      } finally {
        setCargando(false);
      }
    };
    cargar();
  }, []);

  const hora = new Date().getHours();
  const saludo = hora < 12 ? 'Buenos días' : hora < 18 ? 'Buenas tardes' : 'Buenas noches';
  const nombre = usuario?.nombres || 'Usuario';

  const estadoBadgeClass = (estado) => {
    if (estado === 'activo') return 'badge badge-green';
    if (estado === 'devuelto') return 'badge badge-gray';
    if (estado === 'vencido') return 'badge badge-red';
    return 'badge badge-gray';
  };

  return (
    <div>
      {/* Saludo */}
      <div className="page-header">
        <p className="text-secondary text-sm">{saludo},</p>
        <h1 className="page-title">{nombre}</h1>
        <p className="page-subtitle">
          {new Date().toLocaleDateString('es-SV', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
        </p>
      </div>

      {/* Stats */}
      <div className="stats-grid">
        <StatCard
          label="Total de libros"
          valor={cargando ? '—' : stats.libros}
          colorClass="blue"
          subtext="En el catálogo"
          icon="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"
        />
        <StatCard
          label="Préstamos activos"
          valor={cargando ? '—' : stats.prestamos}
          colorClass="green"
          subtext="En circulación"
          icon="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
        />
        <StatCard
          label="Préstamos vencidos"
          valor={cargando ? '—' : stats.vencidos}
          colorClass="red"
          subtext="Requieren atención"
          icon="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"
        />
        <StatCard
          label="Reservas pendientes"
          valor={cargando ? '—' : stats.reservas}
          colorClass="purple"
          subtext="Por confirmar"
          icon="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
        />
      </div>

      {/* Tabla de préstamos recientes */}
      <div className="card">
        <div className="card-header">
          <h2 className="card-title">Préstamos recientes</h2>
          <span className="text-muted text-sm">{recientes.length} registros</span>
        </div>
        <div className="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Libro</th>
                <th>Fecha salida</th>
                <th>Vencimiento</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              {cargando ? (
                <tr><td colSpan={6} className="table-empty">Cargando...</td></tr>
              ) : recientes.length === 0 ? (
                <tr><td colSpan={6} className="table-empty">Sin préstamos registrados</td></tr>
              ) : recientes.map(p => (
                <tr key={p.id_prestamo}>
                  <td className="td-mono">#{p.id_prestamo}</td>
                  <td className="td-primary">
                    {p.usuario ? `${p.usuario.nombres} ${p.usuario.apellidos || ''}`.trim() : '—'}
                  </td>
                  <td>{p.libro?.titulo || '—'}</td>
                  <td>{p.fecha_salida ? new Date(p.fecha_salida).toLocaleDateString('es-SV') : '—'}</td>
                  <td>{p.fecha_devolucion_prevista ? new Date(p.fecha_devolucion_prevista).toLocaleDateString('es-SV') : '—'}</td>
                  <td><span className={estadoBadgeClass(p.estado)}>{p.estado}</span></td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}