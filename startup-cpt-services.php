<?php
/*
Plugin Name: StartUp CPT Services
Description: Le plugin pour activer le Custom Post Services
Author: Yann Caplain
Version: 1.0.0
Text Domain: startup-cpt-services
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Include this to check if a plugin is activated with is_plugin_active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//Include this to check dependencies
include_once( 'inc/dependencies.php' );

//GitHub Plugin Updater
function startup_cpt_services_updater() {
	include_once 'lib/updater.php';
	//define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) {
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'startup-cpt-services',
			'api_url' => 'https://api.github.com/repos/yozzi/startup-cpt-services',
			'raw_url' => 'https://raw.github.com/yozzi/startup-cpt-services/master',
			'github_url' => 'https://github.com/yozzi/startup-cpt-services',
			'zip_url' => 'https://github.com/yozzi/startup-cpt-services/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
		new WP_GitHub_Updater( $config );
	}
}

//add_action( 'init', 'startup_cpt_services_updater' );

//CPT
function startup_cpt_services() {
	$labels = array(
		'name'                => _x( 'Services', 'Post Type General Name', 'startup-cpt-services' ),
		'singular_name'       => _x( 'Service', 'Post Type Singular Name', 'startup-cpt-services' ),
		'menu_name'           => __( 'Services', 'startup-cpt-services' ),
		'name_admin_bar'      => __( 'Services', 'startup-cpt-services' ),
		'parent_item_colon'   => __( 'Parent Item:', 'startup-cpt-services' ),
		'all_items'           => __( 'All Items', 'startup-cpt-services' ),
		'add_new_item'        => __( 'Add New Item', 'startup-cpt-services' ),
		'add_new'             => __( 'Add New', 'startup-cpt-services' ),
		'new_item'            => __( 'New Item', 'startup-cpt-services' ),
		'edit_item'           => __( 'Edit Item', 'startup-cpt-services' ),
		'update_item'         => __( 'Update Item', 'startup-cpt-services' ),
		'view_item'           => __( 'View Item', 'startup-cpt-services' ),
		'search_items'        => __( 'Search Item', 'startup-cpt-services' ),
		'not_found'           => __( 'Not found', 'startup-cpt-services' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'startup-cpt-services' )
	);
	$args = array(
		'label'               => __( 'services', 'startup-cpt-services' ),
		'description'         => __( '', 'startup-cpt-services' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'revisions' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-info',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
        'capability_type'     => array('service','services'),
        'map_meta_cap'        => true
	);
	register_post_type( 'services', $args );

}

add_action( 'init', 'startup_cpt_services', 0 );

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_cpt_services_rewrite_flush() {
    startup_cpt_services();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_cpt_services_rewrite_flush' );

// Capabilities
function startup_cpt_services_caps() {
	$role_admin = get_role( 'administrator' );
	$role_admin->add_cap( 'edit_service' );
	$role_admin->add_cap( 'read_service' );
	$role_admin->add_cap( 'delete_service' );
	$role_admin->add_cap( 'edit_others_services' );
	$role_admin->add_cap( 'publish_services' );
	$role_admin->add_cap( 'edit_services' );
	$role_admin->add_cap( 'read_private_services' );
	$role_admin->add_cap( 'delete_services' );
	$role_admin->add_cap( 'delete_private_services' );
	$role_admin->add_cap( 'delete_published_services' );
	$role_admin->add_cap( 'delete_others_services' );
	$role_admin->add_cap( 'edit_private_services' );
	$role_admin->add_cap( 'edit_published_services' );
}

register_activation_hook( __FILE__, 'startup_cpt_services_caps' );

// Metaboxes
function startup_cpt_services_meta() {
    require ABSPATH . 'wp-content/plugins/startup-cpt-services/inc/font-awesome.php';
    
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_cpt_services_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Service details', 'startup-cpt-services' ),
		'object_types'  => array( 'services' )
	) );
    
    $cmb_box->add_field( array(
            'name'             => __( 'Icon', 'cmb2' ),
            'desc'             => __( 'The service icon', 'startup-cpt-services' ),
            'id'               => $prefix . 'icon',
            'type'             => 'select',
            'show_option_none' => true,
            'options'          => $font_awesome
    ) );
}

add_action( 'cmb2_admin_init', 'startup_cpt_services_meta' );

// Shortcode
function startup_cpt_services_shortcode( $atts ) {

	// Attributes
    $atts = shortcode_atts(array(
            'bg' => '#f0f0f0'
        ), $atts);
    
	// Code
    ob_start();
    if ( function_exists( 'startup_reloaded_setup' ) || function_exists( 'startup_revolution_setup' ) ) {
        require get_template_directory() . '/template-parts/content-services.php';
     } else {
        echo 'You should install <a href="https://github.com/yozzi/startup-reloaded" target="_blank">StartUp Reloaded</a> or <a href="https://github.com/yozzi/startup-revolution" target="_blank">StartUp Revolution</a> theme to make things happen...';
     }
     return ob_get_clean();    
}
add_shortcode( 'services', 'startup_cpt_services_shortcode' );

// Shortcode UI
function startup_cpt_services_shortcode_ui() {

    shortcode_ui_register_for_shortcode(
        'services',
        array(
            'label' => esc_html__( 'Services', 'startup-cpt-services' ),
            'listItemImage' => 'dashicons-info',
            'attrs' => array(
                array(
                    'label' => esc_html__( 'Background', 'startup-cpt-services' ),
                    'attr'  => 'bg',
                    'type'  => 'color',
                ),
            ),
        )
    );
};

if ( function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
    add_action( 'init', 'startup_cpt_services_shortcode_ui');
}

// Enqueue scripts and styles.
function startup_cpt_services_scripts() {
    wp_enqueue_style( 'startup-cpt-services-style', plugins_url( '/css/startup-cpt-services.css', __FILE__ ), array( ), false, 'all' );
}

add_action( 'wp_enqueue_scripts', 'startup_cpt_services_scripts', 15 );
?>