# API Documentation - Operator Mobile App

## Base URL
```
http://your-domain.com/api
```

## Authentication
Semua endpoint (kecuali login) memerlukan Bearer Token yang didapat dari endpoint login.

### Headers
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Endpoints

### 1. Authentication

#### POST /api/auth/login
Login operator dan dapatkan token akses.

**Request Body:**
```json
{
    "username": "operator123",
    "password": "password123"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": {
            "id": 1,
            "username": "operator123",
            "name": "John Doe",
            "role": {
                "id": 2,
                "name": "Operator"
            }
        },
        "token": "1|abc123...",
        "token_type": "Bearer",
        "expires_in": null
    }
}
```

**Response Error (401):**
```json
{
    "success": false,
    "message": "Username atau password salah"
}
```

#### GET /api/auth/me
Dapatkan informasi user yang sedang login.

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "username": "operator123",
            "name": "John Doe",
            "role": {
                "id": 2,
                "name": "Operator"
            },
            "created_at": "2025-01-15 10:30:00",
            "last_login": null
        }
    }
}
```

#### POST /api/auth/logout
Logout dan hapus token akses.

**Response Success (200):**
```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

#### POST /api/auth/refresh
Refresh token akses.

**Response Success (200):**
```json
{
    "success": true,
    "message": "Token refreshed successfully",
    "data": {
        "token": "2|def456...",
        "token_type": "Bearer",
        "expires_in": null
    }
}
```

#### POST /api/auth/change-password
Ubah password user.

**Request Body:**
```json
{
    "current_password": "password123",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Password berhasil diubah. Silakan login ulang."
}
```

### 2. Dashboard

#### GET /api/operator/dashboard
Dapatkan data dashboard operator.

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "username": "operator123"
        },
        "stats": {
            "today_store": 15,
            "today_pull": 8,
            "total_slots": 100,
            "occupied_slots": 75,
            "empty_slots": 25
        },
        "recent_activities": [
            {
                "id": 123,
                "action": "store",
                "quantity": 50,
                "slot_name": "A-01-01",
                "item_description": "Bearing 6205",
                "rack_name": "Rack A",
                "created_at": "2025-01-15 14:30:00"
            }
        ]
    }
}
```

### 3. Store Operations

#### POST /api/operator/store/scan-slot
Scan slot untuk operasi store (validasi).

**Request Body:**
```json
{
    "slot_name": "A-01-01",
    "erp_code": "ERP001",
    "quantity": 50,
    "lot_number": "LOT20250115"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Slot valid untuk store",
    "data": {
        "slot": {
            "slot_name": "A-01-01",
            "rack_name": "Rack A",
            "current_quantity": 100,
            "available_space": 200,
            "capacity": 300
        },
        "item": {
            "erp_code": "ERP001",
            "part_no": "P001",
            "description": "Bearing 6205",
            "model": "6205-2RS",
            "customer": "Customer A"
        }
    }
}
```

#### POST /api/operator/store/by-erp
Lakukan operasi store item.

**Request Body:**
```json
{
    "slot_name": "A-01-01",
    "erp_code": "ERP001",
    "quantity": 50,
    "lot_number": "LOT20250115",
    "notes": "Stock baru dari supplier"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Item berhasil disimpan",
    "data": {
        "log_id": 124,
        "slot_name": "A-01-01",
        "rack_name": "Rack A",
        "quantity_stored": 50,
        "new_total_quantity": 150,
        "available_space": 150,
        "timestamp": "2025-01-15 15:00:00"
    }
}
```

### 4. Pull Operations

#### POST /api/operator/pull/scan-slot
Scan slot untuk operasi pull (validasi).

**Request Body:**
```json
{
    "slot_name": "A-01-01",
    "quantity": 20
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Slot valid untuk pull",
    "data": {
        "slot": {
            "slot_name": "A-01-01",
            "rack_name": "Rack A",
            "current_quantity": 150,
            "available_quantity": 150
        },
        "item": {
            "erp_code": "ERP001",
            "part_no": "P001",
            "description": "Bearing 6205",
            "model": "6205-2RS",
            "customer": "Customer A"
        }
    }
}
```

#### POST /api/operator/pull/by-lot
Lakukan operasi pull item.

**Request Body:**
```json
{
    "slot_name": "A-01-01",
    "quantity": 20,
    "lot_number": "LOT20250115",
    "notes": "Untuk production line A"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Item berhasil diambil",
    "data": {
        "log_id": 125,
        "slot_name": "A-01-01",
        "rack_name": "Rack A",
        "quantity_pulled": 20,
        "remaining_quantity": 130,
        "timestamp": "2025-01-15 15:30:00"
    }
}
```

### 5. Slot Information

#### GET /api/operator/slot/{slot_name}
Dapatkan informasi detail slot.

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "slot": {
            "slot_name": "A-01-01",
            "rack_name": "Rack A",
            "current_quantity": 130,
            "capacity": 300,
            "available_space": 170,
            "occupancy_percentage": 43.33,
            "is_empty": false,
            "is_full": false
        },
        "item": {
            "erp_code": "ERP001",
            "part_no": "P001",
            "description": "Bearing 6205",
            "model": "6205-2RS",
            "customer": "Customer A",
            "part_image": "http://domain.com/storage/parts/bearing.jpg",
            "packaging_image": "http://domain.com/storage/packaging/bearing_pkg.jpg"
        },
        "rack": {
            "rack_name": "Rack A",
            "location": "Area A"
        }
    }
}
```

