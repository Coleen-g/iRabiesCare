import { useState } from "react";

export default function Vaccinations() {
  const [vaccinations, setVaccinations] = useState([
    { id: 1, patient: "Ana Cruz", vaccine: "Anti-Rabies", date: "2025-09-12", status: "Completed" },
    { id: 2, patient: "Mark Reyes", vaccine: "Anti-Rabies", date: "2025-09-15", status: "Pending" },
  ]);

  const [showModal, setShowModal] = useState(false);
  const [editingVaccination, setEditingVaccination] = useState(null);
  const [formData, setFormData] = useState({ patient: "", vaccine: "", date: "", status: "" });

  const openAddModal = () => {
    setEditingVaccination(null);
    setFormData({ patient: "", vaccine: "", date: "", status: "" });
    setShowModal(true);
  };

  const openEditModal = (item) => {
    setEditingVaccination(item);
    setFormData({ patient: item.patient, vaccine: item.vaccine, date: item.date, status: item.status });
    setShowModal(true);
  };

  const handleSave = () => {
    if (!formData.patient || !formData.vaccine || !formData.date) {
      alert("Please fill all required fields!");
      return;
    }
    if (editingVaccination) {
      setVaccinations(vaccinations.map(v => v.id === editingVaccination.id ? { ...v, ...formData } : v));
    } else {
      setVaccinations([...vaccinations, { id: vaccinations.length + 1, ...formData }]);
    }
    setShowModal(false);
    setEditingVaccination(null);
  };

  const handleDelete = (id) => {
    if (window.confirm("Are you sure you want to delete this vaccination record?")) {
      setVaccinations(vaccinations.filter(v => v.id !== id));
    }
  };

  return (
    <div style={cardStyle}>
      <div style={headerStyle}>
        <h2>Vaccination Records</h2>
        <button style={btnPrimary} onClick={openAddModal}>+ Add Vaccination</button>
      </div>

      <table style={tableStyle}>
        <thead style={theadStyle}>
          <tr>
            <th style={thStyle}>ID</th>
            <th style={thStyle}>Patient</th>
            <th style={thStyle}>Vaccine</th>
            <th style={thStyle}>Date</th>
            <th style={thStyle}>Status</th>
            <th style={thStyle}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {vaccinations.map((v, index) => (
            <tr key={v.id} style={rowHover(index)}>
              <td style={tdStyle}>{v.id}</td>
              <td style={tdStyle}>{v.patient}</td>
              <td style={tdStyle}>{v.vaccine}</td>
              <td style={tdStyle}>{v.date}</td>
              <td style={tdStyle}>{v.status}</td>
              <td style={tdStyle}>
                <button style={actionBtn} onClick={() => openEditModal(v)}>Edit</button>
                <button style={{ ...actionBtn, background: "#DC2626" }} onClick={() => handleDelete(v.id)}>Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {/* Modal */}
      {showModal && (
        <div style={modalOverlay}>
          <div style={modalBox}>
            <h3>{editingVaccination ? "Edit Vaccination" : "Add Vaccination"}</h3>
            
            <div style={formGroup}>
              <label style={labelStyle}>Patient</label>
              <input type="text" style={inputStyle} value={formData.patient} onChange={(e) => setFormData({ ...formData, patient: e.target.value })}/>
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Vaccine</label>
              <input type="text" style={inputStyle} value={formData.vaccine} onChange={(e) => setFormData({ ...formData, vaccine: e.target.value })}/>
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Date</label>
              <input type="date" style={inputStyle} value={formData.date} onChange={(e) => setFormData({ ...formData, date: e.target.value })}/>
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Status</label>
              <select style={inputStyle} value={formData.status} onChange={(e) => setFormData({ ...formData, status: e.target.value })}>
                <option value="">-- Select Status --</option>
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
              </select>
            </div>

            <div style={{ textAlign: "right" }}>
              <button style={btnPrimary} onClick={handleSave}>{editingVaccination ? "Update" : "Save"}</button>
              <button style={btnCancel} onClick={() => setShowModal(false)}>Cancel</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

/* ✅ Reuse styles */
const cardStyle = { background: "#fff", padding: "20px", borderRadius: "10px", boxShadow: "0 2px 8px rgba(0,0,0,0.1)" };
const headerStyle = { display: "flex", justifyContent: "space-between", marginBottom: "20px" };
const btnPrimary = { padding: "8px 15px", background: "#2563EB", color: "#fff", border: "none", borderRadius: "5px", cursor: "pointer" };
const btnCancel = { padding: "8px 15px", marginLeft: "10px", background: "#6B7280", color: "#fff", border: "none", borderRadius: "5px", cursor: "pointer" };
const tableStyle = { width: "100%", borderCollapse: "collapse", fontSize: "14px" };
const theadStyle = { background: "#2563EB", color: "#fff" };
const thStyle = { padding: "12px", textAlign: "left", borderBottom: "2px solid #ddd" };
const tdStyle = { padding: "10px", borderBottom: "1px solid #ddd" };
const rowHover = (index) => ({ background: index % 2 === 0 ? "#f9fafb" : "#fff", transition: "background 0.2s" });
const actionBtn = { marginRight: "8px", padding: "6px 12px", border: "none", borderRadius: "4px", background: "#2563EB", color: "#fff", cursor: "pointer" };
const modalOverlay = { position: "fixed", top: 0, left: 0, width: "100%", height: "100%", background: "rgba(0,0,0,0.5)", display: "flex", justifyContent: "center", alignItems: "center", zIndex: 1000 };
const modalBox = { background: "#fff", padding: "25px", borderRadius: "10px", width: "450px", boxShadow: "0 4px 12px rgba(0,0,0,0.3)" };
const formGroup = { marginBottom: "15px", display: "flex", flexDirection: "column" };
const labelStyle = { marginBottom: "5px", fontSize: "14px", fontWeight: "bold", color: "#374151" };
const inputStyle = { width: "100%", padding: "10px", border: "1px solid #ccc", borderRadius: "5px" };
