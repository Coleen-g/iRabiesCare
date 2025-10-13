// src/Pages/Admin/Admin_Vaccinations.jsx
import React, { useState, useEffect } from "react";
import { useAuth } from "../../contexts/AuthContext";
import api from "../../api/axios";

export default function Admin_Vaccinations() {
  const { user } = useAuth();
  const [vaccinations, setVaccinations] = useState([]);
  const [loading, setLoading] = useState(true);

  const [showModal, setShowModal] = useState(false);
  const [editingVaccination, setEditingVaccination] = useState(null);
  const [formData, setFormData] = useState({
    patient_id: "",
    vaccine_name: "",
    date_given: "",
    next_due: "",
    notes: "",
  });

  useEffect(() => {
    if (user?.role === "admin") loadVaccinations();
    else setLoading(false);
  }, [user]);

  const loadVaccinations = async () => {
    try {
      setLoading(true);
      const res = await api.get("/api/vaccinations");
      setVaccinations(Array.isArray(res.data) ? res.data : []);
    } catch (err) {
      console.error("Error fetching vaccinations:", err);
      setVaccinations([]);
      alert("Failed to load vaccinations. Make sure you are logged in as admin.");
    } finally {
      setLoading(false);
    }
  };

  const handleSave = async () => {
    if (!formData.patient_id || !formData.vaccine_name || !formData.date_given) {
      alert("Please fill all required fields!");
      return;
    }

    try {
      if (editingVaccination) {
        await api.put(`/api/vaccinations/${editingVaccination.id}`, formData);
      } else {
        await api.post("/api/vaccinations", formData);
      }
      await loadVaccinations();
      setShowModal(false);
      setEditingVaccination(null);
    } catch (err) {
      console.error("Save error:", err);
      alert("Failed to save vaccination. Make sure you are logged in as admin.");
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm("Are you sure you want to delete this vaccination?")) return;
    try {
      await api.delete(`/api/vaccinations/${id}`);
      await loadVaccinations();
    } catch (err) {
      console.error(err);
      alert("Failed to delete vaccination.");
    }
  };

  if (loading) return <p>Loading vaccinations...</p>;
  if (!user || user.role !== "admin") return <p>Access denied. Admins only.</p>;

  return (
    <div style={cardStyle}>
      <div style={headerStyle}>
        <h2>Vaccinations</h2>
        <button style={btnPrimary} onClick={() => {
          setEditingVaccination(null);
          setFormData({ patient_id: "", vaccine_name: "", date_given: "", next_due: "", notes: "" });
          setShowModal(true);
        }}>+ Add Vaccination</button>
      </div>

      <table style={tableStyle}>
        <thead style={theadStyle}>
          <tr>
            <th style={thStyle}>ID</th>
            <th style={thStyle}>Patient ID</th>
            <th style={thStyle}>Vaccine Name</th>
            <th style={thStyle}>Date Given</th>
            <th style={thStyle}>Next Due</th>
            <th style={thStyle}>Notes</th>
            <th style={thStyle}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {vaccinations.length === 0 ? (
            <tr>
              <td style={tdStyle} colSpan={7} align="center">No vaccinations yet. Add a new record to see it here.</td>
            </tr>
          ) : (
            vaccinations.map((v, i) => (
              <tr key={v.id} style={rowHover(i)}>
                <td style={tdStyle}>{v.id}</td>
                <td style={tdStyle}>{v.patient_id}</td>
                <td style={tdStyle}>{v.vaccine_name}</td>
                <td style={tdStyle}>{v.date_given}</td>
                <td style={tdStyle}>{v.next_due}</td>
                <td style={tdStyle}>{v.notes}</td>
                <td style={tdStyle}>
                  <button style={actionBtn} onClick={() => { setEditingVaccination(v); setFormData({ ...v }); setShowModal(true); }}>Edit</button>
                  <button style={{ ...actionBtn, background: "#DC2626" }} onClick={() => handleDelete(v.id)}>Delete</button>
                </td>
              </tr>
            ))
          )}
        </tbody>
      </table>

      {showModal && (
        <div style={modalOverlay}>
          <div style={modalBox}>
            <h3>{editingVaccination ? "Edit Vaccination" : "Add Vaccination"}</h3>
            {["patient_id", "vaccine_name", "date_given", "next_due", "notes"].map((field) => (
              <div key={field} style={formGroup}>
                <label style={labelStyle}>{field.replace("_", " ").toUpperCase()}</label>
                <input
                  type={field.includes("date") ? "date" : "text"}
                  style={inputStyle}
                  value={formData[field]}
                  onChange={(e) => setFormData({ ...formData, [field]: e.target.value })}
                />
              </div>
            ))}
            <div style={{ marginTop: "20px", textAlign: "right" }}>
              <button style={btnPrimary} onClick={handleSave}>{editingVaccination ? "Update" : "Save"}</button>
              <button style={btnCancel} onClick={() => setShowModal(false)}>Cancel</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

/* ---------------- Reuse same styles ---------------- */
const cardStyle = { background: "#fff", padding: "20px", borderRadius: "10px", boxShadow: "0 2px 8px rgba(0,0,0,0.1)" };
const headerStyle = { display: "flex", justifyContent: "space-between", marginBottom: "20px" };
const btnPrimary = { padding: "8px 15px", background: "#101923", color: "#fff", border: "none", borderRadius: "5px", cursor: "pointer" };
const btnCancel = { padding: "8px 15px", marginLeft: "10px", background: "#6B7280", color: "#fff", border: "none", borderRadius: "5px", cursor: "pointer" };
const tableStyle = { width: "100%", borderCollapse: "collapse", fontSize: "14px" };
const theadStyle = { background: "#101923", color: "#fff" };
const thStyle = { padding: "12px", textAlign: "left", borderBottom: "2px solid #ddd" };
const tdStyle = { padding: "10px", borderBottom: "1px solid #ddd" };
const rowHover = (index) => ({ background: index % 2 === 0 ? "#f9fafb" : "#fff", transition: "background 0.2s" });
const actionBtn = { marginRight: "8px", padding: "6px 12px", border: "none", borderRadius: "4px", background: "#2563EB", color: "#fff", cursor: "pointer" };
const modalOverlay = { position: "fixed", top: 0, left: 0, width: "100%", height: "100%", background: "rgba(0,0,0,0.5)", display: "flex", justifyContent: "center", alignItems: "center", zIndex: 1000 };
const modalBox = { background: "#fff", padding: "25px", borderRadius: "10px", width: "450px", boxShadow: "0 4px 12px rgba(0,0,0,0.3)" };
const formGroup = { marginBottom: "15px", display: "flex", flexDirection: "column" };
const labelStyle = { marginBottom: "5px", fontSize: "14px", fontWeight: "bold", color: "#374151" };
const inputStyle = { width: "100%", padding: "10px", border: "1px solid #ccc", borderRadius: "5px" };
