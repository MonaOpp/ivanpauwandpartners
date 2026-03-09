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
define( 'DB_NAME', 'zivannxetj_wp6d7c' );

/** Database username */
define( 'DB_USER', 'zivannxetj_180' );

/** Database password */
define( 'DB_PASSWORD', 'Dfd7c19aamfzsDTFm24U' );

/** Database hostname */
define( 'DB_HOST', 'dedi1011.jnb2.host-h.net' );

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
define( 'AUTH_KEY',         'u3Wq?Y$n.TcvUC)4!eW6:+#y:_pg2^%BG?asazA<&]sbg=/q_dp6/+EY>1Ecc#?BWkL7cxg2e$?jJ4Pt' );
define( 'SECURE_AUTH_KEY',  '5]C@bh0S&.z*@.&Zt|?;NF6X$Y}=%AcL}(2dX%xFm#T;.F:R&V$_&y]t]..A&zZPM3U~&<-bO5ct`(6v' );
define( 'LOGGED_IN_KEY',    '&/YW-D%Iu;)CI>5A!?Z*]2rzbYF/gHwxx9QFdxo;fdFv$E.dQogx>2M{n>?2YCF^v]FdgkfySU!I#rgx' );
define( 'NONCE_KEY',        '83v.ZKy=k}8@!5%_(MDpSQOF1cla$D2N?!6<>4;pj7-a)3C)JJ)~YP{#La>th2K.1$Z4L+2k3&edPR]Q' );
define( 'AUTH_SALT',        'Ka(5!q3=SyhhuV%WsPHPmzit6|n!:QVay3UT=IB@8Sw<4V.*y=KFGCc|(UXsQPyK!~&E=~w:.eSYi/Q>' );
define( 'SECURE_AUTH_SALT', '+Ndp(t;<EAd`,pxF#lKs~2=i^)o_*28*eF|8!s7%mS~IF`<srkeq7%mW`i/>|N>g:/?dx15Ft46(uKW$' );
define( 'LOGGED_IN_SALT',   'A^2nB,H>4z1!)y55L43:g^Wu~LP;C<5>m_P8~^H=)P={1SElyo2nP{XIn9r6R_L|=H>/A(&zaEr~TGsY' );
define( 'NONCE_SALT',       '-;~N&H!p]Kuc82|e%TB^t[M<aeBP0qlYPpx6-iX:~lg55r.XjniXw!E#j!!|P@t2UYt*GL-3Jkdzaa<p' );

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
