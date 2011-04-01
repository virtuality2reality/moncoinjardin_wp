<?php
/** 
 * The base configurations of bbPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys and bbPress Language. You can get the MySQL settings from your
 * web host.
 *
 * This file is used by the installer during installation.
 *
 * @package bbPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for bbPress */
define( 'BBDB_NAME', 'moncoinjardin_wp' );


if ($_SERVER['HTTP_HOST'] == 'localhost:8888')
{
  /** MySQL database username */
  define( 'BBDB_USER', 'root' );

  /** MySQL database password */
  define( 'BBDB_PASSWORD', 'root' );

  /** MySQL hostname */
  define( 'BBDB_HOST', 'localhost:8889' );
  
  define( 'COOKIEPATH', '/' );
  define( 'COOKIE_DOMAIN', 'localhost' );
}
else
{
  /** MySQL database username */
  define( 'BBDB_USER', 'moncoinjardin' );

  /** MySQL database password */
  define( 'BBDB_PASSWORD', 'EznwetfzQxu6' );

  /** MySQL hostname */
  define( 'BBDB_HOST', 'localhost' );
  
  define( 'COOKIEPATH', '/' );
  define( 'COOKIE_DOMAIN', '.mon-coin-jardin.com' );
}


/** Database Charset to use in creating database tables. */
define( 'BBDB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'BBDB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/bbpress/ WordPress.org secret-key service}
 *
 * @since 1.0
 */
define('BB_AUTH_KEY',        ',.pS.il?iB-ej.H#qNPD=$.BrC6|k;/kY#+R<}9wvI$wXPu<sRJ5yvSbx6UEkQI$');
define('BB_SECURE_AUTH_KEY', 'U*Ba9p!Tq;HWZ8$1KN+CPm.*?R~l:M6w!l5lk9M-MRG}nWFa_kFyYB3(V1aT<EfB');
define('BB_LOGGED_IN_KEY',   'Zm},Xk@i+.&Su|nQ-0qMQUCCt,%g_`+m_4`6{~#}Hh_%L[)F9G-1;(0}I0PLXxhz');
define('BB_NONCE_KEY',       ';tR-_SbNZ-`^|X|0Z-p[782+AM=2^/7&Br)MHW]orHD0.0Oq(yi55wY2f7VJ|3Mc');
/**#@-*/

/**
 * bbPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$bb_table_prefix = 'bb_';

/**
 * bbPress Localized Language, defaults to English.
 *
 * Change this to localize bbPress. A corresponding MO file for the chosen
 * language must be installed to a directory called "my-languages" in the root
 * directory of bbPress. For example, install de.mo to "my-languages" and set
 * BB_LANG to 'de' to enable German language support.
 */
define( 'BB_LANG', 'fr_FR' );
?>