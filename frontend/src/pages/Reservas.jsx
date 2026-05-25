import { useEffect, useState } from 'react';
import api from '../api/axios';
import { useAuth } from '../context/AuthContext';

function Modal({ titulo, onClose, children }) {
  return (
    <div className="modal-overlay">
      <div className="modal">
        <div className="modal-header">
          <h3 className="modal-title">{titulo}</h3>
          <button className="btn btn-ghost" onClick={onClose}>
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
              <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div className="modal-body">{children}</div>
      </div>
    </div>
  );
}

const estadoBadge = (estado) => {
  if (estado === 'pendiente')  return <span className="badge badge-yellow">Pendiente</span>;
  if (estado === 'confirmada') return <span className="badge badge-green">Confirmada</span>;
  if (estado === 'cancelada')  return <span className="badge badge-red">Cancelada</span>;
  if (estado === 'expirada')   return <span className="badge badge-gray">Expirada</span>;
  return <span className="badge badge-gray">{estado}</span>;
};

export default function Reservas() {
  const { usuario } = useAuth();
  const esAdmin = usuario?.rol === 'administrador';

  const [reservas, setReservas] = useState([]);
  const [usuarios, setUsuarios] = useState([]);
  const [libros, setLibros] = useState([]);
  const [cargando, setCargando] = useState(true);
  const [modal, setModal] = useState(false);
  const [filtro, setFiltro] = useState('todos');
  const [form, setForm] = useState({ usuario_id: '', libro_id: '' });
  const [errores, setErrores] = useState({});
  const [guardando, setGuardando] = useState(false);
  const [msg, setMsg] = useState(null);

  const cargar = async () => {
    try {
      const res = await api.get('/reservas');
      const todas = res.data.data;
      // Lector solo ve sus propias reservas
      const filtradas = esAdmin
        ? todas
        : todas.filter(r => r.id_usuario === usuario?.id_usuario);
      setReservas(filtradas);
    } catch (e) {
      console.error(e);
    } finally {
      setCargando(false);
    }
  };

  useEffect(() => {
    cargar();
    if (esAdmin) {
      api.get('/usuarios').then(r => setUsuarios(r.data.data || [])).catch(() => {});
    }
    api.get('/libros').then(r => setLibros(r.data.data || [])).catch(() => {});
  }, []);

  const mostrarMsg = (tipo, texto) => {
    setMsg({ tipo, texto });
    setTimeout(() => setMsg(null), 3000);
  };

  const validar = () => {
    const e = {};
    if (!form.libro_id) e.libro_id = 'Selecciona un libro';
    if (esAdmin && !form.usuario_id) e.usuario_id = 'Selecciona un usuario';
    setErrores(e);
    return Object.keys(e).length === 0;
  };

  const crear = async () => {
    if (!validar()) return;
    setGuardando(true);
    try {
      const payload = { libro_id: form.libro_id };
      if (esAdmin) payload.usuario_id = form.usuario_id;
      await api.post('/reservas', payload);
      mostrarMsg('ok', 'Reserva creada exitosamente');
      setModal(false);
      cargar();
    } catch (e) {
      mostrarMsg('error', e.response?.data?.message || 'Error al crear reserva');
    } finally {
      setGuardando(false);
    }
  };

  const cancelar = async (id) => {
    if (!confirm('¿Cancelar esta reserva?')) return;
    try {
      await api.put(`/reservas/${id}/cancelar`);
      mostrarMsg('ok', 'Reserva cancelada');
      cargar();
    } catch (e) {
      mostrarMsg('error', e.response?.data?.message || 'Error al cancelar');
    }
  };

  const confirmar = async (id) => {
    if (!confirm('¿Confirmar esta reserva?')) return;
    try {
      await api.put(`/reservas/${id}/completar`);
      mostrarMsg('ok', 'Reserva confirmada');
      cargar();
    } catch (e) {
      mostrarMsg('error', e.response?.data?.message || 'Error al confirmar');
    }
  };

  const filtradas = reservas.filter(r => {
    if (filtro === 'pendientes')  return r.estado === 'pendiente';
    if (filtro === 'confirmadas') return r.estado === 'confirmada';
    if (filtro === 'canceladas')  return r.estado === 'cancelada';
    return true;
  });

  const formatFecha = (f) => f ? new Date(f).toLocaleDateString('es-SV') : '—';

  return (
    <div>
      <div className="page-header-row">
        <div>
          <h1 className="page-title">Reservas</h1>
          <p className="page-subtitle">
            {esAdmin ? 'Gestión de reservas de libros' : 'Mis reservas'}
          </p>
        </div>
        <button className="btn btn-primary" onClick={() => { setForm({ usuario_id: '', libro_id: '' }); setErrores({}); setModal(true); }}>
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2.5">
            <path strokeLinecap="round" strokeLinejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Nueva reserva
        </button>
      </div>

      {msg && (
        <div className={`alert ${msg.tipo === 'ok' ? 'alert-success' : 'alert-error'}`}>
          {msg.texto}
        </div>
      )}

      <div className="filter-tabs">
        {[['todos', 'Todas'], ['pendientes', 'Pendientes'], ['confirmadas', 'Confirmadas'], ['canceladas', 'Canceladas']].map(([v, l]) => (
          <button key={v} className={`filter-tab ${filtro === v ? 'active' : ''}`} onClick={() => setFiltro(v)}>{l}</button>
        ))}
      </div>

      <div className="card">
        <div className="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>#</th>
                {esAdmin && <th>Usuario</th>}
                <th>Libro</th>
                <th>Fecha reserva</th>
                <th>Expira</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              {cargando ? (
                <tr><td colSpan={esAdmin ? 7 : 6} className="table-empty">Cargando...</td></tr>
              ) : filtradas.length === 0 ? (
                <tr><td colSpan={esAdmin ? 7 : 6} className="table-empty">Sin reservas</td></tr>
              ) : filtradas.map(r => (
                <tr key={r.id_reserva}>
                  <td className="td-mono">#{r.id_reserva}</td>
                  {esAdmin && (
                    <td className="td-primary">
                      {r.usuario ? `${r.usuario.nombres} ${r.usuario.apellidos || ''}`.trim() : '—'}
                    </td>
                  )}
                  <td>
                    <div className="truncate" style={{ maxWidth: '180px' }}>{r.libro?.titulo || '—'}</div>
                  </td>
                  <td>{formatFecha(r.fecha_reserva)}</td>
                  <td>{formatFecha(r.fecha_expiracion)}</td>
                  <td>{estadoBadge(r.estado)}</td>
                  <td>
                    {r.estado === 'pendiente' && (
                      <div className="flex gap-2">
                        {esAdmin && (
                          <button className="btn btn-success btn-sm" onClick={() => confirmar(r.id_reserva)}>
                            Confirmar
                          </button>
                        )}
                        <button className="btn btn-danger btn-sm" onClick={() => cancelar(r.id_reserva)}>
                          Cancelar
                        </button>
                      </div>
                    )}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {modal && (
        <Modal titulo="Nueva reserva" onClose={() => setModal(false)}>
          <div className="form-group">
            <label>Libro *</label>
            <select value={form.libro_id} onChange={e => setForm({ ...form, libro_id: e.target.value })}>
              <option value="">Seleccionar libro...</option>
              {libros.map(l => (
                <option key={l.id_libro} value={l.id_libro}>
                  {l.titulo} ({l.cantidad_disponible} disponibles)
                </option>
              ))}
            </select>
            {errores.libro_id && <span className="form-error">{errores.libro_id}</span>}
          </div>

          {esAdmin && (
            <div className="form-group">
              <label>Usuario *</label>
              <select value={form.usuario_id} onChange={e => setForm({ ...form, usuario_id: e.target.value })}>
                <option value="">Seleccionar usuario...</option>
                {usuarios.map(u => (
                  <option key={u.id_usuario} value={u.id_usuario}>
                    {u.nombres} {u.apellidos} — {u.carnet_u_identificacion}
                  </option>
                ))}
              </select>
              {errores.usuario_id && <span className="form-error">{errores.usuario_id}</span>}
            </div>
          )}

          <div className="alert alert-warning" style={{ marginBottom: 0 }}>
            La reserva expirará automáticamente en 7 días si no es confirmada.
          </div>

          <div className="modal-footer">
            <button className="btn btn-ghost" onClick={() => setModal(false)}>Cancelar</button>
            <button className="btn btn-primary" onClick={crear} disabled={guardando}>
              {guardando ? 'Creando...' : 'Crear reserva'}
            </button>
          </div>
        </Modal>
      )}
    </div>
  );
}