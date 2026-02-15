# Laravel Booking API - cURL Test Commands
# Copy and paste these commands to test the API

# ===========================================
# 1. LOGIN AS SUPER ADMIN
# ===========================================
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"super@admin.com\",\"password\":\"password123\"}"

# Save the token from response and replace TOKEN below

# ===========================================
# 2. GET CURRENT USER INFO
# ===========================================
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# 3. LIST ALL ORGANIZATIONS
# ===========================================
curl -X GET http://localhost:8000/api/organizations \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# 4. CREATE NEW ORGANIZATION (Super Admin only)
# ===========================================
curl -X POST http://localhost:8000/api/organizations \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"New Corporation\"}"

# ===========================================
# 5. GET ORGANIZATION DETAILS
# ===========================================
curl -X GET http://localhost:8000/api/organizations/1 \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# 6. CREATE TEAM
# ===========================================
curl -X POST http://localhost:8000/api/organizations/1/teams \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"QA Team\"}"

# ===========================================
# 7. ADD TEAM MEMBER
# ===========================================
curl -X POST http://localhost:8000/api/organizations/1/teams/1/members \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"user_id\":4}"

# ===========================================
# 8. CREATE BOOKING
# ===========================================
curl -X POST http://localhost:8000/api/bookings \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"title\":\"Server Maintenance\",\"description\":\"Monthly server maintenance\",\"team_id\":1}"

# ===========================================
# 9. LIST ALL BOOKINGS
# ===========================================
curl -X GET http://localhost:8000/api/bookings \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# 10. GET BOOKING DETAILS
# ===========================================
curl -X GET http://localhost:8000/api/bookings/1 \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# 11. ASSIGN BOOKING (NEW → ASSIGNED)
# ===========================================
curl -X POST http://localhost:8000/api/bookings/1/assign \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"user_id\":4}"

# ===========================================
# 12. START BOOKING (ASSIGNED → IN_PROGRESS)
# ===========================================
curl -X POST http://localhost:8000/api/bookings/1/start \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# 13. COMPLETE BOOKING (IN_PROGRESS → COMPLETED)
# ===========================================
curl -X POST http://localhost:8000/api/bookings/1/complete \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# 14. CANCEL BOOKING
# ===========================================
curl -X POST http://localhost:8000/api/bookings/1/cancel \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# 15. UPDATE BOOKING
# ===========================================
curl -X PUT http://localhost:8000/api/bookings/1 \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"title\":\"Updated Title\",\"description\":\"Updated description\"}"

# ===========================================
# 16. DELETE BOOKING
# ===========================================
curl -X DELETE http://localhost:8000/api/bookings/1 \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# 17. REMOVE TEAM MEMBER
# ===========================================
curl -X DELETE http://localhost:8000/api/organizations/1/teams/1/members/4 \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# 18. UPDATE TEAM
# ===========================================
curl -X PUT http://localhost:8000/api/organizations/1/teams/1 \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"Updated Team Name\"}"

# ===========================================
# 19. DELETE TEAM
# ===========================================
curl -X DELETE http://localhost:8000/api/organizations/1/teams/1 \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# 20. UPDATE ORGANIZATION
# ===========================================
curl -X PUT http://localhost:8000/api/organizations/1 \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"Updated Organization Name\"}"

# ===========================================
# 21. DELETE ORGANIZATION (Super Admin only)
# ===========================================
curl -X DELETE http://localhost:8000/api/organizations/1 \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# 22. LOGOUT
# ===========================================
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer TOKEN"

# ===========================================
# TEST WITH DIFFERENT ROLES
# ===========================================

# Login as Org Admin
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin1@acme.com\",\"password\":\"password123\"}"

# Login as Team Member
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"member1@acme.com\",\"password\":\"password123\"}"

# Login as Org Admin 2 (Different Organization)
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin2@techsolutions.com\",\"password\":\"password123\"}"

# ===========================================
# REGISTER NEW USER
# ===========================================
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"John Doe\",\"email\":\"john@example.com\",\"password\":\"password123\",\"organization_id\":1,\"role_id\":3}"
