# 📊 System Architecture - Meeting Room Booking

## 🏗️ System Overview

```
┌─────────────────────────────────────────────────────────┐
│                    WEB BROWSER                          │
│  (http://localhost:8000)                                │
└────────────────┬────────────────────────────────────────┘
                 │
                 │ HTTP Requests/Responses
                 │
┌─────────────────────────────────────────────────────────┐
│              LARAVEL APPLICATION                        │
│  ┌──────────────────────────────────────────────────┐  │
│  │         Routes (routes/web.php)                  │  │
│  │  GET  / → DashboardController@index              │  │
│  │  GET  /register → View                           │  │
│  │  POST /register → AuthController@register        │  │
│  │  GET  /login → View                              │  │
│  │  POST /login → AuthController@login              │  │
│  │  POST /logout → AuthController@logout            │  │
│  │  GET  /bookings → BookingController@index        │  │
│  │  POST /bookings → BookingController@store        │  │
│  │  GET  /bookings/create → BookingController@create│  │
│  │  PUT  /bookings/{id} → BookingController@update  │  │
│  │  DEL  /bookings/{id} → BookingController@destroy │  │
│  └──────────────────────────────────────────────────┘  │
│                       │                                  │
│  ┌────────────────────┴──────────────────────────────┐  │
│  │                                                   │  │
│  │  ┌──────────────────────────────────────────┐   │  │
│  │  │      Controllers                        │   │  │
│  │  ├──────────────────────────────────────────┤   │  │
│  │  │ • DashboardController                   │   │  │
│  │  │   - getCurrentStatus()                  │   │  │
│  │  │   - getActiveBooking()                  │   │  │
│  │  │                                         │   │  │
│  │  │ • BookingController                    │   │  │
│  │  │   - Validation (24h, conflict)         │   │  │
│  │  │   - CRUD operations                    │   │  │
│  │  │   - Authorization check                │   │  │
│  │  └──────────────────────────────────────────┘   │  │
│  │                      │                           │  │
│  │  ┌────────────────────┴──────────────────────┐   │  │
│  │  │                                          │   │  │
│  │  │      Models (Eloquent ORM)               │   │  │
│  │  │  ┌──────────────────────────────────┐   │   │  │
│  │  │  │ • Room                          │   │   │  │
│  │  │  │   - id, floor, name, desc      │   │   │  │
│  │  │  │   - hasMany(Booking)           │   │   │  │
│  │  │  │   - getCurrentStatus()         │   │   │  │
│  │  │  │   - getActiveBooking()         │   │   │  │
│  │  │  │                                 │   │   │  │
│  │  │  │ • Booking                      │   │   │  │
│  │  │  │   - id, user_id, room_id      │   │   │  │
│  │  │  │   - start_time, end_time      │   │   │  │
│  │  │  │   - title, description        │   │   │  │
│  │  │  │   - belongsTo(User, Room)     │   │   │  │
│  │  │  │   - isActive()                │   │   │  │
│  │  │  │   - isEnded()                 │   │   │  │
│  │  │  │                                 │   │   │  │
│  │  │  │ • User (Updated)               │   │   │  │
│  │  │  │   - name, email, password     │   │   │  │
│  │  │  │   - role                      │   │   │  │
│  │  │  │   - hasMany(Booking)          │   │   │  │
│  │  │  └──────────────────────────────┘   │   │  │
│  │  └──────────────────────────────────────┘   │  │
│  └──────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
                       │
                       │ SQL Queries
                       │
┌─────────────────────────────────────────────────────────┐
│           MySQL DATABASE (register_db)                  │
│                                                         │
│  ┌──────────────────────────────────────────────────┐  │
│  │ rooms                                            │  │
│  ├──────────────────────────────────────────────────┤  │
│  │ id (PK) │ floor │ name │ description │ timestamps│  │
│  ├─────────┼───────┼──────┼─────────────┼──────────┤  │
│  │ 1       │ 1     │ Ruang Rapat A  │ ... │ ...  │  │
│  │ 2       │ 2     │ Ruang Rapat B  │ ... │ ...  │  │
│  │ 3       │ 3     │ Ruang Rapat C  │ ... │ ...  │  │
│  │ 4       │ 4     │ Ruang Rapat D  │ ... │ ...  │  │
│  └──────────────────────────────────────────────────┘  │
│                                                         │
│  ┌──────────────────────────────────────────────────┐  │
│  │ bookings                                         │  │
│  ├──────────────────────────────────────────────────┤  │
│  │ id │user_id│room_id│start_time│end_time│...│ts│  │
│  ├────┼───────┼───────┼──────────┼────────┼───┼──┤  │
│  │ 1  │ 1     │ 1     │2026-... │2026-... │...*  │  │
│  │ 2  │ 1     │ 2     │2026-... │2026-... │... │  │
│  │... │...    │...    │...      │...      │... │  │
│  └──────────────────────────────────────────────────┘  │
│                                                         │
│  ┌──────────────────────────────────────────────────┐  │
│  │ users                                            │  │
│  ├──────────────────────────────────────────────────┤  │
│  │ id │ name │ email │ password │ role │ ... │ ts│  │
│  ├────┼──────┼───────┼──────────┼──────┼─────┼──┤  │
│  │ 1  │Test..│test@..│ (hash)   │user  │...  │..│  │
│  │... │...   │...    │...       │...   │...  │..│  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

## 🔄 Data Flow - Create Booking

```
User clicks "Buat Booking"
         │
         ↓
