<?php
/**
 * The front page template file
 *
 * @package RenalInfo
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php
	// Hero Section
	?>
	<section class="hero-section">
		<div class="container">
			<div class="hero-content">
				<h1 class="hero-title">
					<?php
					echo esc_html(
						get_theme_mod(
							'renalinfo_hero_title',
							__( 'Paediatric Nephrology Care & Information', 'renalinfo' )
						)
					);
					?>
				</h1>
				<p class="hero-subtitle">
					<?php
					echo esc_html(
						get_theme_mod(
							'renalinfo_hero_subtitle',
							__( 'Trusted medical information for families and healthcare professionals', 'renalinfo' )
						)
					);
					?>
				</p>
				<div class="hero-cta">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'article' ) ); ?>" class="button button-primary">
						<?php esc_html_e( 'Browse Articles', 'renalinfo' ); ?>
					</a>
					<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="button button-secondary">
						<?php esc_html_e( 'About Our Clinic', 'renalinfo' ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>

	<?php
	// Category Entry Points Section
	$article_categories = get_terms(
		array(
			'taxonomy'   => 'article_category',
			'hide_empty' => true,
			'number'     => 6,
		)
	);

	if ( ! empty( $article_categories ) && ! is_wp_error( $article_categories ) ) :
		?>
		<section class="category-section">
			<div class="container">
				<h2 class="section-title"><?php esc_html_e( 'Explore by Topic', 'renalinfo' ); ?></h2>
				<div class="category-grid grid grid-cols-1 grid-cols-md-2 grid-cols-lg-3">
					<?php foreach ( $article_categories as $category ) : ?>
						<div class="category-card">
							<a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="category-card-link">
								<div class="category-card-icon">
									<span class="icon" aria-hidden="true">📚</span>
								</div>
								<h3 class="category-card-title"><?php echo esc_html( $category->name ); ?></h3>
								<p class="category-card-count">
									<?php
									/* translators: %d: number of articles */
									printf( esc_html( _n( '%d article', '%d articles', $category->count, 'renalinfo' ) ), absint( $category->count ) );
									?>
								</p>
								<?php if ( $category->description ) : ?>
									<p class="category-card-description"><?php echo esc_html( $category->description ); ?></p>
								<?php endif; ?>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// Featured Articles Section
	$featured_args = array(
		'post_type'      => 'article',
		'posts_per_page' => 3,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_query'     => array(
			array(
				'key'     => '_audience',
				'value'   => 'family',
				'compare' => '=',
			),
		),
	);

	$featured_query = new WP_Query( $featured_args );

	if ( $featured_query->have_posts() ) :
		?>
		<section class="featured-articles-section">
			<div class="container">
				<h2 class="section-title"><?php esc_html_e( 'Recent Family Articles', 'renalinfo' ); ?></h2>
				<div class="featured-articles-grid grid grid-cols-1 grid-cols-md-2 grid-cols-lg-3">
					<?php
					while ( $featured_query->have_posts() ) :
						$featured_query->the_post();
						?>
						<article class="article-card">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>" class="article-card-thumbnail">
									<?php the_post_thumbnail( 'article-thumb' ); ?>
								</a>
							<?php endif; ?>
							<div class="article-card-content">
								<?php renalinfo_the_audience_badge(); ?>
								<h3 class="article-card-title">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h3>
								<?php if ( has_excerpt() ) : ?>
									<p class="article-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
								<?php endif; ?>
								<div class="article-card-meta">
									<span class="reading-time">
										<?php renalinfo_the_reading_time(); ?>
									</span>
								</div>
							</div>
						</article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
				<div class="section-cta">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'article' ) ); ?>" class="button button-outline">
						<?php esc_html_e( 'View All Articles', 'renalinfo' ); ?>
					</a>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// Patient Journey Modules Section
	$journey_args = array(
		'post_type'      => 'journey',
		'posts_per_page' => 3,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$journey_query = new WP_Query( $journey_args );

	if ( $journey_query->have_posts() ) :
		?>
		<section class="journey-section">
			<div class="container">
				<h2 class="section-title"><?php esc_html_e( 'Guided Learning Journeys', 'renalinfo' ); ?></h2>
				<p class="section-description">
					<?php esc_html_e( 'Follow step-by-step guides through important topics', 'renalinfo' ); ?>
				</p>
				<div class="journey-grid grid grid-cols-1 grid-cols-md-2 grid-cols-lg-3">
					<?php
					while ( $journey_query->have_posts() ) :
						$journey_query->the_post();
						$estimated_time = get_post_meta( get_the_ID(), '_estimated_time', true );
						$article_ids    = get_post_meta( get_the_ID(), '_journey_articles', true );
						$article_count  = ! empty( $article_ids ) ? count( renalinfo_sanitize_ids( $article_ids ) ) : 0;
						?>
						<article class="journey-card">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>" class="journey-card-thumbnail">
									<?php the_post_thumbnail( 'article-thumb' ); ?>
								</a>
							<?php endif; ?>
							<div class="journey-card-content">
								<h3 class="journey-card-title">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h3>
								<?php if ( has_excerpt() ) : ?>
									<p class="journey-card-excerpt"><?php the_excerpt(); ?></p>
								<?php endif; ?>
								<div class="journey-card-meta">
									<?php if ( $article_count > 0 ) : ?>
										<span class="journey-articles">
											<?php
											/* translators: %d: number of articles */
											printf( esc_html( _n( '%d article', '%d articles', $article_count, 'renalinfo' ) ), absint( $article_count ) );
											?>
										</span>
									<?php endif; ?>
									<?php if ( ! empty( $estimated_time ) ) : ?>
										<span class="journey-time">
											<?php
											/* translators: %d: estimated time in minutes */
											printf( esc_html__( '%d min', 'renalinfo' ), absint( $estimated_time ) );
											?>
										</span>
									<?php endif; ?>
								</div>
							</div>
						</article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// For Professionals Section
	$professional_args = array(
		'post_type'      => 'article',
		'posts_per_page' => 3,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_query'     => array(
			array(
				'key'     => '_audience',
				'value'   => 'professional',
				'compare' => '=',
			),
		),
	);

	$professional_query = new WP_Query( $professional_args );

	if ( $professional_query->have_posts() ) :
		?>
		<section class="professional-section">
			<div class="container">
				<h2 class="section-title"><?php esc_html_e( 'For Healthcare Professionals', 'renalinfo' ); ?></h2>
				<div class="professional-grid grid grid-cols-1 grid-cols-md-3">
					<?php
					while ( $professional_query->have_posts() ) :
						$professional_query->the_post();
						?>
						<article class="article-card article-card-professional">
							<div class="article-card-content">
								<?php renalinfo_the_audience_badge(); ?>
								<h3 class="article-card-title">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h3>
								<?php if ( has_excerpt() ) : ?>
									<p class="article-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 15 ) ); ?></p>
								<?php endif; ?>
							</div>
						</article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// Call to Action Section
	?>
	<section class="cta-section">
		<div class="container">
			<div class="cta-content">
				<h2 class="cta-title"><?php esc_html_e( 'Need Medical Advice?', 'renalinfo' ); ?></h2>
				<p class="cta-description">
					<?php esc_html_e( 'This website provides information only. For medical advice, please contact our clinic.', 'renalinfo' ); ?>
				</p>
				<a href="tel:<?php echo esc_attr( get_theme_mod( 'renalinfo_phone_number', '+94112345678' ) ); ?>" class="button button-large button-primary">
					<?php esc_html_e( 'Call Us', 'renalinfo' ); ?>
				</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
