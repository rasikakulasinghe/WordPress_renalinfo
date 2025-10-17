<?php
/**
 * The template for displaying search results pages
 *
 * @package RenalInfo
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main search-results">

	<header class="page-header search-header">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<h1 class="page-title">
					<?php
					/* translators: %s: search query */
					printf( esc_html__( 'Search Results for: %s', 'renalinfo' ), '<span class="search-query">' . get_search_query() . '</span>' );
					?>
				</h1>
				
				<p class="search-results-count">
					<?php
					global $wp_query;
					/* translators: %s: number of results */
					printf(
						esc_html( _n( '%s result found', '%s results found', $wp_query->found_posts, 'renalinfo' ) ),
						'<strong>' . number_format_i18n( $wp_query->found_posts ) . '</strong>'
					);
					?>
				</p>

				<?php if ( function_exists( 'pll_current_language' ) ) : ?>
					<div class="search-language-filter">
						<?php
						$current_lang = pll_current_language();
						$show_all = isset( $_GET['all_languages'] ) && $_GET['all_languages'] === '1';
						?>
						<p class="filter-label">
							<?php
							if ( $show_all ) {
								esc_html_e( 'Showing results in all languages', 'renalinfo' );
							} else {
								printf(
									/* translators: %s: language name */
									esc_html__( 'Showing results in %s', 'renalinfo' ),
									'<strong>' . esc_html( renalinfo_get_language_name( $current_lang ) ) . '</strong>'
								);
							}
							?>
						</p>
						<a href="<?php echo esc_url( add_query_arg( 'all_languages', $show_all ? '0' : '1' ) ); ?>" class="btn btn-secondary btn-sm">
							<?php
							if ( $show_all ) {
								printf(
									/* translators: %s: language name */
									esc_html__( 'Show only %s', 'renalinfo' ),
									esc_html( renalinfo_get_language_name( $current_lang ) )
								);
							} else {
								esc_html_e( 'Show all languages', 'renalinfo' );
							}
							?>
						</a>
					</div>
				<?php endif; ?>

			<?php else : ?>
				<h1 class="page-title">
					<?php esc_html_e( 'Nothing Found', 'renalinfo' ); ?>
				</h1>
			<?php endif; ?>
		</div>
	</header><!-- .page-header -->

	<div class="container">

		<?php if ( have_posts() ) : ?>

			<div class="search-results-list">

				<?php
				while ( have_posts() ) :
					the_post();
					
					// Get post language for badge display.
					$post_lang = renalinfo_get_post_language( get_the_ID() );
					$current_lang = function_exists( 'pll_current_language' ) ? pll_current_language() : 'en';
					$is_different_lang = $post_lang && $post_lang !== $current_lang;
					?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result-item' ); ?>>

						<div class="search-result-content">

							<?php if ( has_post_thumbnail() ) : ?>
								<div class="search-result-thumbnail">
									<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
										<?php the_post_thumbnail( 'thumbnail' ); ?>
									</a>
								</div>
							<?php endif; ?>

							<div class="search-result-details">

								<div class="search-result-meta">
									<span class="post-type-badge">
										<?php
										$post_type_obj = get_post_type_object( get_post_type() );
										echo esc_html( $post_type_obj->labels->singular_name );
										?>
									</span>

									<?php
									// Display audience badge for articles.
									if ( 'article' === get_post_type() ) {
										renalinfo_the_audience_badge();
									}
									?>

									<?php if ( $is_different_lang ) : ?>
										<span class="language-badge" lang="<?php echo esc_attr( $post_lang ); ?>">
											<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
												<path d="M8 1C4.13 1 1 4.13 1 8C1 11.87 4.13 15 8 15C11.87 15 15 11.87 15 8C15 4.13 11.87 1 8 1ZM13.03 5H11.18C10.98 4.21 10.69 3.45 10.31 2.74C11.53 3.16 12.58 3.97 13.03 5ZM8 2.04C8.68 2.81 9.2 3.75 9.53 4.79H6.47C6.8 3.75 7.32 2.81 8 2.04ZM2.21 9.5C2.07 9.01 2 8.51 2 8C2 7.49 2.07 6.99 2.21 6.5H4.32C4.27 6.99 4.24 7.49 4.24 8C4.24 8.51 4.27 9.01 4.32 9.5H2.21ZM2.97 11H4.82C5.02 11.79 5.31 12.55 5.69 13.26C4.47 12.84 3.42 12.03 2.97 11ZM4.82 5H2.97C3.42 3.97 4.47 3.16 5.69 2.74C5.31 3.45 5.02 4.21 4.82 5ZM8 13.96C7.32 13.19 6.8 12.25 6.47 11.21H9.53C9.2 12.25 8.68 13.19 8 13.96ZM9.84 9.5H6.16C6.11 9.01 6.08 8.51 6.08 8C6.08 7.49 6.11 6.99 6.16 6.5H9.84C9.89 6.99 9.92 7.49 9.92 8C9.92 8.51 9.89 9.01 9.84 9.5ZM10.31 13.26C10.69 12.55 10.98 11.79 11.18 11H13.03C12.58 12.03 11.53 12.84 10.31 13.26ZM11.68 9.5C11.73 9.01 11.76 8.51 11.76 8C11.76 7.49 11.73 6.99 11.68 6.5H13.79C13.93 6.99 14 7.49 14 8C14 8.51 13.93 9.01 13.79 9.5H11.68Z" fill="currentColor"/>
											</svg>
											<?php echo esc_html( renalinfo_get_language_native_name( $post_lang ) ); ?>
										</span>
									<?php endif; ?>
								</div>

								<header class="search-result-header">
									<?php the_title( sprintf( '<h2 class="search-result-title"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								</header>

								<div class="search-result-excerpt">
									<?php
									if ( has_excerpt() ) {
										the_excerpt();
									} else {
										echo '<p>' . wp_kses_post( wp_trim_words( get_the_content(), 30 ) ) . '</p>';
									}
									?>
								</div>

								<div class="search-result-footer">
									<time class="search-result-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
										<?php echo esc_html( get_the_date() ); ?>
									</time>

									<?php if ( 'article' === get_post_type() ) : ?>
										<span class="search-result-reading-time">
											<?php renalinfo_the_reading_time(); ?>
										</span>
									<?php endif; ?>
								</div>

							</div><!-- .search-result-details -->

						</div><!-- .search-result-content -->

					</article><!-- .search-result-item -->

				<?php endwhile; ?>

			</div><!-- .search-results-list -->

			<?php
			// Pagination.
			the_posts_pagination(
				array(
					'mid_size'           => 2,
					'prev_text'          => __( 'Previous', 'renalinfo' ),
					'next_text'          => __( 'Next', 'renalinfo' ),
					'screen_reader_text' => __( 'Search results navigation', 'renalinfo' ),
				)
			);
			?>

		<?php else : ?>

			<div class="no-results not-found">
				<div class="page-content">
					<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'renalinfo' ); ?></p>

					<?php get_search_form(); ?>

					<div class="search-suggestions">
						<h3><?php esc_html_e( 'Suggestions:', 'renalinfo' ); ?></h3>
						<ul>
							<li><?php esc_html_e( 'Make sure all words are spelled correctly', 'renalinfo' ); ?></li>
							<li><?php esc_html_e( 'Try different keywords', 'renalinfo' ); ?></li>
							<li><?php esc_html_e( 'Try more general keywords', 'renalinfo' ); ?></li>
							<?php if ( function_exists( 'pll_current_language' ) && ! isset( $_GET['all_languages'] ) ) : ?>
								<li>
									<a href="<?php echo esc_url( add_query_arg( 'all_languages', '1' ) ); ?>">
										<?php esc_html_e( 'Try searching in all languages', 'renalinfo' ); ?>
									</a>
								</li>
							<?php endif; ?>
						</ul>
					</div>

					<?php if ( function_exists( 'renalinfo_the_popular_articles' ) ) : ?>
						<div class="popular-articles">
							<h3><?php esc_html_e( 'Popular Articles', 'renalinfo' ); ?></h3>
							<?php renalinfo_the_popular_articles( 5 ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

		<?php endif; ?>

	</div><!-- .container -->

</main><!-- #primary -->

<?php
get_footer();
