import { useState, useEffect } from "react";
import axios from "axios";

export default function Vaccinations() {
  const [vaccinations, setVaccinations] = useState([]);
  const [loading, setLoading] = useState(true);

  const [showModal, setShowModal] = useState(false);
  const [editingVaccination, setEditingVaccination] = useState(null);
  const [formData, setFormData] = useState({
    patient_id: "",
    vaccine_type: "",
    dose_number: "",
    vaccination_date: "",
    remarks: ""
  });

  // ✅ Fetch Vaccinations from API
  useEffect(() => {
    fetchVaccinations();
  }, []);

  const fetchVaccinations = async () => {
    try {
      const response = await axios.get("http://127.0.0.1:8000/api/vaccinations");
      setVaccinations(response.data);
      setLoading(false);
    } catch (error) {
      console.error("Error fetching vaccinations:", error);
    }
  };

  const openAddModal = () => {
    setEditingVaccination(null);
    setFormData({ patient_id: "", vaccine_type: "", dose_number: "", vaccination_date: "", remarks: "" });
    setShowModal(true);
  };

  const openEditModal = (item) => {
    setEditingVaccination(item);
    setFormData({
      patient_id: item.patient_id,
      vaccine_type: item.vaccine_type,
      dose_number: item.dose_number,
      vaccination_date: item.vaccination_date,
      remarks: item.remarks,
    });
    setShowModal(true);
  };

  // ✅ Save (Add / Update)
  const handleSave = async () => {
    if (!formData.patient_id || !formData.vaccine_type || !formData.vaccination_date) {
      alert("Please fill all required fields!");
      return;
    }

    try {
      if (editingVaccination) {
        // Update
        await axios.put(`http://127.0.0.1:8000/api/vaccinations/${editingVaccination.id}`, formData);
      } else {
        // Create
        await axios.post("http://127.0.0.1:8000/api/vaccinations", formData);
      }
      fetchVaccinations(); // Refresh list
      setShowModal(false);
      setEditingVaccination(null);
    } catch (error) {
      console.error("Error saving vaccination:", error);
    }
  };

  // ✅ Delete
  const handleDelete = async (id) => {
    if (window.confirm("Are you sure you want to delete this vaccination record?")) {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/vaccinations/${id}`);
        fetchVaccinations();
      } catch (error) {
        console.error("Error deleting vaccination:", error);
      }
    }
  };

  if (loading) return <p>Loading vaccinations...</p>;

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
            <th style={thStyle}>Patient ID</th>
            <th style={thStyle}>Vaccine</th>
            <th style={thStyle}>Dose #</th>
            <th style={thStyle}>Date</th>
            <th style={thStyle}>Remarks</th>
            <th style={thStyle}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {vaccinations.map((v, index) => (
            <tr key={v.id} style={rowHover(index)}>
              <td style={tdStyle}>{v.id}</td>
              <td style={tdStyle}>{v.patient_id}</td>
              <td style={tdStyle}>{v.vaccine_type}</td>
              <td style={tdStyle}>{v.dose_number}</td>
              <td style={tdStyle}>{v.vaccination_date}</td>
              <td style={tdStyle}>{v.remarks}</td>
              <td style={tdStyle}>
                <button style={actionBtn} onClick={() => openEditModal(v)}>Edit</button>
                <button style={{ ...actionBtn, background: "#DC2626" }} onClick={() => handleDelete(v.id)}>Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {/* ✅ Modal */}
      {showModal && (
        <div style={modalOverlay}>
          <div style={modalBox}>
            <h3>{editingVaccination ? "Edit Vaccination" : "Add Vaccination"}</h3>

            <div style={formGroup}>
              <label style={labelStyle}>Patient ID</label>
              <input type="number" style={inputStyle} value={formData.patient_id} onChange={(e) => setFormData({ ...formData, patient_id: e.target.value })}/>
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Vaccine Type</label>
              <input type="text" style={inputStyle} value={formData.vaccine_type} onChange={(e) => setFormData({ ...formData, vaccine_type: e.target.value })}/>
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Dose Number</label>
              <input type="number" style={inputStyle} value={formData.dose_number} onChange={(e) => setFormData({ ...formData, dose_number: e.target.value })}/>
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Vaccination Date</label>
              <input type="date" style={inputStyle} value={formData.vaccination_date} onChange={(e) => setFormData({ ...formData, vaccination_date: e.target.value })}/>
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Remarks</label>
              <textarea style={inputStyle} value={formData.remarks} onChange={(e) => setFormData({ ...formData, remarks: e.target.value })}></textarea>
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
