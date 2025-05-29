# Rankolab Backend Website Deployment Instructions

This document provides instructions for deploying the Rankolab backend website to your production server.

## System Requirements

- Web server (Apache or Nginx)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- SSL certificate (recommended for secure transactions)

## Deployment Steps

### 1. Database Setup

1. Create a new database on your server for Rankolab
2. Import the `database_setup.sql` file to create the initial database structure
3. Create a database user with appropriate permissions

### 2. File Transfer

1. Upload all files from the `rankolab_package` directory to your web server's public_html directory
2. Ensure proper file permissions:
   - Directories: 755
   - Files: 644
   - wp-config.php: 600 (after configuration)

### 3. WordPress Configuration

1. Rename `wp-config-sample.php` to `wp-config.php`
2. Edit `wp-config.php` and update the following:
   - Database connection details
   - Authentication unique keys and salts
   - Table prefix (if desired)
   - Debug settings (set to false for production)

### 4. Stripe Integration

1. Update the Stripe API keys in the WordPress admin dashboard:
   - Navigate to Dashboard > Rankolab Settings > Payment Integration
   - Enter your Stripe API keys (provided separately)
   - Test the connection before going live

### 5. License System Configuration

1. Configure the license system in the WordPress admin dashboard:
   - Navigate to Dashboard > Rankolab Settings > License Management
   - Set up the Master License Key
   - Configure license validation settings

### 6. Affiliate System Setup

1. Configure the affiliate system in the WordPress admin dashboard:
   - Navigate to Dashboard > Rankolab Settings > Affiliate Management
   - Set commission rates and payment thresholds
   - Configure affiliate registration options

### 7. Final Configuration

1. Set up permalinks (Settings > Permalinks)
2. Configure site title, tagline, and timezone (Settings > General)
3. Set up user roles and permissions (Users > All Users)
4. Test all functionality before going live

## Post-Deployment Tasks

1. Set up regular database backups
2. Configure server caching for optimal performance
3. Set up SSL certificate if not already configured
4. Test the entire user journey from registration to subscription

## Support

For any issues during deployment, please contact our support team at support@rankolab.com or through the support ticket system.
