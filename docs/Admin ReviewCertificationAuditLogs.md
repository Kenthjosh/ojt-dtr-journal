# Audit Logs & User Management System

This module handles audit logging, user management, and weekly report monitoring for the DTR system.  
It focuses on tracking actions, enforcing role-based rules, and ensuring data integrity.

---

## Features Overview

### 📋 DTR Logs
- Records Daily Time Records (DTR) of users.
- **View-only**: records cannot be edited or modified.
- Used strictly for auditing and monitoring purposes.

---

### 🛠️ Admin Activities
- Logs actions performed by administrators (e.g., certifications, user creation).
- **View-only** audit trail.
- Ensures accountability and traceability of admin actions.

---

### 📝 Weekly Reports
- Weekly reports submitted by interns.
- Admins can:
  - **Mark reports as Viewed**
  - **Certify reports**
- Status updates automatically when actions are performed.

#### Restrictions
- Once a report is **Certified**:
  - It can no longer be certified again.
  - It cannot be marked as viewed.
  - Related action buttons are **disabled** to prevent changes.

---

### 👤 User Creation & Management
- Admins can create new users.
- Supported roles:
  - **Admin**
  - **Intern**

#### Role Rules
- **Admin**
  - Does NOT require a shift.
- **Intern**
  - MUST be assigned a shift.
  - Available shifts:
    - Day Shift
    - Night Shift
  - Intern users **cannot be created** without assigning a shift.

---

## Purpose
This system ensures:
- Proper auditing of sensitive records
- Role-based access control
- Accurate tracking of intern progress and admin actions
- Prevention of invalid or unauthorized data changes

---

## Notes
- Audit-related records are intentionally immutable.
- Business rules are enforced both in the UI and backend logic.
