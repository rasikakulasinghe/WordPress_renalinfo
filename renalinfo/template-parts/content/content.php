<?php
/**
 * Template part for displaying post content in the loop
 *
 * @package RenalInfo
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-card' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="article-thumbnail">
			<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
				<?php
				the_post_thumbnail(
					'medium',
					array(
						'class' => 'article-image',
						'alt'   => the_title_attribute( array( 'echo' => false ) ),
					)
				);
				?>
			</a>
		</div>
	<?php endif; ?>

	<div class="article-content-wrapper">

		<?php
		// Display audience badge
		renalinfo_the_audience_badge();
		?>

		<?php
		// Post title
		the_title(
			sprintf( '<h2 class="article-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
			'</a></h2>'
		);
		?>

		<div class="article-meta">
			<?php
			// Post date
			?>
			<time class="article-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
				<?php echo esc_html( get_the_date() ); ?>
			</time>

			<?php
			// Reading time
			renalinfo_the_reading_time();
			?>

			<?php
			// Categories
			$categories = get_the_terms( get_the_ID(), 'article_category' );
			if ( $categories && ! is_wp_error( $categories ) ) :
				?>
				<span class="article-categories">
					<?php
					foreach ( $categories as $category ) {
						echo '<a href="' . esc_url( get_term_link( $category ) ) . '" class="article-category">' . esc_html( $category->name ) . '</a>';
					}
					?>
				</span>
			<?php endif; ?>
		</div>

		<?php
		// Post excerpt
		if ( has_excerpt() ) :
			?>
			<div class="article-excerpt">
				<?php the_excerpt(); ?>
			</div>
		<?php else : ?>
			<div class="article-excerpt">
				<?php echo esc_html( wp_trim_words( get_the_content(), 30, '...' ) ); ?>
			</div>
		<?php endif; ?>

		<?php
		// Read more link
		?>
		<a href="<?php the_permalink(); ?>" class="read-more-link btn btn-secondary">
			<?php esc_html_e( 'Read More', 'renalinfo' ); ?>
			<span class="screen-reader-text"><?php the_title(); ?></span>
		</a>

	</div>

</article>
