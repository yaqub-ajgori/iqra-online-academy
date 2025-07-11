# Iqra Online Academy - Deployment Guide

## 📋 Pre-Deployment Checklist

### ✅ Completed
- [x] Security fixes implemented
- [x] XSS vulnerabilities patched
- [x] Input sanitization added
- [x] Rate limiting configured
- [x] File upload system working
- [x] Production assets built
- [x] Production .env.example created

## 🚀 Deployment Steps

### 1. Upload Files
Upload all project files to your web server, **EXCEPT**:
- `.env` (will be created on server)
- `node_modules/` (not needed on server)
- `storage/logs/` (will be generated)

### 2. Set Up Environment
1. Copy `.env.example` to `.env`
2. Update these values in `.env`:
   ```bash
   APP_NAME="Iqra Online Academy"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-actual-domain.com
   
   # Database (get from hosting provider)
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   
   # Email (get SMTP details from hosting provider)
   MAIL_HOST=your-smtp-host
   MAIL_USERNAME=your-email@your-domain.com
   MAIL_PASSWORD=your-email-password
   MAIL_FROM_ADDRESS="your-email@your-domain.com"
   ```

### 3. Run Laravel Commands (via hosting panel or SSH)
```bash
# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate --force

# Create storage symlink
php artisan storage:link

# Seed initial data (admin user, blog content)
php artisan db:seed

# Cache configuration (for better performance)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Set Permissions
Ensure these directories are writable:
- `storage/`
- `bootstrap/cache/`

### 5. Admin Access
After deployment, access admin panel at:
`https://your-domain.com/admin`

Default admin credentials (change after first login):
- Email: admin@example.com
- Password: password

## 🔧 Important Notes

### File Storage
- Blog images will be stored in `storage/app/public/blog-images/`
- Course images in `storage/app/public/courses/thumbnails/`
- Teacher avatars in `storage/app/public/teachers/avatars/`

### Security Features Enabled
- Rate limiting on forms (3-5 attempts per 10-15 minutes)
- XSS protection with HTML sanitization
- Input validation and sanitization
- Secure iframe sandbox attributes

### Email Configuration
The contact form and notifications require SMTP setup. Most hosting providers offer:
- SMTP server details
- Email account creation
- Sometimes free email limits

### Database
Requires MySQL 5.7+ or MySQL 8.0+. The migrations will create all necessary tables.

## 🎯 Post-Deployment Testing

1. **Homepage**: Check if loads correctly
2. **Blog**: Test blog posts and images display
3. **Courses**: Verify course listings and details
4. **Contact Form**: Test form submission
5. **Admin Panel**: Login and test blog/course creation
6. **Payment**: Test enrollment flow (if payment gateway configured)

## 📞 Support

If you encounter issues:
1. Check Laravel logs in `storage/logs/`
2. Verify .env configuration
3. Ensure database connection works
4. Check file permissions

---

**Your application is ready for deployment!** 🎉