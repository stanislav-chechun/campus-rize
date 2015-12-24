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
define('DB_NAME', 'primatec_campus');

/** MySQL database username */
define('DB_USER', 'primatec_campus');

/** MySQL database password */
define('DB_PASSWORD', 'uuwe35hg');

/** MySQL hostname */
define('DB_HOST', 'primatec.mysql.ukraine.com.ua');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ':#Jr0a&yEX5=6-KjTT>;&9M8K=h|{#Ex*C*Do=9?c8Y$xey9:#+ItT|uW<=`|B;W');
define('SECURE_AUTH_KEY',  '|lmhAkA-1<,MLfY6,/uB:w3=JRd)^4/4I!7,wlzNO)6by2lsGq8K=Xd-Zgyq8@@$');
define('LOGGED_IN_KEY',    '2Di$M3-;Ixvn)-1520I5M4q@adEDl@jgb<nQ-I4vU]BM9@CLOo@//z^}.:|R-ysO');
define('NONCE_KEY',        'Q%:N:>B ^sU;FrXEL~&.jLm#4jHSl?!dwRnjX%HoQsTFyF(8(a*(F#q_1M>`roG.');
define('AUTH_SALT',        'n@)M5`,=Re8<:FA|-)~g0Q^%euH-e(VEx>ngktt$*nv[Cl/Tg- 8nQ31l9-g3YK%');
define('SECURE_AUTH_SALT', '4AGW$FW}=J.~[x+H*.b[W.qqAOzbV-&}Chnr-C4oIE+eAFAhFky|}<?YL)}-$r4?');
define('LOGGED_IN_SALT',   'Wm?j8phQ)ulTT==b+OC$?0W:lK#hVsm$vnR0al1?Q{?ydjO@MU^G=gc!v1zQ61r6');
define('NONCE_SALT',       'iW`f1]9RL-HX<di5O)AL(6#|NVQ>o6I-D#%$`4%8Fo}`K=aJ~xD$-.w176=TLD_4');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
