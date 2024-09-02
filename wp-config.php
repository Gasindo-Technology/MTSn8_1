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
define( 'DB_NAME', 'mtsn8ngawi' );

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
define( 'AUTH_KEY',         ')i>eSiC$v0:h<s~e.=h3(y*Z-Ih}*frOFs3d8!LI}Wak6@=h !@NH9&=1jRpk5v0' );
define( 'SECURE_AUTH_KEY',  '2:{yD}FiO>a7<}?!31=AB_!%#tT,D0_&~u*jjRZEv:3uKjLrl4Y48xbOns[A>,7c' );
define( 'LOGGED_IN_KEY',    'FRb,~k7B9h/.2n;}hXlzh1_c.a/,pB}Q8bxrM}/JJii&Rj_9Devj^r[d,z$k)*C+' );
define( 'NONCE_KEY',        '5)$Y^{0M8V9N )w7ko{BLJvVPVl-6?efW-QarQ(PD3BHqZe3OD/X]Wemx)@f8J#_' );
define( 'AUTH_SALT',        'uy0rCj`qchJ_KNM#o(sX,)E)@@qC?im*qWo;)^e(?X<D&i@}{?y=s5NH$(TO1s(H' );
define( 'SECURE_AUTH_SALT', 'HbBW)os!Z~/O6-f1H#N$oN*MdC!i.8e]lr]:Esj<,m.p;@AP-Q9 :ONv<Xjj<Pjy' );
define( 'LOGGED_IN_SALT',   '+c)XL[NHvR!9gom)VaPaE;[#(jPmd:ZVk^Ee.^))z=>+!2Ai+VEvr72L&x,xXc-9' );
define( 'NONCE_SALT',       's4XT)BXf#TV@>qbJ&`$W(P}-$HHs _`Tx6LT!X|~yg 24c,9=AJ4dJI?Xfm!MGd5' );

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
