import { useState, useEffect } from "react";
import axios from "axios";

export default function Patients() {
  const [patients, setPatients] = useState([]);
  const [showModal, setShowModal] = useState(false);
  const [editingPatient, setEditingPatient] = useState(null);
  const [formData, setFormData] = useState({
    name: "",
    age: "",
    gender: "",
    address: "",
    contact_number: "",
  });

  // Fetch patients from Laravel API
  useEffect(() => {
    axios
      .get("http://127.0.0.1:8000/api/patients")
      .then((res) => setPatients(res.data))
      .catch((err) => {
        console.error(err.response?.data || err.message);
        alert("Error fetching patients!");
      });
  }, []);

  const openAddModal = () => {
    setEditingPatient(null);
    setFormData({ name: "", age: "", gender: "", address: "", contact_number: "" });
    setShowModal(true);
  };

  const openEditModal = (patient) => {
    setEditingPatient(patient);
    setFormData({
      name: patient.name,
      age: patient.age,
      gender: patient.gender,
      address: patient.address,
      contact_number: patient.contact_number,
    });
    setShowModal(true);
  };

 const handleSave = async () => {
  // ✅ Validate required fields
  if (!formData.name || !formData.age || !formData.gender || !formData.address) {
    alert("Please fill all required fields!");
    return;
  }

  // ✅ Convert age to number
  const payload = { ...formData, age: Number(formData.age) };

  try {
    let res;

    if (editingPatient) {
      // ✅ Update existing patient
      res = await axios.put(
        `http://127.0.0.1:8000/api/patients/${editingPatient.id}`,
        payload,
        { headers: { "Content-Type": "application/json" } }
      );
      setPatients(patients.map((p) => (p.id === editingPatient.id ? res.data : p)));
    } else {
      // ✅ Add new patient
      res = await axios.post(
        "http://127.0.0.1:8000/api/patients",
        payload,
        { headers: { "Content-Type": "application/json" } }
      );
      setPatients([...patients, res.data]);
    }

    // ✅ Close modal
    setShowModal(false);
    setEditingPatient(null);
  } catch (err) {
    console.error(err.response || err);
    alert("Error saving patient! Check console for details.");
  }
};


  const handleDelete = async (id) => {
    if (window.confirm("Are you sure you want to delete this patient?")) {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/patients/${id}`);
        setPatients(patients.filter((p) => p.id !== id));
      } catch (err) {
        console.error(err.response?.data || err.message);
        alert("Error deleting patient!");
      }
    }
  };

  return (
    <div style={cardStyle}>
      <div style={headerStyle}>
        <h2 style={{ margin: 0 }}>Patients Management</h2>
        <button style={btnPrimary} onClick={openAddModal}>
          + Add Patient
        </button>
      </div>

      <table style={tableStyle}>
        <thead style={theadStyle}>
          <tr>
            <th style={thStyle}>ID</th>
            <th style={thStyle}>Name</th>
            <th style={thStyle}>Age</th>
            <th style={thStyle}>Gender</th>
            <th style={thStyle}>Address</th>
            <th style={thStyle}>Contact Number</th>
            <th style={thStyle}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {patients.map((patient, index) => (
            <tr key={patient.id} style={rowHover(index)}>
              <td style={tdStyle}>{patient.id}</td>
              <td style={tdStyle}>{patient.name}</td>
              <td style={tdStyle}>{patient.age}</td>
              <td style={tdStyle}>{patient.gender}</td>
              <td style={tdStyle}>{patient.address}</td>
              <td style={tdStyle}>{patient.contact_number}</td>
              <td style={tdStyle}>
                <button style={actionBtn} onClick={() => openEditModal(patient)}>
                  Edit
                </button>
                <button
                  style={{ ...actionBtn, background: "#DC2626" }}
                  onClick={() => handleDelete(patient.id)}
                >
                  Delete
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {showModal && (
        <div style={modalOverlay}>
          <div style={modalBox}>
            <h3 style={{ marginBottom: "15px" }}>
              {editingPatient ? "Edit Patient" : "Add New Patient"}
            </h3>

            <div style={formGroup}>
              <label style={labelStyle}>Name</label>
              <input
                type="text"
                style={inputStyle}
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              />
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Age</label>
              <input
                type="number"
                style={inputStyle}
                value={formData.age}
                onChange={(e) => setFormData({ ...formData, age: e.target.value })}
              />
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Gender</label>
              <select
                style={inputStyle}
                value={formData.gender}
                onChange={(e) => setFormData({ ...formData, gender: e.target.value })}
              >
                <option value="">-- Select --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Address</label>
              <input
                type="text"
                style={inputStyle}
                value={formData.address}
                onChange={(e) => setFormData({ ...formData, address: e.target.value })}
              />
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Contact Number</label>
              <input
                type="text"
                style={inputStyle}
                value={formData.contact_number}
                onChange={(e) =>
                  setFormData({ ...formData, contact_number: e.target.value })
                }
              />
            </div>

            <div style={{ marginTop: "20px", textAlign: "right" }}>
              <button style={btnPrimary} onClick={handleSave}>
                {editingPatient ? "Update" : "Save"}
              </button>
              <button style={btnCancel} onClick={() => setShowModal(false)}>
                Cancel
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

/* Styles */
const cardStyle = { background: "#fff", padding: "20px", borderRadius: "10px", boxShadow: "0 2px 8px rgba(0,0,0,0.1)" };
const headerStyle = { display: "flex", justifyContent: "space-between", marginBottom: "20px" };
const btnPrimary = { padding: "8px 15px", background: "#101923", color: "#fff", border: "none", borderRadius: "5px", cursor: "pointer" };
const btnCancel = { padding: "8px 15px", marginLeft: "10px", background: "#6B7280", color: "#fff", border: "none", borderRadius: "5px", cursor: "pointer" };
const tableStyle = { width: "100%", borderCollapse: "collapse", fontSize: "14px" };
const theadStyle = { background: "#101923", color: "#fff" };
const thStyle = { padding: "12px", textAlign: "left", borderBottom: "2px solid #ddd" };
const tdStyle = { padding: "10px", borderBottom: "1px solid #ddd" };
const rowHover = (index) => ({ background: index % 2 === 0 ? "#f9fafb" : "#fff", transition: "background 0.2s" });
const actionBtn = { marginRight: "8px", padding: "6px 12px", border: "none", borderRadius: "4px", background: "#101923", color: "#fff", cursor: "pointer" };
const modalOverlay = { position: "fixed", top: 0, left: 0, width: "100%", height: "100%", background: "rgba(0,0,0,0.5)", display: "flex", justifyContent: "center", alignItems: "center", zIndex: 1000 };
const modalBox = { background: "#fff", padding: "25px", borderRadius: "10px", width: "420px", boxShadow: "0 4px 12px rgba(0,0,0,0.3)" };
const formGroup = { marginBottom: "15px", display: "flex", flexDirection: "column" };
const labelStyle = { marginBottom: "5px", fontSize: "14px", fontWeight: "bold", color: "#374151" };
const inputStyle = { width: "100%", padding: "10px", border: "1px solid #ccc", borderRadius: "5px" };
