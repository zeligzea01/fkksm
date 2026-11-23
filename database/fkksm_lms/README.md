# FKKSM LMS database

The LMS uses MySQL database `fkksm_lms`. The database schema is in `schema.sql`.

Import `schema.sql` in phpMyAdmin or run it with the MySQL command-line client. The LMS connection is configured separately in `public/fkksm/lms/config.php` (defaults: host `127.0.0.1`, port `3306`, database `fkksm_lms`, user `root`, blank password).

This package contains the table schema, not preloaded user/course records. Laravel's `.env` database setting is separate from this legacy LMS connection.