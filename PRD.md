# Product Requirements Document (PRD)

# CatWang — Personal Finance Management Web App

**Version:** 1.0
**Status:** Planned
**Framework:** Laravel 13
**Database:** MySQL
**Frontend:** Blade + Tailwind CSS
**Chart:** Chart.js
**Authentication:** Laravel Authentication
**Tagline:** *Catat Uang, Atur Masa Depan.*

---

# 1. Overview

## 1.1 Nama Produk

**CatWang**

CatWang merupakan singkatan dari **Catat Uang**, yaitu aplikasi web untuk membantu pengguna mencatat, mengelola, dan memantau kondisi keuangan pribadi.

## 1.2 Deskripsi

CatWang adalah aplikasi manajemen keuangan pribadi berbasis web yang memungkinkan pengguna mencatat pemasukan dan pengeluaran secara terstruktur.

Aplikasi menyediakan dashboard modern yang menampilkan kondisi keuangan pengguna secara ringkas melalui saldo, statistik pemasukan dan pengeluaran, grafik, kategori pengeluaran, serta transaksi terbaru.

CatWang dirancang agar proses pencatatan keuangan menjadi sederhana, cepat, dan mudah dipahami.

---

# 2. Brand Identity

## 2.1 Brand Name

**CatWang**

## 2.2 Brand Meaning

**CatWang = Catat Uang**

Nama ini menggambarkan fungsi utama aplikasi, yaitu membantu pengguna mencatat dan mengelola keuangan pribadi.

## 2.3 Tagline

> **Catat Uang, Atur Masa Depan.**

## 2.4 Brand Personality

CatWang memiliki karakter:

* Modern
* Simple
* Friendly
* Clean
* Praktis
* Terpercaya
* Mudah digunakan

---

# 3. Background

Pengelolaan keuangan pribadi sering kali masih dilakukan secara manual menggunakan catatan, spreadsheet, atau hanya mengandalkan ingatan.

Hal tersebut dapat menyebabkan beberapa masalah:

* Pengguna lupa mencatat transaksi.
* Pengguna tidak mengetahui total pengeluaran.
* Pengguna sulit mengetahui uang digunakan untuk apa.
* Pengguna sulit mengetahui kategori pengeluaran terbesar.
* Pengguna tidak memiliki gambaran kondisi keuangan secara keseluruhan.
* Pengguna kesulitan membandingkan pemasukan dan pengeluaran setiap bulan.

CatWang dibuat untuk memberikan solusi sederhana melalui sistem pencatatan keuangan berbasis web.

---

# 4. Product Goals

## 4.1 Primary Goals

CatWang harus memungkinkan pengguna untuk:

1. Mencatat pemasukan.
2. Mencatat pengeluaran.
3. Memberikan kategori pada transaksi.
4. Menambahkan deskripsi transaksi.
5. Menentukan tanggal transaksi.
6. Melihat saldo secara otomatis.
7. Melihat riwayat transaksi.
8. Melihat statistik keuangan.
9. Melakukan filter transaksi.
10. Melihat laporan keuangan.

## 4.2 Secondary Goals

Pada pengembangan berikutnya CatWang dapat membantu pengguna:

* Menentukan budget.
* Membuat target tabungan.
* Memantau progress tabungan.
* Mendapatkan peringatan ketika budget hampir habis.
* Mengekspor laporan keuangan.

---

# 5. Target Users

## 5.1 Primary User

CatWang ditujukan untuk individu yang ingin mengelola keuangan pribadi.

Contoh:

* Mahasiswa.
* Freelancer.
* Karyawan.
* Pemilik usaha kecil.
* Pengguna yang baru belajar mengatur keuangan.

## 5.2 User Needs

User membutuhkan:

* Cara mencatat transaksi yang cepat.
* Informasi saldo yang jelas.
* Riwayat transaksi.
* Statistik pengeluaran.
* Laporan keuangan sederhana.
* Tampilan yang mudah dipahami.

---

# 6. Product Scope

CatWang dikembangkan secara bertahap agar fitur inti dapat diselesaikan dan diuji terlebih dahulu sebelum fitur tambahan dikembangkan.

## 6.1 MVP — Version 1.0

MVP berfokus pada kebutuhan utama pengguna untuk **mencatat dan memahami kondisi keuangan pribadi**.

Fitur MVP:

