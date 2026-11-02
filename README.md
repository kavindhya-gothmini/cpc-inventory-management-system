# CPC Inventory Management System

<p align="center">
  <strong>A Centralized IT Asset, Inventory and Resource Management Platform</strong>
</p>

<p align="center">
  Built with Laravel · PHP · MySQL · Microsoft SQL Server · Bootstrap
</p>

<p align="center">
  <a href="https://github.com/kavindhya-gothmini/cpc-inventory-management-system">
    GitHub Repository
  </a>
</p>

---

## Overview

The **CPC Inventory Management System** is a web-based application designed to streamline and centralize IT asset, inventory, spare parts and resource management within the Ceylon Petroleum Corporation (CPC).

The system provides a structured platform for managing IT assets, tracking inventory, monitoring repairs, managing spare parts, recording fault reports and generating operational reports.

It is developed using the Laravel framework and integrates a local MySQL database with existing CPC Microsoft SQL Server databases to support centralized master-data access and application-specific data management.

The application follows a role-based and branch-aware access approach to help ensure that users can access information according to their assigned responsibilities.

## Key Features

### 1. Dashboard Management
- Centralized dashboard for monitoring inventory activities.
- Branch-aware dashboard information.
- Separate dashboard experiences for relevant user roles and operational areas.
- Summary information for assets, inventory and operational activities.

### 2. IT Asset Management
- Register and manage IT assets.
- Maintain asset details, including asset numbers, types, makes and models.
- Track asset assignments and locations.
- Monitor asset status and availability.
- Search and view asset information.
- Maintain asset history and related records.

### 3. Employee Management
- Employee information management.
- Employee and branch assignments.
- Employee search and record management.
- Role-based access management.
- Employee-related application access.

### 4. Spare Parts Management
- Manage spare part records.
- Maintain branch-specific spare part stock.
- Monitor stock availability.
- Track spare part usage.
- Support inventory movements and stock management.

### 5. Repair and Maintenance Management
- Record repair activities.
- Track repair status.
- Manage repair-related spare part usage.
- Maintain repair and maintenance information.

### 6. Fault Reporting
- Submit IT fault reports.
- Track fault report progress.
- Manage report assignments.
- Monitor submitted, ongoing and resolved reports.

### 7. CPC Database Integration
- Integration with CPC HR master data.
- Integration with CPC Help Desk and Asset Management data.
- Centralized access to authorized master data.
- Mapping between external CPC values and local application records.
- Support for source-data reconciliation and duplicate prevention.

### 8. Reporting and Information Management
- Inventory-related reports.
- Asset information and status reporting.
- Repair and maintenance reporting.
- Operational data summaries.

*Features are subject to ongoing development, testing and approval.*

---

## Technology Stack

| Component | Technology |
|---|---|
| Backend Framework | Laravel 12 |
| Programming Language | PHP 8.2 |
| Frontend | HTML5, CSS3, JavaScript |
| UI Framework | Bootstrap |
| Frontend Build Tool | Vite |
| Primary Database | MySQL |
| External Database Integration | Microsoft SQL Server |
| Database Access | Laravel Database / Query Builder |
| Dependency Management | Composer and npm |
| Version Control | Git and GitHub |

## System Architecture

The application uses a dual-database approach.

```text
                    CPC INVENTORY MANAGEMENT SYSTEM
                                  |
                         Laravel Application
                                  |
                +-----------------+-----------------+
                |                 |                 |
          Web Interface     Application Logic   Authentication
                |                 |                 |
                +-----------------+-----------------+
                                  |
                         Database Integration
                                  |
                   +--------------+--------------+
                   |                             |
              Local MySQL                  CPC SQL Server
                   |                             |
          Application Records             CPC Master Data
          Local Mappings                   HR Information
          Inventory Records                Asset Information
          Repair Records                   Department Data
          User Information                 Other Authorized Data
```

### Database Responsibilities

**Local MySQL Database**

Used for application-specific information, including:
- Application users and records.
- Inventory and spare parts.
- Local branch and application reference data.
- External master-data mappings.
- Repair and fault-report records.

**CPC Microsoft SQL Server Databases**

Used for authorized access to existing CPC master data:
- CPC HR database.
- CPC Help Desk and Asset Management database.
- Employee and department information.
- Asset-related source information.

The application maintains separate database connections for these systems.

---

## Project Structure

The project follows the standard Laravel application structure.

