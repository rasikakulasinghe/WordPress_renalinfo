<?php
/**
 * Register Custom Post Types
 *
 * @package RenalInfo
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Article custom post type
 *
 * @since 1.0.0
 */
function renalinfo_register_article_post_type() {
	$labels = array(
		'name'                  => _x( 'Articles', 'Post type general name', 'renalinfo' ),
		'singular_name'         => _x( 'Article', 'Post type singular name', 'renalinfo' ),
		'menu_name'             => _x( 'Articles', 'Admin Menu text', 'renalinfo' ),
		'name_admin_bar'        => _x( 'Article', 'Add New on Toolbar', 'renalinfo' ),
		'add_new'               => __( 'Add New', 'renalinfo' ),
		'add_new_item'          => __( 'Add New Article', 'renalinfo' ),
		'new_item'              => __( 'New Article', 'renalinfo' ),
		'edit_item'             => __( 'Edit Article', 'renalinfo' ),
		'view_item'             => __( 'View Article', 'renalinfo' ),
		'all_items'             => __( 'All Articles', 'renalinfo' ),
		'search_items'          => __( 'Search Articles', 'renalinfo' ),
		'parent_item_colon'     => __( 'Parent Articles:', 'renalinfo' ),
		'not_found'             => __( 'No articles found.', 'renalinfo' ),
		'not_found_in_trash'    => __( 'No articles found in Trash.', 'renalinfo' ),
		'featured_image'        => _x( 'Article Featured Image', 'Overrides the "Featured Image" phrase', 'renalinfo' ),
		'set_featured_image'    => _x( 'Set featured image', 'Overrides the "Set featured image" phrase', 'renalinfo' ),
		'remove_featured_image' => _x( 'Remove featured image', 'Overrides the "Remove featured image" phrase', 'renalinfo' ),
		'use_featured_image'    => _x( 'Use as featured image', 'Overrides the "Use as featured image" phrase', 'renalinfo' ),
		'archives'              => _x( 'Article archives', 'The post type archive label', 'renalinfo' ),
		'insert_into_item'      => _x( 'Insert into article', 'Overrides the "Insert into post" phrase', 'renalinfo' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this article', 'Overrides the "Uploaded to this post" phrase', 'renalinfo' ),
		'filter_items_list'     => _x( 'Filter articles list', 'Screen reader text', 'renalinfo' ),
		'items_list_navigation' => _x( 'Articles list navigation', 'Screen reader text', 'renalinfo' ),
		'items_list'            => _x( 'Articles list', 'Screen reader text', 'renalinfo' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'article' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-media-document',
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'article', $args );
}
add_action( 'init', 'renalinfo_register_article_post_type' );

/**
 * Register Journey custom post type
 *
 * @since 1.0.0
 */
function renalinfo_register_journey_post_type() {
	$labels = array(
		'name'                  => _x( 'Journeys', 'Post type general name', 'renalinfo' ),
		'singular_name'         => _x( 'Journey', 'Post type singular name', 'renalinfo' ),
		'menu_name'             => _x( 'Journeys', 'Admin Menu text', 'renalinfo' ),
		'add_new_item'          => __( 'Add New Journey', 'renalinfo' ),
		'new_item'              => __( 'New Journey', 'renalinfo' ),
		'edit_item'             => __( 'Edit Journey', 'renalinfo' ),
		'view_item'             => __( 'View Journey', 'renalinfo' ),
		'all_items'             => __( 'All Journeys', 'renalinfo' ),
		'search_items'          => __( 'Search Journeys', 'renalinfo' ),
		'not_found'             => __( 'No journeys found.', 'renalinfo' ),
		'not_found_in_trash'    => __( 'No journeys found in Trash.', 'renalinfo' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'journey' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 6,
		'menu_icon'          => 'dashicons-arrow-right-alt',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'journey', $args );
}
add_action( 'init', 'renalinfo_register_journey_post_type' );

/**
 * Register Staff custom post type
 *
 * @since 1.0.0
 */
function renalinfo_register_staff_post_type() {
	$labels = array(
		'name'                  => _x( 'Staff', 'Post type general name', 'renalinfo' ),
		'singular_name'         => _x( 'Staff Member', 'Post type singular name', 'renalinfo' ),
		'menu_name'             => _x( 'Team', 'Admin Menu text', 'renalinfo' ),
		'add_new_item'          => __( 'Add New Staff Member', 'renalinfo' ),
		'new_item'              => __( 'New Staff Member', 'renalinfo' ),
		'edit_item'             => __( 'Edit Staff Member', 'renalinfo' ),
		'view_item'             => __( 'View Staff Member', 'renalinfo' ),
		'all_items'             => __( 'All Staff', 'renalinfo' ),
		'search_items'          => __( 'Search Staff', 'renalinfo' ),
		'not_found'             => __( 'No staff members found.', 'renalinfo' ),
		'not_found_in_trash'    => __( 'No staff members found in Trash.', 'renalinfo' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'staff' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 7,
		'menu_icon'          => 'dashicons-groups',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'staff', $args );
}
add_action( 'init', 'renalinfo_register_staff_post_type' );

/**
 * Register Medical Term custom post type
 *
 * @since 1.0.0
 */
function renalinfo_register_medical_term_post_type() {
	$labels = array(
		'name'                  => _x( 'Medical Terms', 'Post type general name', 'renalinfo' ),
		'singular_name'         => _x( 'Medical Term', 'Post type singular name', 'renalinfo' ),
		'menu_name'             => _x( 'Medical Terms', 'Admin Menu text', 'renalinfo' ),
		'add_new_item'          => __( 'Add New Medical Term', 'renalinfo' ),
		'new_item'              => __( 'New Medical Term', 'renalinfo' ),
		'edit_item'             => __( 'Edit Medical Term', 'renalinfo' ),
		'view_item'             => __( 'View Medical Term', 'renalinfo' ),
		'all_items'             => __( 'All Medical Terms', 'renalinfo' ),
		'search_items'          => __( 'Search Medical Terms', 'renalinfo' ),
		'not_found'             => __( 'No medical terms found.', 'renalinfo' ),
		'not_found_in_trash'    => __( 'No medical terms found in Trash.', 'renalinfo' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => 'edit.php?post_type=article',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'glossary' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_icon'          => 'dashicons-book',
		'supports'           => array( 'title', 'editor', 'revisions' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'medical_term', $args );
}
add_action( 'init', 'renalinfo_register_medical_term_post_type' );

/**
 * Register Support Resource custom post type
 *
 * @since 1.0.0
 */
function renalinfo_register_support_resource_post_type() {
	$labels = array(
		'name'                  => _x( 'Support Resources', 'Post type general name', 'renalinfo' ),
		'singular_name'         => _x( 'Support Resource', 'Post type singular name', 'renalinfo' ),
		'menu_name'             => _x( 'Support Resources', 'Admin Menu text', 'renalinfo' ),
		'add_new_item'          => __( 'Add New Support Resource', 'renalinfo' ),
		'new_item'              => __( 'New Support Resource', 'renalinfo' ),
		'edit_item'             => __( 'Edit Support Resource', 'renalinfo' ),
		'view_item'             => __( 'View Support Resource', 'renalinfo' ),
		'all_items'             => __( 'All Resources', 'renalinfo' ),
		'search_items'          => __( 'Search Resources', 'renalinfo' ),
		'not_found'             => __( 'No resources found.', 'renalinfo' ),
		'not_found_in_trash'    => __( 'No resources found in Trash.', 'renalinfo' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'resources' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 8,
		'menu_icon'          => 'dashicons-heart',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'support_resource', $args );
}
add_action( 'init', 'renalinfo_register_support_resource_post_type' );

/**
 * Register custom image sizes
 *
 * @since 1.0.0
 */
function renalinfo_register_image_sizes() {
	add_image_size( 'article-hero', 1200, 600, true );
	add_image_size( 'staff-profile', 400, 400, true );
	add_image_size( 'article-thumb', 300, 200, true );
}
add_action( 'after_setup_theme', 'renalinfo_register_image_sizes' );