1. Authentication
2. Dashboard
3. Category Management
4. Transaction Management
5. Transaction Filtering
6. Financial Summary
7. Financial Report
8. Financial Charts

## 6.2 V1 Enhancement — Version 1.1

Setelah MVP stabil, fitur berikut dikembangkan:

1. Budget Management
2. Saving Goals
3. Export PDF
4. Export Excel/CSV
5. UI/UX Improvement
6. Dark Mode

## 6.3 Future Development — Version 2.0+

Fitur lanjutan:

1. Recurring Transactions
2. Budget Notifications
3. Bill Reminders
4. Multiple Wallets
5. Bank Account
6. E-Wallet
7. Transfer antar-wallet
8. CSV Import
9. PWA
10. Multi-currency
11. Financial Health Score
12. Financial Analytics
13. Expense Prediction

---

# 7. User Flow

## 7.1 Registration

```text
User
 ↓
Register
 ↓
Input Name
Input Email
Input Password
 ↓
Validation
 ↓
Account Created
 ↓
Login
 ↓
Dashboard
```

## 7.2 Login

```text
User
 ↓
Login
 ↓
Email & Password Validation
 ↓
Dashboard
```

## 7.3 Add Transaction

```text
Dashboard
 ↓
Tambah Transaksi
 ↓
Pilih Jenis
 ├── Pemasukan
 └── Pengeluaran
 ↓
Pilih Kategori
 ↓
Masukkan Nominal
 ↓
Masukkan Deskripsi
 ↓
Pilih Tanggal
 ↓
Simpan
 ↓
Database
 ↓
Dashboard diperbarui
```

## 7.4 View Report

```text
Dashboard
 ↓
Laporan
 ↓
Pilih Periode
 ↓
System mengambil transaksi
 ↓
Menampilkan laporan
```

---

# 8. Functional Requirements

## 8.1 Authentication

### 8.1.1 Register

User dapat membuat akun dengan:

* Nama.
* Email.
* Password.
* Konfirmasi password.

### 8.1.2 Login

User dapat login menggunakan:

* Email.
* Password.

### 8.1.3 Logout

User dapat logout dari aplikasi.

### 8.1.4 User Data Isolation

Setiap user hanya dapat melihat dan mengelola data keuangannya sendiri.

User A tidak boleh dapat melihat transaksi User B.

---

## 8.2 Dashboard

Dashboard adalah halaman utama CatWang setelah user login.

Dashboard harus menampilkan informasi penting secara ringkas.

### 8.2.1 Balance Card

Menampilkan saldo pengguna.

Rumus:

```text
Saldo = Total Pemasukan - Total Pengeluaran
```

### 8.2.2 Income Card

Menampilkan total pemasukan berdasarkan periode tertentu.

### 8.2.3 Expense Card

Menampilkan total pengeluaran berdasarkan periode tertentu.

### 8.2.4 Transaction Count

Menampilkan jumlah transaksi.

### 8.2.5 Recent Transactions

Menampilkan transaksi terbaru.

Contoh:

```text
Makan Siang       - Rp25.000
Bensin            - Rp50.000
Gaji              + Rp5.000.000
```

### 8.2.6 Financial Chart

Dashboard menampilkan grafik:

* Pemasukan.
* Pengeluaran.

### 8.2.7 Category Chart

Menampilkan pengeluaran berdasarkan kategori.

---

## 8.3 Transaction Management

CatWang memiliki dua tipe transaksi:

```text
income
expense
```

### 8.3.1 Income

Digunakan untuk mencatat uang masuk.

Contoh:

* Gaji.
* Freelance.
* Bonus.
* Hadiah.
* Penjualan.
* Lainnya.

### 8.3.2 Expense

Digunakan untuk mencatat uang keluar.

Contoh:

* Makanan.
* Transportasi.
* Belanja.
* Tagihan.
* Hiburan.
* Pendidikan.
* Kesehatan.
* Internet.
* Lainnya.

### 8.3.3 Transaction Fields

| Field            | Type        | Required |
| ---------------- | ----------- | -------- |
| id               | Big Integer | Yes      |
| user_id          | Big Integer | Yes      |
| category_id      | Big Integer | Yes      |
| type             | String      | Yes      |
| amount           | Decimal     | Yes      |
| description      | Text        | Yes      |
| transaction_date | Date        | Yes      |
| created_at       | Timestamp   | Yes      |
| updated_at       | Timestamp   | Yes      |

