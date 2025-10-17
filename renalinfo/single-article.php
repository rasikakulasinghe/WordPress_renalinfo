<?php
/**
 * The template for displaying single articles
 *
 * @package RenalInfo
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main single-article-main">

	<?php
	while ( have_posts() ) :
		the_post();

		// Get article template type
		$article_template = renalinfo_get_article_template();
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-article' ); ?>>

			<header class="article-header">
				<div class="container content-wrapper">
					<?php renalinfo_breadcrumb(); ?>

					<?php renalinfo_the_audience_badge(); ?>

					<?php the_title( '<h1 class="article-title">', '</h1>' ); ?>

					<div class="article-meta">
						<span class="article-date">
							<?php
							/* translators: %s: publish date */
							printf(
								esc_html__( 'Published: %s', 'renalinfo' ),
								esc_html( get_the_date() )
							);
							?>
						</span>
						<span class="article-reading-time">
							<?php renalinfo_the_reading_time(); ?>
						</span>
					</div>

					<?php
					// Display update notice if recently updated
					renalinfo_the_update_notice();
					?>

					<?php
					// Display medical review information
					renalinfo_the_medical_review_date();
					renalinfo_the_medical_reviewer();
					?>
				</div>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="article-featured-image">
					<div class="container">
						<?php
						echo renalinfo_get_responsive_image(
							get_post_thumbnail_id(),
							'article-hero',
							array(
								'class' => 'article-hero-image',
								'alt'   => get_the_title(),
							)
						);
						?>
					</div>
				</div>
			<?php endif; ?>

			<div class="article-content-wrapper">
				<div class="container content-wrapper">

					<?php
					// Display translation notice if content not available in selected language
					renalinfo_maybe_show_translation_notice();
					?>

					<?php
					// Load template-specific content
					switch ( $article_template ) {
						case 'condition-explainer':
							get_template_part( 'template-parts/content/content', 'condition-explainer' );
							break;

						case 'treatment-procedure':
							get_template_part( 'template-parts/content/content', 'treatment-procedure' );
							break;

						case 'professional-article':
							get_template_part( 'template-parts/content/content', 'professional-article' );
							break;

						default:
							// Default article content
							?>
							<div class="article-content entry-content">
								<?php
								the_content();

								wp_link_pages(
									array(
										'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'renalinfo' ),
										'after'  => '</div>',
									)
								);
								?>
							</div>
							<?php
							break;
					}
					?>

					<?php
					// Display key takeaways if present
					renalinfo_the_key_takeaways();
					?>

					<footer class="article-footer">
						<?php
						// Display article categories
						$categories = get_the_terms( get_the_ID(), 'article_category' );
						if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
							?>
							<div class="article-categories">
								<strong><?php esc_html_e( 'Categories:', 'renalinfo' ); ?></strong>
								<?php
								$category_links = array();
								foreach ( $categories as $category ) {
									$category_links[] = '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';
								}
								echo implode( ', ', $category_links );
								?>
							</div>
						<?php endif; ?>

						<?php
						// Display version history link for editors
						if ( current_user_can( 'edit_post', get_the_ID() ) ) {
							renalinfo_the_version_history_link();
						}
						?>
					</footer>

				</div>
			</div>

			<?php
			// Related articles section
			renalinfo_the_related_articles( get_the_ID(), 3 );
			?>

			<?php
			// Support resources section
			get_template_part( 'template-parts/content/support-resources' );
			?>

		</article>

		<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;

	endwhile;
	?>

</main>

<?php
get_footer();
