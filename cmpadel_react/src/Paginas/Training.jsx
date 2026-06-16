import { useState, useEffect } from 'react';
import { NavLink } from "react-router-dom";
import { useParams } from "react-router"; // Para coger el id que hay en el url
import { LuNotepadText } from "react-icons/lu";
import { FaCalendarAlt, FaRegClock, FaMoneyBill } from "react-icons/fa";
import { PiPersonSimpleThrowBold } from "react-icons/pi";
import { IoPeople } from "react-icons/io5";
import { TbGps } from "react-icons/tb";
import Header from "../Plantilla/Header.jsx";
import Footer from "../Plantilla/Footer.jsx";
import "./Training.css";

export default function Training() {
    
    const [datos, setDatos] = useState(null);
    const [cargando, setCargando] = useState(true);
    const [inscrito, setInscrito] = useState(false);
    const [procesando, setProcesando] = useState(false);
    const userId = Number(localStorage.getItem("userId"));
    const {trainingId} = useParams(); // Obtengo el valor de la url dada
// el 1 no esta disponible

    useEffect(() => {
        // alert(trainingId);
        fetch(`http://localhost/club_padel_cm/public/api/training/${trainingId}`, {
            headers: {
                "x-api-key": "base64:q7LJZBOOfUf8n2SnNYeaVkz/T+QAhvflSvAMJ8P6E7Q=",
                "Accept": "application/json"
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.data) {
                    setDatos(data.data);
                    const estaInscrito = data.data.clientes && data.data.clientes.some(cliente => cliente.id === userId);
                    setInscrito(estaInscrito);
                }
                setCargando(false);
            })
            .catch(error => {
                console.error("Error al obtener training:", error);
                setCargando(false);
            });
    }, [trainingId, userId]);


    const toggleInscripcion = () => {
        if (procesando) return; // Evitar múltiples clics
        
        const monitor = localStorage.getItem("monitor");

        if (monitor === "true") {
            alert("Los monitores no pueden apuntarse a Trainings");
            return
        }

        setProcesando(true);

        const datosInscripcion = {
            id_training: trainingId,
            user_id: userId
        };

        fetch("http://localhost/club_padel_cm/public/api/training", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "x-api-key": "base64:q7LJZBOOfUf8n2SnNYeaVkz/T+QAhvflSvAMJ8P6E7Q="
            },
            body: JSON.stringify(datosInscripcion)
        })
            .then(res => res.json())
            .then(data => {
                if (data.data) {
                    setDatos(data.data);
                    const estaInscrito = data.data.clientes && data.data.clientes.some(cliente => cliente.id === userId);
                    setInscrito(estaInscrito);
                } else if (data.message) {
                    console.error("Error: ", data.message);
                }
                setProcesando(false);
            })
            .catch(error => {
                console.error("Error al procesar inscripción:", error);
                setProcesando(false);
            });
    };


    if (cargando) {
        return (
            <>
                <Header />
                <div className="page-wrap">
                    <NavLink to="/clases" className="btn-volver">Volver a clases</NavLink>
                    <p style={{ color: '#888', fontSize: '.9rem', margin: "138.5px" }} className='text-center'>Cargando training...</p>
                </div>
                <Footer />
            </>
        );
    }

    if (!datos) {
        return (
            <>
                <Header />
                <div className="page-wrap">
                    <NavLink to="/clases" className="btn-volver">Volver a clases</NavLink>
                    <p style={{ fontSize: '.9rem', margin: "138.5px" }} className='text-center text-danger'>No se encontró el training.</p>
                </div>
                <Footer />
            </>
        );
    }

    const entreno = datos;
    const personasActuales = datos.clientes ? datos.clientes.length : 0;
    const personasMax = datos.activity.personasMax;
    const plazasLlenas = personasActuales >= personasMax;
    const monitorNombre = `${datos.monitor_nombre} ${datos.monitor_apellido}`;

    const hoy = new Date(); hoy.setHours(0, 0, 0, 0);
    const noDisponible = hoy > new Date(datos.fechaEnd) || hoy < new Date(datos.fechaIni);

    return (
        <>
            <Header />

            <div className="page-wrap">

                <NavLink to="/clases" className="btn-volver">Volver a clases</NavLink>

                <h1 className="detalle-titulo font-bebas">{datos.activity.nombre}</h1>

                <div className="detalle-info">
                    <p>
                        <span className="lbl"><LuNotepadText /> Descripción:</span> <span>{datos.activity.descripcion}</span>
                    </p>
                    <p>
                        <span className="lbl"><FaCalendarAlt /> Período de inscripción:</span> <span>{entreno.fechaIni} al {entreno.fechaEnd}</span>
                    </p>
                    <p>
                        <span className="lbl"><FaRegClock /> Fecha y hora:</span> <span>{entreno.dia} a las {entreno.hora.slice(0, 5)}</span> {/* 20:00:00 -> 20:00 */}
                    </p>
                    <p>
                        <span className="lbl"><FaRegClock /> Duración:</span> <span> {entreno.activity.duracion * 60}min</span> {/* Le ponemos los minutos */}
                    </p>
                    <p>
                        <span className="lbl"><IoPeople /> Plazas máximas:</span> <span>{personasMax} personas ({personasActuales} inscritos)</span>
                    </p>
                    <p>
                        <span className="lbl"><PiPersonSimpleThrowBold /> Monitor/a:</span> <span>{monitorNombre}</span>
                    </p>
                    <p>
                        <span className="lbl"><FaMoneyBill /> Precio por sesión:</span> <span>{datos.activity.precio.toFixed(2)} €</span>
                    </p>
                    <p>
                        <span className="lbl"><TbGps /> Pista:</span> <span>Pista nº {entreno.numPista} · {entreno.zone_type_name} · {entreno.court_type_name}</span>
                    </p>
                </div>

                {/* inscrito -> no-disponible -> plazas llenas -> inscribir */}
                <button
                    className={`btn-inscribir ${ inscrito ? 'desinscribir' : noDisponible ? 'no-disponible' : 'inscribir'} ${(plazasLlenas && !inscrito) || noDisponible || procesando ? 'noAllow' : ''}`}
                    onClick={() => userId ? toggleInscripcion() : alert("Antes de apuntarte a un Training debes iniciar sesión")}
                    disabled={(plazasLlenas && !inscrito) || noDisponible || procesando}
                >
                    {inscrito       ? 'Desinscribirme' :
                     noDisponible   ? 'No disponible'  :
                     plazasLlenas   ? 'Plazas llenas'  :
                     'Inscribirme'}
                </button>

            </div>

            <Footer/>
        </>
    );
}