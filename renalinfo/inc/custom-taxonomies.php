<?php
/**
 * Register Custom Taxonomies
 *
 * @package RenalInfo
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Article Category taxonomy
 *
 * @since 1.0.0
 */
function renalinfo_register_article_category_taxonomy() {
	$labels = array(
		'name'              => _x( 'Article Categories', 'taxonomy general name', 'renalinfo' ),
		'singular_name'     => _x( 'Article Category', 'taxonomy singular name', 'renalinfo' ),
		'search_items'      => __( 'Search Categories', 'renalinfo' ),
		'all_items'         => __( 'All Categories', 'renalinfo' ),
		'parent_item'       => __( 'Parent Category', 'renalinfo' ),
		'parent_item_colon' => __( 'Parent Category:', 'renalinfo' ),
		'edit_item'         => __( 'Edit Category', 'renalinfo' ),
		'update_item'       => __( 'Update Category', 'renalinfo' ),
		'add_new_item'      => __( 'Add New Category', 'renalinfo' ),
		'new_item_name'     => __( 'New Category Name', 'renalinfo' ),
		'menu_name'         => __( 'Categories', 'renalinfo' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'article-category' ),
	);

	register_taxonomy( 'article_category', array( 'article' ), $args );
}
add_action( 'init', 'renalinfo_register_article_category_taxonomy' );

/**
 * Register Audience Type taxonomy
 *
 * @since 1.0.0
 */
function renalinfo_register_audience_type_taxonomy() {
	$labels = array(
		'name'          => _x( 'Audience Types', 'taxonomy general name', 'renalinfo' ),
		'singular_name' => _x( 'Audience Type', 'taxonomy singular name', 'renalinfo' ),
		'menu_name'     => __( 'Audience', 'renalinfo' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'audience' ),
	);

	register_taxonomy( 'audience_type', array( 'article' ), $args );
}
add_action( 'init', 'renalinfo_register_audience_type_taxonomy' );

/**
 * Register Specialization taxonomy
 *
 * @since 1.0.0
 */
function renalinfo_register_specialization_taxonomy() {
	$labels = array(
		'name'          => _x( 'Specializations', 'taxonomy general name', 'renalinfo' ),
		'singular_name' => _x( 'Specialization', 'taxonomy singular name', 'renalinfo' ),
		'menu_name'     => __( 'Specializations', 'renalinfo' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'specialization' ),
	);

	register_taxonomy( 'specialization', array( 'staff' ), $args );
}
add_action( 'init', 'renalinfo_register_specialization_taxonomy' );

/**
 * Register Resource Type taxonomy
 *
 * @since 1.0.0
 */
function renalinfo_register_resource_type_taxonomy() {
	$labels = array(
		'name'          => _x( 'Resource Types', 'taxonomy general name', 'renalinfo' ),
		'singular_name' => _x( 'Resource Type', 'taxonomy singular name', 'renalinfo' ),
		'menu_name'     => __( 'Resource Types', 'renalinfo' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'resource-type' ),
	);

	register_taxonomy( 'resource_type', array( 'support_resource' ), $args );
}
add_action( 'init', 'renalinfo_register_resource_type_taxonomy' );
