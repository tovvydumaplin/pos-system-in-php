# POS System Features Documentation

## Table of Contents
- [Overview](#overview)
- [User Roles & Permissions](#user-roles--permissions)
- [Dashboard](#dashboard)
- [Orders (POS)](#orders-pos)
- [Categories](#categories)
- [Services](#services)
- [Inventory Management](#inventory-management)
- [Branches](#branches)
- [Analytics & Reports](#analytics--reports)
- [Backup & Restore](#backup--restore)
- [Users Management](#users-management)
- [Customers](#customers)

---

## Overview

A comprehensive Point of Sale (POS) system built for laundry service businesses with multi-branch support, inventory tracking, analytics, and role-based access control.

**Tech Stack:**
- Backend: PHP with MySQLi
- Frontend: Bootstrap 5, JavaScript
- Charts: Chart.js
- Database: MySQL

---

## User Roles & Permissions

### Super Admin
- **Full system access** across all branches
- Can view and manage **all branches** data
- Access to analytics with **branch filtering**
- Can manage all users, including creating other admins
- Displays with **red badge** indicator

### Admin
- Access to **specific branch** data only
- Can manage their assigned branch operations
- Access to analytics for **their branch only**
- Can manage users and customers
- Cannot see other branches' data
- Displays with **blue badge** indicator

### Staff
- **Limited access** - POS operations only
- Can create orders and view orders
- **Cannot access** admin features:
  - Categories, Services, Inventory
  - Branches, Backup, Analytics
  - User management
- Displays with **grey badge** indicator

**Navigation:** Menu items automatically adjust based on user role. Staff users only see Dashboard, Create Order, and Orders.

---

## Dashboard

**Path:** `/admin/index.php`  
**Access:** All users

### Features:
- **POS Overview** with date display
- **Quick Actions:** New Order and View Orders buttons
- **Statistics Cards:**
  - Today's Orders count
  - Total Orders (lifetime)
  - Today's Sales amount
  - Total Sales (lifetime)
- **Recent Orders Table:**
  - Last 5 orders with customer info
  - Tracking number, date, status, amount
  - Quick links to view all orders
- **Business Records Summary:**
  - Categories count
  - Services count
  - Customers count
  - Branches count
- **User Statistics:**
  - Admin count
  - Staff count
  - Total users

**Layout:** Full-width responsive design with card-based statistics and clean tables.

---

## Orders (POS)

**Path:** `/admin/pos/`  
**Access:** All users

### Create Order (`order-create.php`)
- Select customer from dropdown
- Add multiple services with quantities
- Automatic price calculation
- Payment method selection (Cash/Online)
- Service type: Pick-up or Delivery
- Generates unique tracking number and invoice number
- Real-time order summary with totals

### View Orders (`orders.php`)
**Features:**
- **Super Admin View:**
  - Filter by branch or view all branches
  - Branch column displayed in table
  - "Super Admin View" badge indicator
- **Admin/Staff View:**
  - See only orders from their branch
  - No branch filter option
- **Filters:**
  - Date range filter
  - Payment status (Cash/Online)
  - Tracking number search
- **Order Table:**
  - Tracking number, customer info, phone
  - Order date, status, payment mode
  - Branch name (Super Admin only)
  - View and Print actions

### Order Details (`orders-view.php`)
- Complete order information
- Customer details
- Service items with quantities and prices
- Payment and delivery information
- Print-friendly layout

---

## Categories

**Path:** `/admin/categories/`  
**Access:** Admin, Super Admin

### Features:
- **Create Category** (`categories-create.php`)
  - Category name (required)
  - Description (optional)
  - Visibility status with checkbox
  - Helper text: "Check to hide category"
- **View Categories** (`categories.php`)
  - Table with ID, name, status
  - Status badges (Visible/Hidden)
  - Edit and Delete actions
- **Edit Category** (`categories-edit.php`)
  - Modify existing categories
  - Update visibility status
  - Cancel option to revert changes

**Visibility Control:** Hidden categories don't appear in service selections but remain in database.

---

## Services

**Path:** `/admin/services/`  
**Access:** Admin, Super Admin

### Features:
- **Create Service** (`services-create.php`)
  - Service name (required)
  - Description
  - Price (required)
  - Quantity/Stock (required)
  - Image upload (optional)
  - Visibility status: "Check to hide service"
- **View Services** (`services.php`)
  - Table with image preview (50x50px)
  - Service ID, name, status
  - Status badges (Visible/Hidden)
  - Edit and Delete actions
- **Edit Service** (`services-edit.php`)
  - Update service details
  - Replace service image
  - Current image preview (60x60px)
  - Visibility toggle

**Image Handling:**
- Uploads stored in `/assets/uploads/services/`
- Filename: timestamp-based for uniqueness
- Displays thumbnail previews
- Object-fit cover with rounded corners

---

## Inventory Management

**Path:** `/admin/inventory/`  
**Access:** Admin, Super Admin

### Laundry Stocks (`inventory.php`)
- View current stock levels
- Add new inventory items
- Track laundry supplies and materials
- Stock quantity monitoring

### Stock Movement (`stock-movement.php`)
- Record stock transactions
- Track stock in/out movements
- Movement history with dates
- Audit trail for inventory changes

**Purpose:** Monitor and manage laundry supplies, detergents, and other consumables.

---

## Branches

**Path:** `/admin/branches/branches.php`  
**Access:** Admin, Super Admin

### Features:
- **Create Branch:**
  - Branch name
  - Location/Address
  - Contact information
- **View Branches:**
  - List of all business locations
  - Branch details
  - Edit and manage options
- **Branch Assignment:**
  - Users can be assigned to specific branches
  - Orders linked to branches
  - Branch-based reporting

**Super Admin Capability:** View and compare performance across all branches in analytics.

---

## Analytics & Reports

**Path:** `/admin/analytics/analytics.php`  
**Access:** Admin, Super Admin (Staff: No Access)

### Super Admin Features:
- **Branch Filter:** Dropdown to select specific branch or view all
- **Super Admin View Badge:** Red indicator
- **Branch Performance Table:**
  - Orders per branch
  - Total sales per branch
  - Average order value per branch

### Admin Features:
- **Branch-Specific Data:** Automatically filtered to their assigned branch
- No branch selection - sees only their data

### Common Features:
- **Date Range Filters:**
  - Start Date and End Date inputs
  - Defaults to current month
  - Apply and Reset buttons
- **Export Options:**
  - **Export to Excel:** Download analytics as CSV file with all data tables
  - **Export to PDF:** Print-friendly view that can be saved as PDF using browser
  - Exports respect current filters (date range, branch selection)
  - Timestamped filenames for easy organization
- **Overview Cards:**
  - Total Sales with date range
  - Total Orders count
  - Average Order Value
- **Daily Sales Chart:**
  - Line chart showing sales trend
  - Interactive Chart.js visualization
  - Smooth curve with gradient fill
  - Peso formatting on tooltips and axes
- **Payment Method Breakdown:**
  - Cash vs Online payments
  - Order count badges
  - Total sales per method
- **Top Services Table:**
  - Most ordered services
  - Times ordered, quantity, revenue
  - Sorted by revenue
- **Top Customers Table:**
  - Highest spending customers
  - Order count and total spent
  - Contact information displayed

**Data Visualization:** Professional charts with responsive design and proper currency formatting.

**Export Formats:**
- **Excel (CSV):** Complete data export with all sections - overview stats, payment breakdown, daily sales, top services, top customers, and branch performance (super admin)
- **PDF (Print):** Professional print layout optimized for saving as PDF via browser's print function

---

## Backup & Restore

**Path:** `/admin/backup-restore/backup-restore.php`  
**Access:** Admin, Super Admin

### Database Backup:
- **One-Click Backup:** Generate SQL dump of entire database
- **Filename:** Timestamped (e.g., `db_backup_may_15.sql`)
- **Storage:** `/backups/` directory
- **Contents:** Complete database structure and data

### Database Restore:
- **File Upload:** Select SQL file to restore
- **Restore Process:** Executes SQL commands to rebuild database
- **Safety:** Overwrites existing data - use with caution

### Use Cases:
- Regular database backups for safety
- Data migration between environments
- Disaster recovery
- System updates and rollbacks

**Best Practice:** Create backups before major updates or data imports.

---

## Users Management

**Path:** `/admin/users/`  
**Access:** Admin, Super Admin

### Features:
- **Create User** (`CreateUser.php`)
  - Name, email, password (required)
  - User Type dropdown:
    - **Super Admin** (full access)
    - **Admin** (branch-limited)
    - **Staff** (POS only)
  - Phone number
  - Branch assignment (optional)
  - Ban status checkbox
- **View Users** (`users.php`)
  - Filter by user type:
    - All Users
    - Super Admins Only
    - Admins Only
    - Staff Only
  - User table with:
    - ID, Name, Type badge (color-coded)
    - Email, Phone, Branch
    - Status (Active/Banned)
    - Edit and Delete actions
- **Edit User** (`EditUser.php`)
  - Update user information
  - Change user type
  - Reassign branch
  - Toggle ban status
  - Optional password update (leave blank to keep current)

### User Type Badges:
- **Super Admin:** Red badge
- **Admin:** Blue badge
- **Staff:** Grey badge

**Security:** Passwords hashed using bcrypt. Email addresses must be unique.

---

## Customers

**Path:** `/admin/customers/`  
**Access:** Admin, Super Admin

### Features:
- **Add Customer** (`customers-create.php`)
  - Customer name (required)
  - Email (optional)
  - Phone number (optional)
  - Visibility status: "Check to hide customer"
- **View Customers** (`customers.php`)
  - Customer list with contact info
  - Status badges (Visible/Hidden)
  - Creation date
  - Edit and Delete actions
- **Edit Customer** (`customers-edit.php`)
  - Update customer details
  - Modify contact information
  - Toggle visibility

### Integration:
- Customers linked to orders
- Customer history tracking
- Quick customer selection during order creation
- Analytics show top customers by spending

**Visibility:** Hidden customers remain in system but don't appear in active selections.

---

## Additional Features

### Form Improvements:
- **Clean Labels:** Removed "UnChecked=Visible, Checked=Hidden" text
- **Helper Text:** Small descriptive text below checkboxes
- **Better Buttons:** 
  - Descriptive labels ("Save Service" vs "Save")
  - Cancel buttons for all forms
  - Proper button styling and alignment

### Design System:
- **Consistent Layout:** Card-based design throughout
- **Responsive Tables:** All tables work on mobile
- **Color Scheme:** Minimal colors, professional appearance
- **Full-Width Layout:** No restrictive max-width on containers
- **Badge System:** Color-coded status indicators

### Security:
- **Role-Based Access:** Automatic menu hiding for staff
- **Session Management:** Proper login/logout
- **Input Validation:** Server-side validation on all forms
- **Password Hashing:** Bcrypt for secure storage
- **Branch Isolation:** Admins can't access other branches' data

---

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser with JavaScript enabled

---

## Getting Started

1. Import `pos_system_php.sql` to create database
2. Configure database credentials in `/config/dbcon.php`
3. Create a Super Admin user
4. Set up branches for your business
5. Add services and categories
6. Start creating orders!

---

**Last Updated:** May 16, 2026
