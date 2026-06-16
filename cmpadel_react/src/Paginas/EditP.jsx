import { useState, useEffect } from 'react';
import { GoPersonFill } from "react-icons/go";
import { PiDoorOpen } from "react-icons/pi";
import { NavLink } from "react-router-dom";
import Header from "../Plantilla/Header.jsx";
import Footer from "../Plantilla/Footer.jsx";
import "./EditP.css";

export default function EditP() {

  const [user, setUser] = useState({
    name: "",
    lastname: "",
    phone: "",
    email: "",
  });
  const [loading, setLoading] = useState(true);
  const [updating, setUpdating] = useState(false); // Estado para el botón de guardado

  const userId = localStorage.getItem("userId");
  const token = localStorage.getItem("token");
  const [error, setError] = useState(null);

  useEffect(() => {
    if (userId && token) {
      fetch(`http://localhost/club_padel_cm/public/api/user/${userId}`, {
        headers: {
          "Authorization": `Bearer ${token}`,
          "x-api-key": "base64:q7LJZBOOfUf8n2SnNYeaVkz/T+QAhvflSvAMJ8P6E7Q=",
          "Accept": "application/json"
        }
      })
        .then(res => res.json())
        .then(response => {
          const userData = response.data;
          setUser({
            name: userData.nombre || "",
            lastname: userData.apellido || "",
            phone: userData.telefono || "",
            email: userData.email || ""
          });
          setLoading(false);
        })
        .catch(error => {
          console.error("Error cargando usuario:", error);
          setLoading(false);
          setError("No se ha podido encontrar la información del usuario")
        });
    }
  }, [userId, token]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setUser(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setUpdating(true);

    try {
      const response = await fetch(`http://localhost/club_padel_cm/public/api/user/${userId}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "x-api-key": "base64:q7LJZBOOfUf8n2SnNYeaVkz/T+QAhvflSvAMJ8P6E7Q=",
          "Authorization": `Bearer ${token}`
        },

        body: JSON.stringify({
          ...user,
          id: userId // esto me sirve para verificar la parte del email, y dni que no se repitan
        })

      });

      const result = await response.json();

      if (response.ok) {
        alert("¡Tu perfil se ha actualizado con éxito!");
        console.log("Lo que me devuelve:", result); // tmp, sirve para ver que es lo que me devuelve
      } else {
        alert("Error al actualizar: " + (result.message || "Error desconocido"));
      }
    } catch (error) {
      console.error("Error en la petición PUT:", error);
      alert("Error de conexión con el servidor");
    } finally {
      setUpdating(false);
    }
  };


  return (
    <>
      <Header />
      {/* {console.log(userId)} */}
      <div className="perfil-wrap">

        <aside className="sidebar">
          <NavLink to="/editPerfil" className="sidebar-btn activo" id="btn-editar-perfil">
            <span className="sidebar-icono d-flex align-items-center"><GoPersonFill /></span> Editar perfil
          </NavLink>

          <button className="sidebar-btn cerrar" id="btn-cerrar-sesion" onClick={() => { localStorage.clear(); window.location.href = "/"; }}>
            <span className="sidebar-icono d-flex align-items-center"><PiDoorOpen /></span> Cerrar sesión
          </button>
        </aside>

        {/* Formulario para editar perfil */}

        {
          loading ? (
            <>
              <section className="perfil-contenido" id="seccion-editar-perfil">
                <h2 className="perfil-titulo font-bebas">Editar perfil</h2>
                <p style={{ color: "#aaa", fontSize: ".85rem", margin: "120px" }} className='text-center'>Cargando perfil...</p>
              </section>
            </>
          )
            : error ? (
              <>
                <section className="perfil-contenido" id="seccion-editar-perfil">
                  <h2 className="perfil-titulo font-bebas">Editar perfil</h2>
                  <p style={{ fontSize: ".85rem", margin: "120px" }} className='text-center text-danger'>{error}</p>
                </section>
              </>
            )
              :
              (
                <form onSubmit={handleSubmit}>
                  <section className="perfil-contenido" id="seccion-editar-perfil">

                    <h2 className="perfil-titulo font-bebas">Editar perfil</h2>

                    <div className="mb-3" id="campo-nombre">
                      <label className="form-label" htmlFor="perfil-nombre">Nombre<span style={{ color: "red" }}>*</span></label>
                      {/* Se pone el mismo name que la propiedad del objeto porque en el handleChange se usa e.target.name para saber qué campo actualizar. */}
                      <input name="name" type="text" id="perfil-nombre" className="form-control campo-perfil" value={user.name} onChange={handleChange} required />
                    </div>

                    <div className="mb-3" id="campo-apellido">
                      <label className="form-label" htmlFor="perfil-apellido">Apellido<span style={{ color: "red" }}>*</span></label>
                      <input name="lastname" type="text" id="perfil-apellido" className="form-control campo-perfil" value={user.lastname} onChange={handleChange} required />
                    </div>

                    <div className="mb-3" id="campo-telefono">
                      <label className="form-label" htmlFor="perfil-telefono">Teléfono</label>
                      <input name="phone" type="tel" id="perfil-telefono" className="form-control campo-perfil" value={user.phone} onChange={handleChange} />
                    </div>

                    <div className="mb-3" id="campo-email">
                      <label className="form-label" htmlFor="perfil-telefono">Email<span style={{ color: "red" }}>*</span></label>
                      <input name="email" type="email" id="perfil-telefono" className="form-control campo-perfil" value={user.email} onChange={handleChange} />
                    </div>

                    <div className="text-center">
                      <button className="btn-guardar" id="btn-guardar-perfil" disabled={updating}>{updating ? "Guardando..." : "Guardar cambios"}</button>
                    </div>

                  </section>
                </form>)
        }

      </div>
      <Footer />
    </>
  )
}