### 8.3.4 Create Transaction

User dapat menambahkan transaksi baru.

Form terdiri dari:

```text
Jenis Transaksi
Kategori
Nominal
Deskripsi
Tanggal
```

### 8.3.5 Read Transaction

User dapat melihat:

* Semua transaksi.
* Detail transaksi.
* Pemasukan.
* Pengeluaran.

### 8.3.6 Update Transaction

User dapat mengubah transaksi yang telah dibuat.

### 8.3.7 Delete Transaction

User dapat menghapus transaksi.

Sistem harus menampilkan konfirmasi sebelum transaksi dihapus.

---

## 8.4 Category Management

User dapat mengelola kategori transaksi.

### 8.4.1 Default Income Categories

```text
Gaji
Freelance
Bonus
Hadiah
Penjualan
Lainnya
```

### 8.4.2 Default Expense Categories

```text
Makanan
Transportasi
Belanja
Tagihan
Hiburan
Pendidikan
Kesehatan
Internet
Lainnya
```

### 8.4.3 Category Fields

```text
id
user_id
name
type
created_at
updated_at
```

---

## 8.5 Transaction Filtering

User dapat melakukan filter berdasarkan:

* Tanggal.
* Bulan.
* Tahun.
* Jenis transaksi.
* Kategori.

Contoh:

```text
Periode:
01 Oktober 2026
-
31 Oktober 2026

Jenis:
Pengeluaran

Kategori:
Makanan
```

Sistem hanya menampilkan transaksi sesuai filter.

---

## 8.6 Financial Summary

CatWang menyediakan ringkasan keuangan berdasarkan periode.

### 8.6.1 Summary Data

Laporan menampilkan:

```text
Total Pemasukan
Total Pengeluaran
Saldo
Jumlah Transaksi
```

### 8.6.2 Category Summary

Contoh:

```text
Makanan           Rp1.000.000
Transportasi        Rp500.000
Hiburan             Rp300.000
Belanja             Rp250.000
```

---

## 8.7 Financial Report

User dapat melihat laporan berdasarkan periode.

### 8.7.1 Monthly Report

Contoh:

```text
Januari

Pemasukan    Rp5.000.000
Pengeluaran  Rp2.500.000
Saldo        Rp2.500.000
```

### 8.7.2 Monthly Comparison

Sistem dapat membandingkan kondisi keuangan antarbulan.

Contoh:

```text
September
Pemasukan    Rp5.000.000
Pengeluaran  Rp2.000.000

Oktober
Pemasukan    Rp5.000.000
Pengeluaran  Rp2.500.000
```

---

## 8.8 Financial Visualization

CatWang menggunakan grafik untuk memberikan gambaran visual kondisi keuangan.

### 8.8.1 Income vs Expense

Menampilkan perbandingan pemasukan dan pengeluaran.

### 8.8.2 Expense by Category

Menampilkan distribusi pengeluaran berdasarkan kategori.

### 8.8.3 Monthly Financial Trend

Menampilkan perubahan pemasukan dan pengeluaran dari bulan ke bulan.

---

# 9. Database Design

## 9.1 Entity Relationship

```text
USER
 │
 ├──────────────< TRANSACTIONS
 │                       │
 │                       └──── CATEGORY
 │
 ├──────────────< CATEGORIES
 │
 ├──────────────< BUDGETS
 │
 └──────────────< SAVING_GOALS
```

## 9.2 Users

```text
users
----------------
id
name
email
password
created_at
updated_at
```

## 9.3 Categories

```text
categories
----------------
id
user_id
name
type
created_at
updated_at
```

## 9.4 Transactions

```text
transactions
----------------
id
user_id
category_id
type
amount
description
transaction_date
created_at
updated_at
```

## 9.5 Budgets

```text
budgets
----------------
id
user_id
category_id
amount
period
created_at
updated_at
```

## 9.6 Saving Goals

```text
saving_goals
----------------
id
user_id
name
target_amount
current_amount
target_date
description
created_at
updated_at
```

---

# 10. Database Relationships

## 10.1 User

```text
User hasMany Transactions
User hasMany Categories
User hasMany Budgets
User hasMany SavingGoals
```

## 10.2 Transaction

```text
Transaction belongsTo User
Transaction belongsTo Category
```

## 10.3 Category

```text
Category belongsTo User
Category hasMany Transactions
```

