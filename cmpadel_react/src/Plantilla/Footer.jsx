import { NavLink } from "react-router-dom";
import { MdEmail } from "react-icons/md";
import { FaWhatsapp } from "react-icons/fa";
import "./Footer.css";

export default function Footer() {
    return (
        <footer className="py-4">
            <div className="container py-3">
                <div className="row align-items-center g-4">

                    {/* Logo */}
                    <div className="col-12 col-md-4">
                        <NavLink to="/" className="d-flex align-items-center gap-2 text-decoration-none">
                            <img src="../img/logo_cmp.png" alt="logo" width="44px" />
                        </NavLink>
                    </div>

                    {/* Menu */}
                    <div className="col-12 col-md-4">
                        <ul className="list-unstyled d-flex flex-wrap gap-2 mb-0 justify-content-center">
                            <li>
                                <NavLink to="/faq" className="pie-link">FAQ</NavLink>
                            </li>
                            <li>
                                <NavLink to="/contacto" className="pie-link">Contacto</NavLink>
                            </li>
                        </ul>
                    </div>

                    {/* Redes */}
                    <div className="col-12 col-md-4 d-flex gap-2 justify-content-md-end">
                        <a href="mailto:tuemail@gmail.com?subject=CMPadel&body=Quiero contactarme contigo" style={{ cursor: "pointer" }} >
                            <MdEmail size={30} className="text-light" />
                        </a>
                        <a href="https://wa.me/34000000000" target="_blank" rel="noopener noreferrer" style={{ cursor: "pointer" }}>
                            <FaWhatsapp size={30} className="text-light" style={{ cursor: "pointer" }} />
                        </a>
                    </div>
                </div>

                {/* Copyrigth */}
                <hr style={{ borderColor: "#1a1a1a", marginTop: "1.5rem" }} />
                <div className="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <p className="mb-0" style={{ fontSize: ".77rem", color: "rgba(245, 245, 240, 0.756)", letterSpacing: ".04em" }}>
                        © 2025 cmpadel.com · Todos los derechos reservados
                    </p>
                </div>
            </div>
        </footer>
    )
}