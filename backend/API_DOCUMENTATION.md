# TalinoHub API Documentation

Complete API documentation for the TalinoHub learning management system backend.

## Table of Contents

- [Overview](#overview)
- [Technology Stack](#technology-stack)
- [Getting Started](#getting-started)
- [Architecture](#architecture)
- [Authentication](#authentication)
- [API Endpoints](#api-endpoints)
  - [Authentication](#authentication-endpoints)
  - [Courses](#course-endpoints)
  - [Lessons](#lesson-endpoints)
  - [Enrollments](#enrollment-endpoints)
  - [Favorites](#favorites-endpoints)
  - [Ratings](#rating-endpoints)
  - [Comments](#comment-endpoints)
- [Database Schema](#database-schema)
- [Error Handling](#error-handling)

---

## Overview

TalinoHub is a comprehensive learning management system API built with Laravel. It supports course creation, enrollments, ratings, comments, and more.

**Key Features:**
- User authentication with Laravel Sanctum
- Course management (free and paid courses)
- Lesson management with preview support
- Student enrollments with progress tracking
- Course favorites/bookmarks
- Course ratings and reviews
- Nested comments with likes
- Free course public access

---

## Technology Stack

- **Framework:** Laravel 11
- **Database:** MySQL/PostgreSQL
- **Authentication:** Laravel Sanctum (Token-based)
- **Architecture:** Repository Pattern + Service Layer
- **ORM:** Eloquent

---

## Getting Started

### Installation

```bash
# Clone the repository
cd backend

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=talinohub
DB_USERNAME=root
DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed

# Start the server
php artisan serve
```

### Sample Credentials

After seeding, you can use these credentials:

**Educators:**
- sarah.johnson@example.com / password123
- michael.chen@example.com / password123

**Students:**
- john.smith@example.com / password123
- alice.brown@example.com / password123

---

## Architecture

### Design Pattern

The application follows a layered architecture:

```
Controller → Service → Repository → Model
```

- **Controllers:** Handle HTTP requests and responses
- **Services:** Business logic and validation
- **Repositories:** Database operations
- **Models:** Eloquent ORM models

### Directory Structure

```
app/
├── Http/
│   ├── Controllers/API/
│   │   ├── CourseController.php
│   │   ├── LessonController.php
│   │   ├── EnrollmentController.php
│   │   ├── RatingController.php
│   │   └── CommentController.php
│   └── Requests/
│       ├── Course/
│       ├── Lesson/
│       ├── Enrollment/
│       ├── Rating/
│       └── Comment/
├── Models/
│   ├── Course.php
│   ├── Lesson.php
│   ├── CourseRating.php
│   └── CourseComment.php
├── Services/
│   ├── CourseService.php
│   ├── LessonService.php
│   ├── EnrollmentService.php
│   ├── RatingService.php
│   └── CommentService.php
├── Repositories/
│   ├── Contracts/
│   │   ├── CourseRepositoryInterface.php
│   │   ├── LessonRepositoryInterface.php
│   │   ├── EnrollmentRepositoryInterface.php
│   │   ├── RatingRepositoryInterface.php
│   │   └── CommentRepositoryInterface.php
│   ├── CourseRepository.php
│   ├── LessonRepository.php
│   ├── EnrollmentRepository.php
│   ├── RatingRepository.php
│   └── CommentRepository.php
└── DTO/
    ├── CourseDTO.php
    └── LessonDTO.php
```

---

## Authentication

TalinoHub uses Laravel Sanctum for API authentication.

### Headers Required

For authenticated endpoints, include:

```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

---

## API Endpoints

### Authentication Endpoints

#### Register User

```http
POST /api/auth/register
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "address": "123 Main St"
}
```

**Response:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "1|laravel_sanctum_token_here"
  }
}
```

#### Login

```http
POST /api/auth/login
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "1|laravel_sanctum_token_here"
  }
}
```

#### Logout

```http
POST /api/auth/logout
```

**Headers:** Requires authentication

**Response:**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

### Course Endpoints

#### Get All Published Courses (Public)

```http
GET /api/courses/published
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Complete Web Development Bootcamp 2025",
      "slug": "complete-web-development-bootcamp-2025",
      "short_description": "Learn HTML, CSS, JavaScript, React...",
      "is_free": false,
      "price": "99.99",
      "category": "Web Development",
      "level": "Beginner",
      "average_rating": "4.67",
      "ratings_count": 3,
      "educator": {
        "id": 1,
        "name": "Dr. Sarah Johnson"
      }
    }
  ]
}
```

#### Get Single Course (Public)

```http
GET /api/courses/{id}
GET /api/courses/{slug}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Complete Web Development Bootcamp 2025",
    "slug": "complete-web-development-bootcamp-2025",
    "description": "Master web development from scratch...",
    "is_free": false,
    "price": "99.99",
    "average_rating": "4.67",
    "ratings_count": 3,
    "educator": {
      "id": 1,
      "name": "Dr. Sarah Johnson"
    },
    "lessons": [
      {
        "id": 1,
        "title": "Introduction to Web Development",
        "is_preview": true
      }
    ]
  }
}
```

#### Create Course (Authenticated)

```http
POST /api/courses
```

**Headers:** Requires authentication

**Request Body:**
```json
{
  "title": "Introduction to Python",
  "short_description": "Learn Python basics",
  "description": "Complete guide to Python programming...",
  "is_free": true,
  "price": 0,
  "category": "Programming",
  "level": "Beginner",
  "language": "English"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Course created successfully",
  "data": {
    "id": 6,
    "title": "Introduction to Python",
    "slug": "introduction-to-python-abc123"
  }
}
```

#### Update Course (Authenticated)

```http
PUT /api/courses/{id}
```

**Headers:** Requires authentication

**Request Body:**
```json
{
  "title": "Updated Course Title",
  "price": 79.99
}
```

#### Delete Course (Authenticated)

```http
DELETE /api/courses/{id}
```

**Headers:** Requires authentication

#### Publish/Unpublish Course (Authenticated)

```http
POST /api/courses/{id}/publish
POST /api/courses/{id}/unpublish
```

**Headers:** Requires authentication

#### Get Courses by Category (Public)

```http
GET /api/courses/category/{category}
```

#### Get Courses by Level (Public)

```http
GET /api/courses/level/{level}
```

#### Get Courses by Educator (Public)

```http
GET /api/courses/educator/{userId}
```

---

### Lesson Endpoints

#### Get Lessons for Course (Public)

**Note:** For free courses, returns all lessons. For paid courses, returns only preview lessons.

```http
GET /api/lessons/course/{courseId}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Introduction to Web Development",
      "position": 1,
      "type": "video",
      "video_url": "lessons/video-1.mp4",
      "duration_seconds": 600,
      "is_preview": true
    }
  ]
}
```

#### Get Preview Lessons (Public)

```http
GET /api/lessons/course/{courseId}/preview
```

#### Get Single Lesson (Public)

```http
GET /api/lessons/{id}
GET /api/lessons/{slug}
```

#### Create Lesson (Authenticated)

```http
POST /api/lessons
```

**Headers:** Requires authentication

**Request Body:**
```json
{
  "course_id": 1,
  "title": "Advanced CSS Techniques",
  "type": "video",
  "video_url": "lessons/advanced-css.mp4",
  "duration_seconds": 1200,
  "is_preview": false
}
```

**Note:** `video_url` should be the path to a video file stored in your storage system.

#### Update Lesson (Authenticated)

```http
PUT /api/lessons/{id}
```

**Headers:** Requires authentication

#### Delete Lesson (Authenticated)

```http
DELETE /api/lessons/{id}
```

**Headers:** Requires authentication

#### Reorder Lessons (Authenticated)

```http
POST /api/lessons/course/{courseId}/reorder
```

**Request Body:**
```json
{
  "positions": {
    "1": 2,
    "2": 1,
    "3": 3
  }
}
```

#### Get All Lessons for Enrolled Course (Authenticated)

**Note:** Returns all lessons regardless of course price for enrolled users.

```http
GET /api/lessons/enrolled/course/{courseId}
```

**Headers:** Requires authentication

---

### Enrollment Endpoints

All enrollment endpoints require authentication.

#### Enroll in Course

```http
POST /api/enrollments/enroll
```

**Request Body:**
```json
{
  "course_id": 1
}
```

**Response:**
```json
{
  "success": true,
  "message": "Successfully enrolled in course",
  "data": {
    "id": 1,
    "title": "Complete Web Development Bootcamp 2025"
  }
}
```

**Business Rules:**
- Cannot enroll in unpublished courses
- Cannot enroll in your own course (as educator)
- Cannot enroll twice in the same course

#### Unenroll from Course

```http
DELETE /api/enrollments/courses/{courseId}
```

#### Get My Enrolled Courses

```http
GET /api/enrollments/my-courses
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Complete Web Development Bootcamp 2025",
      "pivot": {
        "enrolled_at": "2024-12-01T10:00:00Z",
        "progress": 75,
        "completed_at": null
      }
    }
  ]
}
```

#### Update Course Progress

```http
PUT /api/enrollments/courses/{courseId}/progress
```

**Request Body:**
```json
{
  "progress": 75
}
```

**Note:** Progress is automatically marked as completed when it reaches 100%.

#### Get Course Students (Authenticated - Educators)

```http
GET /api/enrollments/courses/{courseId}/students
```

---

### Favorites Endpoints

All favorites endpoints require authentication.

#### Add Course to Favorites

```http
POST /api/favorites/courses/{courseId}
```

**Response:**
```json
{
  "success": true,
  "message": "Course added to favorites"
}
```

#### Remove Course from Favorites

```http
DELETE /api/favorites/courses/{courseId}
```

#### Get My Favorite Courses

```http
GET /api/favorites/my-favorites
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Complete Web Development Bootcamp 2025",
      "educator": {
        "id": 1,
        "name": "Dr. Sarah Johnson"
      }
    }
  ]
}
```

---

### Rating Endpoints

#### Rate a Course (Authenticated)

```http
POST /api/ratings/courses/{courseId}
```

**Request Body:**
```json
{
  "rating": 5,
  "review": "Excellent course! Learned so much."
}
```

**Response:**
```json
{
  "success": true,
  "message": "Rating submitted successfully",
  "data": {
    "id": 1,
    "rating": 5,
    "review": "Excellent course! Learned so much."
  }
}
```

**Business Rules:**
- Rating must be between 1-5
- Cannot rate your own course
- Can update existing rating
- Auto-updates course average rating

#### Delete Rating (Authenticated)

```http
DELETE /api/ratings/courses/{courseId}
```

#### Get My Rating for Course (Authenticated)

```http
GET /api/ratings/courses/{courseId}/my-rating
```

#### Get All Ratings for Course (Public)

```http
GET /api/ratings/courses/{courseId}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "average_rating": "4.67",
    "ratings_count": 3,
    "ratings": [
      {
        "id": 1,
        "rating": 5,
        "review": "Amazing course!",
        "user": {
          "id": 2,
          "name": "John Smith"
        },
        "created_at": "2024-12-01T10:00:00Z"
      }
    ]
  }
}
```

---

### Comment Endpoints

#### Get Comments for Course (Public)

```http
GET /api/comments/courses/{courseId}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "comment": "Great course! Just finished the HTML section.",
      "likes_count": 4,
      "user": {
        "id": 2,
        "name": "John Smith"
      },
      "created_at": "2024-12-01T10:00:00Z",
      "replies": [
        {
          "id": 2,
          "comment": "Glad you're enjoying it!",
          "likes_count": 3,
          "user": {
            "id": 1,
            "name": "Dr. Sarah Johnson"
          },
          "replies": []
        }
      ]
    }
  ]
}
```

#### Post Comment (Authenticated)

```http
POST /api/comments/courses/{courseId}
```

**Request Body:**
```json
{
  "comment": "This is a great course!",
  "parent_id": null
}
```

**For Replies:**
```json
{
  "comment": "Thanks for the feedback!",
  "parent_id": 1
}
```

**Response:**
```json
{
  "success": true,
  "message": "Comment posted successfully",
  "data": {
    "id": 5,
    "comment": "This is a great course!",
    "user": {
      "id": 2,
      "name": "John Smith"
    }
  }
}
```

#### Update Comment (Authenticated)

```http
PUT /api/comments/{commentId}
```

**Request Body:**
```json
{
  "comment": "Updated comment text"
}
```

**Business Rule:** Only comment owner can update

#### Delete Comment (Authenticated)

```http
DELETE /api/comments/{commentId}
```

**Business Rule:** Only comment owner can delete. Deleting a comment also deletes all replies.

#### Get My Comments (Authenticated)

```http
GET /api/comments/my-comments
```

#### Like Comment (Authenticated)

```http
POST /api/comments/{commentId}/like
```

**Response:**
```json
{
  "success": true,
  "message": "Comment liked successfully",
  "data": {
    "likes_count": 5
  }
}
```

#### Unlike Comment (Authenticated)

```http
DELETE /api/comments/{commentId}/like
```

**Response:**
```json
{
  "success": true,
  "message": "Comment unliked successfully",
  "data": {
    "likes_count": 4
  }
}
```

---

## Database Schema

### Core Tables

#### users
- id
- name
- email (unique)
- password
- address
- email_verified_at
- timestamps

#### courses
- id
- user_id (educator)
- title
- slug (unique)
- short_description
- description
- is_free
- price
- thumbnail_path
- category
- level
- language
- average_rating
- ratings_count
- is_published
- published_at
- timestamps
- soft_deletes

#### lessons
- id
- course_id
- title
- slug (unique)
- position
- type
- video_url (storage path to video file)
- document_path
- duration_seconds
- is_preview
- timestamps
- soft_deletes

#### course_user (enrollments)
- id
- user_id
- course_id
- enrolled_at
- completed_at
- progress (0-100)
- timestamps
- unique(user_id, course_id)

#### course_favorites
- id
- user_id
- course_id
- favorited_at
- timestamps
- unique(user_id, course_id)

#### course_ratings
- id
- user_id
- course_id
- rating (1-5)
- review (text)
- timestamps
- unique(user_id, course_id)

#### course_comments
- id
- user_id
- course_id
- parent_id (nullable)
- comment
- likes_count
- timestamps
- soft_deletes

#### course_comment_likes
- id
- user_id
- course_comment_id
- liked_at
- timestamps
- unique(user_id, course_comment_id)

### Model Relationships

#### User
- `ownedCourses()` - hasMany Course
- `enrolledCourses()` - belongsToMany Course
- `favoriteCourses()` - belongsToMany Course
- `courseRatings()` - hasMany CourseRating
- `courseComments()` - hasMany CourseComment

#### Course
- `educator()` - belongsTo User
- `lessons()` - hasMany Lesson
- `students()` - belongsToMany User
- `favoritedBy()` - belongsToMany User
- `ratings()` - hasMany CourseRating
- `comments()` - hasMany CourseComment

#### Lesson
- `course()` - belongsTo Course

#### CourseComment
- `user()` - belongsTo User
- `course()` - belongsTo Course
- `parent()` - belongsTo CourseComment
- `replies()` - hasMany CourseComment
- `likes()` - belongsToMany User

---

## Error Handling

### Standard Error Response

```json
{
  "success": false,
  "message": "Error message here"
}
```

### Common HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request (validation errors, business rule violations)
- `401` - Unauthorized (authentication required)
- `403` - Forbidden (not authorized to perform action)
- `404` - Not Found
- `422` - Unprocessable Entity (validation errors)
- `500` - Internal Server Error

### Validation Errors

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ],
    "password": [
      "The password must be at least 8 characters."
    ]
  }
}
```

---

## Business Rules Summary

### Courses
- Only educators can create/update/delete their own courses
- Only published courses are visible to public
- Free courses (is_free=true) have all lessons accessible to everyone
- Paid courses only show preview lessons to non-enrolled users

### Enrollments
- Users cannot enroll in their own courses
- Users cannot enroll in unpublished courses
- Users cannot enroll twice in the same course
- Progress auto-marks course as completed at 100%

### Ratings
- Rating must be 1-5 stars
- Cannot rate your own course
- One rating per user per course (can update)
- Ratings auto-update course average

### Comments
- Comments can be nested (replies to replies)
- Only comment owner can edit/delete
- Deleting a comment deletes all its replies
- Comments can be liked by multiple users
- One like per user per comment

### Favorites
- Can favorite any published course
- One favorite per user per course

---

## Testing with Sample Data

After running `php artisan db:seed`, you'll have:
- 3 educators and 8 students
- 5 courses (2 free, 3 paid)
- Multiple lessons per course
- 7 enrollments with varying progress
- 12 ratings with reviews
- 10 comments with nested replies
- Comment likes
- Course favorites

Use the provided credentials to test different user perspectives (educator vs student).

---

## Additional Notes

### Free Course Access
Free courses (`is_free = true`) allow anyone to:
- View all lessons without enrolling
- Access full course content via public endpoints

### Enrolled User Access
Enrolled users can:
- Access all lessons regardless of course price
- Track their progress
- Comment on courses
- Rate courses

### Security
- All authenticated endpoints use Laravel Sanctum
- Passwords are hashed with bcrypt
- CSRF protection enabled
- SQL injection prevention via Eloquent ORM
- XSS protection via Laravel escaping

---

For questions or issues, please contact the development team.
