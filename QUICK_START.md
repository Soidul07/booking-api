# Quick Start Guide - Laravel Booking API

## Setup Complete! ✅

Your Laravel Booking API with RBAC is now ready to use.

## Quick Test Commands

### 1. Start the Server
```bash
php artisan serve
```

### 2. Test Login (Super Admin)
```bash
curl -X POST http://localhost:8000/api/login ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"super@admin.com\",\"password\":\"password123\"}"
```

**Save the token from the response!**

### 3. Get Current User Info
```bash
curl -X GET http://localhost:8000/api/me ^
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 4. List Organizations
```bash
curl -X GET http://localhost:8000/api/organizations ^
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 5. Create a Booking
```bash
curl -X POST http://localhost:8000/api/bookings ^
  -H "Authorization: Bearer YOUR_TOKEN_HERE" ^
  -H "Content-Type: application/json" ^
  -d "{\"title\":\"Server Maintenance\",\"description\":\"Monthly maintenance\",\"team_id\":1}"
```

### 6. Assign Booking (NEW → ASSIGNED)
```bash
curl -X POST http://localhost:8000/api/bookings/1/assign ^
  -H "Authorization: Bearer YOUR_TOKEN_HERE" ^
  -H "Content-Type: application/json" ^
  -d "{\"user_id\":4}"
```

### 7. Start Booking (ASSIGNED → IN_PROGRESS)
```bash
curl -X POST http://localhost:8000/api/bookings/1/start ^
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 8. Complete Booking (IN_PROGRESS → COMPLETED)
```bash
curl -X POST http://localhost:8000/api/bookings/1/complete ^
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Test Different User Roles

### Login as Org Admin
```bash
curl -X POST http://localhost:8000/api/login ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"admin1@acme.com\",\"password\":\"password123\"}"
```

### Login as Team Member
```bash
curl -X POST http://localhost:8000/api/login ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"member1@acme.com\",\"password\":\"password123\"}"
```

## Seeded Data Summary

### Users Created:
1. **Super Admin** - super@admin.com (Full access)
2. **Org Admin 1** - admin1@acme.com (Acme Corporation)
3. **Org Admin 2** - admin2@techsolutions.com (Tech Solutions Inc)
4. **Team Member 1** - member1@acme.com (Development Team)
5. **Team Member 2** - member2@acme.com (Development & Support Teams)
6. **Team Member 3** - member3@techsolutions.com (Sales Team)

### Organizations:
1. Acme Corporation (ID: 1)
   - Development Team (ID: 1)
   - Support Team (ID: 2)
2. Tech Solutions Inc (ID: 2)
   - Sales Team (ID: 3)

### Roles:
1. super_admin - Full system access
2. org_admin - Organization management
3. org_team - Team member access

All passwords: **password123**

## Import Postman Collection

Import the file: `Booking_API.postman_collection.json`

1. Open Postman
2. Click Import
3. Select the JSON file
4. Create environment with:
   - base_url: http://localhost:8000
   - token: (auto-set after login)

## Full Documentation

See `API_DOCUMENTATION.md` for complete API reference.

## Status Transition Flow

```
NEW (Created)
  ↓ assign
ASSIGNED (User assigned)
  ↓ start
IN_PROGRESS (Work started)
  ↓ complete
COMPLETED (Finished)

Cancel available from: NEW, ASSIGNED, IN_PROGRESS
```

## Authorization Matrix

| Action | Super Admin | Org Admin | Org Team |
|--------|-------------|-----------|----------|
| Create Organization | ✅ | ❌ | ❌ |
| Manage Own Org | ✅ | ✅ | ❌ |
| Create Team | ✅ | ✅ | ❌ |
| Add/Remove Members | ✅ | ✅ | ❌ |
| Create Booking | ✅ | ✅ | ✅ |
| Assign Booking | ✅ | ✅ | ❌ |
| Start Own Booking | ✅ | ✅ | ✅ |
| Complete Own Booking | ✅ | ✅ | ✅ |
| View All Orgs | ✅ | ❌ | ❌ |

## Next Steps

1. Test the API endpoints using curl or Postman
2. Try different user roles to see authorization in action
3. Test booking status transitions
4. Explore organization scoping

Enjoy your Laravel Booking API! 🚀
