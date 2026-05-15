# Database Backups

This directory stores database backup files (.sql) created through the admin panel.

## Security Notes
- This directory is protected from direct web access via .htaccess
- Backup files are excluded from version control via .gitignore
- Only download backups through the admin panel

## File Naming
- Auto-generated: backup_YYYY-MM-DD_HH-MM-SS.sql
- Custom: user-defined-name.sql
