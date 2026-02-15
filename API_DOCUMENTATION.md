# Laravel Booking API with RBAC

A complete Laravel 11 REST API with Sanctum authentication, Role-Based Access Control (RBAC), organization scoping, team management, and a booking module with strict status transitions.

## Features

- **Authentication**: Laravel Sanctum token-based authentication
- **RBAC**: Three roles - Super Admin, Org Admin, Org Team
- **Organization Scoping**: Multi-tenant architecture with organization isolation
- **Team Management**: Add/remove members, role/permission control
- **Booking Module**: Status transitions (NEW → ASSIGNED → IN_PROGRESS → COMPLETED) with cancel rules
- **Policies & Middleware**: Authorization using Laravel policies
- **Request Validation**: Form request validation for all inputs
- **API Resources**: Structured JSON responses

## Requirements

- PHP 8.2+
- MySQL 5.7+
- Composer

## Installation

1. **Clone the repository**
```bash
cd c:\xampp\htdocs\booking-api
```

2. **Install dependencies**
```bash
composer install
```

3. **Configure environment**
The `.env` file is already configured. Create the database:
```bash
# Create MySQL database
mysql -u root -e "CREATE DATABASE booking_api_db;"
```

4. **Run migrations and seeders**
```bash
php artisan migrate:fresh --seed
```

5. **Start the server**
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

## Seeded Credentials

### Super Admin
- **Email**: super@admin.com
- **Password**: password123
- **Access**: All organizations and resources

### Organization 1 (Acme Corporation)
- **Org Admin**
  - Email: admin1@acme.com
  - Password: password123
  
- **Team Members**
  - Email: member1@acme.com | Password: password123
  - Email: member2@acme.com | Password: password123

### Organization 2 (Tech Solutions Inc)
- **Org Admin**
  - Email: admin2@techsolutions.com
  - Password: password123
  
- **Team Member**
  - Email: member3@techsolutions.com | Password: password123

## API Endpoints

### Authentication

#### Register
```bash
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "organization_id": 1,
  "role_id": 3
}
```

#### Login
```bash
POST /api/login
Content-Type: application/json

{
  "email": "super@admin.com",
  "password": "password123"
}

Response:
{
  "user": {...},
  "token": "1|xxxxxxxxxxxxx"
}
```

#### Logout
```bash
POST /api/logout
Authorization: Bearer {token}
```

#### Get Current User
```bash
GET /api/me
Authorization: Bearer {token}
```

### Organizations

#### List Organizations
```bash
GET /api/organizations
Authorization: Bearer {token}
```

#### Create Organization (Super Admin only)
```bash
POST /api/organizations
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "New Organization"
}
```

#### Get Organization
```bash
GET /api/organizations/{id}
Authorization: Bearer {token}
```

#### Update Organization
```bash
PUT /api/organizations/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated Name"
}
```

#### Delete Organization (Super Admin only)
```bash
DELETE /api/organizations/{id}
Authorization: Bearer {token}
```

### Teams

#### Create Team
```bash
POST /api/organizations/{org_id}/teams
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "New Team"
}
```

#### Update Team
```bash
PUT /api/organizations/{org_id}/teams/{team_id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated Team Name"
}
```

#### Delete Team
```bash
DELETE /api/organizations/{org_id}/teams/{team_id}
Authorization: Bearer {token}
```

#### Add Team Member
```bash
POST /api/organizations/{org_id}/teams/{team_id}/members
Authorization: Bearer {token}
Content-Type: application/json

{
  "user_id": 4
}
```

#### Remove Team Member
```bash
DELETE /api/organizations/{org_id}/teams/{team_id}/members/{user_id}
Authorization: Bearer {token}
```

### Bookings

#### List Bookings
```bash
GET /api/bookings
Authorization: Bearer {token}
```

#### Create Booking
```bash
POST /api/bookings
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "New Booking",
  "description": "Booking description",
  "team_id": 1
}
```

#### Get Booking
```bash
GET /api/bookings/{id}
Authorization: Bearer {token}
```

#### Update Booking
```bash
PUT /api/bookings/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated Title",
  "description": "Updated description"
}
```

#### Delete Booking
```bash
DELETE /api/bookings/{id}
Authorization: Bearer {token}
```

#### Assign Booking (NEW → ASSIGNED)
```bash
POST /api/bookings/{id}/assign
Authorization: Bearer {token}
Content-Type: application/json

{
  "user_id": 4
}
```

#### Start Booking (ASSIGNED → IN_PROGRESS)
```bash
POST /api/bookings/{id}/start
Authorization: Bearer {token}
```

