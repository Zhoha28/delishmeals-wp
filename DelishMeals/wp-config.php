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
define( 'DB_NAME', 'delishmealswp' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'H>1%x`s l;z)R/{#k#rMa |/pvd6v~U45LDLab^!_sL9n VI|#.Befy;OP@zZRo*' );
define( 'SECURE_AUTH_KEY',  '4{SOJb(_|E[dPqj@zuXlQ<R)]f/4c*/%>>uY;5Zg[D2-Y)f(ibv_J_[cX^>/B*p!' );
define( 'LOGGED_IN_KEY',    '4/LzX)VHBid7msgUear~ }]:Vi9^4oTK~hk53A/d}yc1o]Gsqw?*&E(R~p;^l%Pl' );
define( 'NONCE_KEY',        'tLfP^RlkcaWXn%tx*-s!y1sem-cN!GOBstyUazN cPHu&2NYHPO)[L7{Rbh x=CO' );
define( 'AUTH_SALT',        ')]Q~m3qp5Z1c5}j`YP9Ke~jQwY uAqiJK@^GOT;MZCN#U}O>GvmMSw.zlM&r*<6^' );
define( 'SECURE_AUTH_SALT', 'hz)?,S?&_DtjEcwC^(fF2.^fPFbz2ml8+4$Oxe`I]4)Ds[#+cv<d{M|tB6gm=*WV' );
define( 'LOGGED_IN_SALT',   ';|5}ZR-1K+r|<Wui)>4Q]I:?IiNpmlPq,8Y[BEAi.*VBd2H}_~NCfH?CA7Mm$CB!' );
define( 'NONCE_SALT',       '=D,Amb@$4Gl[RbCf5qw^- ?)E;>5|@C2dXXmu@vQ*LLlfWKXby0.%?qo`*14|uDG' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'delishmealswp_';

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