## 10.4 Budget

```text
Budget belongsTo User
Budget belongsTo Category
```

## 10.5 Saving Goal

```text
SavingGoal belongsTo User
```

---

# 11. Technology Stack

CatWang menggunakan:

```text
Backend
Laravel 13
PHP
Laravel Eloquent

Database
MySQL

Frontend
Blade
Tailwind CSS
JavaScript

Visualization
Chart.js

Authentication
Laravel Authentication

Development
Laravel Herd / PHP Artisan
Git
GitHub
```

---

# 12. Project Structure

```text
catwang/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── DashboardController.php
│   │       ├── TransactionController.php
│   │       ├── CategoryController.php
│   │       ├── BudgetController.php
│   │       └── ReportController.php
│   │
│   └── Models/
│       ├── User.php
│       ├── Transaction.php
│       ├── Category.php
│       ├── Budget.php
│       └── SavingGoal.php
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── navigation.blade.php
│       │
│       ├── dashboard/
│       │   └── index.blade.php
│       │
│       ├── transactions/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       │
│       ├── categories/
│       │   ├── index.blade.php
│       │   └── create.blade.php
│       │
│       ├── budgets/
│       │   ├── index.blade.php
│       │   └── create.blade.php
│       │
│       └── reports/
│           └── index.blade.php
│
├── routes/
│   └── web.php
│
└── database/
    ├── migrations/
    └── seeders/
```

---

# 13. Route Structure

Contoh route utama:

```php
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('transactions', TransactionController::class);

    Route::resource('categories', CategoryController::class);

    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

});
```

---

# 14. UI/UX Requirements

## 14.1 Design Style

CatWang menggunakan desain:

* Modern.
* Minimalis.
* Clean.
* Responsive.
* Friendly.
* Mudah digunakan.

## 14.2 Color System

```text
Background : #F8FAFC
Card       : #FFFFFF
Text       : #0F172A
Secondary  : #64748B
Income     : Green
Expense    : Red
Primary    : Blue / Indigo
```

## 14.3 Navigation

Navigation utama:

```text
Dashboard
Transaksi
Kategori
Laporan
Budget
Target Tabungan
Pengaturan
Logout
```

Fitur yang belum tersedia dapat diberi label:

```text
Coming Soon
```

---

# 15. Dashboard Layout

Konsep dashboard:

```text
┌─────────────────────────────────────────────────┐
│ CatWang                         👤 Profile      │
├───────────────┬─────────────────────────────────┤
│               │                                 │
│ Dashboard     │  Selamat datang kembali 👋     │
│               │                                 │
│ Transaksi     │  ┌────────┐ ┌────────┐ ┌─────┐│
│               │  │ Saldo  │ │ Masuk  │ │Keluar│
│ Kategori      │  │        │ │        │ │      ││
│               │  └────────┘ └────────┘ └─────┘│
│ Laporan       │                                 │
│               │  ┌──────────────┐ ┌──────────┐│
│ Budget        │  │ Grafik       │ │ Kategori ││
│               │  │ Keuangan     │ │          ││
│ Target        │  │              │ │          ││
│               │  └──────────────┘ └──────────┘│
│               │                                 │
│               │  Transaksi Terbaru             │
│               │  ───────────────────────────── │
│               │  Makan       - Rp25.000       │
│               │  Bensin      - Rp50.000       │
│               │  Gaji        + Rp5.000.000     │
└───────────────┴─────────────────────────────────┘
```

---

# 16. Validation Requirements

Transaction harus divalidasi sebelum disimpan.

```text
type
required
income / expense

category_id
required
valid category

amount
required
numeric
minimum 0

description
required
string

transaction_date
required
valid date
```

User tidak dapat menyimpan transaksi jika data wajib belum lengkap.

---

# 17. Security Requirements

CatWang harus:

1. Menggunakan authentication.
2. Menggunakan authorization berdasarkan user.
3. Memisahkan data setiap user.
4. Menggunakan password hashing.
5. Menggunakan CSRF protection.
6. Melakukan validation terhadap input.
7. Menggunakan Eloquent ORM.
8. Menggunakan route middleware `auth`.
9. Tidak memperbolehkan user mengakses transaksi milik user lain.

Contoh query:

```php
Transaction::where('user_id', auth()->id())->get();
```

---

# 18. Non-Functional Requirements

## 18.1 Performance

