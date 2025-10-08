import { useState, useEffect } from "react";
import axios from "axios";

export default function Cases() {
  const [casesList, setCasesList] = useState([]);
  const [patients, setPatients] = useState([]);
  const [showModal, setShowModal] = useState(false);
  const [editingCase, setEditingCase] = useState(null);
  const [formData, setFormData] = useState({
    patient_id: "",
    bite_date: "",
    bite_category: "",
    bite_location: "",
    animal_type: "",
    outcome: "",
  });

  // ✅ Fetch cases and patients on load
  useEffect(() => {
    fetchCases();
    fetchPatients();
  }, []);

  const fetchCases = async () => {
    try {
      const res = await axios.get("http://127.0.0.1:8000/api/cases");
      setCasesList(res.data);
    } catch (err) {
      console.error("Error fetching cases:", err);
    }
  };

  const fetchPatients = async () => {
    try {
      const res = await axios.get("http://127.0.0.1:8000/api/patients");
      setPatients(res.data);
    } catch (err) {
      console.error("Error fetching patients:", err);
    }
  };

  const openAddModal = () => {
    setEditingCase(null);
    setFormData({
      patient_id: "",
      bite_date: "",
      bite_category: "",
      bite_location: "",
      animal_type: "",
      outcome: "",
    });
    setShowModal(true);
  };

  const openEditModal = (caseItem) => {
    setEditingCase(caseItem);
    setFormData({
      patient_id: caseItem.patient_id,
      bite_date: caseItem.bite_date,
      bite_category: caseItem.bite_category,
      bite_location: caseItem.bite_location,
      animal_type: caseItem.animal_type,
      outcome: caseItem.outcome,
    });
    setShowModal(true);
  };

  const handleSave = async () => {
    if (
      !formData.patient_id ||
      !formData.bite_date ||
      !formData.bite_category ||
      !formData.bite_location ||
      !formData.animal_type ||
      !formData.outcome
    ) {
      alert("Please fill all required fields!");
      return;
    }

    try {
      if (editingCase) {
        // ✅ Update existing case
        await axios.put(
          `http://127.0.0.1:8000/api/cases/${editingCase.id}`,
          formData
        );
      } else {
        // ✅ Add new case
        await axios.post("http://127.0.0.1:8000/api/cases", formData);
      }
      fetchCases();
      setShowModal(false);
      setEditingCase(null);
    } catch (err) {
      console.error("Error saving case:", err);
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm("Are you sure you want to delete this case?")) {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/cases/${id}`);
        fetchCases();
      } catch (err) {
        console.error("Error deleting case:", err);
      }
    }
  };

  return (
    <div style={cardStyle}>
      <div style={headerStyle}>
        <h2 style={{ margin: 0 }}>Rabies Cases</h2>
        <button style={btnPrimary} onClick={openAddModal}>
          + Add Case
        </button>
      </div>

      <table style={tableStyle}>
        <thead style={theadStyle}>
          <tr>
            <th style={thStyle}>ID</th>
            <th style={thStyle}>Patient</th>
            <th style={thStyle}>Bite Date</th>
            <th style={thStyle}>Category</th>
            <th style={thStyle}>Location</th>
            <th style={thStyle}>Animal</th>
            <th style={thStyle}>Outcome</th>
            <th style={thStyle}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {casesList.map((c, index) => (
            <tr key={c.id} style={rowHover(index)}>
              <td style={tdStyle}>{c.id}</td>
              <td style={tdStyle}>{c.patient?.name}</td>
              <td style={tdStyle}>{c.bite_date}</td>
              <td style={tdStyle}>{c.bite_category}</td>
              <td style={tdStyle}>{c.bite_location}</td>
              <td style={tdStyle}>{c.animal_type}</td>
              <td style={tdStyle}>{c.outcome}</td>
              <td style={tdStyle}>
                <button style={actionBtn} onClick={() => openEditModal(c)}>
                  Edit
                </button>
                <button
                  style={{ ...actionBtn, background: "#DC2626" }}
                  onClick={() => handleDelete(c.id)}
                >
                  Delete
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {/* ✅ Modal */}
      {showModal && (
        <div style={modalOverlay}>
          <div style={modalBox}>
            <h3 style={{ marginBottom: "15px" }}>
              {editingCase ? "Edit Case" : "Add New Case"}
            </h3>

            <div style={formGroup}>
              <label style={labelStyle}>Patient</label>
              <select
                style={inputStyle}
                value={formData.patient_id}
                onChange={(e) =>
                  setFormData({ ...formData, patient_id: e.target.value })
                }
              >
                <option value="">-- Select Patient --</option>
                {patients.map((p) => (
                  <option key={p.id} value={p.id}>
                    {p.name}
                  </option>
                ))}
              </select>
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Bite Date</label>
              <input
                type="date"
                style={inputStyle}
                value={formData.bite_date}
                onChange={(e) =>
                  setFormData({ ...formData, bite_date: e.target.value })
                }
              />
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Bite Category</label>
              <input
                type="text"
                style={inputStyle}
                value={formData.bite_category}
                onChange={(e) =>
                  setFormData({ ...formData, bite_category: e.target.value })
                }
              />
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Bite Location</label>
              <input
                type="text"
                style={inputStyle}
                value={formData.bite_location}
                onChange={(e) =>
                  setFormData({ ...formData, bite_location: e.target.value })
                }
              />
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Animal Type</label>
              <input
                type="text"
                style={inputStyle}
                value={formData.animal_type}
                onChange={(e) =>
                  setFormData({ ...formData, animal_type: e.target.value })
                }
              />
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Outcome</label>
              <input
                type="text"
                style={inputStyle}
                value={formData.outcome}
                onChange={(e) =>
                  setFormData({ ...formData, outcome: e.target.value })
                }
              />
            </div>

            <div style={{ marginTop: "20px", textAlign: "right" }}>
              <button style={btnPrimary} onClick={handleSave}>
                {editingCase ? "Update" : "Save"}
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

/* ✅ Styles */
const cardStyle = {
  background: "#fff",
  padding: "20px",
  borderRadius: "10px",
  boxShadow: "0 2px 8px rgba(0,0,0,0.1)",
};
const headerStyle = {
  display: "flex",
  justifyContent: "space-between",
  marginBottom: "20px",
};
const btnPrimary = {
  padding: "8px 15px",
  background: "#101923", // updated color
  color: "#fff",
  border: "none",
  borderRadius: "5px",
  cursor: "pointer",
};
const btnCancel = {
  padding: "8px 15px",
  marginLeft: "10px",
  background: "#6B7280",
  color: "#fff",
  border: "none",
  borderRadius: "5px",
  cursor: "pointer",
};
const tableStyle = { width: "100%", borderCollapse: "collapse", fontSize: "14px" };
const theadStyle = { background: "#101923", color: "#fff" }; // updated color
const thStyle = { padding: "12px", textAlign: "left", borderBottom: "2px solid #ddd" };
const tdStyle = { padding: "10px", borderBottom: "1px solid #ddd" };
const rowHover = (index) => ({
  background: index % 2 === 0 ? "#f9fafb" : "#fff",
  transition: "background 0.2s",
});
const actionBtn = {
  marginRight: "8px",
  padding: "6px 12px",
  border: "none",
  borderRadius: "4px",
  background: "#101923", // updated color
  color: "#fff",
  cursor: "pointer",
};

const modalOverlay = {
  position: "fixed",
  top: 0,
  left: 0,
  width: "100%",
  height: "100%",
  background: "rgba(0,0,0,0.5)",
  display: "flex",
  justifyContent: "center",
  alignItems: "center",
  zIndex: 1000,
};
const modalBox = {
  background: "#fff",
  padding: "25px",
  borderRadius: "10px",
  width: "450px",
  boxShadow: "0 4px 12px rgba(0,0,0,0.3)",
};
const formGroup = {
  marginBottom: "15px",
  display: "flex",
  flexDirection: "column",
};
const labelStyle = {
  marginBottom: "5px",
  fontSize: "14px",
  fontWeight: "bold",
  color: "#374151",
};
const inputStyle = {
  width: "100%",
  padding: "10px",
  border: "1px solid #ccc",
  borderRadius: "5px",
};
