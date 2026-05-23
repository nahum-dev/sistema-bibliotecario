import { useEffect, useState } from 'react';
import api from '../api/axios';

function Modal({ onClose, children, titulo }) {
  return (
    <div style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.5)', zIndex: 1000, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '16px' }}>
      <div style={{ background: 'white', borderRadius: '12px', width: '100%', maxWidth: '480px', boxShadow: '0 20px 60px rgba(0,0,0,0.15)' }}>
        <div style={{ padding: '20px 24px', borderBottom: '1px solid #e5e7eb', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
          <h3 style={{ fontSize: '16px', fontWeight: '600', color: '#111827', fontFamily: 'system-ui' }}>{titulo}</h3>
          <button onClick={onClose} style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#6b7280' }}>
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2"><path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
          </button>
        </div>
        <div style={{ padding: '24px' }}>{children}</div>
      </div>
    </div>
  );
}

const inputStyle = { width: '100%', padding: '10px 12px', border: '1px solid #d1d5db', borderRadius: '7px', fontSize: '13px', fontFamily: 'system-ui', outline: 'none', boxSizing: 'border-box', color: '#111827' };
const labelStyle = { display: 'block', fontSize: '12px', fontWeight: '600', color: '#374151', marginBottom: '6px', fontFamily: 'system-ui' };

const estadoBadge = (estado) => {
  const map = {
    pendiente: { bg: '#fef3c7', color: '#92400e', text: 'Pendiente' },
    confirmada: { bg: '#f0fdf4', color: '#15803d', text: 'Confirmada' },
    cancelada: { bg: '#fef2f2', color: '#dc2626', text: 'Cancelada' },
    expirada: { bg: '#f3f4f6', color: '#6b7280', text: 'Expirada' },
  }[estado] || { bg: '#f3f4f6', color: '#374151', text: estado };
  return <span style={{ padding: '3px 10px', borderRadius: '20px', fontSize: '11px', fontWeight: '600', background: map.bg, color: map.color }}>{map.text}</span>;
};

export default function Reservas() {
  const [reservas, setReservas] = useState([]);
  const [usuarios, setUsuarios] = useState([]);
  const [libros, setLibros] = useState([]);
  const [cargando, setCargando] = useState(true);
  const [modal, setModal] = useState(false);
  const [filtro, setFiltro] = useState('todos');
  const [form, setForm] = useState({ usuario_id: '', libro_id: '' });
  const [guardando, setGuardando] = useState(false);
  const [msg, setMsg] = useState(null);

  const cargar = async () => {
    try {
      const res = await api.get('/reservas');
      setReservas(res.data.data);
    } catch (e) { console.error(e); }
    finally { setCargando(false); }
  };

  useEffect(() => {
    cargar();
    api.get('/usuarios').then(r => setUsuarios(r.data.data || [])).catch(() => {});
    api.get('/libros').then(r => setLibros(r.data.data || [])).catch(() => {});
  }, []);

  const crear = async () => {
    setGuardando(true);
    try {
      await api.post('/reservas', form);
      setMsg({ tipo: 'ok', texto: 'Reserva creada exitosamente' });
      setModal(false);
      cargar();
    } catch (e) {
      setMsg({ tipo: 'error', texto: e.response?.data?.message || 'Error al crear reserva' });
    } finally {
      setGuardando(false);
      setTimeout(() => setMsg(null), 3000);
    }
  };

  const cancelar = async (id) => {
    if (!confirm('¿Cancelar esta reserva?')) return;
    try {
      await api.put(`/reservas/${id}/cancelar`);
      setMsg({ tipo: 'ok', texto: 'Reserva cancelada' });
      cargar();
    } catch (e) {
      setMsg({ tipo: 'error', texto: e.response?.data?.message || 'Error al cancelar' });
    } finally { setTimeout(() => setMsg(null), 3000); }
  };

  const confirmar = async (id) => {
    if (!confirm('¿Confirmar esta reserva?')) return;
    try {
      await api.put(`/reservas/${id}/completar`);
      setMsg({ tipo: 'ok', texto: 'Reserva confirmada' });
      cargar();
    } catch (e) {
      setMsg({ tipo: 'error', texto: e.response?.data?.message || 'Error al confirmar' });
    } finally { setTimeout(() => setMsg(null), 3000); }
  };

  const filtradas = reservas.filter(r => {
    if (filtro === 'pendientes') return r.estado === 'pendiente';
    if (filtro === 'confirmadas') return r.estado === 'confirmada';
    if (filtro === 'canceladas') return r.estado === 'cancelada';
    return true;
  });

  return (
    <div style={{ padding: '32px', fontFamily: 'system-ui, sans-serif' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '24px' }}>
        <div>
          <h1 style={{ fontSize: '22px', fontWeight: '700', color: '#111827', marginBottom: '2px' }}>Reservas</h1>
          <p style={{ fontSize: '13px', color: '#6b7280' }}>Gestión de reservas de libros</p>
        </div>
        <button onClick={() => { setForm({ usuario_id: '', libro_id: '' }); setModal(true); }} style={{ padding: '9px 18px', background: '#3b82f6', border: 'none', borderRadius: '8px', color: 'white', fontSize: '13px', fontWeight: '600', cursor: 'pointer', display: 'flex', alignItems: 'center', gap: '6px' }}>
          <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="white" strokeWidth="2.5"><path strokeLinecap="round" strokeLinejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
          Nueva reserva
        </button>
      </div>

      {msg && (
        <div style={{ padding: '12px 16px', borderRadius: '8px', marginBottom: '16px', fontSize: '13px', background: msg.tipo === 'ok' ? '#f0fdf4' : '#fef2f2', border: `1px solid ${msg.tipo === 'ok' ? '#bbf7d0' : '#fecaca'}`, color: msg.tipo === 'ok' ? '#15803d' : '#dc2626' }}>{msg.texto}</div>
      )}

      <div style={{ display: 'flex', gap: '8px', marginBottom: '16px' }}>
        {[['todos', 'Todas'], ['pendientes', 'Pendientes'], ['confirmadas', 'Confirmadas'], ['canceladas', 'Canceladas']].map(([v, l]) => (
          <button key={v} onClick={() => setFiltro(v)} style={{ padding: '7px 14px', borderRadius: '7px', fontSize: '12px', fontWeight: '500', cursor: 'pointer', background: filtro === v ? '#3b82f6' : 'white', color: filtro === v ? 'white' : '#6b7280', border: filtro === v ? '1px solid #3b82f6' : '1px solid #e5e7eb' }}>{l}</button>
        ))}
      </div>

      <div style={{ background: 'white', borderRadius: '12px', border: '1px solid #e5e7eb', overflow: 'hidden' }}>
        {cargando ? (
          <div style={{ padding: '48px', textAlign: 'center', color: '#9ca3af' }}>Cargando...</div>
        ) : (
          <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
            <thead>
              <tr style={{ background: '#f9fafb' }}>
                {['#', 'Usuario', 'Libro', 'Fecha reserva', 'Expira', 'Estado', 'Acciones'].map(h => (
                  <th key={h} style={{ padding: '10px 16px', textAlign: 'left', fontSize: '11px', fontWeight: '600', color: '#6b7280', textTransform: 'uppercase', letterSpacing: '0.5px', whiteSpace: 'nowrap' }}>{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {filtradas.map((r, i) => (
                <tr key={r.id_reserva} style={{ borderTop: '1px solid #f3f4f6', background: i % 2 === 0 ? 'white' : '#fafafa' }}>
                  <td style={{ padding: '12px 16px', color: '#9ca3af', fontSize: '12px', fontFamily: 'monospace' }}>#{r.id_reserva}</td>
                  <td style={{ padding: '12px 16px', fontWeight: '500', color: '#111827' }}>
                    {r.usuario ? `${r.usuario.nombres} ${r.usuario.apellidos || ''}`.trim() : '—'}
                  </td>
                  <td style={{ padding: '12px 16px', color: '#374151', maxWidth: '180px' }}>
                    <div style={{ overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{r.libro?.titulo || '—'}</div>
                  </td>
                  <td style={{ padding: '12px 16px', color: '#6b7280', whiteSpace: 'nowrap' }}>{r.fecha_reserva ? new Date(r.fecha_reserva).toLocaleDateString('es-SV') : '—'}</td>
                  <td style={{ padding: '12px 16px', color: '#6b7280', whiteSpace: 'nowrap' }}>{r.fecha_expiracion ? new Date(r.fecha_expiracion).toLocaleDateString('es-SV') : '—'}</td>
                  <td style={{ padding: '12px 16px' }}>{estadoBadge(r.estado)}</td>
                  <td style={{ padding: '12px 16px' }}>
                    {r.estado === 'pendiente' && (
                      <div style={{ display: 'flex', gap: '6px' }}>
                        <button onClick={() => confirmar(r.id_reserva)} style={{ padding: '5px 10px', background: '#f0fdf4', border: '1px solid #bbf7d0', borderRadius: '6px', cursor: 'pointer', color: '#15803d', fontSize: '12px' }}>Confirmar</button>
                        <button onClick={() => cancelar(r.id_reserva)} style={{ padding: '5px 10px', background: '#fef2f2', border: '1px solid #fecaca', borderRadius: '6px', cursor: 'pointer', color: '#dc2626', fontSize: '12px' }}>Cancelar</button>
                      </div>
                    )}
                  </td>
                </tr>
              ))}
              {filtradas.length === 0 && <tr><td colSpan={7} style={{ padding: '48px', textAlign: 'center', color: '#9ca3af' }}>Sin reservas</td></tr>}
            </tbody>
          </table>
        )}
      </div>

      {modal && (
        <Modal titulo="Nueva Reserva" onClose={() => setModal(false)}>
          <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
            <div>
              <label style={labelStyle}>Usuario *</label>
              <select style={inputStyle} value={form.usuario_id} onChange={e => setForm({ ...form, usuario_id: e.target.value })}>
                <option value="">Seleccionar usuario...</option>
                {usuarios.map(u => <option key={u.id_usuario} value={u.id_usuario}>{u.nombres} {u.apellidos} — {u.carnet_u_identificacion}</option>)}
              </select>
            </div>
            <div>
              <label style={labelStyle}>Libro *</label>
              <select style={inputStyle} value={form.libro_id} onChange={e => setForm({ ...form, libro_id: e.target.value })}>
                <option value="">Seleccionar libro...</option>
                {libros.map(l => <option key={l.id_libro} value={l.id_libro}>{l.titulo} ({l.cantidad_disponible} disponibles)</option>)}
              </select>
            </div>
            <p style={{ fontSize: '12px', color: '#6b7280', background: '#f9fafb', padding: '10px 12px', borderRadius: '7px' }}>
              La reserva expirará automáticamente en 7 días si no es confirmada.
            </p>
            <div style={{ display: 'flex', gap: '10px', justifyContent: 'flex-end', paddingTop: '8px' }}>
              <button onClick={() => setModal(false)} style={{ padding: '9px 18px', background: 'white', border: '1px solid #d1d5db', borderRadius: '7px', cursor: 'pointer', fontSize: '13px', color: '#374151' }}>Cancelar</button>
              <button onClick={crear} disabled={guardando || !form.usuario_id || !form.libro_id} style={{ padding: '9px 18px', background: '#3b82f6', border: 'none', borderRadius: '7px', cursor: 'pointer', fontSize: '13px', color: 'white', fontWeight: '600', opacity: (!form.usuario_id || !form.libro_id) ? 0.5 : 1 }}>
                {guardando ? 'Creando...' : 'Crear reserva'}
              </button>
            </div>
          </div>
        </Modal>
      )}
    </div>
  );
}
