import { NavLink } from "react-router-dom";
import Header from "../Plantilla/Header.jsx";
import Footer from "../Plantilla/Footer.jsx";
import "./Inicio.css";

export default function Inicio() {

    return (
        <>
            <Header />

            <section id="hero">
                <div className="hero-grid-bg"></div>
                <div className="hero-glow"></div>
                <div className="hero-content">
                    <h1 className="hero-title"> Juega en<br /> <span>otro nivel</span> </h1>
                    <p className="hero-sub"> Mejora tu nivel de pádel apuntándote a clases, o si prefieres jugar por tu cuenta, reserva una pista y disfruta del partido </p>
                    <div className="hero-botones">
                        <NavLink to="/reserva-pista" className="btn-hero-reserva">Reservar pista</NavLink>
                        <NavLink to="/clases" className="btn-hero-clase">Clases</NavLink>
                    </div>
                </div>
            </section>

            <section id="quienes" className="pt-5 pb-2">
                <div className="container py-4">
                    <h2 className="font-bebas mb-4" style={{ fontSize: "clamp(2.4rem,5vw,4rem)" }}>Quiénes <span>somos</span></h2>
                    <div className="row g-4 align-items-start">
                        <div>
                            <p> En CMPádel queremos ofrecer una forma diferente de disfrutar del pádel, tal y como lo vivimos nosotros. No solo podrás reservar pistas, sino también apuntarte a clases para mejorar tu nivel. A diferencia de otros clubes, buscamos que todo el proceso sea lo más sencillo posible, permitiéndote gestionar tus reservas y clases directamente desde la web de forma rápida y cómoda. Y si prefieres un trato más cercano o necesitas ayuda, siempre puedes ponerte en contacto con nosotros a través de nuestra página de contacto, por whatsApp, por correo electrónico o de forma presencial. Trabajamos cada día para ofrecer una experiencia única, cuidando cada detalle para que disfrutes del pádel de la manera más cómoda posible. </p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="horarios" className="pb-5">
                <div className="container py-4">
                    <h2 className="font-bebas mb-4" style={{ fontSize: "clamp(2.4rem,5vw,4rem)" }}>Horarios</h2>

                    <div className="row g-4">
                        {/* Lunes a Viernes */}
                        <div className="col-md-6">
                            <div className="horario-tarjeta p-4 h-100">
                                <p className="font-bebas text-white mb-3" style={{ fontSize: "clamp(2rem,4vw,3rem)", lineHeight: 1 }}>Lunes a Viernes </p>
                                <div className="d-flex align-items-baseline gap-2">
                                    <span className="tiempo-grande" style={{ color: "var(--amarillo)" }}>08:00</span>
                                    <span className="font-bebas text-white opacity-50" style={{ fontSize: "1.4rem" }}>—</span>
                                    <span className="tiempo-grande" style={{ color: "var(--amarillo)" }}>21:00</span>
                                </div>
                            </div>
                        </div>
                        {/* Sábados y Domingos */}
                        <div className="col-md-6">
                            <div className="horario-tarjeta p-4 h-100">
                                <p className="font-bebas text-white mb-3" style={{ fontSize: "clamp(2rem,4vw,3rem)", lineHeight: 1 }}>Sábados y Domingos </p>
                                <div className="d-flex align-items-baseline gap-2">
                                    <span className="tiempo-grande" style={{ color: "var(--amarillo)" }}>09:00</span>
                                    <span className="font-bebas text-white opacity-50" style={{ fontSize: "1.4rem" }}>—</span>
                                    <span className="tiempo-grande" style={{ color: "var(--amarillo)" }}>20:00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <Footer />
        </>
    )
}