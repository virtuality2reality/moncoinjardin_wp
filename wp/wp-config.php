<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/Editing_wp-config.php Modifier
 * wp-config.php} (en anglais). C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'moncoinjardin_wp');

if ($_SERVER['HTTP_HOST'] == 'localhost:8888')
{
  /** Utilisateur de la base de données MySQL. */
  define('DB_USER', 'root');

  /** Mot de passe de la base de données MySQL. */
  define('DB_PASSWORD', 'root');

  /** Adresse de l'hébergement MySQL. */
  define('DB_HOST', 'localhost:8889');
  
  /** 
   * Pour les développeurs : le mode deboguage de WordPress.
   * 
   * En passant la valeur suivante à "true", vous activez l'affichage des
   * notifications d'erreurs pendant votre essais.
   * Il est fortemment recommandé que les développeurs d'extensions et
   * de thèmes se servent de WP_DEBUG dans leur environnement de 
   * développement.
   */ 
  define('WP_DEBUG', false);
  
  define( 'COOKIEPATH', '/' );
  define( 'COOKIE_DOMAIN', 'localhost' );
}
else
{
  /** Utilisateur de la base de données MySQL. */
  define('DB_USER', 'moncoinjardin-user');

  /** Mot de passe de la base de données MySQL. */
  define('DB_PASSWORD', 'EznwetfzQxu6');

  /** Adresse de l'hébergement MySQL. */
  define('DB_HOST', 'localhost');
  
  define('WP_DEBUG', false);
  
  define( 'COOKIEPATH', '/' );
  define( 'COOKIE_DOMAIN', '.mon-coin-jardin.com' );
}

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ',.pS.il?iB-ej.H#qNPD=$.BrC6|k;/kY#+R<}9wvI$wXPu<sRJ5yvSbx6UEkQI$');
define('SECURE_AUTH_KEY',  'U*Ba9p!Tq;HWZ8$1KN+CPm.*?R~l:M6w!l5lk9M-MRG}nWFa_kFyYB3(V1aT<EfB');
define('LOGGED_IN_KEY',    'Zm},Xk@i+.&Su|nQ-0qMQUCCt,%g_`+m_4`6{~#}Hh_%L[)F9G-1;(0}I0PLXxhz');
define('NONCE_KEY',        ';tR-_SbNZ-`^|X|0Z-p[782+AM=2^/7&Br)MHW]orHD0.0Oq(yi55wY2f7VJ|3Mc');
define('AUTH_SALT',        'OdVBfDJ0rZ-C<{M&Bt=76c-5y/aW0Lf(-9%X$fF$&&^lQM +iqD07=<runor/J,l');
define('SECURE_AUTH_SALT', '2W{I`b|kR-+hw/J+q9wV1n#S7OH{ 3pxNttUF0AU3N?(;={y{r:R8&gI*`Y C!`W');
define('LOGGED_IN_SALT',   '~8juc}i!)?3RDP-(Uve7Q.xz$hS`Qs,H^;2U=-QaMWLu611`+GFcjFGRDI$:U6v}');
define('NONCE_SALT',       '@iV|AQEKw$te(M|wtDz.WMh>.:*I.TP/w(X!#qb:w^rG!,YvYIOlI1=IEIP?/-MH');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Langue de localisation de WordPress, par défaut en Anglais.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier wp-content/languages.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans wp-content/languages, et réglez l'option ci-dessous à "fr_FR".
 */
define('WPLANG', 'fr_FR');

define('WP_MEMORY_LIMIT', '64M');

// define( 'COOKIEPATH', '/' );

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');