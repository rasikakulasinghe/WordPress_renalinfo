<?php
/**
 * The archive template file
 *
 * @package RenalInfo
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main archive-main">

	<div class="container">

		<?php if ( have_posts() ) : ?>

			<header class="archive-header">
				<?php
				the_archive_title( '<h1 class="archive-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>

				<?php
				// Breadcrumb navigation
				renalinfo_breadcrumb();
				?>

				<?php
				// Audience filter for article archives
				if ( is_post_type_archive( 'article' ) || ( is_tax() && get_queried_object()->taxonomy === 'article_category' ) ) :
					?>
					<div class="archive-filters">
						<form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'article' ) ); ?>" class="audience-filter-form">
							<label for="audience-filter" class="filter-label">
								<?php esc_html_e( 'Show content for:', 'renalinfo' ); ?>
							</label>
							<select name="audience" id="audience-filter" class="filter-select">
								<option value=""><?php esc_html_e( 'All Audiences', 'renalinfo' ); ?></option>
								<option value="family" <?php selected( isset( $_GET['audience'] ) ? $_GET['audience'] : '', 'family' ); ?>>
									<?php esc_html_e( 'Families', 'renalinfo' ); ?>
								</option>
								<option value="professional" <?php selected( isset( $_GET['audience'] ) ? $_GET['audience'] : '', 'professional' ); ?>>
									<?php esc_html_e( 'Healthcare Professionals', 'renalinfo' ); ?>
								</option>
							</select>
							<button type="submit" class="button button-small">
								<?php esc_html_e( 'Filter', 'renalinfo' ); ?>
							</button>
						</form>
					</div>
				<?php endif; ?>
			</header>

			<div class="archive-content">
				<div class="articles-list">

					<?php
					while ( have_posts() ) :
						the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content/content', get_post_type() );

					endwhile;
					?>

				</div>

				<?php
				// Pagination
				the_posts_pagination(
					array(
						'mid_size'           => 2,
						'prev_text'          => __( '&larr; Previous', 'renalinfo' ),
						'next_text'          => __( 'Next &rarr;', 'renalinfo' ),
						'screen_reader_text' => __( 'Articles navigation', 'renalinfo' ),
					)
				);
				?>
			</div>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content/content', 'none' ); ?>

		<?php endif; ?>

	</div>

</main>

<?php
get_footer();
