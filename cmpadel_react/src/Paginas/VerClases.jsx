import { useState, useEffect } from 'react';
import FullCalendar from '@fullcalendar/react';
import { PiDoorOpen } from "react-icons/pi";
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';
import Header from "../Plantilla/Header.jsx";
import Footer from "../Plantilla/Footer.jsx";
import "./VerClases.css";

export default function VerClases() {

    const [monitor, setMonitor] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const userId = localStorage.getItem("userId");

    useEffect(() => {
        fetch(`http://localhost/club_padel_cm/public/api/user/${userId}`, {
            headers: {
                "x-api-key": "base64:q7LJZBOOfUf8n2SnNYeaVkz/T+QAhvflSvAMJ8P6E7Q=",
                "Accept": "application/json"
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.data) {
                    setMonitor(data.data);
                    setLoading(false);
                }
            })
            .catch(error => {
                setLoading(false);
                console.error("Error al obtener monitor:", error);
                setError("No se ha podido encontrar la información del usuario");
            });
    }, [userId]);

    const clasesMonitor = monitor ? monitor.trainings.map(t => ({
        titulo: t.activity.nombre,
        dia: t.dia,
        hora: t.hora.slice(0, 5),       // 20:00:00 -> 20:00
        duracion: t.activity.duracion * 60, // horas en minutos
        pista: t.pista,
        clientes: t.clientes.length,
        plazas: t.activity.personasMax,
        // datos.activity.personasMax
    })) : [];

    // extendedProps(propio de fullcalendar): datos extra accesibles al hacer click en el evento, es decir, para ver mas info
    const eventos = clasesMonitor.map(clase => ({
        title: clase.titulo + '  ' + clase.hora,
        start: clase.dia,
        extendedProps: {
            hora: clase.hora,
            duracion: clase.duracion,
            pista: clase.pista,
            titulo: clase.titulo,
            clientes: clase.clientes,
            plazas: clase.plazas
        },
        backgroundColor: '#c8f03a',
        textColor: 'black'
    }));

    // eventClick(propio de fullcalendar): se llama cuando el usuario pulsa sobre un evento del calendario
    const handleEventClick = (info) => {
        // console.log(info.event.extendedProps.pista)
        const p = info.event.extendedProps;
        alert(
            'Clase: ' + p.titulo + '\n' +
            'Hora: ' + p.hora + '\n' +
            'Duración: ' + p.duracion + ' min\n' +
            'Pista n.º ' + p.pista.numPista + " · " + p.pista.tipo_zona.nombre + " · " + p.pista.tipo_pista.nombre + '\n' +
            'Personas incritas ' + p.clientes + "/" + p.plazas
        );
    };

    return (
        <>
            <Header />
            {/* {console.log(monitor)} */}
            {loading ?
                <p style={{ color: "#aaa", fontSize: ".85rem", margin: "213px" }} className='text-center'>Cargando perfil...</p>
                : error ?
                    <p style={{ fontSize: ".85rem", margin: "213px" }} className='text-center text-danger'>{error}</p>
                    :
                    <div className="page-wrap">

                        <p className="monitor-titulo font-bebas">
                            Monitor/a: <span>{monitor && `${monitor.nombre} ${monitor.apellido}`}</span>
                        </p>
                        <p className="font-bebas mb-0">
                            Email: <span>{monitor && `${monitor.email}`}</span>
                        </p>
                        <p className="font-bebas mb-0">
                            Tel.: <span>{monitor && `${monitor.telefono}`}</span>
                        </p>
                        <p className="font-bebas mb-0">
                            DNI.: <span>{monitor && `${monitor.dni}`}</span>
                        </p>

                        <p className="info-calendario">Pulsa sobre cualquier clase para ver los detalles.</p>

                        <FullCalendar
                            plugins={[dayGridPlugin, interactionPlugin]}
                            initialView="dayGridMonth"
                            locale={esLocale}
                            headerToolbar={{ left: 'prev', center: 'title', right: 'next' }}
                            height="auto"
                            fixedWeekCount={true}
                            events={eventos}
                            eventClick={handleEventClick}
                        />

                        <aside className="sidebar">
                            <button className="sidebar-btn cerrar mt-3" id="btn-cerrar-sesion" onClick={() => { localStorage.clear(); window.location.href = "/"; }}>
                                <span className="sidebar-icono d-flex align-items-center"><PiDoorOpen /></span> Cerrar sesión
                            </button>
                        </aside>

                    </div>
            }

            <Footer />
        </>
    );
}