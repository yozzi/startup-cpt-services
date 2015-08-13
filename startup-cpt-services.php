<?php
/*
Plugin Name: StartUp Services Custom Post
Description: Le plugin pour activer le Custom Post Services
Author: Yann Caplain
Version: 1.0
*/

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
		'capability_type'     => 'page'
	);
	register_post_type( 'services', $args );

}
add_action( 'init', 'startup_reloaded_services', 0 );

// Metaboxes
add_action( 'cmb2_init', 'startup_reloaded_metabox_services' );

function startup_reloaded_metabox_services() {
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
?>