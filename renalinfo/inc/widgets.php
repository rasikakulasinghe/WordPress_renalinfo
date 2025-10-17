<?php
/**
 * Custom Widgets
 *
 * @package RenalInfo
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register widget areas
 *
 * @since 1.0.0
 */
function renalinfo_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Widget Area', 'renalinfo' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'renalinfo' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'renalinfo_widgets_init' );
