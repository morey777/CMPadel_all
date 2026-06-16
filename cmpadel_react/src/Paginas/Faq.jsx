import Header from "../Plantilla/Header.jsx";
import Footer from "../Plantilla/Footer.jsx";
import "./Faq.css";

export default function Faq() {
    return (
        <>
            <Header />

            <div className="page-wrap">

                <h1 className="page-titulo font-bebas mb-4">Preguntas <span style={{ color: "var(--amarillo-oscuro)" }}>frecuentes</span></h1>

                <div className="accordion" id="faqAccordion">

                    <div className="accordion-item">
                        <h2 className="accordion-header">
                            <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1"> ¿Cómo puedo reservar una pista?</button>
                        </h2>
                        <div id="faq1" className="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div className="accordion-body"> Para reservar una pista, accede a la parte superior de la página y entra en el apartado <strong>"Reservar pista"</strong>. Al hacerlo, verás un calendario. Selecciona el día en el que quieres jugar y, a la derecha, aparecerán las pistas disponibles. Cada pista está identificada con un número e indica si es individual o doble. Al seleccionar una pista, se desplegarán los horarios disponibles para ese día. Los horarios ocupados aparecerán en <strong>rojo y tachados</strong>, mientras que los disponibles estarán en <strong>verde</strong>. Solo podrás seleccionar los horarios disponibles. Una vez elegido el horario, deberás indicar la duración del juego. El precio se mostrará automáticamente según la pista y el tiempo seleccionado.</div>
                        </div>
                    </div>

                    <div className="accordion-item">
                        <h2 className="accordion-header">
                            <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2"> ¿Cuánto cuesta alquilar una pista?</button>
                        </h2>
                        <div id="faq2" className="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div className="accordion-body">
                                Los precios por 1 hora son los siguientes:
                                <ul>
                                    <li>Pista doble exterior: <strong>12 €</strong></li>
                                    <li>Pista doble interior: <strong>14,50 €</strong></li>
                                    <li>Pista individual exterior: <strong>7 €</strong></li>
                                    <li>Pista individual interior: <strong>8,70 €</strong></li>
                                </ul>
                                Si reservas más de 1 hora, cada 30 minutos adicionales tiene un coste de <strong>2 €</strong>.
                            </div>
                        </div>
                    </div>

                    <div className="accordion-item">
                        <h2 className="accordion-header">
                            <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3"> ¿Cómo funciona la duración de la reserva?</button>
                        </h2>
                        <div id="faq3" className="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div className="accordion-body"> Las reservas se realizan en intervalos de <strong>30 minutos</strong>. Por ejemplo, si quieres jugar 1 hora y 30 minutos, deberás seleccionar 1,5 horas. No se permiten reservas inferiores a 1 hora ni duraciones que no sean múltiplos de 30 minutos (por ejemplo, 1h 15min no sería válido). </div>
                        </div>
                    </div>

                    <div className="accordion-item">
                        <h2 className="accordion-header">
                            <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4"> ¿Qué clases aparecen disponibles en la plataforma?</button>
                        </h2>
                        <div id="faq4" className="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div className="accordion-body"> Se muestran todas las clases visibles para el usuario desde que se abre el período de inscripción hasta el día en que se realiza la clase. Esto permite que puedas consultar tanto clases disponibles como aquellas en las que ya estás inscrito, incluso si ya no es posible apuntarse. </div>
                        </div>
                    </div>

                    <div className="accordion-item">
                        <h2 className="accordion-header">
                            <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5"> ¿Qué significa que una clase esté "disponible", "llena" o "no disponible"?</button>
                        </h2>
                        <div id="faq5" className="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div className="accordion-body">
                                <ul>
                                    <li><strong>Disponible:</strong> puedes apuntarte, siempre que haya plazas libres.</li>
                                    <li><strong>Llena:</strong> se ha alcanzado el número máximo de personas y no admite más inscripciones.</li>
                                    <li><strong>No disponible:</strong> la clase ya no admite inscripciones, pero sigue visible para que puedas consultar la información o comprobar si estás inscrito.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div className="accordion-item">
                        <h2 className="accordion-header">
                            <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">  ¿Qué información puedo ver al entrar en una clase? </button>
                        </h2>
                        <div id="faq6" className="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div className="accordion-body">
                                Dentro de cada clase podrás encontrar:
                                <ul>
                                    <li>Descripción de la actividad</li>
                                    <li>Período de inscripción (tiempo disponible para apuntarte antes del cierre)</li>
                                    <li>Fecha y hora de realización</li>
                                    <li>Número máximo de participantes</li>
                                    <li>Monitor que imparte la clase</li>
                                    <li>Precio por persona</li>
                                    <li>Ubicación (pista donde se realiza la clase)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div className="accordion-item">
                        <h2 className="accordion-header">
                            <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7"> ¿Cómo sé si estoy inscrito en una clase? </button>
                        </h2>
                        <div id="faq7" className="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div className="accordion-body">
                                Cuando accedas a una clase, verás un botón:
                                <ul>
                                    <li>Si aparece <strong>"Desinscribirme"</strong>, significa que ya estás inscrito.</li>
                                    <li>Si aparece <strong>"Inscribirme"</strong>, significa que todavía no lo estás.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div className="accordion-item">
                        <h2 className="accordion-header">
                            <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8"> ¿Cómo puedo editar mi perfil?</button>
                        </h2>
                        <div id="faq8" className="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div className="accordion-body"> Para editar tu perfil, primero debes iniciar sesión. Después, accede al icono de perfil en la parte superior de la página. Desde ahí podrás modificar los datos disponibles y guardar los cambios haciendo clic en <strong>"Guardar cambios"</strong>.</div>
                        </div>
                    </div>

                </div>
            </div>

            <Footer />

        </>
    )
}