GET /bookings/create
         │
         ↓
BookingController@create
         │
         ├─ Room::all()
         │
         ↓
Display create_booking.blade.php with room list
         │
         ↓
User fills form & clicks "Buat Booking"
         │
         ↓
POST /bookings (with data)
         │
         ↓
BookingController@store
         │
         ├─ Validate input
         │  ├─ Room exists
         │  ├─ Start time > now (for create)
         │  ├─ End time > start time
         │  └─ Duration ≤ 24 hours
         │
         ├─ Check conflicts
         │  └─ Booking::where('room_id', $roomId)
         │            ->where(conflict conditions)
         │            ->exists()
         │
         ├─ If valid & no conflict
         │  └─ Booking::create($data)
         │
         ↓
Redirect to /bookings with success message
```

## 🎨 Status Update Flow

```
Dashboard loaded (GET /)
         │
         ↓
DashboardController@index
         │
         ├─ Room::all()
         │
         ├─ For each room:
         │  ├─ $room->getCurrentStatus()
         │  │  └─ Check: start_time ≤ NOW < end_time
         │  │     ├─ If true → 'booked'
         │  │     └─ If false → 'available'
         │  │
         │  └─ $room->getActiveBooking()
         │     └─ Return current booking if active
         │
         ↓
Render index.blade.php with rooms & status
         │
         ↓
Browser displays:
  🟢 HIJAU if available
  🔴 MERAH if booked
         │
         ├─ JavaScript setTimeout
         │  └─ Every 30 seconds
         │     └─ location.reload()
         │
         ↓
Status updates automatically
```

## 📋 Booking Status Lifecycle

```
CREATED
   │
   ├─ start_time > now ────────────────┐
   │                                   │
   │                            🟦 MENDATANG
   │                                   │
   └─ Waiting for start_time to reach...
                                       │
                                       ↓
                              start_time ≤ now
                              AND
                              now < end_time
                                       │
                                       ↓
                                 🔴 SEDANG BERLANGSUNG
                                       │
                                       ↓
                              now ≥ end_time
                                       │
                                       ↓
                                   ⚪ SELESAI
                                       │
                                    DELETED (optional)
