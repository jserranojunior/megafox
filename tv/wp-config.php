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
define('DB_NAME', 'redemegafox_wp592');

/** MySQL database username */
define('DB_USER', 'redemegafox_wp592');

/** MySQL database password */
define('DB_PASSWORD', 'S-64[9thJp');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'h4pdm5y2irlm86yjposscvioxaymi1v7hamppqngjbon2okcigopzi96hgvg1tps');
define('SECURE_AUTH_KEY',  'iaiz7bfudgbp3tlswicej1ahhfq9jtb1wf528lj2dv2ryjaweikgxj6kx6fvpz73');
define('LOGGED_IN_KEY',    'ehggivjknsybp9ynziyovmol6hhhtibsgfn18jenc7cwi0iu1dblxupqlrpidav2');
define('NONCE_KEY',        'qcq2u3hnoygraeodx4xey9y9iqzquwlutyjnksxkklmqqqydb34sfqwwj2cfywho');
define('AUTH_SALT',        'kcidccbaqr5bnr3p7prczapjndxzp1ifpuokaa1hkydof4shtc5msyldqleyqy8c');
define('SECURE_AUTH_SALT', 'o1k3nbw4em4p88fk593x9rhqzmutxga5jk4sauzh4qo35voiks5fqrqxuln3x765');
define('LOGGED_IN_SALT',   'ftnoc6drtfi02kwfo4fv8fnnfxpx2hem31an8zfw6l2woelz3d06c45zcmephcrs');
define('NONCE_SALT',       '0dpu7ayftlskigl6stzcb7tmjvdqzbkciisd7dxhsbx13bwuyf73rmeigyamb7my');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wphc_';

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
