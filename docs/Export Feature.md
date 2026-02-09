# Export Feature Documentation

## Overview
The system provides an export feature for **DTR logs** and **Weekly Reports**, with access control based on user roles.

---

## Export Capabilities

### DTR Logs
- Logs can be exported using **bulk actions**.
- Supported export formats:
  - CSV
  - XLSX
- Exported files are downloaded directly by the user.

---

### Weekly Reports
- Reports can be exported in two ways:
  - **Multi-export** by selecting multiple reports from the table.
  - **Single export** when viewing an individual report.
- ⚠️ **Certification Rule**
  - Only **certified reports** can be exported.
  - If non-certified reports are included in a multi-selection, they will be **automatically ignored**.

---

## User Permissions

### Admins
- Can export:
  - All DTR logs
  - All Weekly Reports

### Interns
- Can export **only their own**:
  - DTR logs
  - Weekly Reports

---

## File Storage

- Exported files are temporarily stored at:
