import "./Header.css";
import { NavLink } from "react-router-dom";

export default function Header() {
    const token = localStorage.getItem("token");
    const monitor = localStorage.getItem("monitor");

    return (
        <nav id="cabecera" className="navbar navbar-expand-lg bg-black sticky-top px-3">
            <div className="container px-0" style={{ maxWidth: "1280px" }}>

                <NavLink to="/" className="navbar-brand d-flex align-items-center gap-2">
                    <img src="../img/logo_cmp.png" alt="logo" width="44px" />
                </NavLink>

                <button className="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav"
                    aria-label="Menú">
                    <span className="navbar-toggler-icon" style={{ filter: "invert(1)" }}></span>
                </button>

                <div className="collapse navbar-collapse justify-content-end" id="menuNav">
                    <ul className="navbar-nav align-items-lg-center gap-lg-1">
                        <li className="nav-item">
                            <NavLink to="/" className={({ isActive }) => isActive ? "nav-link-cm btn-reservar-nav px-3 rounded" : "nav-link-cm"}>Inicio</NavLink>
                        </li>
                        <li className="nav-item">
                            <NavLink to="/reserva-pista" className={({ isActive }) => isActive ? "nav-link-cm btn-reservar-nav px-3 rounded" : "nav-link-cm"}>Reservar Pista</NavLink>
                        </li>
                        <li className="nav-item">
                            <NavLink to="/clases" className={({ isActive }) => isActive ? "nav-link-cm btn-reservar-nav px-3 rounded" : "nav-link-cm"}>Clases</NavLink>
                        </li>
                        <li className="nav-item">
                            <NavLink to="/faq" className={({ isActive }) => isActive ? "nav-link-cm btn-reservar-nav px-3 rounded" : "nav-link-cm"}>FAQ</NavLink>
                        </li>
                        <li className="nav-item">
                            <NavLink to="/contacto" className={({ isActive }) => isActive ? "nav-link-cm btn-reservar-nav px-3 rounded" : "nav-link-cm"}>Contacto</NavLink>
                        </li>

                        {/* Registro/Login */}
                        <li className="nav-item d-flex align-items-center">
                            <NavLink to={monitor == "true" ? "/panel" : token ? "/editPerfil" : "/login"} className="btn-perfil">
                                <img src="../img/perfil.png" alt="Perfil" className="bg-white" />
                            </NavLink>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    )
}