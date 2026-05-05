# Laundry POS System and Administrator
Date: 4/30/2026
By: Tovvy Dumaplin
_______________________________________________________________

## 📋 Development Roadmap

### PHASE 1: MVC Refactor (Foundation) 🏗️
**Goal**: Restructure existing code into proper MVC architecture

#### 1.1 Create Folder Structure
- [ ] Create `Controllers/` folder for all business logic
- [ ] Create `Views/` folder for all presentation code
- [ ] Create `Models/` folder for database operations
- [ ] Create `Components/` folder for reusable UI elements

#### 1.2 Extract Components (Week 1)
- [ ] Move `includes/header.php` → `Components/Header.php` (class-based)
- [ ] Move `includes/sidebar.php` → `Components/Sidebar.php` (class-based)
- [ ] Move `includes/footer.php` → `Components/Footer.php` (class-based)
- [ ] Move `admin/includes/navbar.php` → `Components/Navbar.php`
- [ ] Create `Components/Alert.php` for alert messages

#### 1.3 Refactor Controllers (Week 2)
- [ ] Move functions from `config/function.php` → `Controllers/` (separate by domain)
  * `Controllers/AuthController.php` - Login, logout, session management
  * `Controllers/AdminController.php` - Admin CRUD operations
  * `Controllers/CustomerController.php` - Customer CRUD
  * `Controllers/ServiceController.php` - Service management
  * `Controllers/OrderController.php` - Order processing
  * `Controllers/CategoryController.php` - Category operations
- [ ] Move logic from `admin/code.php` to respective controllers
- [ ] Move logic from `admin/orders-code.php` to `OrderController.php`

#### 1.4 Refactor Models (Week 2-3)
- [ ] Create `Models/Admin.php` - Database queries for admins table
- [ ] Create `Models/Customer.php` - Database queries for customers table
- [ ] Create `Models/Service.php` - Database queries for services table
- [ ] Create `Models/Order.php` - Database queries for orders table
- [ ] Create `Models/Category.php` - Database queries for categories table
- [ ] Create `Models/Database.php` - Centralized connection handler

#### 1.5 Refactor Views (Week 3)
- [ ] Move all admin pages to `Views/Admin/`
- [ ] Move public pages to `Views/Public/`
- [ ] Update all include paths
- [ ] Test all pages work after refactor

_______________________________________________________________

### PHASE 2: Core Features (Inventory & POS) 🛒
**Goal**: Add critical missing functionality

#### 2.1 Inventory Management (Week 4)
**Database Changes**:
- [ ] Create `consumables` table (id, name, quantity, unit, min_stock_level, created_at)
- [ ] Create `consumable_transactions` table (id, consumable_id, type, quantity, reference, created_at)
- [ ] Add `consumables_used` field to services table (JSON or separate table)

**Features**:
- [ ] Consumables Creation page
  * Form to add new consumable item
  * Set minimum stock level alert
  * Acceptance: Can add item with name, quantity, unit
- [ ] Consumables List/Edit page
  * Display all consumables with current stock
  * Add stock (increase quantity)
  * Deduct stock (decrease quantity)
  * Show transaction history
  * Low stock alerts (when below min_stock_level)
  * Acceptance: Can view all items, add/deduct stock, see history
- [ ] Link consumables to services
  * Define what consumables each service uses and how much
  * Acceptance: When creating/editing service, can assign consumables

#### 2.2 Enhanced POS (Week 5)
- [ ] Display services with assigned consumables
- [ ] Automatic consumable deduction when service is ordered
  * Check if sufficient stock before processing
  * Record transaction in consumable_transactions
  * Acceptance: Stock reduces automatically, transaction logged
- [ ] Customer creation from POS
  * Quick add customer form in POS interface
  * Acceptance: Can create customer without leaving POS screen

_______________________________________________________________

### PHASE 3: User Management & Multi-Store (Week 6)
**Goal**: Support multiple users and store locations

#### 3.1 Account System
**Database Changes**:
- [ ] Create `users` table (id, name, email, password, role, store_id, is_active, created_at)
- [ ] Roles: 'admin', 'manager', 'staff'

**Features**:
- [ ] User registration page (admin only)
- [ ] Assign role and store to user
- [ ] Login works for both admins and users
- [ ] Role-based permissions
  * Admin: Full access
  * Manager: View all, edit store-specific
  * Staff: POS and orders only
- [ ] Acceptance: Different users see different menus based on role

#### 3.2 Multi-Store Support
**Database Changes**:
- [ ] Create `stores` table (id, name, location, is_active, created_at)
- [ ] Add `store_id` to orders, services, users tables

**Features**:
- [ ] Stores management page (admin only)
  * Add/edit/deactivate stores
  * Acceptance: Can create stores with name and location
- [ ] User/Employee designation to store
  * Assign users to specific store
  * Acceptance: Users only see data for their store
- [ ] Filter orders and analytics by store

_______________________________________________________________

### PHASE 4: Backup & Restore (Week 7)
**Goal**: Data safety and audit trail

#### 4.1 Database Backup
- [ ] SQL Download functionality
  * Generate .sql dump of entire database
  * Include timestamp in filename
  * Acceptance: Downloads complete SQL file
- [ ] SQL Upload/Restore functionality
  * Upload .sql file to restore database
  * Confirmation before overwriting
  * Acceptance: Can restore from backup file

#### 4.2 Audit Trail
**Database Changes**:
- [ ] Create `backup_logs` table (id, action, filename, user_id, created_at)

**Features**:
- [ ] Log all backup and restore actions
- [ ] Display backup history
  * Who, when, what action
  * Acceptance: Can see all backup/restore activity

_______________________________________________________________

### PHASE 5: Analytics & Reporting (Week 8)
**Goal**: Business insights and reporting

#### 5.1 Earnings Dashboard
- [ ] Total earnings display (all time)
- [ ] Earnings per store
  * Dropdown to select store
  * Show earnings for selected store
  * Acceptance: Can view earnings by store
- [ ] Date filtering
  * Filter by day (DD)
  * Filter by month (MM)
  * Filter by year (YY)
  * Date range picker
  * Acceptance: Shows accurate totals for selected period
- [ ] Charts/Graphs
  * Earnings over time (line chart)
  * Top services (bar chart)
  * Store comparison (pie chart)

#### 5.2 Reports
- [ ] Sales report by date range
- [ ] Services popularity report
- [ ] Consumables usage report
- [ ] Customer report (most frequent)

_______________________________________________________________

## 🔧 Technical Debt & Improvements
- [ ] Add input validation on all forms
- [ ] Implement CSRF protection
- [ ] Use prepared statements everywhere (prevent SQL injection)
- [ ] Add error logging to file
- [ ] Create session timeout mechanism
- [ ] Responsive design improvements for mobile POS

_______________________________________________________________

## 📝 Notes
- No API endpoints needed (pure PHP/MySQL)
- Use existing jQuery for AJAX calls
- Bootstrap for UI consistency
- Focus on server-side rendering


