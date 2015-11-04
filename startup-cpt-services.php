<?php
/*
Plugin Name: StartUp CPT Services
Description: Le plugin pour activer le Custom Post Services
Author: Yann Caplain
Version: 1.2.0
Text Domain: startup-cpt-services
*/

//GitHub Plugin Updater
function startup_reloaded_services_updater() {
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

add_action( 'init', 'startup_reloaded_services_updater' );

//CPT
function startup_reloaded_services() {
	$labels = array(
		'name'                => _x( 'Services', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Service', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Services', 'text_domain' ),
		'name_admin_bar'      => __( 'Services', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'All Items', 'text_domain' ),
		'add_new_item'        => __( 'Add New Item', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'new_item'            => __( 'New Item', 'text_domain' ),
		'edit_item'           => __( 'Edit Item', 'text_domain' ),
		'update_item'         => __( 'Update Item', 'text_domain' ),
		'view_item'           => __( 'View Item', 'text_domain' ),
		'search_items'        => __( 'Search Item', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' )
	);
	$args = array(
		'label'               => __( 'services', 'text_domain' ),
		'description'         => __( '', 'text_domain' ),
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

add_action( 'init', 'startup_reloaded_services', 0 );

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_reloaded_services_rewrite_flush() {
    startup_reloaded_services();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_reloaded_services_rewrite_flush' );

// Capabilities
function startup_reloaded_services_caps() {
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

register_activation_hook( __FILE__, 'startup_reloaded_services_caps' );

// Metaboxes
function startup_reloaded_services_meta() {
    require get_template_directory() . '/inc/font-awesome.php';
    
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_reloaded_services_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Service details', 'cmb2' ),
		'object_types'  => array( 'services' )
	) );
    
    $cmb_box->add_field( array(
            'name'             => __( 'Icon', 'cmb2' ),
            'desc'             => __( 'The service icon', 'cmb2' ),
            'id'               => $prefix . 'icon',
            'type'             => 'select',
            'show_option_none' => true,
            'options'          => $font_awesome
    ) );
}

add_action( 'cmb2_admin_init', 'startup_reloaded_services_meta' );

// Shortcode
add_shortcode( 'services', function( $atts, $content= null ){
    ob_start();
    require get_template_directory() . '/template-parts/content-services.php';
    return ob_get_clean();
});
?>