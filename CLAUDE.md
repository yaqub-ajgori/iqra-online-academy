# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Iqra Online Academy is an Islamic Learning Management System (LMS) built with Laravel, Vue.js, and Filament Admin Panel. It provides online courses for Islamic education including Quran, Hadith, Fiqh, and other Islamic studies. The platform supports course enrollment, payment processing, progress tracking, and donation management.

## Key Technologies

- **Backend**: Laravel 12 with PHP 8.2+
- **Frontend**: Vue.js 3 + TypeScript + Inertia.js
- **Admin Panel**: Filament v4 (beta)
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Styling**: Tailwind CSS 4
- **Build Tools**: Vite 6
- **Testing**: Pest PHP

## Development Commands

### Starting Development
```bash
# Start all development services (Laravel server + queue + Vite)
composer dev

# Start with SSR support
composer dev:ssr

# Frontend development only
npm run dev

# Build for production
npm run build
npm run build:ssr
```

### Testing & Quality
```bash
# Run PHP tests
composer test
vendor/bin/pest

# Run single test file
vendor/bin/pest tests/Feature/DashboardTest.php

# Frontend linting and formatting
npm run lint
npm run format
npm run format:check
```

### Database Management
```bash
# Run migrations
php artisan migrate

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Specific seeder
php artisan db:seed --class=CourseSeeder
```

## Application Architecture

### Core Models & Relationships

**User Management:**
- `User` - Base user model
- `Student` - Extends User for learners
- `Teacher` - Instructors/content creators
- `UserRole` - Role-based permissions

**Course System:**
- `Course` - Main course entity with pricing, status, thumbnails
- `CourseCategory` - Course categorization
- `CourseModule` - Course sections/chapters
- `CourseLesson` - Individual lessons within modules
- `CourseEnrollment` - Student-course relationships
- `LessonProgress` - Tracks student progress per lesson

**Financial:**
- `Payment` - Payment transactions for courses
- `Donation` - Donation records

**Blog System:**
- `BlogPost` - Blog articles with SEO metadata, view tracking
- `BlogCategory` - Hierarchical blog categories
- `BlogTag` - Tags for blog posts
- `BlogComment` - Nested comments with moderation
- `BlogReaction` - User reactions/likes on posts

### Key Relationships
- Course → Teacher (instructor_id)
- Course → CourseCategory (category_id)
- Course → CourseModule → CourseLesson (hierarchical content)
- Student ↔ Course (many-to-many via CourseEnrollment)
- Student → LessonProgress → Lesson (progress tracking)
- BlogPost → BlogCategory, User (author)
- BlogPost ↔ BlogTag (many-to-many)
- BlogComment → BlogPost, User (hierarchical comments)
- BlogReaction → BlogPost, User/Session (guest support)

### Frontend Architecture

**Pages Structure:**
- `Frontend/` - Public-facing pages (Vue components)
- `Frontend/Blog/` - Blog pages (Index, Show, Category, Tag)
- `auth/` - Authentication pages
- `Errors/` - Error handling pages

**Key Frontend Components:**
- `FrontendLayout.vue` - Main layout wrapper
- `CourseCard.vue` - Course display component
- `CourseProgressBar.vue` - Progress visualization
- `PrimaryButton.vue` - Consistent button styling
- `BlogCard.vue` - Blog post display component
- `BlogComments.vue` - Comment section component
- `BlogSidebar.vue` - Blog sidebar with categories/tags
- UI components in `components/ui/` (shadcn-vue style)

**Routing:**
- `routes/frontend.php` - Public routes (courses, payment, learning)
- `routes/auth.php` - Authentication routes
- `routes/web.php` - Main web routes
- `routes/blog.php` - Blog routes (posts, categories, tags, comments)

### Admin Panel (Filament)

**Resource Structure:**
```
app/Filament/Resources/
├── Courses/CourseResource.php
├── Students/StudentResource.php  
├── Teachers/TeacherResource.php
├── Payments/PaymentResource.php
├── Donations/DonationResource.php
├── BlogPosts/BlogPostResource.php
├── BlogCategories/BlogCategoryResource.php
└── BlogTags/BlogTagResource.php
```

Each resource includes:
- `Pages/` - CRUD pages (List, Create, Edit)
- `Schemas/` - Form definitions
- `Tables/` - Table configurations
- `RelationManagers/` - Relationship management

### Database Schema Highlights

**Courses Table:**
- Hierarchical content: Course → Module → Lesson
- Pricing with discount support
- Status workflow: draft → review → published → archived
- Full-text search on title and description
- Multiple indexes for performance

**Enrollment System:**
- Tracks enrollment date, progress percentage, completion status
- Links students to courses with pivot data

**Blog System:**
- Full-featured blog with categories, tags, and comments
- SEO metadata (title, description, keywords)
- View tracking and reading time calculation
- Hierarchical categories and tag cloud
- Comment moderation with spam detection
- Guest reactions using session tracking

## Configuration Notes

### Environment Setup
- Uses SQLite by default (`database/database.sqlite`)
- PWA-enabled via Vite plugin
- Email verification and password reset configured
- Queue system with database driver

### File Storage
- Course thumbnails stored in `public/storage`
- File uploads handled via Laravel's storage system

### Localization
- Primary language: Bengali (Bangla)
- Uses Bengali numerals in frontend
- Islamic-themed styling and terminology

## Common Development Patterns

### Adding New Course Content
1. Create migration for new content type
2. Add model with proper relationships to Course/Module
3. Create Filament resource for admin management
4. Add frontend components for display
5. Update progress tracking if needed

### Blog System Features
1. **Content Management**: Rich text editor, featured images, SEO metadata
2. **Categorization**: Hierarchical categories, tags for cross-referencing
3. **User Engagement**: Comments with moderation, like/helpful reactions
4. **SEO Optimization**: Meta tags, reading time, view tracking
5. **Admin Features**: Draft/publish workflow, featured posts, sticky posts

### Payment Integration
- Multiple payment methods: bKash, Nagad, Rocket
- Manual transaction ID verification workflow
- Donation system separate from course payments

### Testing Strategy
- Feature tests for authentication flows
- Course enrollment and progress testing
- Payment workflow testing
- Admin panel functionality testing

### Adding Blog Content
1. Create blog post via admin panel
2. Assign categories and tags
3. Set SEO metadata and featured image
4. Moderate comments through admin interface
5. Track engagement via view counts and reactions

## Important Implementation Details

- **Bangla Text Handling**: Proper UTF-8 support for Bengali content
- **Islamic Theming**: Color scheme using Islamic green/blue palette
- **Performance**: Database indexes on frequently queried columns
- **Security**: Proper middleware for student/admin access control
- **SEO**: Meta tags and structured data for course pages
- **Blog Features**: 
  - Automatic reading time calculation based on content length
  - Related posts algorithm using tags and categories
  - RSS feed support for blog posts
  - Sticky posts and featured posts functionality
  - Comment threading with nested replies
  - Spam detection for comments using keywords