# CS Student Support Assistant (React + PHP + MySQL)

A full-stack chatbot assistant for student support services.

- Frontend: React (Vite)
- Backend: PHP (WAMP-compatible)
- Database: MySQL
- Auth: Email/password + Azure AD SSO
- LLM: OpenAI (default) or Azure OpenAI

## Features
- Admin Panel
  - Dashboard analytics (users, chats, usage)
  - Knowledge Base ingestion (text files, URLs)
  - Student resources upload and management
  - Provider settings (Azure OpenAI / OpenAI)
- Student Portal
  - Login/register or SSO with school account (Azure AD)
  - Chatbot with RAG over knowledge base
  - Browse/download resources

---

## Quick Start (Windows + WAMP)

1) Prerequisites
- WAMP Server (Apache + PHP >= 8.1 + MySQL 8)
- Node.js >= 18 (for building frontend)
- Composer (for PHP dependencies)

2) Database
- Create a database, e.g. `cs_assistant`
- Import schema:
  - Open `sql/schema.sql` and run it in your MySQL client (phpMyAdmin or CLI)

3) Backend setup
- Copy the `backend` directory into your WAMP `www` root as `cs-assistant/api`
- In a terminal inside `cs-assistant/api`:
  - Copy `.env.example` to `.env` and fill values
  - Run `composer install`
- Ensure Apache `mod_rewrite` is enabled. In WAMP, enable `rewrite_module`.

4) Frontend setup
- Copy the `frontend` directory into your WAMP `www` root as `cs-assistant/frontend` (or keep outside; only `dist` must be hosted)
- In a terminal inside `frontend`:
  - Run `npm install`
  - Create `.env` from `.env.example` and set `VITE_API_BASE` to your API URL, e.g. `http://localhost/cs-assistant/api`
  - Build static files: `npm run build`
- Copy the generated `frontend/dist` into `cs-assistant` root to serve as the site root, or set a vhost pointing to `dist`.
  - Simplest: place `dist` contents into `www/cs-assistant/` and ensure API is reachable at `/cs-assistant/api`.

5) Folder layout under WAMP `www`
```
www/
  cs-assistant/
    index.html (from frontend/dist)
    assets/... (from frontend/dist)
    api/ (backend public dir)
      index.php
      .htaccess
      vendor/ (after composer install)
      ...
```

6) Configure environment
- Backend: `backend/config/.env.example` → create `backend/config/.env`
- Frontend: `frontend/.env.example` → create `frontend/.env`

7) Run
- Start WAMP, ensure MySQL and Apache are running
- Visit `http://localhost/cs-assistant/`
- Login as admin after registering a first user, then in DB set `role` to `admin` for that user

---

## Azure OpenAI Setup (Optional)
1) Provision Azure OpenAI in Azure Portal
2) Create deployments:
   - Chat model, e.g. `gpt-4o-mini` or `gpt-4o`
   - Embeddings model, e.g. `text-embedding-3-large`
3) Collect:
   - Resource endpoint, e.g. `https://your-resource.openai.azure.com`
   - API key
   - Deployment names for chat and embeddings
4) Backend `.env` settings:
```
PROVIDER=azure
AZURE_OPENAI_API_KEY=your_key
AZURE_OPENAI_ENDPOINT=https://your-resource.openai.azure.com
AZURE_OPENAI_CHAT_DEPLOYMENT=gpt-4o-mini
AZURE_OPENAI_EMBED_DEPLOYMENT=text-embedding-3-large
AZURE_OPENAI_API_VERSION=2024-02-15-preview
```

## OpenAI Setup (Alternative)
- Create API key at OpenAI
- Backend `.env`:
```
PROVIDER=openai
OPENAI_API_KEY=your_key
OPENAI_CHAT_MODEL=gpt-4o-mini
OPENAI_EMBED_MODEL=text-embedding-3-large
```

---

## Azure AD SSO Setup (OIDC)
1) In Azure Entra ID → App registrations → New registration
2) Supported account types: Single tenant (or per school policy)
3) Redirect URI: `http://localhost/cs-assistant/api/auth/oidc/callback`
4) Note `Tenant ID`, `Client ID`, and create a `Client Secret`
5) Backend `.env`:
```
AZURE_AD_TENANT_ID=...
AZURE_AD_CLIENT_ID=...
AZURE_AD_CLIENT_SECRET=...
AZURE_AD_REDIRECT_URI=http://localhost/cs-assistant/api/auth/oidc/callback
```

---

## Admin First Login
- Register a user at `/register`
- In MySQL, set the user's `role` to `admin`
- Re-login; admin UI will appear

---

## Knowledge Base Ingestion
- Upload `.txt` or `.md` files
- Add URLs (the system fetches the page and extracts text)
- The backend chunks the text and stores embeddings for retrieval

Note: PDF/DOCX ingestion is disabled by default to avoid extra Windows dependencies. See comments in `KBController.php` to enable with Composer add-ons.

---

## Security Notes
- Set a strong `JWT_SECRET`
- Restrict `uploads/` permissions to prevent script execution
- Consider HTTPS for production

---

## Packaging
- After you build the frontend, you can zip the entire `cs-assistant` folder (including `api` and frontend `dist`) for distribution.

---

## Troubleshooting
- CORS errors: ensure `FRONTEND_ORIGIN` matches your site origin in backend `.env`
- 404 on API: verify `.htaccess` rewrite enabled in Apache
- 401: ensure token present; login again
