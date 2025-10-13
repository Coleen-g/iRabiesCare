import React, { createContext, useContext, useState, useEffect } from "react";
import api, { initCSRF } from "../api/axios";
import { useNavigate } from "react-router-dom";

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const navigate = useNavigate();
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchUser = async () => {
      try {
        await initCSRF();
        const res = await api.get("/api/me"); // only /api/me
        setUser(res.data);
      } catch {
        setUser(null);
      } finally {
        setLoading(false);
      }
    };
    fetchUser();
  }, []);

  const login = async (email, password) => {
    await initCSRF();
    const res = await api.post("/login", { email, password }); // no /api
    setUser(res.data.user);

    switch (res.data.user.role) {
      case "admin":
        navigate("/admin/dashboard");
        break;
      case "health_staff":
        navigate("/staff/dashboard");
        break;
      case "patient":
        navigate("/patient/dashboard");
        break;
      default:
        navigate("/");
    }
  };

  const logout = async () => {
    await api.post("/logout"); // no /api
    setUser(null);
    navigate("/");
  };

  return (
    <AuthContext.Provider value={{ user, loading, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
