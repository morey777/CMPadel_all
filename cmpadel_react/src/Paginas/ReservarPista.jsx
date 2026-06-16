import { useState, useEffect } from 'react';
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';
import Header from "../Plantilla/Header.jsx";
import Footer from "../Plantilla/Footer.jsx";
import "./ReservarPista.css";

// Cabeceras compartidas para todas las llamadas a la API
const API_HEADERS = {
    "x-api-key": "base64:q7LJZBOOfUf8n2SnNYeaVkz/T+QAhvflSvAMJ8P6E7Q=",
    "Accept": "application/json"
};

export default function ReservarPista() {

    // Metiendo las pistas usando la API
    const [pistas, setPistas] = useState([]);

    // Metiendo las reservas usando la API
    const [reservas, setReservas] = useState([]);

    // Metiendo los trainings, para saber que franjas de horarios debo bloquear, para que la gente no se apunte a ellas
    const [trainings, setTrainings] = useState([]);

    // Día que el usuario ha pulsado en el calendario
    const [fechaSeleccionada, setFechaSeleccionada] = useState(null);

    // Identifico que pistas estan abiertas, las que ya estan y las selecciono se cierra el despliegue
    const [pistasAbiertas, setPistasAbiertas] = useState({});

    // Guardo la hora que selecciona el usuario en x pista y dia
    const [horasPorPista, setHorasPorPista] = useState({});

    // duracionPorPista guarda el valor del input de duración de cada pista.
    const [duracionPorPista, setDuracionPorPista] = useState({});

    // Usuario activo
    const userId = localStorage.getItem("userId"); 

    const [cargando, setCargando] = useState(true);  // true mientras el fetch no termina
    const [error, setError] = useState(false);       // true si el fetch falla

    // obtenerPistas: carga pistas y sus reservas desde la API.
    function obtenerPistas() {
        fetch("http://localhost/club_padel_cm/public/api/court", { headers: API_HEADERS })
            .then(res => res.json())
            .then(data => {
                // Si no hay datos no devuelve nada
                if (!data.data) return;

                // Convertir las pistas con otro formato
                const pistasFormateadas = data.data.map(p => ({
                    numPista:       p.numPista,
                    tipo_pista:     p.tipo_pista.nombre, // Doble o Individual
                    tipo_zona: p.tipo_zona.nombre // Interior o Exterior
                }));
                setPistas(pistasFormateadas);

                // Extraer reservas de todos los usuarios de todas las pistas, para el tema de que un usuario no pueda reservar una pista ya ocupada
                const reservasFormateadas = [];
                data.data.forEach(p => {
                    (p.usuarios || []).forEach(u => { // Si da undefined o null, da un array vacio para que no de un error
                        // "10:00:00" → 10,  "10:30:00" → 10.5
                        const [hh, mm] = u.hora.split(":");
                        const horaNum  = parseInt(hh) + (parseInt(mm) === 30 ? 0.5 : 0);
                        reservasFormateadas.push({
                            pistaNum: p.numPista,
                            fecha:    u.dia,
                            hora:     horaNum,
                            duracion: u.duracion
                        });
                    });
                });
                setReservas(reservasFormateadas);
                setCargando(false);

            })
            .catch(error => {
                console.error("Error al obtener pistas:", error);
                setCargando(false);  // <- fetch terminó (con error)
                setError(true);      // <- marcar que hubo fallo
            });
    }

    // obtenerTrainings: carga los trainings desde la API de training.
    function obtenerTrainings() {
        fetch("http://localhost/club_padel_cm/public/api/training", { headers: API_HEADERS })
            .then(res => res.json())
            .then(data => {
                if (!data.data) return;

                const trainingsFormateados = data.data.map(t => {
                    const [hh, mm] = t.hora.split(":");
                    const horaNum  = parseInt(hh) + (parseInt(mm) === 30 ? 0.5 : 0);
                    return {
                        pistaNum: t.numPista,
                        fecha:    t.dia,
                        hora:     horaNum,
                        duracion: t.activity.duracion
                    };
                });
                setTrainings(trainingsFormateados);
            })
            .catch(error => console.error("Error al obtener trainings:", error));
    }

    useEffect(() => {
        alert("Cuidado: Una vez reservada la pista no hay reembolsos");
        obtenerPistas();
        obtenerTrainings();
    }, []);


    // Horario: Devuelve el horario de apertura según el día de la semana
    // getDay() devuelve 0=domingo, 1=lunes ... 6=sábado, ya que es un metodo oficial
    function getHorario(fechaStr) {
        var dia = new Date(fechaStr).getDay();
        if (dia === 0 || dia === 6) return { inicio: 9, fin: 20 }; // fin de semana
        return { inicio: 8, fin: 21 };                              // entre semana
    }


    // Genera los slots: Genera la parte de las horas según el dia, y en cada pista
    function generarSlots(pista) {
        if (!fechaSeleccionada) return [];

        var h = getHorario(fechaSeleccionada); // h: contiene la hora ini y fin, de la apertura según ese dia
        var slots = [];

        for (var hora = h.inicio; hora < h.fin; hora += 0.5) {
            var ocupado = estaOcupado(pista.numPista, fechaSeleccionada, hora);
            var hh = Math.floor(hora);
            var mm = (hora % 1 === 0) ? '00' : '30'; // Ej. 1.5 % 1 === 0, es entero? no, entonces ponemos 30
            var texto = cero(hh) + ':' + mm;

            slots.push({ hora, texto, ocupado });
        }

        return slots;
    }


    // Abre o cierra el cuerpo de una card al hacer click en su cabecera
    function togglePista(pistaNum) {
        setPistasAbiertas(prev => ({
            ...prev,
            [pistaNum]: !prev[pistaNum],
        }));
    }


    // Comprueba si una pista en x hora esta ocupada o no, devolviendo como true que esta ocupada
    // Mira con el training y con reservas si hay algunas pistas en x horas si estan ocupadas
    function estaOcupado(pistaNum, fecha, hora) {
        // For para comprobar las reservas de usuarios
        for (var i = 0; i < reservas.length; i++) {
            var r = reservas[i];
            if (r.pistaNum === pistaNum && r.fecha === fecha && hora >= r.hora && hora < r.hora + r.duracion) {
                return true;
            }
        }
        // For para comprobar los trainings
        for (var j = 0; j < trainings.length; j++) {
            var t = trainings[j];
            if (t.pistaNum === pistaNum && t.fecha === fecha && hora >= t.hora && hora < t.hora + t.duracion) {
                return true;
            }
        }
        return false;
    }


    // Al seleccionar una hora, se guarda la hora elegida + sus anteriores, y también se guarda la duración elegida
    function seleccionar(pistaNum, hora) {
        setHorasPorPista(prev => ({ ...prev, [pistaNum]: hora }));
        setDuracionPorPista(prev => ({ ...prev, [pistaNum]: 1 }));
    }


    // calcPrecio: devuelve el precio de una reserva según pista, zona y duración.
    function calcPrecio(pista, duracion) {
        // Si el usuario nos da una duración menor que 1hora no se podrá reservar, y si no hay duración tampoco 
        if (!duracion || duracion < 1) return "Pon una duración que sea >= 1";

        let base = 0;
        if (pista.tipo_pista == "Doble" && pista.tipo_zona == "Exterior") base = 12;
        if (pista.tipo_pista == "Doble" &&  pista.tipo_zona == "Interior") base = 14.5;
        if (pista.tipo_pista == "Individual" && pista.tipo_zona == "Exterior") base = 7;
        if (pista.tipo_pista == "Individual" &&  pista.tipo_zona == "Interior") base = 8.7;

        const extra = (duracion - 1) / 0.5;
        return extra > 0 ? (base + (extra * 2)) : base ;
    }


    // confirmar: Mira si se puede reservar la pista según algunas condiciones, y si todo va bien lo mete en el array + meto el registro de que se ha metido un usuario en una pista mediante una API
    function confirmar(pistaNum, hora, pistaTipo, pistaCubierta) {
        
        const monitor = localStorage.getItem("monitor");

        if (monitor === "true") {
            alert("Los monitores no pueden reservar pistas");
            return
        }

        var dur = parseFloat(duracionPorPista[pistaNum]);

        var h = getHorario(fechaSeleccionada);

        if (dur < 1) {
            alert('La duración mínima es 1 hora');
            return;
        }

        if (hora + dur > h.fin) {
            alert('Fuera de horario. El cierre es a las ' + cero(h.fin) + ':00');
            return;
        }

        if (dur % 1 !== 0 && dur % 1 !== 0.5) {
            alert("La duración debe ser un número entero o terminar en .5");
            return;
        }

        // Verifico que no se pueda reservar una pista si hay alguien que se haya apuntado
        for (var t = hora; t < hora + dur; t += 0.5) {
            if (estaOcupado(pistaNum, fechaSeleccionada, t)) {
                alert('Lo sentimos, el horario de las ' + cero(Math.floor(t)) + ':' + (t % 1 === 0 ? '00' : '30') + ' ya está ocupado. Elige otro horario.');
                return;
            }
        }

        // Si no ha lanzado ya un return, quiere decir que todo va bien, y meto el registro del usuario con la pista mediante una API
        var hh = Math.floor(hora);
        var mm = (hora % 1 === 0) ? '00' : '30';
        var horaFormato = cero(hh) + ':' + mm + ':00'; // "HH:MM:SS"

        const datosReserva = {
            pista_num: pistaNum,
            duracion: dur,
            user_id: userId,
            dia: fechaSeleccionada,
            hora: horaFormato
        };

        fetch("http://localhost/club_padel_cm/public/api/court", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "x-api-key": "base64:q7LJZBOOfUf8n2SnNYeaVkz/T+QAhvflSvAMJ8P6E7Q="
            },
            body: JSON.stringify(datosReserva)
        })
            .then(res => res.json())
            // .then(lala => console.log(lala))
            .then(data => {
                if (data) {
                    alert(
                        '¡Pista reservada!\n' +
                        'Pista nº ' + pistaNum + ' · ' + pistaTipo + ' · '+ pistaCubierta + '\n' +
                        'Dia: '   + fechaSeleccionada + ' a las '+ + cero(hh) + ':' + mm + '\n' +
                        'Duración: '   + dur*60 + 'min' + '\n' +
                        'Precio: '   + data.precio + '€' + '\n'
                    );
                    setReservas(prev => [...prev, { pistaNum, fecha: fechaSeleccionada, hora, duracion: dur }]);
                    
                    // Limpiar el formulario de esa pista tras confirmar
                    setHorasPorPista(prev => { const s = { ...prev }; delete s[pistaNum]; return s; });
                    setDuracionPorPista(prev => { const s = { ...prev }; delete s[pistaNum]; return s; });
                } else {
                    alert('Error al guardar la reserva. Intentalo de nuevo.');
                }
            })
            .catch(error => {
                console.error("Error al reservar:", error);
                alert('Error de conexión. Intentalo de nuevo.');
            });
    }

    // Añade un cero delante si el número es menor que 10, Ej. 8 -> "08", 12 -> "12"
    function cero(h) {
        return h < 10 ? '0' + h : '' + h;
    }


    // colorea la celda del dia seleccionada
    const dayCellClassNames = (arg) => {
        // convierte la fecha a formato YYYY-MM-DD para evitar problemas de localización
        const fechaCelda = arg.date.toLocaleDateString("sv-SE"); // "YYYY-MM-DD"
        if (fechaSeleccionada && fechaCelda === fechaSeleccionada) {
            return ['dia-seleccionada'];
        }
        return [];
    };

    const handleDateClick = (info) => {
        // Bloquear días pasados
        var hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        if (new Date(info.dateStr) < hoy) return;

        setFechaSeleccionada(info.dateStr); // YYYY-MM-DD

        setPistasAbiertas({});
        setHorasPorPista({});
        setDuracionPorPista({});
    };

    return (
        <>
            <Header />

            <div className="page-contenido">

                <h2 className="font-bebas" style={{ fontSize: "clamp(2.4rem,5vw,4rem)" }}>
                    Reserva tu <span style={{ color: "var(--amarillo-oscuro)" }}>pista</span>
                </h2>
                <p style={{ color: "#888", fontSize: ".9rem" }}>Selecciona un día y elige pista, hora y duración.</p>

                <div className="reserva-layout">

                    {/* Calendario */}
                    <div id="calendario-div">
                        <FullCalendar
                            plugins={[dayGridPlugin, interactionPlugin]}
                            initialView="dayGridMonth"
                            locale={esLocale}
                            headerToolbar={{ left: 'prev', center: 'title', right: 'next' }}
                            height="auto"
                            fixedWeekCount={true}
                            dateClick={handleDateClick}
                            dayCellClassNames={dayCellClassNames}
                        />

                        <div className="horario-info">
                            <b>Lun-Vie:</b> 08:00-21:00 &nbsp;|&nbsp; <b>Sáb-Dom:</b> 09:00-20:00
                        </div>
                    </div>

                    {/* Lista de pistas */}
                    <div id="pistas-div">
                        <h5 className="font-bebas" style={{ fontSize: "1.3rem", marginBottom: ".8rem" }}>Pistas</h5>
                        <div id="listaPistas">
                            {cargando ? (
                                <p style={{ color: "#aaa", fontSize: ".85rem" }}>Cargando pistas...</p>
                            ) : error ? (
                                <p style={{ fontSize: ".85rem" }} className='text-danger'>No se ha podido conectar con el servidor. Inténtalo de nuevo.</p>
                            ) : pistas.length === 0 ? (
                                <p style={{ color: "#aaa", fontSize: ".85rem" }}>No hay pistas disponibles.</p>
                            ) : !fechaSeleccionada ? (
                                <p style={{ color: "#aaa", fontSize: ".85rem" }}>Selecciona un día para ver las pistas.</p>
                            ) : (

                                pistas.map(p => {
                                    const slots    = generarSlots(p);
                                    const abierta  = pistasAbiertas[p.numPista];
                                    const horaElegida = horasPorPista[p.numPista];
                                    const duracion    = duracionPorPista[p.numPista] ?? 1;

                                    const precio = calcPrecio(p, duracion);

                                    return (
                                        <div key={p.numPista} className={`card-pista ${abierta ? 'abierta' : ''}`} id={`card-${p.numPista}`}>

                                            <div className="card-pista-header" onClick={() => togglePista(p.numPista)}>
                                                Pista {p.numPista}
                                                <span className="tags">
                                                    {p.tipo_pista} · {p.tipo_zona}
                                                </span>
                                            </div>

                                            {abierta && (
                                                <div className="card-pista-body" id={`body-${p.numPista}`}>

                                                    <div className="slots-wrap">
                                                        {slots.map((slot, index) => (
                                                            <div
                                                                key={index}
                                                                className={`slot ${slot.ocupado ? 'ocupado' : 'disponible'}`}
                                                                onClick={() => !slot.ocupado && seleccionar(p.numPista, slot.hora)}
                                                                style={{ cursor: slot.ocupado ? 'not-allowed' : 'pointer' }}
                                                            >
                                                                {slot.texto} 
                                                            </div>
                                                        ))}
                                                    </div>

                                                    {horaElegida && (
                                                        <div id={`form-${p.numPista}`} className="form-reserva">
                                                            <b>Pista {p.numPista}</b> · {cero(Math.floor(horaElegida))}:{horaElegida % 1 === 0 ? '00' : '30'} · {fechaSeleccionada}<br />
                                                            Duración (mín. 1h, pasos de 0.5h):
                                                            <input
                                                                id={`dur-${p.numPista}`}
                                                                type="number"
                                                                min="1"
                                                                step="0.5"
                                                                value={duracion}
                                                                onChange={e => setDuracionPorPista(prev => ({ ...prev, [p.numPista]: e.target.value }))}
                                                            />
                                                            <button onClick={() => userId ? confirmar(p.numPista, horaElegida, p.tipo_pista, p.tipo_zona) : alert("Debes iniciar sesión, antes de hacer una reserva")}>OK</button>

                                                            <br/> 
                                                            <span>Precio: {precio}€</span>
                                                        </div>
                                                    )}

                                                </div>
                                            )}

                                        </div>
                                    );
                                })
                            )}
                        </div>
                    </div>

                </div>
            </div>

            <Footer />
        </>
    );
}