Dashboard harus dapat menampilkan data dengan cepat untuk penggunaan personal.

## 18.2 Responsiveness

CatWang harus dapat digunakan pada:

* Desktop.
* Laptop.
* Tablet.
* Smartphone.

## 18.3 Usability

User harus dapat menambahkan transaksi dengan cepat tanpa proses yang rumit.

## 18.4 Maintainability

Kode harus mengikuti struktur Laravel yang jelas:

```text
Model
Controller
Request
Route
View
Migration
```

---

# 19. Development Roadmap

## 19.1 Phase 1 — Project Setup

**Tujuan:** Menyiapkan fondasi project CatWang.

### Tasks

* [ ] Install Laravel 13.
* [ ] Create CatWang project.
* [ ] Setup MySQL.
* [ ] Configure `.env`.
* [ ] Setup authentication.
* [ ] Setup Tailwind CSS.
* [ ] Setup Git repository.
* [ ] Setup basic application layout.
* [ ] Setup navigation/sidebar.

### Output

```text
Register
↓
Login
↓
Logout
↓
Dashboard
```

---

## 19.2 Phase 2 — Database & Category

**Tujuan:** Menyiapkan database dan sistem kategori.

### Tasks

* [ ] Create categories migration.
* [ ] Create transactions migration.
* [ ] Setup User → Category relationship.
* [ ] Setup User → Transaction relationship.
* [ ] Setup Category → Transaction relationship.
* [ ] Create Category model.
* [ ] Create Transaction model.
* [ ] Create default category seeder.
* [ ] Create Category CRUD.
* [ ] Add authorization untuk Category.

### Output

User dapat:

```text
Melihat kategori
↓
Menambah kategori
↓
Mengedit kategori
↓
Menghapus kategori
```

---

## 19.3 Phase 3 — Transaction Management

**Tujuan:** Membuat fitur inti CatWang untuk mencatat pemasukan dan pengeluaran.

### Tasks

* [ ] Transaction model.
* [ ] Transaction controller.
* [ ] Transaction Form Request.
* [ ] Transaction list.
* [ ] Add income.
* [ ] Add expense.
* [ ] Edit transaction.
* [ ] Delete transaction.
* [ ] Transaction detail.
* [ ] Transaction validation.
* [ ] User ownership authorization.

### Output

```text
Tambah Pemasukan
Tambah Pengeluaran
        ↓
Simpan
        ↓
Lihat Transaksi
        ↓
Edit / Hapus
```

---

## 19.4 Phase 4 — Dashboard

**Tujuan:** Memberikan gambaran kondisi keuangan secara cepat.

### Tasks

* [ ] Calculate total income.
* [ ] Calculate total expense.
* [ ] Calculate balance.
* [ ] Calculate transaction count.
* [ ] Display recent transactions.
* [ ] Add dashboard summary cards.
* [ ] Add responsive dashboard layout.
* [ ] Add empty states.

### Output

User dapat mengetahui kondisi keuangannya tanpa harus membuka halaman transaksi.

---

## 19.5 Phase 5 — Filter, Report & Visualization

**Tujuan:** Membantu user memahami pola keuangan.

### Transaction Filter

* [ ] Filter berdasarkan tanggal.
* [ ] Filter berdasarkan bulan.
* [ ] Filter berdasarkan tahun.
* [ ] Filter berdasarkan tipe.
* [ ] Filter berdasarkan kategori.
* [ ] Combine multiple filters.
* [ ] Reset filter.

### Financial Report

* [ ] Monthly summary.
* [ ] Total income.
* [ ] Total expense.
* [ ] Balance.
* [ ] Transaction count.
* [ ] Expense by category.
* [ ] Monthly comparison.

### Charts

* [ ] Income vs Expense chart.
* [ ] Expense by Category chart.
* [ ] Monthly Financial Trend chart.

### Output

User dapat mengetahui:

> "Berapa pengeluaran saya bulan ini?"

> "Kategori apa yang paling banyak menghabiskan uang?"

> "Apakah pengeluaran bulan ini lebih besar dari bulan lalu?"

---

## 19.6 Phase 6 — MVP Stabilization & Release

**Tujuan:** Memastikan seluruh fitur MVP stabil sebelum CatWang v1.0 dirilis.

### Tasks

