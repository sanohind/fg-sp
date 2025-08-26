# Controller Documentation - Racking System

## Overview
Sistem racking ini menggunakan **Controller Web** karena aplikasi menggunakan Blade template untuk tampilan. Berikut adalah dokumentasi lengkap untuk semua controller yang telah dibuat.

## Daftar Controller

### 1. RackController
**File:** `app/Http/Controllers/RackController.php`

**Fungsi:** Mengelola data rack (rak) dengan fitur CRUD dan history tracking.

**Method:**
- `index()` - Menampilkan daftar semua rack
- `create()` - Form tambah rack baru
- `store()` - Menyimpan rack baru dengan history
- `edit($id)` - Form edit rack
- `update($id)` - Update rack dengan history
- `destroy($id)` - Hapus rack dengan history
- `history($id)` - Lihat history perubahan rack

**Fitur:**
- Validasi rack name unik
- Validasi total slots minimal 1
- Pencatatan history untuk setiap perubahan
- Alasan perubahan wajib diisi saat edit

### 2. SlotController
**File:** `app/Http/Controllers/SlotController.php`

**Fungsi:** Mengelola data slot dengan fitur assign part dan history tracking.

**Method:**
- `index()` - Menampilkan daftar semua slot
- `create()` - Form tambah slot baru
- `store()` - Menyimpan slot baru dengan history
- `edit($id)` - Form edit slot
- `update($id)` - Update slot dengan history
- `destroy($id)` - Hapus slot dengan history
- `assignPart($id)` - Form assign part ke slot
- `storeAssignPart()` - Proses assign part
- `changePart($id)` - Form ganti part di slot
- `storeChangePart()` - Proses ganti part
- `detail($id)` - Detail slot
- `history($id)` - History perubahan slot

**Fitur:**
- Validasi kapasitas slot
- Pengecekan item sudah ter-assign
- Pencatatan history untuk assign/change part
- Relasi dengan rack dan item

### 3. ItemController
**File:** `app/Http/Controllers/ItemController.php`

**Fungsi:** Mengelola data item/part dengan upload gambar dan history tracking.

**Method:**
- `index()` - Menampilkan daftar semua item
- `create()` - Form tambah item baru
- `store()` - Menyimpan item baru dengan upload gambar
- `edit($id)` - Form edit item
- `update($id)` - Update item dengan history
- `destroy($id)` - Hapus item dengan history
- `show($id)` - Detail item
- `history($id)` - History perubahan item

**Fitur:**
- Upload gambar part dan packaging
- Validasi ERP code unik
- Pencatatan history lengkap
- Hapus gambar lama saat update

### 4. UserController
**File:** `app/Http/Controllers/UserController.php`

**Fungsi:** Mengelola user (hanya untuk superadmin).

**Method:**
- `index()` - Daftar semua user
- `create()` - Form tambah user
- `store()` - Menyimpan user baru
- `edit($id)` - Form edit user
- `update($id)` - Update user
- `destroy($id)` - Hapus user
- `show($id)` - Detail user

**Fitur:**
- Middleware role superadmin
- Hash password otomatis
- Validasi username unik
- Pencegahan hapus akun sendiri

### 5. HistoryController
**File:** `app/Http/Controllers/HistoryController.php`

**Fungsi:** Menampilkan dan export data log store pull.

**Method:**
- `index()` - Daftar history dengan filter
- `show($id)` - Detail log
- `export()` - Export ke CSV

**Fitur:**
- Filter berdasarkan tanggal
- Filter berdasarkan action (store/pull)
- Filter berdasarkan user
- Filter berdasarkan slot_name
- Filter berdasarkan part_no
- Export data ke CSV
- Pagination

### 6. AdminController
**File:** `app/Http/Controllers/AdminController.php`

**Fungsi:** Dashboard admin dengan statistik dan akses ke semua fitur.

**Method:**
- `index()` - Dashboard dengan statistik
- `rackIndex()` - Index rack dengan statistik
- `slotIndex()` - Index slot
- `itemIndex()` - Index item
- `historyIndex()` - Index history
- `userIndex()` - Index user (superadmin only)

**Fitur:**
- Statistik total rack, slot, item, user
- Statistik slot ter-assign
- Recent activities
- Rack utilization

### 7. OperatorController
**File:** `app/Http/Controllers/OperatorController.php`

**Fungsi:** Operasi posting dan pulling untuk operator dengan QR code scanning.

**Method:**
- `index()` - Dashboard operator
- `posting()` - Form posting item
- `scanSlotForPosting()` - Scan QR code slot untuk posting
- `scanBoxForPosting()` - Scan QR code box untuk posting
- `pulling()` - Form pulling item
- `scanSlotForPulling()` - Scan QR code slot untuk pulling
- `scanBoxForPulling()` - Scan QR code box untuk pulling
- `getSlotInfo($slotName)` - Info slot berdasarkan nama slot
- `searchItem()` - Search item (AJAX)
- `getSlotLotNumbers($slotName)` - Daftar lot numbers di slot

**Fitur:**
- QR code scanning untuk slot dan box
- Validasi part number sesuai slot
- Validasi kapasitas slot
- Auto-assign item ke slot kosong
- Auto-clear slot jika qty = 0
- Log setiap scan box ke log_store_pull
- Real-time quantity tracking
- **Lot number tracking** - Setiap box memiliki lot number unik

## Routes

### Admin Routes (superadmin, admin)
```php
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:superadmin,admin'])
```

