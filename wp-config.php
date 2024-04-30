<?php

define( 'WP_CACHE', false /* Modified by NitroPack */ );
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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', "levanterealestat_levantere_new" );
/** Database username */
define( 'DB_USER', "levanterealestat_levantere_new" );
/** Database password */
define( 'DB_PASSWORD', "0V(tJ{-}tN%6" );
/** Database hostname */
define( 'DB_HOST', "localhost" );
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
define( 'AUTH_KEY',         'C//D)}0z:y7*PYP_a.)awTaR`}_c=~wMeY/bxJ2IM0,}Oh40Ky=}X3+y7oe~z+n1' );
define( 'SECURE_AUTH_KEY',  'W9K_(^F.qw{~Y7e]U/U:$%7najk>BU@6Qv9,1P;|>{3<U7l_+h0_5OdEd](9e06f' );
define( 'LOGGED_IN_KEY',    'WP8N:{^gUv4)!4@JNrQ=Tox) p~~k&6uI!B5#[=Zg[@r0JE_zi o=W u*A42/*mB' );
define( 'NONCE_KEY',        '#+Kjwk`}SzKcax I<DOOApuerDSs,^ta8a<2k89N2[WICcquoFr=(B?X@3xm>Kcv' );
define( 'AUTH_SALT',        'IWH)9a!OGhdv/DEU&L%H`91_N-TCAO]7i)BT<E0#Aps*+f?ff@EPf{PTIPvtW*RP' );
define( 'SECURE_AUTH_SALT', ' gDwfFDq9t:j+F1ouW#iankXl.:8n-D];aV4 CgeZMs=}E5B}wxIzP>m)u.^L1VE' );
define( 'LOGGED_IN_SALT',   'Q&)i`$)WR}#TF$CY{^KYeC&QG;__/2Am,cP3J=]W2$8WgAsp!G)>T}`S|>eV/LX5' );
define( 'NONCE_SALT',       'n(|k`Mn~5I;{>s6&~&F8(dM)-D[HC*SjP/v`ETt?]s;~h[gh+VXw>S_0ZPL>yQxL' );
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
/* Add any custom values between this line and the "stop editing" line. */
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';