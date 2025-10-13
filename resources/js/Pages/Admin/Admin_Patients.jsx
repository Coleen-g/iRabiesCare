// src/Pages/Admin/Admin_Patients.jsx
import React, { useState, useEffect } from "react";
import { useAuth } from "../../contexts/AuthContext";
import api from "../../api/axios";

export default function Admin_Patients() {
  const { user } = useAuth();
  const [patients, setPatients] = useState([]);
  const [loading, setLoading] = useState(true);

  const [showModal, setShowModal] = useState(false);
  const [editingPatient, setEditingPatient] = useState(null);
  const [formData, setFormData] = useState({
    name: "",
    age: "",
    gender: "",
    address: "",
    contact_number: "",
  });

  useEffect(() => {
    if (user?.role === "admin") loadPatients();
    else setLoading(false);
  }, [user]);

  const loadPatients = async () => {
    try {
      setLoading(true);
      const res = await api.get("/api/patients");
      setPatients(Array.isArray(res.data) ? res.data : []);
    } catch (err) {
      console.error("Error fetching patients:", err);
      setPatients([]);
      alert("Failed to load patients. Make sure you are logged in as admin.");
    } finally {
      setLoading(false);
    }
  };

  const handleSave = async () => {
    if (!formData.name || !formData.age || !formData.gender || !formData.address) {
      alert("Please fill all required fields!");
      return;
    }

    try {
      if (editingPatient) {
        await api.put(`/api/patients/${editingPatient.id}`, formData);
      } else {
        await api.post("/api/patients", formData);
      }
      await loadPatients();
      setShowModal(false);
      setEditingPatient(null);
    } catch (err) {
      console.error("Save error:", err);
      alert("Failed to save patient. Make sure you are logged in as admin.");
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm("Are you sure you want to delete this patient?")) return;
    try {
      await api.delete(`/api/patients/${id}`);
      await loadPatients();
    } catch (err) {
      console.error(err);
      alert("Failed to delete patient.");
    }
  };

  if (loading) return <p>Loading patients...</p>;
  if (!user || user.role !== "admin") return <p>Access denied. Admins only.</p>;

  return (
    <div style={cardStyle}>
      <div style={headerStyle}>
        <h2>Patients</h2>
        <button style={btnPrimary} onClick={() => {
          setEditingPatient(null);
          setFormData({ name: "", age: "", gender: "", address: "", contact_number: "" });
          setShowModal(true);
        }}>+ Add Patient</button>
      </div>

      <table style={tableStyle}>
        <thead style={theadStyle}>
          <tr>
            <th style={thStyle}>ID</th>
            <th style={thStyle}>Name</th>
            <th style={thStyle}>Age</th>
            <th style={thStyle}>Gender</th>
            <th style={thStyle}>Address</th>
            <th style={thStyle}>Contact</th>
            <th style={thStyle}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {patients.length === 0 ? (
            <tr>
              <td style={tdStyle} colSpan={7} align="center">
                No patients yet. Add a new patient to see it here.
              </td>
            </tr>
          ) : (
            patients.map((p, i) => (
              <tr key={p.id} style={rowHover(i)}>
                <td style={tdStyle}>{p.id}</td>
                <td style={tdStyle}>{p.name}</td>
                <td style={tdStyle}>{p.age}</td>
                <td style={tdStyle}>{p.gender}</td>
                <td style={tdStyle}>{p.address}</td>
                <td style={tdStyle}>{p.contact_number}</td>
                <td style={tdStyle}>
                  <button style={actionBtn} onClick={() => {
                    setEditingPatient(p);
                    setFormData({ ...p });
                    setShowModal(true);
                  }}>Edit</button>
                  <button style={{ ...actionBtn, background: "#DC2626" }} onClick={() => handleDelete(p.id)}>Delete</button>
                </td>
              </tr>
            ))
          )}
        </tbody>
      </table>

      {/* Modal */}
      {showModal && (
        <div style={modalOverlay}>
          <div style={modalBox}>
            <h3>{editingPatient ? "Edit Patient" : "Add Patient"}</h3>

            {["name", "age", "gender", "address", "contact_number"].map((field) => (
              <div key={field} style={formGroup}>
                <label style={labelStyle}>{field.replace("_", " ").toUpperCase()}</label>
                {field === "gender" ? (
                  <select style={inputStyle} value={formData.gender} onChange={(e) => setFormData({ ...formData, gender: e.target.value })}>
                    <option value="">-- Select Gender --</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                ) : (
                  <input
                    type={field === "age" ? "number" : "text"}
                    style={inputStyle}
                    value={formData[field]}
                    onChange={(e) => setFormData({ ...formData, [field]: e.target.value })}
                  />
                )}
              </div>
            ))}

            <div style={{ marginTop: "20px", textAlign: "right" }}>
              <button style={btnPrimary} onClick={handleSave}>{editingPatient ? "Update" : "Save"}</button>
              <button style={btnCancel} onClick={() => setShowModal(false)}>Cancel</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

/* ---------------- Styles ---------------- */
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
