# ✅ WORKING SETUP GUIDE - Laravel Booking API

## Step 1: Start the Server

Open Command Prompt in `c:\xampp\htdocs\booking-api` and run:

```bash
php artisan serve
```

**Keep this terminal open!** The server must be running.

## Step 2: Test with cURL (Windows CMD)

Open a **NEW** Command Prompt window and test:

```bash
curl -X POST http://127.0.0.1:8000/api/login -H "Content-Type: application/json" -H "Accept: application/json" -d "{\"email\":\"super@admin.com\",\"password\":\"password123\"}"
```

**Expected Response:**
```json
{
  "user": {
    "id": 1,
    "name": "Super Admin",
    "email": "super@admin.com",
    ...
  },
  "token": "1|xxxxxxxxxxxxxxxxxxxxxx"
}
```

## Step 3: Use Postman (Recommended)

### A. Import Collection
1. Open Postman
2. Click **Import** button
3. Select file: `Booking_API.postman_collection.json`

### B. Create Environment
1. Click **Environments** (left sidebar)
2. Click **+** to create new environment
3. Name it: `Booking API Local`
4. Add variables:
   - Variable: `base_url` | Initial Value: `http://127.0.0.1:8000` | Current Value: `http://127.0.0.1:8000`
   - Variable: `token` | Initial Value: (leave empty) | Current Value: (leave empty)
5. Click **Save**
6. Select this environment from dropdown (top right)

### C. Test Login
1. Go to **Collections** → **Laravel Booking API** → **Authentication** → **Login - Super Admin**
2. Click **Send**
3. The `token` variable will be automatically set!
4. Now all other requests will work automatically

## Step 4: Test All Endpoints

After logging in, try these in order:

1. **Get Current User** - `GET /api/me`
2. **List Organizations** - `GET /api/organizations`
3. **Create Booking** - `POST /api/bookings`
4. **Assign Booking** - `POST /api/bookings/1/assign`
5. **Start Booking** - `POST /api/bookings/1/start`
6. **Complete Booking** - `POST /api/bookings/1/complete`

## Troubleshooting

### Issue: "404 Not Found"
**Solution:** Make sure server is running with `php artisan serve`

### Issue: "Route [login] not defined"
**Solution:** Already fixed! Just restart server:
```bash
# Press Ctrl+C to stop
php artisan serve
```

### Issue: "Unauthenticated"
**Solution:** Make sure you're using the token from login response:
```bash
curl -X GET http://127.0.0.1:8000/api/me -H "Authorization: Bearer YOUR_TOKEN_HERE" -H "Accept: application/json"
```

### Issue: Postman not working
**Solution:** 
1. Make sure environment is selected (top right dropdown)
2. Check `base_url` is set to `http://127.0.0.1:8000`
3. Login first to set the token
4. Add `Accept: application/json` header if needed

## Quick cURL Examples (Copy & Paste)

### 1. Login
```bash
curl -X POST http://127.0.0.1:8000/api/login -H "Content-Type: application/json" -H "Accept: application/json" -d "{\"email\":\"super@admin.com\",\"password\":\"password123\"}"
```

### 2. Get Current User (Replace TOKEN)
```bash
curl -X GET http://127.0.0.1:8000/api/me -H "Authorization: Bearer TOKEN" -H "Accept: application/json"
```

### 3. List Organizations (Replace TOKEN)
```bash
curl -X GET http://127.0.0.1:8000/api/organizations -H "Authorization: Bearer TOKEN" -H "Accept: application/json"
```

### 4. Create Booking (Replace TOKEN)
```bash
curl -X POST http://127.0.0.1:8000/api/bookings -H "Authorization: Bearer TOKEN" -H "Content-Type: application/json" -H "Accept: application/json" -d "{\"title\":\"Server Maintenance\",\"description\":\"Monthly maintenance\",\"team_id\":1}"
```

### 5. Assign Booking (Replace TOKEN)
```bash
curl -X POST http://127.0.0.1:8000/api/bookings/1/assign -H "Authorization: Bearer TOKEN" -H "Content-Type: application/json" -H "Accept: application/json" -d "{\"user_id\":4}"
```

## Test Credentials

| Role | Email | Password |
|------|-------|----------|
| Super Admin | super@admin.com | password123 |
| Org Admin | admin1@acme.com | password123 |
| Team Member | member1@acme.com | password123 |

## API is Working When...

✅ Login returns a token
✅ `/api/me` returns user info
✅ `/api/organizations` returns list of organizations
✅ You can create, assign, start, and complete bookings

## Need Help?

1. Make sure MySQL is running (XAMPP Control Panel)
2. Make sure database `booking_api_db` exists
3. Make sure server is running: `php artisan serve`
4. Check server terminal for errors
5. Use `Accept: application/json` header in all requests

## Success! 🎉

Your API is ready when you can:
- ✅ Login and get a token
- ✅ Access protected endpoints with the token
- ✅ Create and manage bookings
- ✅ Test different user roles

**The API is fully functional and ready to use!**
