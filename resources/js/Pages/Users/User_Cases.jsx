import { useState } from "react";

export default function Cases() {
  const [casesList, setCasesList] = useState([
    {
      id: 1,
      patient: "Ana Cruz",
      date: "2025-09-10",
      status: "Ongoing",
      remarks: "Under treatment",
    },
    {
      id: 2,
      patient: "Mark Reyes",
      date: "2025-09-05",
      status: "Resolved",
      remarks: "Completed vaccination",
    },
  ]);

  const [showModal, setShowModal] = useState(false);
  const [editingCase, setEditingCase] = useState(null);
  const [formData, setFormData] = useState({
    patient: "",
    date: "",
    status: "",
    remarks: "",
  });

  const openAddModal = () => {
    setEditingCase(null);
    setFormData({ patient: "", date: "", status: "", remarks: "" });
    setShowModal(true);
  };

  const openEditModal = (caseItem) => {
    setEditingCase(caseItem);
    setFormData({
      patient: caseItem.patient,
      date: caseItem.date,
      status: caseItem.status,
      remarks: caseItem.remarks,
    });
    setShowModal(true);
  };

  const handleSave = () => {
    if (!formData.patient || !formData.date || !formData.status) {
      alert("Please fill all required fields!");
      return;
    }

    if (editingCase) {
      setCasesList(
        casesList.map((c) =>
          c.id === editingCase.id ? { ...c, ...formData } : c
        )
      );
    } else {
      const newEntry = { id: casesList.length + 1, ...formData };
      setCasesList([...casesList, newEntry]);
    }

    setShowModal(false);
    setEditingCase(null);
  };

  const handleDelete = (id) => {
    if (window.confirm("Are you sure you want to delete this case?")) {
      setCasesList(casesList.filter((c) => c.id !== id));
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
            <th style={thStyle}>Date</th>
            <th style={thStyle}>Status</th>
            <th style={thStyle}>Remarks</th>
            <th style={thStyle}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {casesList.map((c, index) => (
            <tr key={c.id} style={rowHover(index)}>
              <td style={tdStyle}>{c.id}</td>
              <td style={tdStyle}>{c.patient}</td>
              <td style={tdStyle}>{c.date}</td>
              <td style={tdStyle}>{c.status}</td>
              <td style={tdStyle}>{c.remarks}</td>
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

      {/* ✅ Fixed Modal */}
      {showModal && (
        <div style={modalOverlay}>
          <div style={modalBox}>
            <h3 style={{ marginBottom: "15px" }}>
              {editingCase ? "Edit Case" : "Add New Case"}
            </h3>

            <div style={formGroup}>
              <label style={labelStyle}>Patient</label>
              <input
                type="text"
                style={inputStyle}
                value={formData.patient}
                onChange={(e) =>
                  setFormData({ ...formData, patient: e.target.value })
                }
              />
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Date</label>
              <input
                type="date"
                style={inputStyle}
                value={formData.date}
                onChange={(e) =>
                  setFormData({ ...formData, date: e.target.value })
                }
              />
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Status</label>
              <select
                style={inputStyle}
                value={formData.status}
                onChange={(e) =>
                  setFormData({ ...formData, status: e.target.value })
                }
              >
                <option value="">-- Select Status --</option>
                <option value="Ongoing">Ongoing</option>
                <option value="Resolved">Resolved</option>
                <option value="Pending">Pending</option>
              </select>
            </div>

            <div style={formGroup}>
              <label style={labelStyle}>Remarks</label>
              <textarea
                style={{ ...inputStyle, minHeight: "60px" }}
                value={formData.remarks}
                onChange={(e) =>
                  setFormData({ ...formData, remarks: e.target.value })
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

/* ✅ Reuse styles */
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
  background: "#2563EB",
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
const theadStyle = { background: "#2563EB", color: "#fff" };
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
  background: "#2563EB",
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
