# 🖥️ Swatcher

> Self-hosted, fully configurable server monitoring dashboard built with **Laravel 12** + **Vue 3**.

---

## 🚀 What is Swatcher?

Swatcher is a modular, self-hosted server monitoring tool. Instead of fixed themes, every user configures their own dashboard layout, widget placement, and even custom CSS. All data is stored persistently — for a configurable number of days or permanently.

---

## ✨ Features (v1 Scope)

### 🔐 Auth & Roles
- Login / Logout via **Laravel Sanctum** (SPA Cookie Auth)
- Role system: `superadmin`, `admin`, `editor`, `viewer`
- Per-resource Policies (Dashboard, Server, Widget, User)
- Audit log for all admin actions

### 🖥️ Server Management
- Add/edit/delete monitored servers & nodes
- Tag servers with groups (e.g. `web`, `db`, `game`)
- Connection method: SSH agent or local node_exporter

### 📊 Metrics Collection
- CPU, RAM, Disk, Load Average, Uptime
- Network traffic per host
- Basic process/service status
- Polling interval configurable per server
- Metrics stored in **VictoriaMetrics** (TSDB)
- Configurable retention period (days, months, years, permanent)

### 🧩 Modular Dashboard Builder
- Per-user dashboards with **drag & resize** via Vue 3 Grid Layout
- Multiple dashboards per user
- Dashboard variables / filters (e.g. switch between servers)
- Widget types: `stat`, `timeseries`, `status`
- Save layout to database (JSON)

### 🎨 UI & Theming
- Light / Dark mode
- No fixed themes — instead: configurable **CSS design tokens** per user
- Optional scoped **custom CSS** per dashboard (validated, sandboxed)
- Global defaults configurable by admins

### ⚙️ Settings
- Global admin settings page (polling interval, retention, default widgets)
- Per-user preferences (layout, tokens, CSS overrides)

### 🔔 Alerts (basic)
- Threshold alerts for CPU, RAM, Disk
- Notification channels configurable (v1.1+)

---

## 🗺️ Roadmap

| Version | Focus |
|---------|-------|
| **v1** | Auth, Roles, Server CRUD, Host Metrics, Dashboard Builder, Settings, Base Alerts |
| **v1.1** | Docker container metrics, Alert notification channels |
| **v1.2** | Pterodactyl integration |
| **v1.3** | Public status pages, Dashboard sharing |
| **v2** | Plugin system, Custom widget types, Multi-node agent |

---

## 🏗️ Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12 (API-only) |
| Frontend | Vue 3 + Pinia + Vue Router |
| Auth | Laravel Sanctum (SPA) |
| App Database | PostgreSQL |
| Metrics Storage | VictoriaMetrics |
| Queue / Scheduler | Laravel Queues + Scheduler |
| Container | Docker + Docker Compose |

---

## 📁 Project Structure

```
Swatcher/
├── backend/               # Laravel 12 API
│   ├── app/
│   │   ├── Console/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Auth/
│   │   │   │   ├── Dashboard/
│   │   │   │   ├── Server/
│   │   │   │   ├── Widget/
│   │   │   │   ├── User/
│   │   │   │   └── Settings/
│   │   │   └── Middleware/
│   │   ├── Jobs/
│   │   │   └── PollServerMetrics.php
│   │   ├── Models/
│   │   │   ├── User.php
│   │   │   ├── Role.php
│   │   │   ├── Server.php
│   │   │   ├── Dashboard.php
│   │   │   ├── DashboardWidget.php
│   │   │   ├── WidgetLayout.php
│   │   │   ├── AlertRule.php
│   │   │   ├── RetentionPolicy.php
│   │   │   ├── UserPreference.php
│   │   │   ├── UiToken.php
│   │   │   ├── UiCustomCss.php
│   │   │   └── AuditLog.php
│   │   ├── Policies/
│   │   │   ├── DashboardPolicy.php
│   │   │   ├── ServerPolicy.php
│   │   │   ├── WidgetPolicy.php
│   │   │   └── UserPolicy.php
│   │   └── Services/
│   │       ├── MetricsService.php
│   │       └── VictoriaMetricsService.php
│   ├── database/
│   │   └── migrations/
│   ├── routes/
│   │   └── api.php
│   └── .env.example
├── frontend/              # Vue 3 SPA
│   ├── src/
│   │   ├── assets/
│   │   ├── components/
│   │   │   ├── dashboard/
│   │   │   │   ├── DashboardGrid.vue
│   │   │   │   ├── WidgetWrapper.vue
│   │   │   │   └── widgets/
│   │   │   │       ├── StatWidget.vue
│   │   │   │       ├── TimeseriesWidget.vue
│   │   │   │       └── StatusWidget.vue
│   │   │   ├── layout/
│   │   │   │   ├── AppSidebar.vue
│   │   │   │   ├── AppHeader.vue
│   │   │   │   └── AppFooter.vue
│   │   │   └── ui/
│   │   ├── pages/
│   │   │   ├── auth/
│   │   │   │   └── LoginPage.vue
│   │   │   ├── dashboard/
│   │   │   │   └── DashboardPage.vue
│   │   │   ├── servers/
│   │   │   │   ├── ServerListPage.vue
│   │   │   │   └── ServerDetailPage.vue
│   │   │   ├── settings/
│   │   │   │   ├── GlobalSettingsPage.vue
│   │   │   │   └── UserPreferencesPage.vue
│   │   │   ├── admin/
│   │   │   │   ├── UserManagementPage.vue
│   │   │   │   └── AuditLogPage.vue
│   │   │   └── alerts/
│   │   │       └── AlertRulesPage.vue
│   │   ├── stores/
│   │   │   ├── auth.js
│   │   │   ├── dashboard.js
│   │   │   ├── servers.js
│   │   │   └── settings.js
│   │   ├── router/
│   │   │   └── index.js
│   │   ├── composables/
│   │   │   ├── useMetrics.js
│   │   │   └── useTheme.js
│   │   └── App.vue
│   ├── public/
│   ├── index.html
│   ├── vite.config.js
│   └── package.json
├── docker/
│   ├── nginx/
│   │   └── default.conf
│   └── php/
│       └── Dockerfile
├── docker-compose.yml
├── docker-compose.prod.yml
└── README.md
```

---

## 🗃️ Database Schema (Overview)

```
users                  # Auth + profile
roles                  # superadmin | admin | editor | viewer
permissions            # Fine-grained permissions per role
role_user              # Pivot
servers                # Monitored hosts
server_checks          # Last check status
metric_sources         # Where metrics come from (node_exporter, etc.)
dashboards             # Per-user dashboards
dashboard_widgets      # What is shown on a dashboard
widget_layouts         # Where/how each widget is positioned
alert_rules            # Threshold alerts
retention_policies     # Configurable data retention
user_preferences       # Per-user settings
ui_tokens              # CSS design tokens (per user or global)
ui_custom_css          # Scoped CSS overrides per dashboard
audit_logs             # Admin action log
```

---

## 🐳 Quick Start (Docker)

```bash
git clone https://github.com/crackscout123/Swatcher.git
cd Swatcher
cp backend/.env.example backend/.env
docker compose up -d
```

Then:

```bash
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate --seed
```

Frontend dev server:

```bash
cd frontend
npm install
npm run dev
```

---

## ⚙️ Environment Variables (Key)

```env
APP_NAME=Swatcher
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=swatcher
DB_USERNAME=swatcher
DB_PASSWORD=secret

VICTORIAMETRICS_URL=http://victoriametrics:8428
VICTORIAMETRICS_RETENTION=90d

SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost
```

---

## 📄 License

MIT — feel free to fork and self-host.
