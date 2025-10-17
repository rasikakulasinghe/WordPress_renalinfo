<?php
/**
 * Template part for displaying professional articles
 *
 * @package RenalInfo
 * @since 1.0.0
 */

?>

<div class="professional-article-content">

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
	// Clinical guidelines and recommendations
	$guidelines = get_post_meta( get_the_ID(), '_clinical_guidelines', true );
	if ( ! empty( $guidelines ) ) :
		?>
		<div class="clinical-guidelines-section">
			<h3 class="section-subtitle"><?php esc_html_e( 'Clinical Guidelines', 'renalinfo' ); ?></h3>
			<div class="guidelines-content professional-content">
				<?php echo wp_kses_post( wpautop( $guidelines ) ); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php
	// Evidence summary
	$evidence = get_post_meta( get_the_ID(), '_evidence_summary', true );
	if ( ! empty( $evidence ) ) :
		?>
		<div class="evidence-summary-section">
			<h3 class="section-subtitle"><?php esc_html_e( 'Evidence Summary', 'renalinfo' ); ?></h3>
			<div class="evidence-content professional-content">
				<?php echo wp_kses_post( wpautop( $evidence ) ); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php
	// Display FAQ section
	renalinfo_the_faq_items();
	?>

	<?php
	// References and citations
	$references = get_post_meta( get_the_ID(), '_references', true );
	if ( ! empty( $references ) ) :
		?>
		<div class="references-section">
			<h3 class="section-subtitle"><?php esc_html_e( 'References', 'renalinfo' ); ?></h3>
			<ol class="references-list">
				<?php
				$references_array = explode( "\n", $references );
				foreach ( $references_array as $reference ) :
					$reference = trim( $reference );
					if ( ! empty( $reference ) ) :
						?>
						<li class="reference-item"><?php echo wp_kses_post( $reference ); ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ol>
		</div>
	<?php endif; ?>

	<?php
	// Related research and resources
	$related_research = get_post_meta( get_the_ID(), '_related_research', true );
	if ( ! empty( $related_research ) ) :
		?>
		<div class="related-research-section">
			<h3 class="section-subtitle"><?php esc_html_e( 'Related Research', 'renalinfo' ); ?></h3>
			<div class="research-content professional-content">
				<?php echo wp_kses_post( wpautop( $related_research ) ); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php
	// Medical terms glossary section (technical definitions)
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

		if ( ( ! empty( $abbreviation ) && stripos( $content, $abbreviation ) !== false ) ||
			( ! empty( $full_name ) && stripos( $content, $full_name ) !== false ) ) {
			$mentioned_terms[] = $term_post;
		}
	}

	if ( ! empty( $mentioned_terms ) ) :
		?>
		<div class="glossary-section">
			<h3 class="glossary-title"><?php esc_html_e( 'Medical Terminology', 'renalinfo' ); ?></h3>
			<dl class="glossary-list professional-glossary">
				<?php foreach ( $mentioned_terms as $term_post ) : ?>
					<?php
					$abbreviation         = get_post_meta( $term_post->ID, '_term_abbreviation', true );
					$full_name            = get_post_meta( $term_post->ID, '_term_full_name', true );
					$technical_definition = get_post_meta( $term_post->ID, '_term_technical_definition', true );
					$simple_definition    = get_post_meta( $term_post->ID, '_term_simple_definition', true );
					$pronunciation        = get_post_meta( $term_post->ID, '_term_pronunciation', true );
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
							<?php
							// Use technical definition for professional articles, fallback to simple
							$definition = ! empty( $technical_definition ) ? $technical_definition : $simple_definition;
							echo esc_html( $definition );
							?>
						</dd>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>
	<?php endif; ?>

	<?php
	// Practice points or key takeaways for professionals
	$practice_points = get_post_meta( get_the_ID(), '_practice_points', true );
	if ( ! empty( $practice_points ) ) :
		?>
		<div class="practice-points-section">
			<h3 class="section-subtitle"><?php esc_html_e( 'Key Practice Points', 'renalinfo' ); ?></h3>
			<div class="practice-points-content professional-content">
				<?php echo wp_kses_post( wpautop( $practice_points ) ); ?>
			</div>
		</div>
	<?php endif; ?>

</div>