* [ ] Test authentication.
* [ ] Test category CRUD.
* [ ] Test transaction CRUD.
* [ ] Test dashboard calculations.
* [ ] Test filtering.
* [ ] Test reports.
* [ ] Test charts.
* [ ] Test authorization.
* [ ] Test user data isolation.
* [ ] Test responsive UI.
* [ ] Fix validation errors.
* [ ] Fix UI/UX issues.
* [ ] Improve loading states.
* [ ] Add empty states.
* [ ] Add error states.
* [ ] Add confirmation dialogs.

### Release

**CatWang v1.0 — MVP**

```text
Authentication
+
Category
+
Transaction CRUD
+
Dashboard
+
Filter
+
Report
+
Charts
+
Testing
```

---

## 19.7 Phase 7 — V1 Enhancement

**Tujuan:** Menambahkan fitur untuk membantu user mengontrol dan merencanakan keuangan.

### 19.7.1 Budget

* [ ] Budget model.
* [ ] Budget migration.
* [ ] Budget CRUD.
* [ ] Budget berdasarkan kategori.
* [ ] Budget berdasarkan periode.
* [ ] Budget progress.
* [ ] Budget status.

### 19.7.2 Saving Goals

* [ ] Saving Goal model.
* [ ] Saving Goal migration.
* [ ] Create goal.
* [ ] Edit goal.
* [ ] Delete goal.
* [ ] Add saving.
* [ ] Calculate progress.
* [ ] Target date.

### 19.7.3 Export

* [ ] Export CSV.
* [ ] Export Excel.
* [ ] Export PDF.
* [ ] Export berdasarkan periode.
* [ ] Export berdasarkan filter.

### 19.7.4 UI Enhancement

* [ ] Improve dashboard.
* [ ] Improve mobile layout.
* [ ] Add dark mode.
* [ ] Improve animations.
* [ ] Improve loading states.
* [ ] Improve empty states.

### Release

**CatWang v1.1**

```text
MVP
+
Budget
+
Saving Goals
+
Export
+
UI Enhancement
```

---

## 19.8 Phase 8 — Advanced Features

**Tujuan:** Mengembangkan CatWang menjadi platform manajemen keuangan yang lebih lengkap.

Fitur:

* [ ] Recurring Transactions.
* [ ] Budget Notifications.
* [ ] Bill Reminders.
* [ ] Multiple Wallets.
* [ ] Bank Account.
* [ ] E-Wallet.
* [ ] Transfer antar-wallet.
* [ ] CSV Import.
* [ ] PWA.
* [ ] Multi-currency.
* [ ] Financial Health Score.
* [ ] Advanced Financial Analytics.
* [ ] Expense Prediction.

### Release

**CatWang v2.0+**

---

# 20. MVP Feature Matrix

| Feature                | MVP v1.0 | V1.1 | V2+ |
| ---------------------- | :------: | :--: | :-: |
| Register               |     ✓    |      |     |
| Login                  |     ✓    |      |     |
| Logout                 |     ✓    |      |     |
| User Isolation         |     ✓    |      |     |
| Category CRUD          |     ✓    |      |     |
| Income                 |     ✓    |      |     |
| Expense                |     ✓    |      |     |
| Transaction CRUD       |     ✓    |      |     |
| Transaction Detail     |     ✓    |      |     |
| Transaction Filter     |     ✓    |      |     |
| Balance                |     ✓    |      |     |
| Dashboard              |     ✓    |      |     |
| Recent Transactions    |     ✓    |      |     |
| Income/Expense Chart   |     ✓    |      |     |
| Category Chart         |     ✓    |      |     |
| Monthly Report         |     ✓    |      |     |
| Budget                 |          |   ✓  |     |
| Saving Goals           |          |   ✓  |     |
| Export CSV             |          |   ✓  |     |
| Export Excel           |          |   ✓  |     |
| Export PDF             |          |   ✓  |     |
| Dark Mode              |          |   ✓  |     |
| Recurring Transaction  |          |      |  ✓  |
| Budget Notification    |          |      |  ✓  |
| Bill Reminder          |          |      |  ✓  |
| Multiple Wallet        |          |      |  ✓  |
| Transfer               |          |      |  ✓  |
| CSV Import             |          |      |  ✓  |
| Financial Health Score |          |      |  ✓  |
| PWA                    |          |      |  ✓  |
| Multi-Currency         |          |      |  ✓  |
| Expense Prediction     |          |      |  ✓  |

---

# 21. MVP Definition of Done

