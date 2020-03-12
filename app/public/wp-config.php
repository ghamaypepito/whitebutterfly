<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '+A6Mmc9QQJE3Dd382sMO9NHROhardkdGj51RnH0j5DXhHFDkQBe+qIBLjx/Nilq8z5uKgMAekbwLnOVCi0BVQg==');
define('SECURE_AUTH_KEY',  'AxLEUUCj+XSLicbdweUUowmAB8tGOjm9Q1YBpLDi3tA6JAhz2oCc2EnxBFJPOL20HYIv0KCEgFaZ0MNDZrDnLA==');
define('LOGGED_IN_KEY',    'QSYQBrHz686qg8HYCvvc+DOML8j0OBHuxBmmazGS7ZAyDxzK2g9liFNHqTVuZN7HEQW4WLKkJk/Y/StYfedBoA==');
define('NONCE_KEY',        'JIRRm3BKK+cHgdzVpF3L00DstTLIPvGgOwEP6Q1LTXivwD/vFeriytVsnxTguGJ9wE2J0ekosJAwa2uXCUGSfw==');
define('AUTH_SALT',        'HychGjhii4QYOkDrqzG2C8A7ZjyrApmdXO/QxrXkpouv3VwPy/Q5V78pG6Ab2MR2/M4HQGD8WpC7MvwR/k/DWA==');
define('SECURE_AUTH_SALT', 'D0dA/F/SlFalDmz9nz9ylNYp65j6ev6M6V/dmSWOj1dxFK72QHjxTHFSYLxd/VRGYPpbinot2f7rbWS6GhPFIw==');
define('LOGGED_IN_SALT',   'dGWMMnVsJn/A1onzq/EBHZOD33BK3FdDRYzGa2ZPqcuiut2A3aOaM5Kv7Z6x0Orv6gATU1Q8pscxgtTfsWs7yQ==');
define('NONCE_SALT',       'mHEWYmFxUptmaVwYmAB9C/A64As8/6FPHiRWUl10pebg1Gw6Scx8OYweBkFwiF8xQX2ydK3E7M+M5pnADMYmHQ==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
