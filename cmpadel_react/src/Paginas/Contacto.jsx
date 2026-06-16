import Header from "../Plantilla/Header.jsx";
import Footer from "../Plantilla/Footer.jsx";
import "./Contacto.css";

export default function Contacto() {

    return (
        <>
            <Header />

            <div className="page-contenido">

                <h2 className="page-title font-bebas">Ponte en <span>contacto</span></h2>
                <p style={{ color: "#888", fontSize: ".9rem", marginBottom: "2rem" }}>¿Tienes alguna duda? Escríbenos y te responderemos lo antes posible.</p>

                <div className="mb-3">
                    <label className="form-label" htmlFor="nombre">Nombre</label>
                    <input type="text" id="nombre" className="form-control" placeholder="Tu nombre" />
                </div>

                <div className="mb-3">
                    <label className="form-label" htmlFor="email">Correo electrónico</label>
                    <input type="email" id="email" className="form-control" placeholder="Tu correo" />
                </div>

                <div className="mb-3">
                    <label className="form-label" htmlFor="asunto">Asunto</label>
                    <input type="text" id="asunto" className="form-control" placeholder="Asunto" />
                </div>

                <div className="mb-3">
                    <label className="form-label" htmlFor="mensaje">Mensaje</label>
                    <textarea id="mensaje" className="form-control" placeholder="Escribe tu mensaje aquí..."></textarea>
                </div>

                <button className="btn-enviar">Enviar mensaje</button>

            </div>

            <Footer />
        </>
    )
}