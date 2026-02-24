# Admin Portal — Blockchain-Based Document Repository

A **Laravel 10** web application that provides an admin portal for a blockchain-based academic document repository. Institutions can register, generate cryptographic key pairs, digitally sign student documents, and anchor document hashes on a blockchain for tamper-proof verification.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [User Roles](#user-roles)
- [System Architecture](#system-architecture)
- [Cryptographic Design](#cryptographic-design)
- [Prerequisites](#prerequisites)
- [Installation & Setup](#installation--setup)
- [Configuration](#configuration)
- [Usage](#usage)
- [Security Considerations](#security-considerations)
- [License](#license)

---

## Overview

Centralized document systems are vulnerable to data tampering, single points of failure, and lack transparent verification. This system solves those problems by combining a traditional web backend with a blockchain network:

- Institutions generate **EC key pairs**; the public key is stored on-chain.
- Uploaded PDF documents are **SHA-256 hashed** and **digitally signed** with the institution's private key.
- The hash and signature are submitted to a **blockchain API**, producing an immutable transaction ID.
- Any party can verify a document's authenticity by comparing its hash and signature against the on-chain record.

---

## Features

- **Institution management** — register institutions, store public keys and encrypted private keys.
- **Cryptographic key pair generation** — EC key pairs generated per institution; private keys encrypted with AES-256-CBC (PBKDF2-derived key).
- **Student management** — admins manage students belonging to their institution.
- **Faculty & Program Study management** — hierarchical academic structure per institution.
- **Document upload & signing** — PDF files are hashed, signed, and stored securely; duplicate detection via SHA-256 hash.
- **Blockchain submission** — document metadata (hash, signature, public key) is sent to an external blockchain API and a transaction ID is recorded.
- **Blockchain explorer** — view all on-chain blocks and their associated institution data.
- **Role-based access control** — three distinct roles with separate route groups.
- **Soft deletes** — records are soft-deleted to preserve audit history.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.1+, Laravel 10 |
| Frontend | Bootstrap 5, Bootstrap Icons, SweetAlert2 |
| Asset bundler | Vite |
| Database | MySQL |
| Cryptography | phpseclib3 (EC), OpenSSL (AES-256-CBC), PBKDF2, SHA-256 |
| Authentication | Laravel Sanctum |
| Blockchain | External REST API (Hyperledger Fabric or compatible) |

---

## User Roles

| Role | Capabilities |
|---|---|
| `super_admin` | Manage institutions, manage users |
| `admin` | Manage students, upload/sign/submit documents to blockchain, manage faculties & program studies, view blockchain explorer |
| `student` | View own documents, initiate document verification |

---

## System Architecture

```
Browser (Bootstrap 5 + Vite)
        │
        ▼
Laravel 10 Application
  ├── Auth (Sanctum + role middleware)
  ├── InstitutionController  ──► MySQL (institutions, encrypted_keys)
  ├── StudentController      ──► MySQL (students, users)
  ├── DocumentController     ──► Local private storage (PDFs)
  │       │                        MySQL (documents: hash, signature, tx_id)
  │       └── Blockchain API ──► POST /api/documents  (hash + signature)
  └── BlockchainController   ──► GET  /api/blocks
```

### Document Workflow

1. **Admin** uploads a PDF for a student and provides the institution passphrase.
2. The passphrase is used (via PBKDF2) to decrypt the institution's AES-256-CBC-encrypted private key.
3. The PDF is hashed with **SHA-256**.
4. The hash is **signed** using the institution's **EC private key** (phpseclib3).
5. The document record (hash, base64 signature, file path) is saved to MySQL.
6. The admin submits the document to the **blockchain API**, which returns a `txId`.
7. The `txId` is stored in the database — documents with a `txId` are immutable (cannot be deleted).

---

## Cryptographic Design

| Primitive | Algorithm |
|---|---|
| Key generation | Elliptic Curve (EC) via phpseclib3 |
| Document hashing | SHA-256 |
| Digital signature | EC sign over SHA-256 hash |
| Private key encryption | AES-256-CBC with PBKDF2-derived key (100,000 iterations, SHA-256) |

The combination of hashing and digital signatures provides:
- **Integrity** — any change to the document produces a different hash.
- **Authenticity** — only the institution holding the private key can produce a valid signature.
- **Non-repudiation** — the on-chain record ties a specific institution's public key to a specific document hash.

---

## Prerequisites

- **PHP** ≥ 8.1 with extensions: `openssl`, `pdo_mysql`, `mbstring`, `fileinfo`
- **Composer** ≥ 2
- **Node.js** ≥ 18 and **npm**
- **MySQL** ≥ 8.0
- A running **blockchain API** service (configured via `BLOCKCHAIN_API_URL`)

---

## Installation & Setup

```bash
# 1. Clone the repository
git clone https://github.com/himeriusnico/adminPortal.git
cd adminPortal

# 2. Install PHP dependencies
composer install

# 3. Install JavaScript dependencies
npm install

# 4. Copy the environment file and edit it
cp .env.example .env

# 5. Generate the application key
php artisan key:generate

# 6. Run database migrations
php artisan migrate

# 7. Seed the database with initial roles, document types, and a super-admin user
php artisan db:seed

# 8. Build frontend assets (production)
npm run build

# 9. Start the development server
php artisan serve
```

The application will be available at `http://localhost:8000`.

> For local asset development with hot reload, run `npm run dev` in a separate terminal instead of (or alongside) `npm run build`.

---

## Configuration

Key environment variables in `.env`:

| Variable | Description | Default |
|---|---|---|
| `APP_NAME` | Application display name | `Laravel` |
| `APP_URL` | Public URL of the application | `http://localhost` |
| `DB_HOST` | MySQL host | `127.0.0.1` |
| `DB_PORT` | MySQL port | `3306` |
| `DB_DATABASE` | Database name | `laravel` |
| `DB_USERNAME` | Database user | `root` |
| `DB_PASSWORD` | Database password | _(empty)_ |
| `BLOCKCHAIN_API_URL` | Base URL of the blockchain REST API | `http://172.20.4.199:3000` |
| `FILESYSTEM_DISK` | Storage disk for uploaded files | `local` |

> **Important:** PDF documents are stored on the `private` disk under `storage/app/private/`. Ensure the `storage/app` directory is writable by the web server.

---

## Usage

### 1. Log in as Super Admin
Register or seed a `super_admin` account, then navigate to `/login`.

### 2. Create an Institution (Super Admin)
Go to **Institutions** → fill in the institution name, email, and address → submit.  
A cryptographic EC key pair is generated automatically; the public key is stored and the encrypted private key is saved alongside a random salt and IV.

### 3. Create an Admin User (Super Admin)
Go to **Users** → create a user with the `admin` role and assign them to an institution.

### 4. Manage Students (Admin)
Log in as an `admin` → go to **Students** → add student records and associate them with faculties and program studies.

### 5. Upload & Sign a Document (Admin)
Go to **Documents** → select a student, choose a document type, upload a PDF, and enter the institution passphrase.  
The system hashes the file, decrypts the private key, signs the hash, and saves everything to the database.

### 6. Submit to Blockchain (Admin)
On the Documents page, click **Send to Blockchain** next to a signed document.  
A transaction ID (`txId`) is returned and stored — the document is now immutably recorded on-chain.

### 7. View Blockchain Explorer (Admin)
Navigate to **Blockchain** to see all on-chain blocks, transaction IDs, institution names, and timestamps.

---

## Security Considerations

- **Private key protection** — private keys are never stored in plain text; they are encrypted with AES-256-CBC using a PBKDF2-derived key from the admin's passphrase.
- **Blockchain immutability** — documents with a recorded `txId` cannot be deleted through the application.
- **Institution isolation** — admins can only access students and documents belonging to their own institution.
- **File storage** — PDFs are stored on the `private` disk and served through authenticated Laravel routes, never exposed directly.
- **Duplicate detection** — the SHA-256 hash is checked before saving to prevent re-uploading identical files.

---

## License

This project is open-sourced under the [MIT License](https://opensource.org/licenses/MIT).
