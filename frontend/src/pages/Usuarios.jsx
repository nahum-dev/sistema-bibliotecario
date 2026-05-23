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

const FORM_INICIAL = { nombres: '', apellidos: '', correo_electronico: '', carnet_u_identificacion: '', password: '', rol: 'lector' };

export default function Usuarios() {
  const [usuarios, setUsuarios] = useState([]);
  const [cargando, setCargando] = useState(true);
  const [modal, setModal] = useState(false);
  const [userSel, setUserSel] = useState(null);
  const [form, setForm] = useState(FORM_INICIAL);
  const [guardando, setGuardando] = useState(false);
  const [msg, setMsg] = useState(null);

  const cargar = async () => {
    try {
      const res = await api.get('/usuarios');
      setUsuarios(res.data.data);
    } catch (e) {
      console.error(e);
    } finally {
      setCargando(false);
    }
  };

  useEffect(() => { cargar(); }, []);

  const mostrarMsg = (tipo, texto) => {
    setMsg({ tipo, texto });
    setTimeout(() => setMsg(null), 3000);
  };

  const abrirCrear = () => {
    setForm(FORM_INICIAL);
    setUserSel(null);
    setModal(true);
  };

  const abrirEditar = (u) => {
    setUserSel(u);
    setForm({
      nombres: u.nombres || '',
      apellidos: u.apellidos || '',
      correo_electronico: u.correo_electronico || '',
      carnet_u_identificacion: u.carnet_u_identificacion || '',
      password: '',
      rol: u.rol || 'lector',
    });
    setModal(true);
  };

  const guardar = async () => {
    setGuardando(true);
    try {
      const payload = { ...form };
      if (!payload.password) delete payload.password;
      if (userSel) {
        await api.put(`/usuarios/${userSel.id_usuario}`, payload);
        mostrarMsg('ok', 'Usuario actualizado exitosamente');
      } else {
        await api.post('/usuarios', payload);
        mostrarMsg('ok', 'Usuario creado exitosamente');
      }
      setModal(false);
      cargar();
    } catch (e) {
      mostrarMsg('error', e.response?.data?.message || 'Error al guardar');
    } finally {
      setGuardando(false);
    }
  };

  const eliminar = async (u) => {
    if (!confirm(`¿Eliminar a ${u.nombres} ${u.apellidos}?`)) return;
    try {
      await api.delete(`/usuarios/${u.id_usuario}`);
      mostrarMsg('ok', 'Usuario eliminado');
      cargar();
    } catch (e) {
      mostrarMsg('error', e.response?.data?.message || 'Error al eliminar');
    }
  };

  const rolBadge = (rol) => {
    if (rol === 'administrador') return <span className="badge badge-yellow">Administrador</span>;
    return <span className="badge badge-blue">Lector</span>;
  };

  const iniciales = (u) =>
    ((u.nombres?.[0] || '') + (u.apellidos?.[0] || '')).toUpperCase();

  return (
    <div>
      <div className="page-header-row">
        <div>
          <h1 className="page-title">Usuarios</h1>
          <p className="page-subtitle">Gestión de cuentas del sistema</p>
        </div>
        <button className="btn btn-primary" onClick={abrirCrear}>
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2.5">
            <path strokeLinecap="round" strokeLinejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Nuevo usuario
        </button>
      </div>

      {msg && (
        <div className={`alert ${msg.tipo === 'ok' ? 'alert-success' : 'alert-error'}`}>
          {msg.texto}
        </div>
      )}

      <div className="card">
        <div className="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Usuario</th>
                <th>Carnet / ID</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              {cargando ? (
                <tr><td colSpan={6} className="table-empty">Cargando...</td></tr>
              ) : usuarios.length === 0 ? (
                <tr><td colSpan={6} className="table-empty">Sin usuarios registrados</td></tr>
              ) : usuarios.map(u => (
                <tr key={u.id_usuario}>
                  <td>
                    <div className="flex items-center gap-2">
                      <div className="avatar">{iniciales(u)}</div>
                      <div className="min-w-0">
                        <div className="td-primary">{u.nombres} {u.apellidos}</div>
                      </div>
                    </div>
                  </td>
                  <td className="td-mono">{u.carnet_u_identificacion || '—'}</td>
                  <td>{u.correo_electronico}</td>
                  <td>{rolBadge(u.rol)}</td>
                  <td>
                    <span className={`badge ${u.activo ? 'badge-green' : 'badge-gray'}`}>
                      {u.activo ? 'Activo' : 'Inactivo'}
                    </span>
                  </td>
                  <td>
                    <div className="flex gap-2">
                      <button className="btn btn-secondary btn-sm" onClick={() => abrirEditar(u)}>Editar</button>
                      <button className="btn btn-danger btn-sm" onClick={() => eliminar(u)}>Eliminar</button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {modal && (
        <Modal
          titulo={userSel ? 'Editar usuario' : 'Nuevo usuario'}
          onClose={() => setModal(false)}
        >
          <div className="form-row">
            <div className="form-group">
              <label>Nombres *</label>
              <input type="text" placeholder="Juan Carlos" value={form.nombres} onChange={e => setForm({ ...form, nombres: e.target.value })} />
            </div>
            <div className="form-group">
              <label>Apellidos *</label>
              <input type="text" placeholder="García López" value={form.apellidos} onChange={e => setForm({ ...form, apellidos: e.target.value })} />
            </div>
          </div>

          <div className="form-group">
            <label>Correo electrónico *</label>
            <input type="email" placeholder="correo@universidad.edu" value={form.correo_electronico} onChange={e => setForm({ ...form, correo_electronico: e.target.value })} />
          </div>

          <div className="form-group">
            <label>Carnet / Identificación</label>
            <input type="text" placeholder="AB123456" value={form.carnet_u_identificacion} onChange={e => setForm({ ...form, carnet_u_identificacion: e.target.value })} />
          </div>

          <div className="form-row">
            <div className="form-group">
              <label>{userSel ? 'Nueva contraseña (opcional)' : 'Contraseña *'}</label>
              <input type="password" placeholder="••••••••" value={form.password} onChange={e => setForm({ ...form, password: e.target.value })} />
            </div>
            <div className="form-group">
              <label>Rol</label>
              <select value={form.rol} onChange={e => setForm({ ...form, rol: e.target.value })}>
                <option value="lector">Lector</option>
                <option value="administrador">Administrador</option>
              </select>
            </div>
          </div>

          <div className="modal-footer">
            <button className="btn btn-ghost" onClick={() => setModal(false)}>Cancelar</button>
            <button className="btn btn-primary" onClick={guardar} disabled={guardando}>
              {guardando ? 'Guardando...' : 'Guardar'}
            </button>
          </div>
        </Modal>
      )}
    </div>
  );
}