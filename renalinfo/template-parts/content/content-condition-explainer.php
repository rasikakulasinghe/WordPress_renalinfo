<?php
/**
 * Template part for displaying condition explainer articles
 *
 * @package RenalInfo
 * @since 1.0.0
 */

?>

<div class="condition-explainer-content">

	<?php
	// Main article content
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
	// Display FAQ section with schema.org markup
	renalinfo_the_faq_items();
	?>

	<?php
	// Medical terms glossary section (if terms are mentioned in content)
	$content           = get_the_content();
	$mentioned_terms   = array();
	$all_medical_terms = get_posts(
		array(
			'post_type'      => 'medical_term',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		)
	);

	foreach ( $all_medical_terms as $term_post ) {
		$abbreviation = get_post_meta( $term_post->ID, '_term_abbreviation', true );
		$full_name    = get_post_meta( $term_post->ID, '_term_full_name', true );

		// Check if term is mentioned in content
		if ( ( ! empty( $abbreviation ) && stripos( $content, $abbreviation ) !== false ) ||
			( ! empty( $full_name ) && stripos( $content, $full_name ) !== false ) ) {
			$mentioned_terms[] = $term_post;
		}
	}

	if ( ! empty( $mentioned_terms ) ) :
		?>
		<div class="glossary-section">
			<h3 class="glossary-title"><?php esc_html_e( 'Medical Terms Explained', 'renalinfo' ); ?></h3>
			<dl class="glossary-list">
				<?php foreach ( $mentioned_terms as $term_post ) : ?>
					<?php
					$abbreviation        = get_post_meta( $term_post->ID, '_term_abbreviation', true );
					$full_name           = get_post_meta( $term_post->ID, '_term_full_name', true );
					$simple_definition   = get_post_meta( $term_post->ID, '_term_simple_definition', true );
					$pronunciation       = get_post_meta( $term_post->ID, '_term_pronunciation', true );
					?>
					<div class="glossary-item" id="term-<?php echo esc_attr( $term_post->ID ); ?>">
						<dt class="glossary-term">
							<?php if ( ! empty( $abbreviation ) ) : ?>
								<strong><?php echo esc_html( $abbreviation ); ?></strong>
								<?php if ( ! empty( $full_name ) ) : ?>
									<span class="glossary-full-name"> - <?php echo esc_html( $full_name ); ?></span>
								<?php endif; ?>
							<?php else : ?>
								<strong><?php echo esc_html( $full_name ); ?></strong>
							<?php endif; ?>

							<?php if ( ! empty( $pronunciation ) ) : ?>
								<span class="glossary-pronunciation">(<?php echo esc_html( $pronunciation ); ?>)</span>
							<?php endif; ?>
						</dt>
						<dd class="glossary-definition">
							<?php echo esc_html( $simple_definition ); ?>
						</dd>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>
	<?php endif; ?>

	<?php
	// Related treatments and conditions (from related articles)
	$related_articles = renalinfo_get_related_articles( get_the_ID(), 4 );
	if ( ! empty( $related_articles ) ) :
		?>
		<div class="related-treatments-section">
			<h3 class="section-subtitle"><?php esc_html_e( 'Related Information', 'renalinfo' ); ?></h3>
			<ul class="related-treatments-list">
				<?php foreach ( $related_articles as $related_article ) : ?>
					<li>
						<a href="<?php echo esc_url( get_permalink( $related_article->ID ) ); ?>">
							<?php echo esc_html( get_the_title( $related_article->ID ) ); ?>
						</a>
						<?php
						$related_audience = get_post_meta( $related_article->ID, '_audience', true );
						if ( ! empty( $related_audience ) ) :
							$audience_label = ( 'professional' === $related_audience ) ? __( 'For Professionals', 'renalinfo' ) : __( 'For Families', 'renalinfo' );
							?>
							<span class="audience-badge audience-badge-<?php echo esc_attr( $related_audience ); ?> audience-badge-small">
								<?php echo esc_html( $audience_label ); ?>
							</span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php
	// Simple diagrams or illustrations section (if featured image or gallery)
	$gallery = get_post_gallery( get_the_ID(), false );
	if ( ! empty( $gallery ) && isset( $gallery['ids'] ) ) :
		$image_ids = explode( ',', $gallery['ids'] );
		?>
		<div class="article-gallery-section">
			<h3 class="section-subtitle"><?php esc_html_e( 'Illustrations', 'renalinfo' ); ?></h3>
			<div class="article-gallery grid grid-cols-2 grid-cols-md-3">
				<?php foreach ( $image_ids as $image_id ) : ?>
					<figure class="gallery-item">
						<?php
						echo renalinfo_get_responsive_image(
							$image_id,
							'medium',
							array( 'class' => 'gallery-image' )
						);
						?>
						<?php
						$caption = wp_get_attachment_caption( $image_id );
						if ( $caption ) :
							?>
							<figcaption class="gallery-caption"><?php echo esc_html( $caption ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

</div>