### Superadmin Only Routes
```php
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:superadmin'])
```

### Operator Routes
```php
Route::prefix('operator')->name('operator.')->middleware(['auth', 'role:operator'])
```

## QR Code Scanning System

### Posting Process
1. **Scan Slot QR** - Operator scan QR code slot
2. **Display Slot Info** - Sistem tampilkan info slot (kosong/terisi)
3. **Scan Box QR** - Operator scan QR code setiap box
4. **Validation** - Sistem validasi part number sesuai slot
5. **Update Quantity** - Quantity di slot bertambah
6. **Log Activity** - Setiap scan box dicatat di log_store_pull dengan lot number unik

### Pulling Process
1. **Scan Slot QR** - Operator scan QR code slot
2. **Display Slot Info** - Sistem tampilkan info item di slot
3. **Scan Box QR** - Operator scan QR code setiap box
4. **Validation** - Sistem validasi part number sesuai slot
5. **Update Quantity** - Quantity di slot berkurang
6. **Log Activity** - Setiap scan box dicatat di log_store_pull dengan lot number unik

## Lot Number Concept

### Konsep Lot Number
- **Satu slot** menyimpan **part number yang sama**
- **Setiap box** memiliki **lot number yang berbeda**
- Lot number menjadi **pembeda utama** antar box dalam slot yang sama

### Contoh:
```
Slot A-01:
- Part No: ABC123
- ERP Code: ERP456
- Box 1: Lot No: LOT001
- Box 2: Lot No: LOT002  
- Box 3: Lot No: LOT003
- Total Qty: 3
```

### Tracking Lot Numbers
- Setiap scan box dicatat dengan lot number unik
- Sistem dapat melacak lot number yang masih ada di slot
- Method `getSlotLotNumbers()` untuk melihat daftar lot numbers aktif

## Log Store Pull Structure

Tabel `log_store_pull` menyimpan setiap aktivitas scan box:

```sql
id | erp_code | part_no | slot_id | slot_name | rack_name | lot_no | action | user_id | name | qty | created_at | updated_at
```

**Fields:**
- `erp_code` - ERP code dari box yang di-scan
- `part_no` - Part number dari box (sama untuk slot yang sama)
- `slot_id` - ID slot
- `slot_name` - Nama slot
- `rack_name` - Nama rack
- `lot_no` - **Lot number unik per box** (pembeda utama)
- `action` - 'store' atau 'pull'
- `user_id` - ID operator
- `name` - Nama operator
- `qty` - Quantity (selalu 1 per box)

## Middleware

### RoleMiddleware
**File:** `app/Http/Middleware/RoleMiddleware.php`

**Fungsi:** Kontrol akses berdasarkan role user.

**Fitur:**
- Support multiple roles
- Session dan Sanctum authentication
- Redirect ke login jika tidak authorized
- JSON response untuk API

## Role-Based Access Control

### Superadmin
- Akses semua fitur admin
- Manajemen user
- CRUD rack, slot, item
- Lihat history

### Admin
- CRUD rack, slot, item
- Lihat history
- **Tidak bisa** akses manajemen user

### Operator
- Dashboard operator
- QR code scanning untuk posting/pulling
- Lihat aktivitas sendiri
- **Tidak bisa** akses fitur admin

## History Tracking

### Tabel History (untuk edit/delete)
1. **RackHistory** - Perubahan data rack
2. **SlotHistory** - Perubahan data slot dan assign part
3. **ItemHistory** - Perubahan data item

### Tabel Log Store Pull (untuk aktivitas operator)
- **log_store_pull** - Setiap scan box untuk posting/pulling dengan lot number tracking

## Validasi

Semua controller menggunakan validasi Laravel:
- Required fields
- Unique constraints
- File upload validation
- Integer validation
- String length limits
- QR code validation
- **Lot number validation**

## File Upload

Controller Item mendukung upload gambar:
- Part image
- Packaging image
- Auto delete file lama
- Storage di folder public

## Database Relationships

Controller menggunakan Eloquent relationships:
- User -> Role
- Rack -> Slots
- Slot -> Rack, Item
- Item -> Slot
- LogStorePull -> User, Slot

## Error Handling

- Try-catch untuk operasi database
- Validation errors
- File upload errors
- Authorization errors
- QR code validation errors
- Custom error messages untuk scan box
- **Lot number tracking errors**

## API Endpoints untuk QR Scanning

### Posting
- `POST /operator/posting/scan-slot` - Scan slot QR
- `POST /operator/posting/scan-box` - Scan box QR

### Pulling
- `POST /operator/pulling/scan-slot` - Scan slot QR
- `POST /operator/pulling/scan-box` - Scan box QR

### Utility
- `GET /operator/slot/{slotName}/info` - Info slot
- `GET /operator/slot/{slotName}/lot-numbers` - Daftar lot numbers di slot
- `GET /operator/search-item` - Search item

## Lot Number Tracking Features

### Method: getSlotLotNumbers()
**Endpoint:** `GET /operator/slot/{slotName}/lot-numbers`

**Fungsi:** Mendapatkan daftar lot numbers yang masih aktif di slot tertentu.

**Response:**
```json
{
    "slot_name": "A-01",
    "lot_numbers": ["LOT001", "LOT002", "LOT003"],
    "total_lots": 3
}
```

**Logika:**
- Mengambil semua lot numbers dengan action 'store'
- Mengurangi lot numbers dengan action 'pull'
- Hasil = lot numbers yang masih ada di slot
