<?php
/**
 * Template part for displaying treatment and procedure articles
 *
 * @package RenalInfo
 * @since 1.0.0
 */

?>

<div class="treatment-procedure-content">

	<?php
	// Main article content with step-by-step procedures
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
	// Before/During/After sections (if custom fields exist)
	$before_procedure = get_post_meta( get_the_ID(), '_procedure_before', true );
	$during_procedure = get_post_meta( get_the_ID(), '_procedure_during', true );
	$after_procedure  = get_post_meta( get_the_ID(), '_procedure_after', true );

	if ( ! empty( $before_procedure ) || ! empty( $during_procedure ) || ! empty( $after_procedure ) ) :
		?>
		<div class="procedure-timeline">
			<?php if ( ! empty( $before_procedure ) ) : ?>
				<div class="procedure-phase procedure-before">
					<h3 class="procedure-phase-title">
						<span class="procedure-icon" aria-hidden="true">📋</span>
						<?php esc_html_e( 'Before the Procedure', 'renalinfo' ); ?>
					</h3>
					<div class="procedure-phase-content">
						<?php echo wp_kses_post( wpautop( $before_procedure ) ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $during_procedure ) ) : ?>
				<div class="procedure-phase procedure-during">
					<h3 class="procedure-phase-title">
						<span class="procedure-icon" aria-hidden="true">⚕️</span>
						<?php esc_html_e( 'During the Procedure', 'renalinfo' ); ?>
					</h3>
					<div class="procedure-phase-content">
						<?php echo wp_kses_post( wpautop( $during_procedure ) ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $after_procedure ) ) : ?>
				<div class="procedure-phase procedure-after">
					<h3 class="procedure-phase-title">
						<span class="procedure-icon" aria-hidden="true">✅</span>
						<?php esc_html_e( 'After the Procedure', 'renalinfo' ); ?>
					</h3>
					<div class="procedure-phase-content">
						<?php echo wp_kses_post( wpautop( $after_procedure ) ); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php
	// Display FAQ section
	renalinfo_the_faq_items();
	?>

	<?php
	// Expected outcomes and recovery timeline
	$expected_outcomes = get_post_meta( get_the_ID(), '_expected_outcomes', true );
	$recovery_timeline = get_post_meta( get_the_ID(), '_recovery_timeline', true );

	if ( ! empty( $expected_outcomes ) || ! empty( $recovery_timeline ) ) :
		?>
		<div class="outcomes-recovery-section">
			<?php if ( ! empty( $expected_outcomes ) ) : ?>
				<div class="expected-outcomes">
					<h3 class="section-subtitle"><?php esc_html_e( 'What to Expect', 'renalinfo' ); ?></h3>
					<div class="outcomes-content">
						<?php echo wp_kses_post( wpautop( $expected_outcomes ) ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $recovery_timeline ) ) : ?>
				<div class="recovery-timeline">
					<h3 class="section-subtitle"><?php esc_html_e( 'Recovery Timeline', 'renalinfo' ); ?></h3>
					<div class="timeline-content">
						<?php echo wp_kses_post( wpautop( $recovery_timeline ) ); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php
	// Risks and complications section
	$risks = get_post_meta( get_the_ID(), '_procedure_risks', true );
	if ( ! empty( $risks ) ) :
		?>
		<div class="risks-section">
			<h3 class="section-subtitle"><?php esc_html_e( 'Possible Risks and Complications', 'renalinfo' ); ?></h3>
			<div class="risks-content notice notice-warning">
				<?php echo wp_kses_post( wpautop( $risks ) ); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php
	// When to contact your doctor
	$contact_info = get_post_meta( get_the_ID(), '_when_to_contact', true );
	if ( ! empty( $contact_info ) ) :
		?>
		<div class="contact-doctor-section">
			<h3 class="section-subtitle"><?php esc_html_e( 'When to Contact Your Doctor', 'renalinfo' ); ?></h3>
			<div class="contact-info notice notice-info">
				<?php echo wp_kses_post( wpautop( $contact_info ) ); ?>
			</div>
		</div>
	<?php endif; ?>

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
					$abbreviation      = get_post_meta( $term_post->ID, '_term_abbreviation', true );
					$full_name         = get_post_meta( $term_post->ID, '_term_full_name', true );
					$simple_definition = get_post_meta( $term_post->ID, '_term_simple_definition', true );
					$pronunciation     = get_post_meta( $term_post->ID, '_term_pronunciation', true );
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

</div>
