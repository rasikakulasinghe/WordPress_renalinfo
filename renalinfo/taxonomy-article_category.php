<?php
/**
 * Template for displaying article category taxonomy archives
 *
 * @package RenalInfo
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main taxonomy-archive">

	<div class="container">

		<?php
		// Breadcrumb navigation
		renalinfo_breadcrumb();
		?>

		<header class="archive-header taxonomy-header">
			<?php
			$term = get_queried_object();
			?>
			<h1 class="archive-title taxonomy-title">
				<?php echo esc_html( $term->name ); ?>
			</h1>

			<?php if ( ! empty( $term->description ) ) : ?>
				<div class="archive-description taxonomy-description">
					<?php echo wp_kses_post( wpautop( $term->description ) ); ?>
				</div>
			<?php endif; ?>

			<?php
			// Article count
			$article_count = $term->count;
			?>
			<div class="taxonomy-meta">
				<span class="article-count">
					<?php
					printf(
						/* translators: %s: number of articles */
						esc_html( _n( '%s article', '%s articles', $article_count, 'renalinfo' ) ),
						esc_html( number_format_i18n( $article_count ) )
					);
					?>
				</span>
			</div>
		</header>

		<?php
		// Audience filter form
		?>
		<div class="taxonomy-filters">
			<form method="get" class="audience-filter-form" role="search">
				<label for="audience-filter">
					<?php esc_html_e( 'Filter by Audience:', 'renalinfo' ); ?>
				</label>
				<select name="audience" id="audience-filter" class="audience-select">
					<option value="">
						<?php esc_html_e( 'All Audiences', 'renalinfo' ); ?>
					</option>
					<option value="family" <?php selected( isset( $_GET['audience'] ) && 'family' === $_GET['audience'] ); ?>>
						<?php esc_html_e( 'For Families', 'renalinfo' ); ?>
					</option>
					<option value="professional" <?php selected( isset( $_GET['audience'] ) && 'professional' === $_GET['audience'] ); ?>>
						<?php esc_html_e( 'For Professionals', 'renalinfo' ); ?>
					</option>
				</select>
				<button type="submit" class="btn btn-primary">
					<?php esc_html_e( 'Apply Filter', 'renalinfo' ); ?>
				</button>
			</form>

			<?php
			// Reading level filter
			?>
			<form method="get" class="reading-level-filter-form" role="search">
				<?php if ( isset( $_GET['audience'] ) ) : ?>
					<input type="hidden" name="audience" value="<?php echo esc_attr( $_GET['audience'] ); ?>">
				<?php endif; ?>
				<label for="reading-level-filter">
					<?php esc_html_e( 'Reading Level:', 'renalinfo' ); ?>
				</label>
				<select name="reading_level" id="reading-level-filter" class="reading-level-select">
					<option value="">
						<?php esc_html_e( 'All Levels', 'renalinfo' ); ?>
					</option>
					<option value="8-9" <?php selected( isset( $_GET['reading_level'] ) && '8-9' === $_GET['reading_level'] ); ?>>
						<?php esc_html_e( 'Grade 8-9 (Simple)', 'renalinfo' ); ?>
					</option>
					<option value="10-12" <?php selected( isset( $_GET['reading_level'] ) && '10-12' === $_GET['reading_level'] ); ?>>
						<?php esc_html_e( 'Grade 10-12 (Moderate)', 'renalinfo' ); ?>
					</option>
					<option value="college" <?php selected( isset( $_GET['reading_level'] ) && 'college' === $_GET['reading_level'] ); ?>>
						<?php esc_html_e( 'College+ (Technical)', 'renalinfo' ); ?>
					</option>
				</select>
				<button type="submit" class="btn btn-primary">
					<?php esc_html_e( 'Apply Filter', 'renalinfo' ); ?>
				</button>
			</form>
		</div>

		<?php
		// Apply filters to the query
		add_filter(
			'pre_get_posts',
			function( $query ) {
				if ( ! is_admin() && $query->is_main_query() && is_tax( 'article_category' ) ) {
					$meta_query = array();

					if ( isset( $_GET['audience'] ) && ! empty( $_GET['audience'] ) ) {
						$meta_query[] = array(
							'key'   => '_audience',
							'value' => sanitize_text_field( $_GET['audience'] ),
						);
					}

					if ( isset( $_GET['reading_level'] ) && ! empty( $_GET['reading_level'] ) ) {
						$meta_query[] = array(
							'key'   => '_reading_level',
							'value' => sanitize_text_field( $_GET['reading_level'] ),
						);
					}

					if ( ! empty( $meta_query ) ) {
						$query->set( 'meta_query', $meta_query );
					}
				}
			}
		);
		?>

		<?php if ( have_posts() ) : ?>

			<div class="taxonomy-articles articles-list">

				<?php
				while ( have_posts() ) :
					the_post();
					?>

					<?php
					// Load the article card template
					get_template_part( 'template-parts/content/content', get_post_type() );
					?>

				<?php endwhile; ?>

			</div>

			<?php
			// Pagination
			the_posts_pagination(
				array(
					'mid_size'           => 2,
					'prev_text'          => __( '&laquo; Previous', 'renalinfo' ),
					'next_text'          => __( 'Next &raquo;', 'renalinfo' ),
					'screen_reader_text' => __( 'Articles navigation', 'renalinfo' ),
				)
			);
			?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content/content', 'none' ); ?>

		<?php endif; ?>

		<?php
		// Related categories (sibling categories)
		$current_term   = get_queried_object();
		$related_terms  = get_terms(
			array(
				'taxonomy'   => 'article_category',
				'exclude'    => array( $current_term->term_id ),
				'number'     => 6,
				'hide_empty' => true,
			)
		);

		if ( ! empty( $related_terms ) && ! is_wp_error( $related_terms ) ) :
			?>
			<div class="related-categories-section">
				<h2 class="section-title"><?php esc_html_e( 'Other Categories', 'renalinfo' ); ?></h2>
				<div class="category-grid grid grid-cols-2 grid-cols-md-3">
					<?php foreach ( $related_terms as $related_term ) : ?>
						<a href="<?php echo esc_url( get_term_link( $related_term ) ); ?>" class="category-card card">
							<h3 class="category-name"><?php echo esc_html( $related_term->name ); ?></h3>
							<span class="category-count">
								<?php
								printf(
									/* translators: %s: number of articles */
									esc_html( _n( '%s article', '%s articles', $related_term->count, 'renalinfo' ) ),
									esc_html( number_format_i18n( $related_term->count ) )
								);
								?>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

	</div><!-- .container -->

</main><!-- #primary -->

<?php
get_footer();
