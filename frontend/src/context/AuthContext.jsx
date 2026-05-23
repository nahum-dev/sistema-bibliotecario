import { createContext, useContext, useState, useEffect } from 'react';
import api from '../api/axios';

const AuthContext = createContext();

export function AuthProvider({ children }) {
    const [usuario, setUsuario] = useState(null);
    const [cargando, setCargando] = useState(true);

    useEffect(() => {
        const usuarioGuardado = localStorage.getItem('usuario');
        const token = localStorage.getItem('token');
        if (usuarioGuardado && usuarioGuardado !== 'undefined' && usuarioGuardado !== 'null' && token) {
            try {
                setUsuario(JSON.parse(usuarioGuardado));
            } catch (e) {
                localStorage.removeItem('usuario');
                localStorage.removeItem('token');
            }
        }
        setCargando(false);
    }, []);

    const login = async (correo_electronico, password) => {
        const res = await api.post('/auth/login', { correo_electronico, password });
        const { token, usuario } = res.data.data;
        localStorage.setItem('token', token);
        localStorage.setItem('usuario', JSON.stringify(usuario));
        setUsuario(usuario);
        return usuario;
    };

    const logout = async () => {
        try {
            await api.post('/auth/logout');
        } catch (e) {
            // continúa aunque falle el logout en el servidor
        } finally {
            localStorage.removeItem('token');
            localStorage.removeItem('usuario');
            setUsuario(null);
        }
    };

    return (
        <AuthContext.Provider value={{ usuario, login, logout, cargando }}>
            {children}
        </AuthContext.Provider>
    );
}

export const useAuth = () => useContext(AuthContext);