```text
cpc-inventory-management-system/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Services/
│
├── bootstrap/
│
├── config/
│   └── database.php
│
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
│
├── public/
│   ├── images/
│   └── build/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   └── web.php
│
├── storage/
│
├── tests/
│
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

---

## Installation and Setup

Follow these instructions to configure the project on a local Windows development environment.

### Prerequisites

Install the following software:

| Software | Requirement |
|---|---|
| PHP | 8.2 or compatible version |
| Composer | Compatible with Laravel 12 |
| Node.js | Compatible version |
| npm | Installed with Node.js |
| XAMPP | MySQL support |
| Git | Latest stable version |
| Visual Studio Code | Recommended editor |

Verify the installations:

```powershell
php -v
composer -V
node -v
npm -v
git --version
```

### Step 1: Clone the Repository

Open PowerShell:

```powershell
cd C:\xampp\htdocs
```

Clone the repository:

```powershell
git clone https://github.com/kavindhya-gothmini/cpc-inventory-management-system.git
```

Navigate to the project:

```powershell
cd cpc-inventory-management-system
```

### Step 2: Install Dependencies

Install Laravel dependencies:

```powershell
composer install
```

Install frontend dependencies:

```powershell
npm install
```

### Step 3: Configure the Environment

Create a local environment file:

```powershell
Copy-Item .env.example .env
```

Generate the Laravel application key:

```powershell
php artisan key:generate
```

Open the environment file:

```powershell
notepad .env
```

Configure your local database connection:

```env
APP_NAME="CPC Inventory Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cpc_inventory
DB_USERNAME=root
DB_PASSWORD=
```

Update the database username and password according to your local MySQL configuration.

### Step 4: Create the MySQL Database

1. Start MySQL from XAMPP.
2. Open phpMyAdmin at http://localhost/phpmyadmin.
3. Create a database named:

```text
cpc_inventory
```

4. Select the `utf8mb4_unicode_ci` collation.

### Step 5: Run Database Migrations

Clear the configuration cache:

```powershell
php artisan config:clear
```

Run the migrations:

```powershell
php artisan migrate
```

Create the public storage link:

```powershell
php artisan storage:link
```

**Important:** Do not use `php artisan migrate:fresh` on a database containing records you need to preserve.

### Step 6: Build Frontend Assets

```powershell
npm run build
```

### Step 7: Run the Application

Start the Laravel development server:

```powershell
php artisan serve
```

Open the application:

http://127.0.0.1:8000

---

## CPC SQL Server Configuration

The project supports separate connections to CPC HR and CPC Asset Management SQL Server databases.

Configure these values in your own `.env` file after obtaining authorized connection details.

### CPC HR Database

```env
CPC_SQLSRV_URL=
CPC_SQLSRV_HOST=
CPC_SQLSRV_PORT=1433
CPC_SQLSRV_DATABASE=
CPC_SQLSRV_USERNAME=
CPC_SQLSRV_PASSWORD=
```

### CPC Asset Management Database

```env
CPC_ASSET_SQLSRV_URL=
CPC_ASSET_SQLSRV_HOST=
CPC_ASSET_SQLSRV_PORT=1433
CPC_ASSET_SQLSRV_DATABASE=
CPC_ASSET_SQLSRV_USERNAME=
CPC_ASSET_SQLSRV_PASSWORD=
```

### SQL Server Security

```env
CPC_SQLSRV_ENCRYPT=yes
CPC_SQLSRV_TRUST_SERVER_CERTIFICATE=false
```

SQL Server connectivity requires:
- Appropriate CPC authorization.
- Access to the CPC network or an approved VPN.
- Correct connection settings.
- Compatible PHP SQL Server extensions, including `sqlsrv` and `pdo_sqlsrv`.

**Security:** Never commit SQL Server credentials, passwords or private database connection details to GitHub.

---

## GitHub Collaboration Guidelines

The project uses Git and GitHub to support collaborative development.

### Main Branch

The `main` branch contains the stable application code.

Developers should not directly push unfinished work to `main`.

### Feature Branches

Each developer should create a separate feature branch for their assigned module.

Example branches:

| Branch | Module |
|---|---|
| `main` | Stable application |
| `feature/employee-management` | Employee Management |
| `feature/asset-management` | Asset Management |
| `feature/spare-parts` | Spare Parts Management |
| `feature/reports` | Reports and Analytics |

### Development Workflow

**1. Update the main branch**

```powershell
git checkout main
git pull origin main
```

**2. Create a feature branch**

```powershell
git checkout -b feature/asset-management
```

**3. Develop and test your assigned module.**

**4. Stage your changes**

```powershell
git add .
```

**5. Commit your changes**

```powershell
git commit -m "Develop asset management module"
```

**6. Push your feature branch**

```powershell
git push -u origin feature/asset-management
```

**7. Create a Pull Request**

Open the GitHub repository and create a Pull Request:

- Base branch: `main`
- Compare branch: `feature/asset-management`

The project lead reviews the code and merges approved changes into `main`.

### Development Rules

1. Always work on your assigned feature branch.
2. Pull the latest changes before starting new work.
3. Do not push unfinished code directly to `main`.
4. Do not commit `.env` or other sensitive configuration files.
5. Coordinate changes to shared files, models, migrations and routes.
6. Test your changes before creating a Pull Request.
7. Use meaningful commit messages.
8. Review merge conflicts carefully.

---

## Security and Data Protection

The following practices must be followed:

- Keep database credentials private.
- Never upload `.env` files.
- Do not commit real employee information or confidential CPC records.
- Use authorized access to CPC databases.
- Avoid modifying CPC master data without permission.
- Follow branch-level data access restrictions.
- Review database migrations before merging.
- Do not expose internal server addresses or credentials in public documentation.

---

## Testing

Run the Laravel test suite:

```powershell
php artisan test
```

Check migration status:

```powershell
php artisan migrate:status
```

Check application configuration:

```powershell
php artisan about
```

Developers should test their assigned features before submitting Pull Requests.

---

## Project Development

The application is under active development.

Development activities include:
- Application module development.
- Database integration and master-data mapping.
- User interface improvements.
- Branch-level access control.
- Inventory and asset workflow improvements.
- Testing and quality assurance.
- Performance and security improvements.

---

## Contribution

Contributions are managed through the project's GitHub repository.

Repository:

https://github.com/kavindhya-gothmini/cpc-inventory-management-system

Before contributing:
1. Obtain repository access from the project lead.
2. Review the project development guidelines.
3. Work on your assigned feature branch.
4. Test your changes.
5. Submit a Pull Request for review.

---

## Project Information

| Information | Details |
|---|---|
| Project Name | CPC Inventory Management System |
| Organization | Ceylon Petroleum Corporation (CPC) |
| Framework | Laravel 12 |
| Backend | PHP |
| Frontend | HTML, CSS, JavaScript, Bootstrap |
| Database | MySQL and Microsoft SQL Server |
| Repository | GitHub |
| Development Model | Collaborative development |

---

<p align="center">
  <strong>CPC Inventory Management System</strong>
  <br>
  Centralized. Structured. Efficient.
</p>