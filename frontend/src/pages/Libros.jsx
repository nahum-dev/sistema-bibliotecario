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

const FORM_INICIAL = { titulo: '', isbn: '', editorial: '', anio_publicacion: '', cantidad_total: '', id_categoria: '', autores: [] };

export default function Libros() {
  const [libros, setLibros] = useState([]);
  const [categorias, setCategorias] = useState([]);
  const [autores, setAutores] = useState([]);
  const [buscar, setBuscar] = useState('');
  const [cargando, setCargando] = useState(true);
  const [modal, setModal] = useState(null);
  const [libroSel, setLibroSel] = useState(null);
  const [form, setForm] = useState(FORM_INICIAL);
  const [guardando, setGuardando] = useState(false);
  const [msg, setMsg] = useState(null);

  const cargar = async (q = '') => {
    setCargando(true);
    try {
      const res = await api.get('/libros', { params: q ? { buscar: q } : {} });
      setLibros(res.data.data);
    } catch (e) {
      console.error(e);
    } finally {
      setCargando(false);
    }
  };

  useEffect(() => {
    cargar();
    api.get('/categorias').then(r => setCategorias(r.data.data || [])).catch(() => {});
    api.get('/autores').then(r => setAutores(r.data.data || [])).catch(() => {});
  }, []);

  const mostrarMsg = (tipo, texto) => {
    setMsg({ tipo, texto });
    setTimeout(() => setMsg(null), 3000);
  };

  const abrirCrear = () => {
    setForm(FORM_INICIAL);
    setLibroSel(null);
    setModal('form');
  };

  const abrirEditar = (libro) => {
    setLibroSel(libro);
    setForm({
      titulo: libro.titulo || '',
      isbn: libro.isbn || '',
      editorial: libro.editorial || '',
      anio_publicacion: libro.anio_publicacion || '',
      cantidad_total: libro.cantidad_total || '',
      id_categoria: libro.id_categoria || libro.categoria?.id_categoria || '',
      autores: libro.autores?.map(a => a.id_autor) || [],
    });
    setModal('form');
  };

  const guardar = async () => {
    setGuardando(true);
    try {
      const payload = { ...form, categoria_id: form.id_categoria };
      if (libroSel) {
        await api.put(`/libros/${libroSel.id_libro}`, payload);
        mostrarMsg('ok', 'Libro actualizado exitosamente');
      } else {
        await api.post('/libros', payload);
        mostrarMsg('ok', 'Libro creado exitosamente');
      }
      setModal(null);
      cargar(buscar);
    } catch (e) {
      mostrarMsg('error', e.response?.data?.message || 'Error al guardar');
    } finally {
      setGuardando(false);
    }
  };

  const eliminar = async (libro) => {
    if (!confirm(`¿Eliminar "${libro.titulo}"?`)) return;
    try {
      await api.delete(`/libros/${libro.id_libro}`);
      mostrarMsg('ok', 'Libro eliminado');
      cargar(buscar);
    } catch (e) {
      mostrarMsg('error', e.response?.data?.message || 'Error al eliminar');
    }
  };

  const toggleAutor = (id) => {
    setForm(f => ({
      ...f,
      autores: f.autores.includes(id)
        ? f.autores.filter(a => a !== id)
        : [...f.autores, id],
    }));
  };

  return (
    <div>
      <div className="page-header-row">
        <div>
          <h1 className="page-title">Libros</h1>
          <p className="page-subtitle">Gestión del catálogo bibliográfico</p>
        </div>
        <button className="btn btn-primary" onClick={abrirCrear}>
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2.5">
            <path strokeLinecap="round" strokeLinejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Nuevo libro
        </button>
      </div>

      {msg && (
        <div className={`alert ${msg.tipo === 'ok' ? 'alert-success' : 'alert-error'}`}>
          {msg.texto}
        </div>
      )}

      <div className="card">
        <div className="search-bar">
          <div className="input-icon-wrap" style={{ flex: 1, maxWidth: '300px' }}>
            <span className="input-icon">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                <path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
              </svg>
            </span>
            <input
              type="text"
              placeholder="Buscar por título, ISBN..."
              value={buscar}
              onChange={e => setBuscar(e.target.value)}
              onKeyDown={e => e.key === 'Enter' && cargar(buscar)}
            />
          </div>
          <button className="btn btn-secondary" onClick={() => cargar(buscar)}>Buscar</button>
          <button className="btn btn-ghost" onClick={() => { setBuscar(''); cargar(''); }}>Limpiar</button>
        </div>

        <div className="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Título</th>
                <th>ISBN</th>
                <th>Categoría</th>
                <th>Autores</th>
                <th>Disponibles</th>
                <th>Total</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              {cargando ? (
                <tr><td colSpan={7} className="table-empty">Cargando...</td></tr>
              ) : libros.length === 0 ? (
                <tr><td colSpan={7} className="table-empty">No se encontraron libros</td></tr>
              ) : libros.map(libro => (
                <tr key={libro.id_libro}>
                  <td>
                    <div className="td-primary truncate" style={{ maxWidth: '200px' }}>{libro.titulo}</div>
                    <div className="text-muted text-xs">{libro.editorial} · {libro.anio_publicacion}</div>
                  </td>
                  <td className="td-mono">{libro.isbn || '—'}</td>
                  <td>{libro.categoria?.nombre_categoria || '—'}</td>
                  <td>
                    <div className="truncate text-sm" style={{ maxWidth: '160px' }}>
                      {libro.autores?.map(a => a.nombre_autor).join(', ') || '—'}
                    </div>
                  </td>
                  <td>
                    <span className={`badge ${libro.cantidad_disponible > 0 ? 'badge-green' : 'badge-red'}`}>
                      {libro.cantidad_disponible}
                    </span>
                  </td>
                  <td>{libro.cantidad_total}</td>
                  <td>
                    <div className="flex gap-2">
                      <button className="btn btn-secondary btn-sm" onClick={() => abrirEditar(libro)}>Editar</button>
                      <button className="btn btn-danger btn-sm" onClick={() => eliminar(libro)}>Eliminar</button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {modal === 'form' && (
        <Modal
          titulo={libroSel ? 'Editar libro' : 'Nuevo libro'}
          onClose={() => setModal(null)}
        >
          <div className="form-group">
            <label>Título *</label>
            <input type="text" placeholder="Título del libro" value={form.titulo} onChange={e => setForm({ ...form, titulo: e.target.value })} />
          </div>

          <div className="form-row">
            <div className="form-group">
              <label>ISBN</label>
              <input type="text" placeholder="978-..." value={form.isbn} onChange={e => setForm({ ...form, isbn: e.target.value })} />
            </div>
            <div className="form-group">
              <label>Año de publicación</label>
              <input type="number" placeholder="2024" value={form.anio_publicacion} onChange={e => setForm({ ...form, anio_publicacion: e.target.value })} />
            </div>
          </div>

          <div className="form-row">
            <div className="form-group">
              <label>Editorial</label>
              <input type="text" placeholder="Editorial" value={form.editorial} onChange={e => setForm({ ...form, editorial: e.target.value })} />
            </div>
            <div className="form-group">
              <label>Cantidad total</label>
              <input type="number" placeholder="1" value={form.cantidad_total} onChange={e => setForm({ ...form, cantidad_total: e.target.value })} />
            </div>
          </div>

          <div className="form-group">
            <label>Categoría</label>
            <select value={form.id_categoria} onChange={e => setForm({ ...form, id_categoria: e.target.value })}>
              <option value="">Seleccionar categoría...</option>
              {categorias.map(c => (
                <option key={c.id_categoria} value={c.id_categoria}>{c.nombre_categoria}</option>
              ))}
            </select>
          </div>

          <div className="form-group">
            <label>Autores</label>
            <div className="autores-grid">
              {autores.map(a => (
                <label key={a.id_autor} className={`autor-chip ${form.autores.includes(a.id_autor) ? 'active' : ''}`}>
                  <input
                    type="checkbox"
                    checked={form.autores.includes(a.id_autor)}
                    onChange={() => toggleAutor(a.id_autor)}
                  />
                  {a.nombre_autor}
                </label>
              ))}
              {autores.length === 0 && <span className="text-muted text-sm">Sin autores disponibles</span>}
            </div>
          </div>

          <div className="modal-footer">
            <button className="btn btn-ghost" onClick={() => setModal(null)}>Cancelar</button>
            <button className="btn btn-primary" onClick={guardar} disabled={guardando}>
              {guardando ? 'Guardando...' : 'Guardar'}
            </button>
          </div>
        </Modal>
      )}
    </div>
  );
}