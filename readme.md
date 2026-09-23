# Employee Management System

A simple, lightweight Employee Management Web Application built with **Core PHP**, **MySQL**, **HTML5**, **Pure CSS**, and **Vanilla JavaScript (Fetch API)**. Developed without any external frameworks (no Bootstrap, Tailwind, jQuery, or Laravel) adhering strictly to fundamental web development principles.

---

## 🛠 Features

- **Full REST API** with standard CRUD operations:
  - `GET /Public/employee_api.php` — Retrieve all employee records.
  - `GET /Public/employee_api.php?id={id}` — Retrieve a single employee by ID.
  - `POST /Public/employee_api.php` — Create a new employee (raw JSON).
  - `PUT /Public/employee_api.php?id={id}` — Update an existing employee (raw JSON).
  - `DELETE /Public/employee_api.php?id={id}` — Delete an employee by ID.
- **Connection Test Endpoint**: `Public/test_connection.php` returns JSON verification of MySQL connection.
- **Web UI**:
  - Add and Edit employee records.
  - Delete records with confirmation prompt.
  - Real-time client-side search/filter by name, email, mobile, job title, and sex.
  - Pure CSS styling with responsive design, card layouts, and status badges.

---

## 📁 Project Structure

```text
ads_employee_management_system/
├── Config/
│   └── database.php                # Database PDO connection class
├── App/
│   ├── Controllers/
│   │   └── EmployeeController.php  # Handles REST actions & validation
│   └── Models/
│       └── Employee.php            # PDO SQL CRUD queries
├── Public/
│   ├── css/
│   │   └── style.css               # Pure CSS (no frameworks)
│   ├── js/
│   │   └── main.js                 # Vanilla JS Fetch API CRUD & DOM
│   ├── employee_api.php            # REST API entry point
│   ├── index.php                   # Web application interface
│   └── test_connection.php         # DB connection test endpoint
├── database.sql                    # MySQL schema & sample seed data
├── index.php                       # Root redirect to Public/index.php
└── readme.md                       # Project documentation
```

---

## 🚀 Setup & Testing

1. **Start Apache & MySQL** in XAMPP Control Panel.
2. **Access Web App**:
   ```text
   http://localhost/myprojects/ads_employee_management_system/
   ```
3. **Test Database Connection in Browser / Postman**:
   ```text
   GET http://localhost/myprojects/ads_employee_management_system/Public/test_connection.php
   ```
4. **Postman API Testing**:
   - Ensure the request header `Content-Type: application/json` is used for `POST` and `PUT`.
   - Provide JSON data in the **Body** tab with **raw** and **JSON** format.