#### Complete Booking (IN_PROGRESS → COMPLETED)
```bash
POST /api/bookings/{id}/complete
Authorization: Bearer {token}
```

#### Cancel Booking
```bash
POST /api/bookings/{id}/cancel
Authorization: Bearer {token}
```

## Booking Status Transitions

```
NEW → ASSIGNED → IN_PROGRESS → COMPLETED
  ↓       ↓            ↓
  └───────┴────────────┴──→ CANCELLED
```

### Rules:
- **NEW**: Can be assigned or cancelled
- **ASSIGNED**: Can be started or cancelled
- **IN_PROGRESS**: Can be completed or cancelled
- **COMPLETED**: Cannot be changed
- **CANCELLED**: Cannot be changed

## Roles & Permissions

### Super Admin
- Full access to all organizations
- Can create/delete organizations
- Can manage all bookings and teams

### Org Admin
- Full access within their organization
- Can manage teams and members
- Can assign and manage bookings
- Cannot access other organizations

### Org Team
- Can view bookings in their organization
- Can create bookings
- Can start/complete assigned bookings
- Limited management capabilities

## Authorization Rules

### Organizations
- Super Admin: Full access
- Org Admin: Can manage their own organization
- Org Team: Read-only access to their organization

### Teams
- Super Admin: Full access
- Org Admin: Can manage teams in their organization
- Org Team: Cannot manage teams

### Bookings
- Super Admin: Full access
- Org Admin: Can manage all bookings in their organization
- Org Team: Can view organization bookings, manage their assigned bookings

## cURL Examples

### Login as Super Admin
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"super@admin.com\",\"password\":\"password123\"}"
```

### Create Booking
```bash
curl -X POST http://localhost:8000/api/bookings \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"title\":\"Fix Server Issue\",\"description\":\"Server is down\",\"team_id\":1}"
```

### Assign Booking
```bash
curl -X POST http://localhost:8000/api/bookings/1/assign \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"user_id\":4}"
```

### Start Booking
```bash
curl -X POST http://localhost:8000/api/bookings/1/start \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Complete Booking
```bash
curl -X POST http://localhost:8000/api/bookings/1/complete \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Postman Collection

Import the following into Postman:

1. Create a new environment with:
   - `base_url`: http://localhost:8000
   - `token`: (will be set after login)

2. Use the endpoints documented above

3. Set Authorization header: `Bearer {{token}}`

## Database Schema

### Tables
- `users` - User accounts with organization and role
- `roles` - Super Admin, Org Admin, Org Team
- `permissions` - Granular permissions
- `role_permission` - Many-to-many relationship
- `organizations` - Multi-tenant organizations
- `teams` - Teams within organizations
- `team_user` - Team membership
- `bookings` - Booking records with status tracking
- `personal_access_tokens` - Sanctum tokens

## Testing the API

### 1. Login as Super Admin
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"super@admin.com\",\"password\":\"password123\"}"
```
Save the token from response.

### 2. Create a Booking (as Org Admin)
```bash
# Login as org admin first
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin1@acme.com\",\"password\":\"password123\"}"

# Create booking
curl -X POST http://localhost:8000/api/bookings \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"title\":\"Server Maintenance\",\"description\":\"Monthly maintenance\",\"team_id\":1}"
```

### 3. Test Status Transitions
```bash
# Assign (NEW → ASSIGNED)
curl -X POST http://localhost:8000/api/bookings/1/assign \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"user_id\":4}"

# Start (ASSIGNED → IN_PROGRESS)
curl -X POST http://localhost:8000/api/bookings/1/start \
  -H "Authorization: Bearer YOUR_TOKEN"

# Complete (IN_PROGRESS → COMPLETED)
curl -X POST http://localhost:8000/api/bookings/1/complete \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Project Structure

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
│       ├── RoleResource.php
│       ├── PermissionResource.php
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

database/
├── migrations/
│   ├── 2024_01_01_000003_create_roles_table.php
│   ├── 2024_01_01_000004_create_permissions_table.php
│   ├── 2024_01_01_000005_create_role_permission_table.php
│   ├── 2024_01_01_000006_create_organizations_table.php
│   ├── 2024_01_01_000007_create_teams_table.php
│   ├── 2024_01_01_000008_add_organization_role_to_users_table.php
│   ├── 2024_01_01_000009_create_team_user_table.php
│   └── 2024_01_01_000010_create_bookings_table.php
└── seeders/
    └── DatabaseSeeder.php
```

## License

MIT License
