<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// config goes here
define( 'SPECTRIX_USER', esc_attr( get_option('wpspx_account') ) );
define( 'SPECTRIX_CERT', esc_attr( get_option('wpspx_crt') ) );
define( 'SPECTRIX_KEY',  esc_attr( get_option('wpspx_key') ) );
define( 'SPECTRIX_API',  esc_attr( get_option('wpspx_api') ) );