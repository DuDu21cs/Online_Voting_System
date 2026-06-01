# 🗳️ Online Voting System

A web-based online voting system built with **PHP** and **MySQL**, designed to support government and public elections. It features a dual-panel architecture — a voter-facing interface for casting ballots and a secure admin panel for managing the entire election lifecycle.

---

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Requirements](#requirements)
- [Installation & Setup](#installation--setup)
- [Usage](#usage)
- [Database Schema Overview](#database-schema-overview)
- [Multi-Language Support](#multi-language-support)
- [Security Notes](#security-notes)
- [Contributing](#contributing)

---

## ✨ Features

### Voter Side
- Voter login with ID and hashed password authentication
- Region-based ballot display
- One-time vote submission with validation
- View election results after voting (when enabled by admin)

### Admin Panel
- Secure admin login and session management
- Dashboard with live statistics (regions, candidates, total voters, votes cast)
- Full CRUD management for:
  - **Positions / Regions** — with ordering (up/down)
  - **Candidates** — with photo upload and position assignment
  - **Voters** — with photo upload and credential management
- Election control — start election with configurable end time
- Toggle homepage visibility and results visibility
- Votes tally with printable report
- Vote reset functionality
- Admin profile update

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP (procedural) |
| Database | MySQL (via MySQLi) |
| Frontend | Bootstrap 3, AdminLTE |
| Charts | Chart.js, Flot |
| Rich Text | CKEditor |
| Icons | Ionicons, Font Awesome |
| PDF Export | TCPDF |
| Dependencies | Bower (managed under `bower_components/`) |

---

## 📁 Project Structure

```
online-voting-system/
├── admin/                  # Admin panel
│   ├── includes/           # Shared components (conn, session, header, nav, modals)
│   ├── index.php           # Admin login
│   ├── home.php            # Dashboard
│   ├── candidates.php      # Candidate management
│   ├── voters.php          # Voter management
│   ├── positions.php       # Position/region management
│   ├── votes.php           # Votes log
│   ├── votes_reset.php     # Reset all votes
│   ├── election_control.php# Start/stop election
│   ├── report.php          # Printable results report
│   └── config.ini          # Election title config
├── includes/               # Voter-side shared components
├── db/
│   └── votesystem.sql      # Database dump
├── languages_*.php         # Language files (10 languages)
├── index.php               # Voter login page
├── home.php                # Voter ballot page
├── submit_ballot.php       # Ballot submission handler
├── voter_results.php       # Results page for voters
├── login.php               # Voter authentication handler
├── logout.php              # Session termination
├── signup.php              # Voter self-registration (if enabled)
└── bower_components/       # Frontend libraries
```

---

## ⚙️ Requirements

- **XAMPP** (or any LAMP/WAMP stack)
  - Apache
  - PHP 7.4+
  - MySQL 5.7+
- A web browser (Chrome, Firefox, Edge)
- A text editor (VS Code, Sublime Text, Notepad++, etc.)

---

## 🚀 Installation & Setup

**1. Clone or download the project**

```bash
git clone https://github.com/DuDu21cs/online-voting-system.git
```

Or download and extract the ZIP file.

**2. Move the project to your web server root**

- For XAMPP on Windows: `C:/xampp/htdocs/`
- For XAMPP on Linux/macOS: `/opt/lampp/htdocs/`

```
htdocs/
└── online-voting-system/
```

**3. Create the database**

- Start Apache and MySQL in XAMPP Control Panel
- Open your browser and go to `http://localhost/phpmyadmin`
- Create a new database named `votesystem`
- Click **Import**, select `db/votesystem.sql`, and click **Go**

**4. Configure the database connection**

Open `admin/includes/conn.php` and update the credentials if needed:

```php
$conn = new mysqli('localhost', 'root', '', 'votesystem');
```

**5. Run the application**

Open your browser and navigate to:

```
http://localhost/online-voting-system/
```

Admin panel:

```
http://localhost/online-voting-system/admin/
```

---

## 🖥️ Usage

### Admin Workflow

1. Log in to the admin panel at `/admin/`
2. Go to **Positions** and add the election positions (e.g., regions, constituencies)
3. Go to **Candidates** and add candidates, assigning each to a position
4. Go to **Voters** and register eligible voters with their credentials
5. Go to **Election Control** to start the election and set an end time
6. Monitor live vote tallies from the **Dashboard**
7. After the election, enable results visibility so voters can view outcomes
8. Generate and print the results report from **Report**
9. Use **Reset Votes** to clear all votes if a re-run is needed

### Voter Workflow

1. Visit the homepage at `/` and log in with your Voter ID and password
2. The ballot is displayed based on your assigned region
3. Select your candidates and submit your ballot
4. Once the admin enables results, view the outcome from your dashboard

---

## 🗄️ Database Schema Overview

| Table | Description |
|---|---|
| `admin` | Admin user credentials |
| `voters` | Registered voter accounts |
| `positions` | Election positions / regions |
| `candidates` | Candidates linked to positions |
| `votes` | Individual vote records |
| `election_settings` | Key-value store for election state (started, end time, results visibility) |

---

## 🌐 Multi-Language Support

The system supports **10 languages**, selectable from the voter interface:

| Code | Language |
|---|---|
| `en` | English |
| `am` | Amharic |
| `om` | Afaan Oromoo |
| `ti` | Tigrinya |
| `so` | Somali |
| `sg` | Sidaama (Sidagneegna) |
| `sid` | Sidama |
| `wal` | Wolaita |
| `hd` | Hadiyya |
| `aa` | Afar |

Language files are located at the project root as `languages_<code>.php`.

---

## 🔒 Security Notes

- Voter passwords are stored using PHP's `password_hash()` and verified with `password_verify()`.
- Admin and voter sessions are managed separately.
- It is recommended to change the default database credentials before deploying.
- For production use, consider adding CSRF protection, input validation middleware, and HTTPS enforcement.
- Restrict direct access to sensitive PHP files using `.htaccess` rules.

---

## 🤝 Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a new branch for your feature or fix:
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. Make your changes and commit them:
   ```bash
   git commit -m "Add: description of your change"
   ```
4. Push to your fork:
   ```bash
   git push origin feature/your-feature-name
   ```
5. Open a **Pull Request** describing your changes

Please ensure your code follows the existing style and that any database changes include an updated SQL dump.

---

> Built for government and public election use. For support or inquiries, please open an issue in the repository.
