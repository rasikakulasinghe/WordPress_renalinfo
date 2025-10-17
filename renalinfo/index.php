<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package RenalInfo
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php
	if ( have_posts() ) {
		// Start the Loop.
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content/content', get_post_type() );
		}

		// Pagination.
		the_posts_pagination(
			array(
				'mid_size'  => 2,
				'prev_text' => __( '&larr; Previous', 'renalinfo' ),
				'next_text' => __( 'Next &rarr;', 'renalinfo' ),
			)
		);
	} else {
		// No content found.
		get_template_part( 'template-parts/content/content', 'none' );
	}
	?>
</main><!-- #primary -->

<?php
get_footer();
