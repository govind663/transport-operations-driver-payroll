<p align="center">
    <img src="https://laravel.com/img/logomark.min.svg" width="120" alt="Laravel Logo">
</p>

<h1 align="center">
🚗 DriveFlow ERP
</h1>

<p align="center">
<b>Transport Operations & Driver Payroll Management System</b>
</p>

<p align="center">
Developed using Laravel 11 • PHP 8.3+ • MySQL
</p>

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-11-red">
    <img src="https://img.shields.io/badge/PHP-8.3-blue">
    <img src="https://img.shields.io/badge/MySQL-8-orange">
    <img src="https://img.shields.io/badge/Status-In%20Development-success">
    <img src="https://img.shields.io/badge/Architecture-CRM%20%7C%20ERP-blueviolet">
    <img src="https://img.shields.io/badge/License-Private-lightgrey">
</p>

---

# 📖 About Project

DriveFlow ERP is a Transport Operations & Driver Payroll Management System designed to digitalize and automate the day-to-day operations of a transport service provider.

The system manages the complete business workflow starting from client travel requests to driver assignment, duty execution, working sheet management, expense tracking, payroll processing, and management reporting.

This project is being developed using **Laravel 11** with a modular, scalable, and enterprise-ready architecture.

---

# 🎯 Business Objectives

- Digitalize transport operations
- Eliminate manual paperwork
- Manage client travel requests
- Simplify driver assignment
- Track vehicle utilization
- Manage working sheets
- Automate payroll calculations
- Reduce payroll errors
- Generate operational reports
- Improve business productivity

---

# 🏢 Business Workflow

```
Client Travel Request
        │
        ▼
Travel Request Received
        │
        ▼
Duty Assignment
        │
        ▼
Vehicle Allocation
        │
        ▼
Driver Assignment
        │
        ▼
Duty Execution
        │
        ▼
Working Sheet Entry
        │
        ▼
Expense Entry
        │
        ▼
Monthly Payroll
        │
        ▼
Salary Processing
        │
        ▼
Reports & Analytics
```

---

# 🚘 Driver Categories

## Floating Driver

- Can work for multiple clients.
- Assigned based on availability.
- Multiple duty slips in a month.

---

## Fixed Driver

- Permanently assigned to one client.
- Fixed reporting location.
- Dedicated duty schedule.

---

# 📦 Modules

## Phase 1 — System Foundation

- Login
- Register
- Forgot Password
- Reset Password
- Dashboard
- User Management
- Profile
- Change Password

---

## Phase 2 — Masters

- Client Management
- Driver Management
- Vehicle Management
- Vehicle Category
- Vehicle Type
- Duty Type
- Company Settings

---

## Phase 3 — Operations

- Travel Request
- Duty Assignment
- Duty Slip
- Working Sheet
- Driver Attendance
- Expense Management

---

## Phase 4 — Payroll

- Salary Processing
- Overtime
- Sunday Duty
- Airport Duty
- Food Allowance
- Mobile Allowance
- Car Wash Allowance
- Day Allowance
- Night Allowance
- Advance Deduction
- Salary Slip

---

## Phase 5 — Reports

- Driver Report
- Client Report
- Vehicle Report
- Duty Report
- Working Sheet Report
- Payroll Report
- Expense Report

---

# 👥 User Roles

| Role | Responsibility |
|-------|---------------|
| Admin | Complete System Management |
| Operations | Duty Assignment & Working Sheet |
| Accountant | Payroll & Salary Processing |
| Driver | Driver Portal / Mobile App |

---

# 📑 Core Business Documents

The system is designed around the following operational documents:

- Travel Request
- Duty Assignment
- Duty Slip
- Working Sheet
- Monthly Salary Sheet
- Payroll Report

---

# 🏗 Technology Stack

| Category | Technology |
|------------|----------------|
| Framework | Laravel 11 |
| Language | PHP 8.3+ |
| Database | MySQL |
| Frontend | Blade, Bootstrap 5 |
| JavaScript | jQuery |
| Authentication | Laravel Auth |
| Version Control | Git |
| Architecture | Modular MVC |

---

# 📂 Project Structure

```
app
│
├── Console
├── Enums
├── Helpers
├── Http
│   ├── Controllers
│   ├── Middleware
│   └── Requests
│
├── Models
├── Notifications
├── Providers
├── Services
├── Traits
└── ViewModels
```

---

# 🚀 Installation

```bash
git clone <repository>

cd driveflow-erp

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan storage:link

php artisan db:seed

php artisan serve
```

---

# 📅 Development Roadmap

## Phase 1

✔ Authentication

✔ Dashboard

✔ User Management

---

## Phase 2

⬜ Client Module

⬜ Driver Module

⬜ Vehicle Module

---

## Phase 3

⬜ Travel Request

⬜ Duty Assignment

⬜ Working Sheet

---

## Phase 4

⬜ Payroll

⬜ Salary Slip

---

## Phase 5

⬜ Reports

⬜ Dashboard Analytics

---

# 📈 Future Enhancements

- Driver Mobile Application
- Client Portal
- GPS Tracking
- Live Vehicle Tracking
- Push Notifications
- SMS Integration
- WhatsApp Notification
- Invoice Management
- Billing Module
- API Integration

---

# 👨‍💻 Developer

**Abhishek Jha**

Senior PHP & Laravel Developer

Specialized in:

- Enterprise ERP
- CRM Development
- HRMS
- Transport Management Systems
- REST APIs
- Business Automation
- Performance Optimization

---

# 📞 Contact

📧 codingthunder1997@gmail.com

📱 +91 9004763926

GitHub

https://github.com/your-username

LinkedIn

https://www.linkedin.com/in/abhishek-laravel-developer/

---

# 📌 Project Status

```
Version      : 1.0.0

Status       : 🚧 Under Development

Framework    : Laravel 11

Architecture : Modular ERP

Database     : MySQL
```

---

## © 2026 Abhishek Jha

**DriveFlow ERP – Transport Operations & Driver Payroll Management System**

Developed with Laravel 11 using Enterprise CRM/ERP Architecture.