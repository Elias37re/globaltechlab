<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wpglobaltechlab' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '12345678' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'T,G@.7{sH3=rp$0tI=Dc2r6>MtMRDB?nqHP=F;AX}60txpc1i.L~Pi1nrh9*9%aA' );
define( 'SECURE_AUTH_KEY',  'C6==eCv%#Y1uXPW[Ps?W$IsQ`NVi8[#Pia]|[o8cZ`5o^x?8TCh%pvSt?O5R)4B+' );
define( 'LOGGED_IN_KEY',    'hc3|iu.2R89%3E},{>1k*l~;({[72Gfeb(*{6i<U-i >VE2~]#y1>R[QT<1^1ei#' );
define( 'NONCE_KEY',        ';!~yHP%vNkn*oHrI{7[wh fg5JI</1JLSR0e3^OFjvC0rrzytU|%}c-|;#P(TpP^' );
define( 'AUTH_SALT',        'kO:c;KjW-84({6/me$6^KF7*cLk%pf`.(GPf(WJ3Z;3z-~4*{L%3@fkwnXjWZ {>' );
define( 'SECURE_AUTH_SALT', 'Hw*%8X,`,E1u4NjIN{ X@bhw#D$(D4#L])=kn,44O:8f:*&ltAYc-+?Adpor(D$&' );
define( 'LOGGED_IN_SALT',   'cKhp{TWQc:5H! 7YPt@MEHNuJ~{:YTby!y)!`$4a5}4)<Iu%6x}~bCP]JY=O(<hP' );
define( 'NONCE_SALT',       '$$UH7kt!.p6G0tf EjPx8V|+{[KbA)A.Z[y>oL/ItV&.9.?9[jY5uRxm8TO/qksD' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wpgtl';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
