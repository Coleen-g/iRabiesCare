// src/api/axios.js
import axios from "axios";

const api = axios.create({
  baseURL: "http://127.0.0.1:8000", // no /api
  withCredentials: true,            // needed for Sanctum
  headers: { "Content-Type": "application/json" },
});

// CSRF
let csrfFetched = false;
export const initCSRF = async () => {
  if (!csrfFetched) {
    await api.get("/sanctum/csrf-cookie"); // must be /sanctum/csrf-cookie
    csrfFetched = true;
  }
};

export default api;
