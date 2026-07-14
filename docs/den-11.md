# Den 11 — JWT + Security

**Datum:** 14. 7. 2026  
**Status:** hotovo

LexikJWTAuthenticationBundle + firewall `json_login` + `jwt`.

---

## Flow

1. `POST /api/login` `{ "email", "password" }` → `{ "token": "…" }`
2. `GET /api/me` s `Authorization: Bearer …`
3. Demo stránka: `/jwt-demo`

Fixtures user: `study@example.com` / `study123`

---

## Security.yaml (jádro)

- firewall `login` — `json_login` + Lexik success/failure handler
- firewall `api` — `jwt: ~` (stateless)
- `User` entity + `app_user_provider`

---

## HS256 vs RS256

Na Windows mi nepěkně selhalo generování PEM klíčů (OpenSSL CLI chyběl, PHP openssl_pkey_new hlásil chybu).  
Pro studium jsem nastavil **HS256** se shared secretem v `.env`.

V produkci bych šel do **RS256** + `private.pem` / `public.pem` (asymmetric — API ověřuje public key).

---

## Laravel analogie

Sanctum = session/token pro SPA. Passport/JWT = bližší Lexiku.  
Firewall koncept je v Symfony explicitnější než `auth:sanctum` middleware — vidíš to v YAML.

---

## Zdroje

- https://symfony.com/doc/current/security.html
- https://github.com/lexik/LexikJWTAuthenticationBundle
