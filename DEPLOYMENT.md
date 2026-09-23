# Deploying TravelMate PHP to Render

This guide provides step-by-step instructions for deploying your **TravelMate** PHP & MySQL application to [Render](https://render.com).

---

## 1. Prerequisites & Overview

This project includes:
- **`Dockerfile`**: Builds a PHP 8.2 + Apache environment with `mysqli` and `pdo_mysql` extensions.
- **`start.sh`**: Handles dynamic port binding for Render (`$PORT`).
- **`config.php`**: Automatically reads environment variables and initializes database tables (`destinations`, `bookings`, `messages`) + seeds data on first run.
- **`render.yaml`**: Render Blueprint configuration for 1-click deployment.

---

## 2. Set Up a Free MySQL Database

Render does not offer a free managed MySQL service directly (only PostgreSQL), so you can pair Render with a free cloud MySQL provider:

### Option A: Aiven (Recommended)
1. Sign up at [aiven.io](https://aiven.io).
2. Create a free **MySQL** database service.
3. Note down the **Host**, **Port**, **User**, **Password**, and **Database Name**.

### Option B: TiDB Cloud / Clever Cloud / Railway
- Any provider offering a standard MySQL host and credentials will work seamlessly.

---

## 3. Deploy to Render

### Method 1: Deploy using Render Blueprint (Easiest)

1. **Push your code to GitHub**:
   ```bash
   git add .
   git commit -m "Configure project for Render deployment"
   git push origin main
   ```

2. **Log in to Render**:
   - Open [dashboard.render.com](https://dashboard.render.com).

3. **Create a Blueprint**:
   - Click **New +** -> **Blueprint**.
   - Connect your GitHub repository (`Travel-Mate`).
   - Render will read `render.yaml` and configure the service automatically.

4. **Add Environment Variables**:
   - Go to your service's **Environment** tab in Render dashboard and set:
     - `DB_HOST`: *(Your MySQL host, e.g. `mysql-123.aivencloud.com`)*
     - `DB_USER`: *(Your MySQL username, e.g. `avnadmin`)*
     - `DB_PASSWORD`: *(Your MySQL password)*
     - `DB_NAME`: `travel_website`
     - `DB_PORT`: `3306` (or your database port)

---

### Method 2: Manual Web Service Creation

1. In Render Dashboard, click **New +** -> **Web Service**.
2. Select **Build and deploy from a Git repository** and pick your repository.
3. Settings:
   - **Name**: `travel-mate`
   - **Environment**: `Docker`
   - **Region**: Select closest to your users (e.g. Singapore / Frankfurt / Oregon)
   - **Branch**: `main`
   - **Instance Type**: `Free`
4. Expand **Advanced** -> **Add Environment Variable**:
   - Add `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`, `DB_PORT`.
   - (Or set a single `DATABASE_URL` e.g. `mysql://user:pass@host:port/dbname`).
5. Click **Create Web Service**.

---

## 4. Verification

1. Once the deployment status turns to **Live**, click on your Render URL (e.g. `https://travel-mate.onrender.com`).
2. `config.php` will automatically:
   - Connect to your cloud MySQL database.
   - Automatically create the tables (`destinations`, `bookings`, `messages`).
   - Seed the initial destination items.
3. Test creating a booking or sending a contact message!
4. Access the admin dashboard at `https://travel-mate.onrender.com/admin/index.php`.

---

## Troubleshooting

- **Database Connection Failed Error**:
  Double-check your `DB_HOST`, `DB_USER`, `DB_PASSWORD`, and `DB_PORT` in the Render Environment settings. Make sure your MySQL provider permits external connections (0.0.0.0/0).
- **Free Instance Spin-Down**:
  On Render's Free tier, the service spins down after 15 minutes of inactivity. The first request after inactivity may take 30-50 seconds to start up.
