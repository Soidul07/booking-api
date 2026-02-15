# Laravel Booking API with RBAC

[![Laravel](https://img.shields.io/badge/Laravel-11-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Sanctum](https://img.shields.io/badge/Sanctum-Authentication-green.svg)](https://laravel.com/docs/sanctum)

A complete Laravel 11 REST API with Sanctum authentication, Role-Based Access Control (RBAC), organization scoping, team management, and a booking module with strict status transitions.

## 🚀 Features

- ✅ **Laravel Sanctum** - Token-based API authentication
- ✅ **RBAC** - Super Admin, Org Admin, Org Team roles
- ✅ **Organization Scoping** - Multi-tenant architecture with data isolation
- ✅ **Team Management** - Add/remove members with role control
- ✅ **Booking Module** - Complete CRUD with status transitions (NEW → ASSIGNED → IN_PROGRESS → COMPLETED)
- ✅ **Authorization Policies** - Laravel policies for resource access control
- ✅ **Request Validation** - Form request validation for all inputs
- ✅ **API Resources** - Structured JSON responses
- ✅ **Migrations & Seeders** - Complete database schema with test data

## 📋 Quick Start

### 1. Database Setup
```bash
# Create database
mysql -u root -e "CREATE DATABASE booking_api_db;"

# Run migrations and seed data
php artisan migrate:fresh --seed
```

### 2. Start Server
```bash
php artisan serve
```
API available at: `http://localhost:8000/api`

### 3. Test Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"super@admin.com\",\"password\":\"password123\"}"
```

## 🔐 Seeded Credentials

### Super Admin (Full Access)
- **Email**: super@admin.com
- **Password**: password123

### Org Admin - Acme Corporation
- **Email**: admin1@acme.com
- **Password**: password123

### Org Admin - Tech Solutions Inc
- **Email**: admin2@techsolutions.com
- **Password**: password123

### Team Members
- **Email**: member1@acme.com | **Password**: password123
- **Email**: member2@acme.com | **Password**: password123
- **Email**: member3@techsolutions.com | **Password**: password123

## 📚 Documentation

- **[SETUP_COMPLETE.md](SETUP_COMPLETE.md)** - Complete setup summary with all details
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - Full API reference with examples
- **[QUICK_START.md](QUICK_START.md)** - Quick start guide with curl commands
- **[curl-tests.sh](curl-tests.sh)** - Ready-to-use curl test commands
- **[Booking_API.postman_collection.json](Booking_API.postman_collection.json)** - Postman collection

## 🔗 API Endpoints

### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - Login and get token
- `POST /api/logout` - Logout
- `GET /api/me` - Get current user

### Organizations
- `GET /api/organizations` - List organizations
- `POST /api/organizations` - Create organization
- `GET /api/organizations/{id}` - Get organization
- `PUT /api/organizations/{id}` - Update organization
- `DELETE /api/organizations/{id}` - Delete organization

### Teams
- `POST /api/organizations/{org}/teams` - Create team
- `PUT /api/organizations/{org}/teams/{team}` - Update team
- `DELETE /api/organizations/{org}/teams/{team}` - Delete team
- `POST /api/organizations/{org}/teams/{team}/members` - Add member
- `DELETE /api/organizations/{org}/teams/{team}/members/{user}` - Remove member

### Bookings
- `GET /api/bookings` - List bookings
- `POST /api/bookings` - Create booking
- `GET /api/bookings/{id}` - Get booking
- `PUT /api/bookings/{id}` - Update booking
- `DELETE /api/bookings/{id}` - Delete booking
- `POST /api/bookings/{id}/assign` - Assign booking (NEW → ASSIGNED)
- `POST /api/bookings/{id}/start` - Start booking (ASSIGNED → IN_PROGRESS)
- `POST /api/bookings/{id}/complete` - Complete booking (IN_PROGRESS → COMPLETED)
- `POST /api/bookings/{id}/cancel` - Cancel booking

## 📊 Booking Status Flow

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

## 🎭 Roles & Permissions

| Feature | Super Admin | Org Admin | Org Team |
|---------|-------------|-----------|----------|
| Create Organization | ✅ | ❌ | ❌ |
| Manage Own Org | ✅ | ✅ | ❌ |
| Create Team | ✅ | ✅ | ❌ |
| Add/Remove Members | ✅ | ✅ | ❌ |
| Create Booking | ✅ | ✅ | ✅ |
| Assign Booking | ✅ | ✅ | ❌ |
| Start Own Booking | ✅ | ✅ | ✅ |
| Complete Own Booking | ✅ | ✅ | ✅ |
| View All Orgs | ✅ | ❌ | ❌ |

## 🗂️ Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── OrganizationController.php
│   │   ├── TeamController.php
│   │   └── BookingController.php
│   ├── Requests/
│   │   ├── RegisterRequest.php
│   │   ├── LoginRequest.php
│   │   ├── OrganizationRequest.php
│   │   ├── TeamRequest.php
│   │   └── BookingRequest.php
│   └── Resources/
│       ├── UserResource.php
│       ├── OrganizationResource.php
│       ├── TeamResource.php
│       └── BookingResource.php
├── Models/
│   ├── User.php
│   ├── Role.php
│   ├── Permission.php
│   ├── Organization.php
│   ├── Team.php
│   └── Booking.php
└── Policies/
    ├── OrganizationPolicy.php
    └── BookingPolicy.php
```

## 📦 Postman Collection

1. Import `Booking_API.postman_collection.json` into Postman
2. Create environment with:
   - `base_url`: http://localhost:8000
   - `token`: (auto-set after login)
3. Start testing!

## 🧪 Example Workflow

```bash
# 1. Login as Org Admin
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin1@acme.com\",\"password\":\"password123\"}"

# 2. Create Booking
curl -X POST http://localhost:8000/api/bookings \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"title\":\"Server Maintenance\",\"description\":\"Monthly maintenance\",\"team_id\":1}"

# 3. Assign Booking
curl -X POST http://localhost:8000/api/bookings/1/assign \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"user_id\":4}"

# 4. Start Booking
curl -X POST http://localhost:8000/api/bookings/1/start \
  -H "Authorization: Bearer YOUR_TOKEN"

# 5. Complete Booking
curl -X POST http://localhost:8000/api/bookings/1/complete \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 🔒 Security Features

- Password hashing with bcrypt
- Token-based authentication (Sanctum)
- Policy-based authorization
- Organization data isolation
- Request validation
- CSRF protection
- SQL injection prevention (Eloquent ORM)
- Mass assignment protection

## 📝 Database Schema

- **users** - User accounts with organization and role
- **roles** - super_admin, org_admin, org_team
- **permissions** - Granular permissions
- **permission_role** - Role-permission relationships
- **organizations** - Multi-tenant organizations
- **teams** - Teams within organizations
- **team_user** - Team membership
- **bookings** - Booking records with status tracking
- **personal_access_tokens** - Sanctum tokens

## 🎯 Testing Different Roles

### Super Admin
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"super@admin.com\",\"password\":\"password123\"}"
```

### Org Admin
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin1@acme.com\",\"password\":\"password123\"}"
```

### Team Member
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"member1@acme.com\",\"password\":\"password123\"}"
```

## 📖 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [API Resource Classes](https://laravel.com/docs/eloquent-resources)
- [Authorization Policies](https://laravel.com/docs/authorization)

## ✨ What's Included

✅ Complete Laravel 11 API setup  
✅ Sanctum authentication  
✅ RBAC with 3 roles  
✅ Organization multi-tenancy  
✅ Team management  
✅ Booking module with status transitions  
✅ Authorization policies  
✅ Request validation  
✅ API resources  
✅ Migrations & seeders  
✅ Postman collection  
✅ Comprehensive documentation  
✅ Test data pre-seeded  

## 🚀 Ready to Use!

Your API is fully configured and ready. Start the server and begin testing:

```bash
php artisan serve
```

Then visit: **http://localhost:8000/api**

For detailed documentation, see **[SETUP_COMPLETE.md](SETUP_COMPLETE.md)**

## 📄 License

MIT License
