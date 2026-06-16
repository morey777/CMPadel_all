import "./Register.css";
import { useState } from 'react';
import { NavLink } from "react-router-dom";

export default function Register() {

    const [mostrar, setMostrar] = useState(false);
    const [email, setEmail] = useState("");
    const [name, setName] = useState("");
    const [lastname, setLastname] = useState("");
    const [phone, setPhone] = useState("");
    const [password, setPassword] = useState("");
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);

    const handleRegister = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError("");

        try {
            const response = await fetch("http://localhost/club_padel_cm/public/api/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                body: JSON.stringify({
                    name: name,
                    lastname: lastname,
                    email: email,
                    phone: phone,
                    password: password,
                }),
            });

            const data = await response.json();

            if (!data.success) {
                data.error_details && setError(data.error_details)
                console.log(data.success);
                setLoading(false);
                return;
            }

            // localStorage.setItem("lelle", JSON.stringify(data));
            if (response.ok) {
                localStorage.setItem("token", data.access_token || data.token);
                if (data.user && data.user.id) {
                    localStorage.setItem("userId", data.user.id);
                }

                setTimeout(() => {
                    window.location.href = "/";
                }, 500); // Medio segundo de margen
            } else {
                console.log("Errores del servidor:", data.errors);
                setError(data.message, "Error en los datos introducidos");
            }
        } catch (err) {
            // error de red o de sintaxis
            console.error("Error detallado:", err);
            setError("Error de conexión o de respuesta del servidor");
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="login-wrap">

            <div className="login-header">
                <img src="img/logo_cmp.png" alt="logo" width="100px" />
                    <h1 className="login-titulo font-bebas">Registrarse</h1>
            </div>

            <form onSubmit={handleRegister}>
                {/* <!-- Nombre --> */}
                <div className="mb-3">
                    <label className="form-label" htmlFor="nombre">Nombre<span style={{color: "red"}}>*</span></label>
                    <input type="text" id="nombre" className="form-control" placeholder="Tu nombre"  value={name} onChange={(e) => setName(e.target.value)} required/>
                </div>

                {/* <!-- Apellido --> */}
                <div className="mb-3">
                    <label className="form-label" htmlFor="apellido">Apellido<span style={{color: "red"}}>*</span></label>
                    <input type="text" id="apellido" className="form-control" placeholder="Tu apellido" value={lastname} onChange={(e) => setLastname(e.target.value)} required/>
                </div>

                {/* <!-- Correo --> */}
                <div className="mb-3">
                    <label className="form-label" htmlFor="email">Correo electrónico<span style={{color: "red"}}>*</span></label>
                    <input type="email" id="email" className="form-control" placeholder="tucorreo@email.com" value={email} onChange={(e) => setEmail(e.target.value)} required/>
                </div>

                {/* <!-- Teléfono --> */}
                <div className="mb-3">
                    <label className="form-label" htmlFor="telefono">Teléfono</label>
                    <input type="tel" id="telefono" className="form-control" placeholder="+34 600 000 000" value={phone} onChange={(e) => setPhone(e.target.value)}/>
                </div>

                {/* <!-- Contraseña con icono ojo --> */}
                <div className="mb-3">
                    <label className="form-label" htmlFor="password">Nueva Contraseña<span style={{color: "red"}}>*</span></label>
                    <div className="campo-password">
                        <input type={mostrar ? "text" : "password"} id="password" className="form-control" placeholder="Crear nueva contraseña" value={password} onChange={(e) => setPassword(e.target.value)} required/>
                            <button className="btn-ojo" onClick={() => setMostrar(x => !x)} id="btnOjo" type="button" title="Mostrar/ocultar contraseña">
                                <img id="iconoOjo" src={mostrar ? "img/ojo-mostrar.svg" : "img/ojo-oculto.svg"} alt="Mostrar contraseña" width="20px" />
                            </button>
                    </div>
                </div>

                {/* <!-- Botón registrarse --> */}
                <button className="btn-login" disabled={loading}>{loading ? "Registrando..." : "Registrarse"}</button>
            </form>

            {error && <p className="mt-4 text-danger text-center">{error}</p>}

            <div className="separador">o</div>

            <div className="registro-bloque">
                <p>¿Ya tienes cuenta en CMPádel?</p>
                <NavLink to="/login" className="btn-registro">
                    Iniciar sesión
                </NavLink>
            </div>

        </div>
    )
}