CatWang MVP **v1.0** dianggap selesai apabila seluruh kriteria berikut terpenuhi.

## 21.1 Authentication

* [ ] User dapat register.
* [ ] User dapat login.
* [ ] User dapat logout.
* [ ] User hanya dapat mengakses data miliknya.

## 21.2 Categories

* [ ] User dapat melihat kategori.
* [ ] User dapat membuat kategori.
* [ ] User dapat mengedit kategori.
* [ ] User dapat menghapus kategori.

## 21.3 Transactions

* [ ] User dapat mencatat pemasukan.
* [ ] User dapat mencatat pengeluaran.
* [ ] User dapat melihat transaksi.
* [ ] User dapat melihat detail transaksi.
* [ ] User dapat mengedit transaksi.
* [ ] User dapat menghapus transaksi.
* [ ] Semua input divalidasi.

## 21.4 Dashboard

* [ ] Total saldo tampil dengan benar.
* [ ] Total pemasukan tampil dengan benar.
* [ ] Total pengeluaran tampil dengan benar.
* [ ] Jumlah transaksi tampil dengan benar.
* [ ] Transaksi terbaru tampil.
* [ ] Grafik pemasukan/pengeluaran tampil.
* [ ] Grafik kategori tampil.

## 21.5 Filter

* [ ] Filter tanggal.
* [ ] Filter bulan.
* [ ] Filter tahun.
* [ ] Filter tipe transaksi.
* [ ] Filter kategori.
* [ ] Reset filter.

## 21.6 Report

* [ ] Laporan bulanan.
* [ ] Total pemasukan.
* [ ] Total pengeluaran.
* [ ] Saldo.
* [ ] Pengeluaran berdasarkan kategori.

## 21.7 UI/UX

* [ ] Responsive desktop.
* [ ] Responsive tablet.
* [ ] Responsive mobile.
* [ ] Loading state.
* [ ] Empty state.
* [ ] Error state.
* [ ] Confirmation delete.

---

# 22. Release Strategy

## 22.1 Release 0.1 — Foundation

```text
Laravel
+
Authentication
+
Database
+
Basic Layout
```

## 22.2 Release 0.2 — Transactions

```text
Category
+
Transaction CRUD
```

## 22.3 Release 0.3 — Dashboard

```text
Dashboard
+
Balance
+
Income
+
Expense
+
Recent Transactions
```

## 22.4 Release 0.4 — Analytics

```text
Filter
+
Report
+
Charts
```

## 22.5 Release 1.0 — MVP

```text
Authentication
+
Category
+
Transaction
+
Dashboard
+
Filter
+
Report
+
Charts
+
Testing
```

## 22.6 Release 1.1 — Enhancement

```text
Budget
+
Saving Goals
+
Export
+
UI Enhancement
```

## 22.7 Release 2.0+ — Advanced

```text
Recurring Transaction
+
Reminder
+
Multiple Wallet
+
Transfer
+
Import
+
Advanced Analytics
+
PWA
```

---

# 23. Future Ideas

Setelah CatWang v2.0 stabil, fitur tambahan dapat dipertimbangkan:

* Financial Health Score.
* Advanced financial analytics.
* Expense prediction.
* Custom dashboard widgets.
* Financial calendar.
* Subscription tracking.
* Net worth tracking.
* Debt tracking.
* Investment tracking.
* Financial goal recommendations.

Fitur-fitur tersebut **tidak menjadi bagian dari MVP** dan hanya dikembangkan apabila terdapat kebutuhan yang jelas.

---

# 24. Product Vision

CatWang bertujuan menjadi aplikasi keuangan pribadi yang membantu pengguna:

```text
CATAT
  ↓
PAHAMI
  ↓
KENDALIKAN
  ↓
RENCANAKAN
  ↓
TUMBUHKAN
```

Versi MVP tidak bertujuan menjadi aplikasi finansial yang kompleks.

Fokus **CatWang v1.0** adalah:

> **Membuat pencatatan pemasukan dan pengeluaran menjadi mudah serta memberikan gambaran kondisi keuangan yang jelas kepada pengguna.**

Setelah fondasi tersebut stabil, CatWang dapat berkembang menjadi aplikasi pengelolaan keuangan pribadi yang lebih lengkap melalui Budget, Saving Goals, Export, Reminder, Multiple Wallet, dan fitur financial analytics.