```

## 🔐 Authorization Rules

```
┌─────────────────────────────────────────────────────┐
│          Authentication & Authorization             │
├─────────────────────────────────────────────────────┤
│                                                     │
│ PUBLIC Routes (No login required):                 │
│ ├─ GET  /                                          │
│ ├─ GET  /register                                  │
│ ├─ POST /register                                  │
│ ├─ GET  /login                                     │
│ └─ POST /login                                     │
│                                                     │
│ PROTECTED Routes (Login required):                 │
│ ├─ GET  /bookings                                  │
│ ├─ GET  /bookings/create                           │
│ ├─ POST /bookings                                  │
│ ├─ GET  /bookings/{id}/edit                        │
│ ├─ PUT  /bookings/{id}                             │
│ ├─ DEL  /bookings/{id}                             │
│ └─ POST /logout                                    │
│                                                     │
│ OWNERSHIP Check (Only own booking):               │
│ ├─ Edit: if (booking.user_id !== auth.user.id)   │
│ │        abort(403)                               │
│ │                                                  │
│ └─ Delete: if (booking.user_id !== auth.user.id)  │
│          abort(403)                               │
│                                                     │
└─────────────────────────────────────────────────────┘
```

## ⏱️ Time Handling

```
┌──────────────────────────────────────────────────┐
│         Time Zone Configuration                  │
├──────────────────────────────────────────────────┤
│                                                  │
│ .env:                                            │
│   APP_TIMEZONE=UTC (default)                    │
│   Ubah ke: APP_TIMEZONE=Asia/Jakarta             │
│                                                  │
│ Database:                                        │
│   start_time DATETIME                            │
│   end_time DATETIME                              │
│   Stored in UTC, converted by Laravel            │
│                                                  │
│ Comparison:                                      │
│   now() returns Carbon instance                  │
│   Automatically uses APP_TIMEZONE                │
│   start_time ≤ now < end_time → 'booked'        │
│                                                  │
└──────────────────────────────────────────────────┘
```

## 🚀 Performance Considerations

```
┌────────────────────────────────────────────────────┐
│         Query Optimization                        │
├────────────────────────────────────────────────────┤
│                                                    │
│ Dashboard (GET /):                                │
│   ✅ Room::all() → 4 queries (small table)        │
│   ✅ No N+1 problem (only 1 query per request)   │
│                                                    │
│ Bookings (GET /bookings):                        │
│   ✅ with('room') → Eager loading                │
│   ✅ 1 query for bookings + 1 for rooms         │
│                                                    │
│ Booking Detail:                                   │
│   ✅ Single booking by ID                        │
│   ✅ with('user', 'room')                       │
│                                                    │
│ Conflict Check (POST /bookings):                 │
│   ⚠️  Linear search (OK for <100 bookings)      │
│   Optimize: Add WHERE room_id filter first       │
│                                                    │
│ Auto-refresh:                                     │
│   ⚠️  Every 30 seconds full page reload         │
│   Optimize: Use AJAX for status only            │
│                                                    │
└────────────────────────────────────────────────────┘
```

## 🎯 Key Validations

```
┌────────────────────────────────────────────────────┐
│      Input Validation Pipeline                    │
├────────────────────────────────────────────────────┤
│                                                    │
│ 1. Form Validation (Controller)                   │
│    ├─ room_id: required, exists:rooms            │
│    ├─ start_time: required, date, after:now      │
│    ├─ end_time: required, date, after:start_time │
│    ├─ title: optional, string, max:255           │
│    └─ description: optional, string              │
│                                                    │
│ 2. Business Logic (Controller)                    │
│    ├─ Duration check                             │
│    │  └─ (end_time - start_time) ≤ 86400 sec    │
│    │                                              │
│    └─ Conflict check                             │
│       └─ No booking overlap on same room         │
│          with (start ≤ new_start < end) OR      │
│              (start < new_end ≤ end) OR         │
│              (new_start ≤ start AND end ≤ new_end)│
│                                                    │
│ 3. Model Validation (Optional)                    │
│    ├─ Custom rules in Model                      │
│    └─ E.g., beforeSave() hooks                   │
│                                                    │
└────────────────────────────────────────────────────┘
```

## 📊 Database Relationships

```
┌────────────┐         ┌───────────┐         ┌──────────┐
│   Users    │         │ Bookings  │         │  Rooms   │
├────────────┤         ├───────────┤         ├──────────┤
│ id (PK)    │◄────┬───│ user_id   │         │ id (PK)  │
│ name       │     │   │ room_id   ├────┬───│ floor    │
│ email      │     │   │ start_time│    │   │ name     │
│ password   │     │   │ end_time  │    │   │ desc     │
│ role       │     │   │ title     │    │   │ ...      │
│ ...        │     │   │ ...       │    │   │ ...      │
└────────────┘     │   └───────────┘    │   └──────────┘
                   │                    │
                   └────┬──────────────┬─┘
                        │              │
                     1:N              N:1
                   (User has        (Many
                    many books)    bookings
                                 per room)

Relationships:
• User hasMany Booking
• Booking belongsTo User
• Booking belongsTo Room
• Room hasMany Booking
```

---

## 🔧 Configuration Summary

```
.env
├─ APP_NAME=Laravel
├─ APP_TIMEZONE=UTC (ubah sesuai kebutuhan)
├─ DB_CONNECTION=mysql
├─ DB_HOST=127.0.0.1
├─ DB_PORT=3306
├─ DB_DATABASE=register_db
├─ DB_USERNAME=root
└─ DB_PASSWORD=(kosong)

config/
├─ app.php (timezone setting)
├─ database.php (connection)
└─ session.php (session driver)
```
