import api from "./axios";

export const fetchPatients = async () => {
  const res = await api.get("/api/patients");
  return res.data;
};

export const createPatient = async (data) => {
  const res = await api.post("/api/patients", data);
  return res.data;
};

export const updatePatient = async (id, data) => {
  const res = await api.put(`/api/patients/${id}`, data);
  return res.data;
};

export const deletePatient = async (id) => {
  const res = await api.delete(`/api/patients/${id}`);
  return res.data;
};
