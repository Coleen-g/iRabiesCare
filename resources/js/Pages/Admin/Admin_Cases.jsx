// src/Pages/Admin/Admin_Cases.jsx
import React, { useState, useEffect } from "react";
import { useAuth } from "../../contexts/AuthContext";
import api from "../../api/axios";

export default function Admin_Cases() {
  const { user } = useAuth();
  const [cases, setCases] = useState([]);
  const [loading, setLoading] = useState(true);

  const [showModal, setShowModal] = useState(false);
  const [editingCase, setEditingCase] = useState(null);
  const [formData, setFormData] = useState({
    patient_id: "",
    diagnosis: "",
    treatment: "",
    notes: "",
  });

  useEffect(() => {
    if (user?.role === "admin") loadCases();
    else setLoading(false);
  }, [user]);

  const loadCases = async () => {
    try {
      setLoading(true);
      const res = await api.get("/api/cases");
      setCases(Array.isArray(res.data) ? res.data : []);
    } catch (err) {
      console.error("Error fetching cases:", err);
      setCases([]);
      alert("Failed to load cases. Make sure you are logged in as admin.");
    } finally {
      setLoading(false);
    }
  };

  const handleSave = async () => {
    if (!formData.patient_id || !formData.diagnosis || !formData.treatment) {
      alert("Please fill all required fields!");
      return;
    }

    try {
      if (editingCase) {
        await api.put(`/api/cases/${editingCase.id}`, formData);
      } else {
        await api.post("/api/cases", formData);
      }
      await loadCases();
      setShowModal(false);
      setEditingCase(null);
    } catch (err) {
      console.error("Save error:", err);
      alert("Failed to save case. Make sure you are logged in as admin.");
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm("Are you sure you want to delete this case?")) return;
    try {
      await api.delete(`/api/cases/${id}`);
      await loadCases();
    } catch (err) {
      console.error(err);
      alert("Failed to delete case.");
    }
  };

  if (loading) return <p>Loading cases...</p>;
  if (!user || user.role !== "admin") return <p>Access denied. Admins only.</p>;

  return (
    <div style={cardStyle}>
      <div style={headerStyle}>
        <h2>Cases</h2>
        <button style={btnPrimary} onClick={() => {
          setEditingCase(null);
          setFormData({ patient_id: "", diagnosis: "", treatment: "", notes: "" });
          setShowModal(true);
        }}>+ Add Case</button>
      </div>

      <table style={tableStyle}>
        <thead style={theadStyle}>
          <tr>
            <th style={thStyle}>ID</th>
            <th style={thStyle}>Patient ID</th>
            <th style={thStyle}>Diagnosis</th>
            <th style={thStyle}>Treatment</th>
            <th style={thStyle}>Notes</th>
            <th style={thStyle}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {cases.length === 0 ? (
            <tr>
              <td style={tdStyle} colSpan={6} align="center">No cases yet. Add a new case to see it here.</td>
            </tr>
          ) : (
            cases.map((c, i) => (
              <tr key={c.id} style={rowHover(i)}>
                <td style={tdStyle}>{c.id}</td>
                <td style={tdStyle}>{c.patient_id}</td>
                <td style={tdStyle}>{c.diagnosis}</td>
                <td style={tdStyle}>{c.treatment}</td>
                <td style={tdStyle}>{c.notes}</td>
                <td style={tdStyle}>
                  <button style={actionBtn} onClick={() => { setEditingCase(c); setFormData({ ...c }); setShowModal(true); }}>Edit</button>
                  <button style={{ ...actionBtn, background: "#DC2626" }} onClick={() => handleDelete(c.id)}>Delete</button>
                </td>
              </tr>
            ))
          )}
        </tbody>
      </table>

      {showModal && (
        <div style={modalOverlay}>
          <div style={modalBox}>
            <h3>{editingCase ? "Edit Case" : "Add Case"}</h3>
            {["patient_id", "diagnosis", "treatment", "notes"].map((field) => (
              <div key={field} style={formGroup}>
                <label style={labelStyle}>{field.replace("_", " ").toUpperCase()}</label>
                <input
                  type="text"
                  style={inputStyle}
                  value={formData[field]}
                  onChange={(e) => setFormData({ ...formData, [field]: e.target.value })}
                />
              </div>
            ))}
            <div style={{ marginTop: "20px", textAlign: "right" }}>
              <button style={btnPrimary} onClick={handleSave}>{editingCase ? "Update" : "Save"}</button>
              <button style={btnCancel} onClick={() => setShowModal(false)}>Cancel</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

/* Reuse same styles from Admin_Patients.jsx */
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
