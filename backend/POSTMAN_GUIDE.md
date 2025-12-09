# Postman Collection Guide

## Import the Collection

1. Open Postman
2. Click **Import** button (top left)
3. Select the file: `TalinoHub_Postman_Collection.json`
4. Collection will appear in your Collections sidebar

## Collection Variables

The collection uses two variables that you can configure:

### 1. Base URL (`base_url`)
Default: `http://localhost:8000/api`

**To change:**
- Click on the collection name
- Go to **Variables** tab
- Update `base_url` value

### 2. Auth Token (`auth_token`)
Default: Empty

**Auto-populated when you:**
- Register a new user
- Login with existing credentials

The collection automatically saves the token after successful login/register!

## Quick Start

### Step 1: Start Your Laravel Server
```bash
cd backend
php artisan serve
```

### Step 2: Login or Register

**Option A - Use Seeded Data:**
1. Open `Authentication` → `Login`
2. Use credentials: `john.smith@example.com` / `password123`
3. Click **Send**
4. Token is automatically saved!

**Option B - Register New User:**
1. Open `Authentication` → `Register`
2. Fill in user details
3. Click **Send**
4. Token is automatically saved!

### Step 3: Test Endpoints

All authenticated endpoints will now work because the token is automatically included!

## Collection Structure

```
📁 TalinoHub API
├── 📁 Authentication (3 endpoints)
│   ├── Register
│   ├── Login
│   └── Logout
│
├── 📁 Courses
│   ├── 📁 Public (6 endpoints)
│   │   ├── Get Published Courses
│   │   ├── Get Course by ID
│   │   ├── Get Course by Slug
│   │   ├── Get Courses by Category
│   │   ├── Get Courses by Level
│   │   └── Get Courses by Educator
│   │
│   └── 📁 Authenticated (6 endpoints)
│       ├── Get My Courses
│       ├── Create Course
│       ├── Update Course
│       ├── Delete Course
│       ├── Publish Course
│       └── Unpublish Course
│
├── 📁 Lessons
│   ├── 📁 Public (3 endpoints)
│   │   ├── Get Lessons by Course
│   │   ├── Get Preview Lessons
│   │   └── Get Lesson by ID
│   │
│   └── 📁 Authenticated (6 endpoints)
│       ├── Get All My Lessons
│       ├── Get Enrolled Course Lessons
│       ├── Create Lesson
│       ├── Update Lesson
│       ├── Delete Lesson
│       └── Reorder Lessons
│
├── 📁 Enrollments (5 endpoints)
│   ├── Enroll in Course
│   ├── Unenroll from Course
│   ├── Get My Enrollments
│   ├── Get Course Students
│   └── Update Course Progress
│
├── 📁 Favorites (3 endpoints)
│   ├── Add to Favorites
│   ├── Remove from Favorites
│   └── Get My Favorites
│
├── 📁 Ratings
│   ├── 📁 Public (1 endpoint)
│   │   └── Get Course Ratings
│   │
│   └── 📁 Authenticated (4 endpoints)
│       ├── Rate Course
│       ├── Update Rating
│       ├── Delete Rating
│       └── Get My Rating
│
└── 📁 Comments
    ├── 📁 Public (1 endpoint)
    │   └── Get Course Comments
    │
    └── 📁 Authenticated (7 endpoints)
        ├── Post Comment
        ├── Post Reply
        ├── Update Comment
        ├── Delete Comment
        ├── Get My Comments
        ├── Like Comment
        └── Unlike Comment
```

**Total: 50 endpoints**

## Testing Workflows

### Workflow 1: Create & Manage Course

1. **Login as Educator:**
   - Authentication → Login
   - Use: `sarah.johnson@example.com` / `password123`

2. **Create Course:**
   - Courses → Authenticated → Create Course
   - Modify request body as needed

3. **Add Lessons:**
   - Lessons → Authenticated → Create Lesson
   - Set course_id to your new course
   - Create multiple lessons

4. **Publish Course:**
   - Courses → Authenticated → Publish Course
   - Use your course ID

### Workflow 2: Student Enrollment

1. **Login as Student:**
   - Authentication → Login
   - Use: `john.smith@example.com` / `password123`

2. **Browse Courses:**
   - Courses → Public → Get Published Courses

3. **View Course Details:**
   - Courses → Public → Get Course by ID
   - Use course ID from previous step

4. **Enroll:**
   - Enrollments → Enroll in Course
   - Set course_id in request body

5. **Track Progress:**
   - Enrollments → Update Course Progress
   - Set progress value (0-100)

### Workflow 3: Interact with Course

1. **Add to Favorites:**
   - Favorites → Add to Favorites
   - Use course ID in URL

2. **Rate Course:**
   - Ratings → Authenticated → Rate Course
   - Set rating (1-5) and review

3. **Post Comment:**
   - Comments → Authenticated → Post Comment
   - Write your comment

4. **Like Comment:**
   - Comments → Authenticated → Like Comment
   - Use comment ID from response

## Sample Data IDs

After running `php artisan db:seed`, you'll have:

**Educators:**
- ID 1: Dr. Sarah Johnson
- ID 2: Prof. Michael Chen
- ID 3: Emma Martinez

**Students:**
- ID 4-11: Various students

**Courses:**
- ID 1: Complete Web Development Bootcamp (Paid - $99.99)
- ID 2: Python Programming for Beginners (Free)
- ID 3: Advanced React & Redux Masterclass (Paid - $79.99)
- ID 4: Digital Marketing Fundamentals (Free)
- ID 5: Machine Learning A-Z (Paid - $129.99)

## Tips

### Auto Token Management
The collection automatically handles tokens:
- Register/Login saves token to `{{auth_token}}`
- All authenticated requests use this token
- No manual copying needed!

### Request Examples
All requests include example bodies with realistic data. Simply:
1. Select the request
2. Review the body (if POST/PUT)
3. Click **Send**

### Testing Different Users
To test as different users:
1. Logout (optional)
2. Login with different credentials
3. Token automatically updates

### Environment Variables
You can create Postman environments for:
- Local: `http://localhost:8000/api`
- Staging: `https://staging.talinohub.com/api`
- Production: `https://api.talinohub.com/api`

## Common Issues

### 401 Unauthorized
- Token expired or invalid
- Re-login to get new token

### 404 Not Found
- Check if ID exists in database
- Verify base_url is correct

### 422 Validation Error
- Check request body format
- Required fields might be missing

## Response Examples

### Successful Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description"
}
```

### Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field": ["Error message"]
  }
}
```

## Need Help?

- Check `API_DOCUMENTATION.md` for detailed endpoint documentation
- Review Laravel logs: `storage/logs/laravel.log`
- Verify database data: `php artisan tinker`

---

Happy Testing! 🚀
