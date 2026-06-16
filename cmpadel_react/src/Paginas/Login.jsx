import { useState } from 'react';
import { NavLink } from "react-router-dom";
import "./Login.css";

export default function Login() {

    const [mostrar, setMostrar] = useState(false);
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);


    const loginearse = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError("");

        try {
            const response = await fetch("http://localhost/club_padel_cm/public/api/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();

            // localStorage.setItem("login_data", JSON.stringify(data));

            if (response.ok) {
                const token = data.access_token || data.token;
                const user = data.user;
                const monitor = data.monitor;

                if (token) {
                    // Guardamos ciertos datos en local
                    localStorage.setItem("token", token);
                    localStorage.setItem("monitor", monitor);

                    if (user && user.id) {
                        localStorage.setItem("userId", user.id);
                    }

                    window.location.href = "/";
                } else {
                    setError(data.error_details);
                }
            } else {
                // Si no hay nada en message, me muestra el error_details
                setError(data.message || data.error_details);
            }
        } catch (error) {
            // error de red o de sintaxis
            console.error("Error de conexión:", error);
            setError("No se pudo conectar con el servidor.");
        } finally {
            setLoading(false);
        }

    }

    return (
        <div className="login-wrap">

            <div className="login-header">
                <img src="img/logo_cmp.png" alt="logo" width="100px" />
                <h1 className="login-titulo font-bebas">Iniciar <span>sesión</span></h1>
            </div>

            <form onSubmit={(e) => loginearse(e)}>
                <div className="mb-3">
                    <label className="form-label" htmlFor="email">Correo electrónico</label>
                    <input type="email" id="email" className="form-control" required placeholder="Tu correo" required value={email} onChange={(e) => setEmail(e.target.value)}/>
                </div>

                <div className="mb-3">
                    <label className="form-label" htmlFor="password">Contraseña</label>
                    <div className="campo-password">
                        <input type={mostrar ? "text" : "password"} id="password" className="form-control" placeholder="Tu contraseña" value={password} onChange={(e) => setPassword(e.target.value)}/>
                        <button className="btn-ojo" onClick={() => setMostrar(x => !x)} id="btnOjo" type="button">
                            <img id="iconoOjo" src={mostrar ? "img/ojo-mostrar.svg" : "img/ojo-oculto.svg"} alt="Mostrar contraseña" width="20px" />
                        </button>
                    </div>
                </div>

                <button className="btn-login"disabled={loading}>{loading ? "Iniciando..." : "Iniciar sesión"}</button>
            </form>
            {error && <p className="mt-4 text-danger text-center">{error}</p>}

            <div className="separador">o</div>

            <div className="registro-bloque">
                <p>¿Aún no tienes cuenta en CMPádel?</p>
                <NavLink to="/register" className="btn-registro">
                    Crear una cuenta
                </NavLink>
            </div>

        </div>
    )

}