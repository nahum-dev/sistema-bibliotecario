import { useEffect, useState } from 'react';
import api from '../api/axios';

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
  if (estado === 'activo')   return <span className="badge badge-green">Activo</span>;
  if (estado === 'devuelto') return <span className="badge badge-gray">Devuelto</span>;
  if (estado === 'vencido')  return <span className="badge badge-red">Vencido</span>;
  return <span className="badge badge-gray">{estado}</span>;
};

export default function Prestamos() {
  const [prestamos, setPrestamos] = useState([]);
  const [usuarios, setUsuarios] = useState([]);
  const [libros, setLibros] = useState([]);
  const [cargando, setCargando] = useState(true);
  const [modal, setModal] = useState(false);
  const [filtro, setFiltro] = useState('todos');
  const [form, setForm] = useState({ usuario_id: '', libro_id: '', fecha_devolucion_esperada: '' });
  const [guardando, setGuardando] = useState(false);
  const [msg, setMsg] = useState(null);

  const cargar = async () => {
    try {
      const res = await api.get('/prestamos');
      setPrestamos(res.data.data);
    } catch (e) {
      console.error(e);
    } finally {
      setCargando(false);
    }
  };

  useEffect(() => {
    cargar();
    api.get('/usuarios').then(r => setUsuarios(r.data.data || [])).catch(() => {});
    api.get('/libros').then(r => setLibros(r.data.data || [])).catch(() => {});
  }, []);

  const mostrarMsg = (tipo, texto) => {
    setMsg({ tipo, texto });
    setTimeout(() => setMsg(null), 3000);
  };

  const crear = async () => {
    setGuardando(true);
    try {
      await api.post('/prestamos', form);
      mostrarMsg('ok', 'Préstamo registrado exitosamente');
      setModal(false);
      cargar();
    } catch (e) {
      mostrarMsg('error', e.response?.data?.message || 'Error al registrar');
    } finally {
      setGuardando(false);
    }
  };

  const devolver = async (id) => {
    if (!confirm('¿Registrar devolución de este préstamo?')) return;
    try {
      await api.put(`/prestamos/${id}/devolver`);
      mostrarMsg('ok', 'Devolución registrada exitosamente');
      cargar();
    } catch (e) {
      mostrarMsg('error', e.response?.data?.message || 'Error al registrar devolución');
    }
  };

  const filtrados = prestamos.filter(p => {
    if (filtro === 'activos')   return p.estado === 'activo';
    if (filtro === 'devueltos') return p.estado === 'devuelto';
    if (filtro === 'vencidos')  return p.estado === 'vencido';
    return true;
  });

  const mañana = new Date();
  mañana.setDate(mañana.getDate() + 1);
  const minFecha = mañana.toISOString().split('T')[0];

  const formatFecha = (f) => f ? new Date(f).toLocaleDateString('es-SV') : '—';

  return (
    <div>
      <div className="page-header-row">
        <div>
          <h1 className="page-title">Préstamos</h1>
          <p className="page-subtitle">Control de préstamos de libros</p>
        </div>
        <button className="btn btn-primary" onClick={() => { setForm({ usuario_id: '', libro_id: '', fecha_devolucion_esperada: '' }); setModal(true); }}>
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2.5">
            <path strokeLinecap="round" strokeLinejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Nuevo préstamo
        </button>
      </div>

      {msg && (
        <div className={`alert ${msg.tipo === 'ok' ? 'alert-success' : 'alert-error'}`}>
          {msg.texto}
        </div>
      )}

      <div className="filter-tabs">
        {[['todos', 'Todos'], ['activos', 'Activos'], ['devueltos', 'Devueltos'], ['vencidos', 'Vencidos']].map(([v, l]) => (
          <button key={v} className={`filter-tab ${filtro === v ? 'active' : ''}`} onClick={() => setFiltro(v)}>{l}</button>
        ))}
      </div>

      <div className="card">
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
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              {cargando ? (
                <tr><td colSpan={7} className="table-empty">Cargando...</td></tr>
              ) : filtrados.length === 0 ? (
                <tr><td colSpan={7} className="table-empty">Sin préstamos</td></tr>
              ) : filtrados.map(p => {
                const vencido = p.estado === 'activo' && new Date(p.fecha_devolucion_prevista) < new Date();
                return (
                  <tr key={p.id_prestamo} className={vencido ? 'row-warning' : ''}>
                    <td className="td-mono">#{p.id_prestamo}</td>
                    <td className="td-primary">
                      {p.usuario ? `${p.usuario.nombres} ${p.usuario.apellidos || ''}`.trim() : '—'}
                    </td>
                    <td>
                      <div className="truncate" style={{ maxWidth: '180px' }}>{p.libro?.titulo || '—'}</div>
                    </td>
                    <td>{formatFecha(p.fecha_salida)}</td>
                    <td className={vencido ? 'text-vencido' : ''}>
                      {formatFecha(p.fecha_devolucion_prevista)}
                      {vencido && <span className="badge badge-red" style={{ marginLeft: '6px' }}>Vencido</span>}
                    </td>
                    <td>{estadoBadge(p.estado)}</td>
                    <td>
                      {p.estado === 'activo' && (
                        <button className="btn btn-success btn-sm" onClick={() => devolver(p.id_prestamo)}>
                          Devolver
                        </button>
                      )}
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>

      {modal && (
        <Modal titulo="Nuevo préstamo" onClose={() => setModal(false)}>
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
          </div>

          <div className="form-group">
            <label>Libro *</label>
            <select value={form.libro_id} onChange={e => setForm({ ...form, libro_id: e.target.value })}>
              <option value="">Seleccionar libro...</option>
              {libros.filter(l => l.cantidad_disponible > 0).map(l => (
                <option key={l.id_libro} value={l.id_libro}>
                  {l.titulo} ({l.cantidad_disponible} disponibles)
                </option>
              ))}
            </select>
          </div>

          <div className="form-group">
            <label>Fecha de devolución esperada *</label>
            <input
              type="date"
              min={minFecha}
              value={form.fecha_devolucion_esperada}
              onChange={e => setForm({ ...form, fecha_devolucion_esperada: e.target.value })}
            />
          </div>

          <div className="modal-footer">
            <button className="btn btn-ghost" onClick={() => setModal(false)}>Cancelar</button>
            <button
              className="btn btn-primary"
              onClick={crear}
              disabled={guardando || !form.usuario_id || !form.libro_id || !form.fecha_devolucion_esperada}
            >
              {guardando ? 'Registrando...' : 'Registrar préstamo'}
            </button>
          </div>
        </Modal>
      )}
    </div>
  );
}