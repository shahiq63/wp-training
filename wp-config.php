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
define('DB_NAME', 'wp-training');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'mehfoozian1R');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'G[NzJH&T963YpnepZ@?Mo^5MIs2|ilAHZl.+-]5 +5~?t6&m* ;pG8Of69FE|Y(6');
define('SECURE_AUTH_KEY',  ']@s.G6sjj!d<c1R07O}S(}pLf%<T.4uq0/Q)i. N}~?_[hrA-X&~^>;FsNd<;1Fj');
define('LOGGED_IN_KEY',    'Bj+dI*H6;!Ne|P]=nII`=.Y`n&,!2ONgKq0s_:HzS)ph3I~/KlIQUTNsSCmd[Vp#');
define('NONCE_KEY',        'Y|,JsHM /.ntFNuNL}et]FQph!@N^><$U:lfKf?vfxgE==Omk]q19NR-|6#VI-y9');
define('AUTH_SALT',        '{^lM6c Ho{Au]qh&`ru^wVM4]i[;1&3<$f*[h-kV.4R|*us#c}?EEfrh!!5`m0}K');
define('SECURE_AUTH_SALT', 'lSb:.S{Dv_TQB)gb5R}p3S&=J^8,&ZJ %FvoH_/T410&]uo}+K5A$EuHQne<:P<i');
define('LOGGED_IN_SALT',   'GBGkuz@hsh;?ozKO&E@FI/FF[ZMkyl@ -a|VlCn@`SktYQX{JA+ HWs7E9ShfzH>');
define('NONCE_SALT',       'g}-]<VCz;$MAiyswt+ Yh6o/^6Qaq00s0$@2q%+`lgnKRng$|#!CmWoZ)EV}!Lr0');

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
