<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'qubbawp' );

/** Database username */
define( 'DB_USER', 'webap' );

/** Database password */
define( 'DB_PASSWORD', 'Alkaline:15' );

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
define( 'AUTH_KEY',         '|o/hc)G:mW#qX9Y out:M`.iFl7Z<iW)3c!p(:W3e5pfuJBnRz^V)YBnj87YNbr[' );
define( 'SECURE_AUTH_KEY',  'S:vQA 60Y897;]M7hh#1g^gYyAniJG!pH5 3I1I33dc7f7KmEO%`l;$t6-9XRot3' );
define( 'LOGGED_IN_KEY',    '=4.p4j$)m0O~=C4=)[@#9j6==gF#=WHNDfxSu,PjqK71FLOt>ZQqLA=6oQ+t*St|' );
define( 'NONCE_KEY',        'WZ^h3JJu{e1K#<fH^}<0Sy,{/]W4ns^;(f|.qm4ejEi$JI[a1Co}~3%7pjwWP(ao' );
define( 'AUTH_SALT',        'RTZLE&D~}p7S%m{3(HhL}y]eU3J|Uv$%[.zQAnSFC5|t#}DyldJU5$UsQX0tWb;h' );
define( 'SECURE_AUTH_SALT', '#xN|k9B=#iWLE#8^e4d!QDqOB7[>h>q_asoWBG4{%u`0B{r;;0~,c1rQ[VH=@DHe' );
define( 'LOGGED_IN_SALT',   '7%&i.S1@th.AlL[=Lcrwd`Jd k[6g@1_=CksTqjp_Vpf@Xbw =CSWw/;VKWF_!TI' );
define( 'NONCE_SALT',       'eW8zY,s*r+W$0S>@fAn&[T+`!fmynhk`du~:W_p~wi*MU, ~52UMt5u#;eC|@W.h' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
