<?php
/**
 * WordPress konfigurace pro lokální vývoj
 *
 * Lokální URL: http://localhost/fotbal_club/kontakty/ (příklad stránky)
 *
 * Tento soubor je konfigurací pro lokální prostředí (XAMPP).
 * Při nasazení na produkční server nahraďte přihlašovací údaje k databázi
 * a odstraňte nebo přepište WP_HOME a WP_SITEURL.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Nastavení databáze (XAMPP výchozí hodnoty) ** //
/** Název databáze */
define( 'DB_NAME', 'slavoj_myto' );

/** Uživatel databáze */
define( 'DB_USER', 'root' );

/** Heslo databáze (v XAMPP prázdné) */
define( 'DB_PASSWORD', '' );

/** Hostitel databáze */
define( 'DB_HOST', 'localhost' );

/** Znaková sada databáze */
define( 'DB_CHARSET', 'utf8mb4' );

/** Řazení databáze */
define( 'DB_COLLATE', '' );

/**
 * Lokální URL projektu
 *
 * Obě konstanty jsou nastaveny na kořenovou adresu složky fotbal_club,
 * protože WordPress je lokálně nainstalován přímo v htdocs/fotbal_club/
 * (ne v žádné podsložce).
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress-in-directory/
 */
define( 'WP_HOME',    'http://localhost/fotbal_club' );
define( 'WP_SITEURL', 'http://localhost/fotbal_club' );

/**#@+
 * Autentizační klíče a soli.
 *
 * Vygenerujte vlastní hodnoty na: https://api.wordpress.org/secret-key/1.1/salt/
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );
/**#@-*/

/**
 * Prefix tabulek databáze WordPress.
 */
$table_prefix = 'wp_';

/**
 * Vývojový režim – zobrazuje chybová hlášení.
 * Na produkčním serveru nastavte na false.
 */
define( 'WP_DEBUG', true );

/* Konec úprav. Nezměňujte nic níže. */

/** Absolutní cesta ke složce WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Načte nastavení WordPress. */
require_once ABSPATH . 'wp-settings.php';
