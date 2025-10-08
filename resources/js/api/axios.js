import axios from "axios";

const api = axios.create({
  baseURL: "http://127.0.0.1:8000",
  withCredentials: true, // allow cookies for Sanctum
  headers: { "Content-Type": "application/json" },
});

export const getCSRFToken = async () => {
  try {
    await api.get("/sanctum/csrf-cookie");
  } catch (err) {
    console.error("Failed to get CSRF token:", err);
  }
};

export default api;
