import { useState, useEffect } from 'react';
import { NavLink } from "react-router-dom";
import { IoPeople } from "react-icons/io5";
import { FaCalendarAlt, FaRegClock } from "react-icons/fa";
import Header from "../Plantilla/Header.jsx";
import Footer from "../Plantilla/Footer.jsx";
import "./Clases.css";

export default function Clases() {

    const [trainings, setTrainings] = useState([]);
    const [cargando, setCargando] = useState(true);

    // Limite inicial de las clases
    const LIMITE = 8;

    const [filtroActivo, setFiltroActivo] = useState('todos');
    const [contar, setContar] = useState(LIMITE);
    const [busqueda, setBusqueda] = useState('');
    const [error, setError] = useState(null);

    useEffect(() => {
        fetch("http://localhost/club_padel_cm/public/api/training", {
            headers: {
                "x-api-key": "base64:q7LJZBOOfUf8n2SnNYeaVkz/T+QAhvflSvAMJ8P6E7Q=",
                "Accept": "application/json"
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.data) {
                    setTrainings(data.data);
                }
                setCargando(false);
            })
            .catch(error => {
                console.error("Error al obtener trainings:", error);
                setCargando(false);
                setError("No se ha encontrado ningún training");
            });
    }, []);

    function getEstado(training) {
        var hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        var ini = new Date(training.fechaIni);
        var fin = new Date(training.fechaEnd);
        var dia = new Date(training.dia);

        // Según el dia actual no se muestran trainings fuera del rango ini - dia
        if (hoy > dia) return 'descartar';
        if (hoy < ini) return 'descartar';

        var personasActuales = training.clientes ? training.clientes.length : 0;
        var personasMax = training.activity.personasMax;

        if (personasActuales >= personasMax) return 'lleno';
        if (hoy >= ini && hoy <= fin) return 'disponible';
        return 'no-disponible';

    }

    function getItemsFiltrados() {
        var busquedaTrim = busqueda.trim().toLowerCase();
        var items = [];

        trainings.forEach(function (training) {
            if (busquedaTrim && training.activity.nombre.toLowerCase().indexOf(busquedaTrim) === -1) return;

            var estado = getEstado(training);
            if (estado === 'descartar') return;
            if (estado === 'descartar') return;
            if (filtroActivo !== 'todos' && estado !== filtroActivo) return; // Si el filtro no es todos y el estado no coincide, no mostrara el training
            items.push({ training: training, estado: estado });
        });

        return items;
    }

    const setFiltro = (filtro) => {
        setFiltroActivo(filtro);
        setContar(LIMITE);
    };

    const resetYRender = (valor) => {
        setBusqueda(valor);
        setContar(LIMITE);
    };

    const items = getItemsFiltrados();
    const total = items.length;
    const visibles = items.slice(0, contar);

    return (
        <>
            <Header />

            <div className="page-wrap">

                <h2 className="page-title font-bebas">Nuestras <span>clases</span></h2>
                <p style={{ color: '#888', fontSize: '.9rem', marginBottom: 0 }}>Apúntate a las sesiones disponibles y mejora tu nivel.</p>

                <div className="barra-filtros">
                    <input type="text" className="input-busqueda" placeholder="Buscar clase..." value={busqueda} onChange={(e) => resetYRender(e.target.value)} />

                    <button className={`btn-filtro ${filtroActivo === 'todos' ? 'activo' : ''}`} onClick={() => setFiltro('todos')}> Todas </button>
                    <button className={`btn-filtro ${filtroActivo === 'disponible' ? 'activo' : ''}`} onClick={() => setFiltro('disponible')} > Disponible </button>
                    <button className={`btn-filtro ${filtroActivo === 'no-disponible' ? 'activo' : ''}`} onClick={() => setFiltro('no-disponible')} > No disponible</button>
                    <button className={`btn-filtro ${filtroActivo === 'lleno' ? 'activo' : ''}`} onClick={() => setFiltro('lleno')}> Plazas llenas </button>

                </div>

                <div className="clases-grid">

                    {cargando ? (
                        <div className="sin-resultados m-5">Cargando trainings...</div>
                    ) :
                        error ? (
                            <div className="sin-resultados m-5 text-danger">{error}</div>
                        )
                            : total === 0 ? (
                                <div className="sin-resultados m-5 text-danger">No se han encontrado trainings con estos filtros.</div>
                            ) : (
                                visibles.map((item, index) => {
                                    var badgeClass = '', badgeTexto = '';
                                    if (item.estado === 'disponible') { badgeClass = 'badge-disponible'; badgeTexto = 'Disponible'; }
                                    if (item.estado === 'no-disponible') { badgeClass = 'badge-no-disponible'; badgeTexto = 'No disponible'; }
                                    if (item.estado === 'lleno') { badgeClass = 'badge-lleno'; badgeTexto = 'Plazas llenas'; }

                                    return (
                                        <NavLink to={"/training/" + item.training.id} key={index} className="clase-card" style={{ textDecoration: 'none', color: 'inherit' }}>
                                            <p className="clase-nombre font-bebas">{item.training.activity.nombre}</p>
                                            <p className="clase-descripcion">{item.training.activity.descripcion}</p>
                                            <div className="clase-info">
                                                <span className='d-flex align-items-center'><IoPeople /> Máx. <b>{item.training.activity.personasMax}</b> personas</span>
                                                <span className='d-flex align-items-center'><FaCalendarAlt /> <b>{item.training.dia}</b></span>
                                                <span className='d-flex align-items-center'><FaRegClock /> <b>{item.training.hora.slice(0, 5)}</b></span>
                                            </div>
                                            <span className={`badge-estado ${badgeClass}`}>{badgeTexto}</span>
                                        </NavLink>
                                    );
                                })
                            )}
                </div>

                {total > LIMITE && (
                    <button
                        className="btn-ver-mas"
                        onClick={() => setContar(x => x > items.length ? LIMITE : x + 4)}
                    >
                        {
                            contar >= items.length ? "Ver menos" : "Ver más"
                        }
                    </button>
                )}

            </div>

            <Footer />
        </>
    );
}