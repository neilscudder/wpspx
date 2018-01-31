<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// config goes here
define( 'SPEKTRIX_USER', esc_attr( get_option('wpspx_account') ) );
define( 'SPEKTRIX_CERT', esc_attr( get_option('wpspx_crt') ) );
define( 'SPEKTRIX_KEY',  esc_attr( get_option('wpspx_key') ) );
define( 'SPEKTRIX_API',  esc_attr( get_option('wpspx_api') ) );