import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";
import Inicio from "./Paginas/Inicio.jsx";
import Contacto from "./Paginas/Contacto.jsx";
import Faq from "./Paginas/Faq.jsx";
import ReservarPista from "./Paginas/ReservarPista.jsx";
import Clases from "./Paginas/Clases.jsx";
import Training from "./Paginas/Training.jsx";
import Login from "./Paginas/Login.jsx";
import EditP from "./Paginas/EditP.jsx";
import Register from "./Paginas/Register.jsx";
import VerClases from "./Paginas/VerClases.jsx";

// Componente para proteger rutas de clientes (no monitores)
const ProtectedRoute = ({ children }) => {
  const token = localStorage.getItem("token");
  const monitor = localStorage.getItem("monitor");

  // Si no hay token, se va login
  if (!token) {
    return <Navigate to="/login" />;
  }

  // Si es monitor, no puede acceder a EditP
  if (monitor === "true") {
    return <Navigate to="/" />;
  }

  return children;
};

// Componente para proteger rutas de monitores (no clientes)
const ProtectedMonitorRoute = ({ children }) => {
  const token = localStorage.getItem("token");
  const monitor = localStorage.getItem("monitor");

  // Si no hay token, se va login
  if (!token) {
    return <Navigate to="/login" />;
  }

  // Si no es monitor, no puede acceder a /panel
  if (monitor !== "true") {
    return <Navigate to="/" />;
  }

  return children;
};

export default function App() {

  return (
    <>
      <Router>
        <Routes>
          {/* Rutas */}
          <Route path="/" element={<Inicio />} />
          <Route path="*" element={<Navigate replace to="/" />} />
          <Route path="/contacto" element={<Contacto />} />
          <Route path="/faq" element={<Faq />} />
          <Route path="/reserva-pista" element={<ReservarPista />} />
          <Route path="/clases" element={<Clases />} />

          {/* Según el :trainingId, se mostrará el training correspondiente */}
          <Route path="/training/:trainingId" element={<Training />} />

          {/* Si ya está logueado y el usuario quiere irse al apartado de login, no podrá y se ira al Home, para que no se vuelva a loguinearse */}
          <Route path="/login" element={
            localStorage.getItem("token") ? <Navigate to="/" /> : <Login />
          } />

          {/* Lo mismo, si el usuario loguineado se va al register, no podrá y se ira al Home */}
          <Route path="/register" element={
            localStorage.getItem("token") ? <Navigate to="/" /> : <Register />
          } />

          {/* Solo para clientes (no monitores) */}
          <Route path="/editPerfil"
            element={
              <ProtectedRoute>
                <EditP />
              </ProtectedRoute>
            }
          />

          {/* Solo para monitores (no clientes) */}
          <Route path="/panel" element={
            <ProtectedMonitorRoute>
              <VerClases />
            </ProtectedMonitorRoute>
          } />

        </Routes>
      </Router>
    </>
  )
}