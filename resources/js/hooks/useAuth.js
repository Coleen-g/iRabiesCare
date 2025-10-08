// resources/js/hooks/useAuth.js
import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/axios";

export default function useAuth(redirectIfUnauthorized = "/login") {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    const checkAuth = async () => {
      try {
        // Get CSRF cookie
        await api.get("/sanctum/csrf-cookie");

        // Fetch user
        const res = await api.get("/user");
        setUser(res.data);
      } catch (error) {
        console.error("Auth check failed:", error);
        navigate(redirectIfUnauthorized);
      } finally {
        setLoading(false);
      }
    };

    checkAuth();
  }, [navigate, redirectIfUnauthorized]);

  const logout = async () => {
    try {
      await api.post("/logout");
      localStorage.clear();
      navigate("/login");
    } catch (error) {
      console.error("Logout failed:", error);
    }
  };

  return { user, loading, logout };
}