### 6. Search and Utilities

#### GET /api/operator/search/items
Cari item berdasarkan keyword.

**Query Parameters:**
- `query` (required): Keyword pencarian
- `limit` (optional): Jumlah maksimal hasil (default: 20, max: 50)

**Example Request:**
```
GET /api/operator/search/items?query=bearing&limit=10
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "items": [
            {
                "id": 1,
                "erp_code": "ERP001",
                "part_no": "P001",
                "description": "Bearing 6205",
                "model": "6205-2RS",
                "customer": "Customer A",
                "total_quantity": 500,
                "stored_quantity": 130,
                "available_quantity": 370,
                "part_image": "http://domain.com/storage/parts/bearing.jpg",
                "packaging_image": "http://domain.com/storage/packaging/bearing_pkg.jpg",
                "slots": [
                    {
                        "slot_name": "A-01-01",
                        "rack_name": "Rack A",
                        "current_quantity": 130,
                        "capacity": 300
                    }
                ]
            }
        ],
        "total_found": 1,
        "query": "bearing"
    }
}
```

#### GET /api/operator/activities
Dapatkan riwayat aktivitas operator.

**Query Parameters:**
- `action` (optional): Filter by action (store/pull)
- `date_from` (optional): Filter from date (YYYY-MM-DD)
- `date_to` (optional): Filter to date (YYYY-MM-DD)
- `limit` (optional): Jumlah per halaman (default: 20, max: 100)
- `page` (optional): Nomor halaman (default: 1)

**Example Request:**
```
GET /api/operator/activities?action=store&date_from=2025-01-01&limit=10
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "activities": [
            {
                "id": 124,
                "action": "store",
                "quantity": 50,
                "lot_number": "LOT20250115",
                "notes": "Stock baru dari supplier",
                "slot_name": "A-01-01",
                "rack_name": "Rack A",
                "item_description": "Bearing 6205",
                "erp_code": "ERP001",
                "timestamp": "2025-01-15 15:00:00"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 5,
            "per_page": 10,
            "total": 50
        }
    }
}
```

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "slot_name": ["Slot name is required"],
        "quantity": ["Quantity must be at least 1"]
    }
}
```

### Authentication Error (401)
```json
{
    "success": false,
    "message": "Unauthenticated"
}
```

### Authorization Error (403)
```json
{
    "success": false,
    "message": "Access denied. Required role: operator",
    "user_role": "Operator",
    "required_role": "operator"
}
```

### Not Found Error (404)
```json
{
    "success": false,
    "message": "Slot tidak ditemukan"
}
```

### Business Logic Error (400)
```json
{
    "success": false,
    "message": "Slot tidak memiliki ruang yang cukup",
    "data": {
        "slot_name": "A-01-01",
        "available_space": 50,
        "requested_quantity": 100,
        "current_quantity": 250,
        "capacity": 300
    }
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Error scanning slot: Database connection failed"
}
```

## Rate Limiting
API memiliki rate limiting untuk mencegah abuse:
- 60 requests per minute untuk authenticated users
- 30 requests per minute untuk unauthenticated requests

## Testing
Untuk testing API, gunakan tools seperti:
- Postman
- Insomnia
- cURL
- Laravel Telescope (jika tersedia)

## Mobile App Integration Tips

1. **Token Management**: Simpan token di secure storage dan refresh secara berkala
2. **Offline Support**: Implementasikan caching untuk data yang sering diakses
3. **Error Handling**: Tampilkan pesan error yang user-friendly
4. **Loading States**: Tampilkan loading indicator saat API call
5. **Retry Logic**: Implementasikan retry mechanism untuk network failures
6. **Data Sync**: Sync data secara berkala untuk memastikan data terbaru
