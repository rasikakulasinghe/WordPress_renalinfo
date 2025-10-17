<?php
/**
 * Template part for displaying support resources
 *
 * @package RenalInfo
 * @since 1.0.0
 */

// Get related support resources for the current article
$article_id = get_the_ID();
$categories = wp_get_post_terms( $article_id, 'article_category', array( 'fields' => 'ids' ) );

// Query for support resources related to the current article's categories
$support_query = new WP_Query(
	array(
		'post_type'      => 'support_resource',
		'posts_per_page' => 4,
		'post_status'    => 'publish',
		'tax_query'      => array(
			array(
				'taxonomy' => 'resource_type',
				'field'    => 'slug',
				'terms'    => array( 'support-group', 'helpline', 'educational-material', 'financial-assistance' ),
			),
		),
	)
);

if ( $support_query->have_posts() ) :
	?>
	<div class="support-resources-section">
		<h3 class="section-title"><?php esc_html_e( 'Support Resources', 'renalinfo' ); ?></h3>
		<p class="section-description">
			<?php esc_html_e( 'Additional resources that may help you and your family:', 'renalinfo' ); ?>
		</p>

		<div class="support-resources-grid grid grid-cols-1 grid-cols-md-2">
			<?php
			while ( $support_query->have_posts() ) :
				$support_query->the_post();
				$resource_type  = wp_get_post_terms( get_the_ID(), 'resource_type', array( 'fields' => 'names' ) );
				$contact_info   = get_post_meta( get_the_ID(), '_resource_contact', true );
				$resource_url   = get_post_meta( get_the_ID(), '_resource_url', true );
				$resource_phone = get_post_meta( get_the_ID(), '_resource_phone', true );
				$resource_email = get_post_meta( get_the_ID(), '_resource_email', true );
				?>
				<div class="support-resource-card card">
					<?php if ( ! empty( $resource_type ) ) : ?>
						<div class="resource-type">
							<?php echo esc_html( $resource_type[0] ); ?>
						</div>
					<?php endif; ?>

					<h4 class="resource-title">
						<?php
						if ( ! empty( $resource_url ) ) :
							?>
							<a href="<?php echo esc_url( $resource_url ); ?>" target="_blank" rel="noopener noreferrer">
								<?php the_title(); ?>
								<span class="screen-reader-text"><?php esc_html_e( '(opens in new window)', 'renalinfo' ); ?></span>
							</a>
						<?php else : ?>
							<?php the_title(); ?>
						<?php endif; ?>
					</h4>

					<?php if ( has_excerpt() ) : ?>
						<div class="resource-description">
							<?php the_excerpt(); ?>
						</div>
					<?php endif; ?>

					<div class="resource-contact-info">
						<?php if ( ! empty( $resource_phone ) ) : ?>
							<div class="resource-contact-item">
								<span class="contact-icon" aria-hidden="true">📞</span>
								<a href="tel:<?php echo esc_attr( str_replace( array( ' ', '-', '(', ')' ), '', $resource_phone ) ); ?>">
									<?php echo esc_html( $resource_phone ); ?>
								</a>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $resource_email ) ) : ?>
							<div class="resource-contact-item">
								<span class="contact-icon" aria-hidden="true">✉️</span>
								<a href="mailto:<?php echo esc_attr( $resource_email ); ?>">
									<?php echo esc_html( $resource_email ); ?>
								</a>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $resource_url ) ) : ?>
							<div class="resource-contact-item">
								<span class="contact-icon" aria-hidden="true">🌐</span>
								<a href="<?php echo esc_url( $resource_url ); ?>" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'Visit Website', 'renalinfo' ); ?>
									<span class="screen-reader-text"><?php esc_html_e( '(opens in new window)', 'renalinfo' ); ?></span>
								</a>
							</div>
						<?php endif; ?>
					</div>

					<?php
					// Languages supported
					$languages = get_post_meta( get_the_ID(), '_resource_languages', true );
					if ( ! empty( $languages ) && is_array( $languages ) ) :
						?>
						<div class="resource-languages">
							<strong><?php esc_html_e( 'Languages:', 'renalinfo' ); ?></strong>
							<?php echo esc_html( implode( ', ', $languages ) ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>

		<?php
		// Link to all support resources
		$support_archive_url = get_post_type_archive_link( 'support_resource' );
		if ( $support_archive_url ) :
			?>
			<div class="view-all-resources">
				<a href="<?php echo esc_url( $support_archive_url ); ?>" class="btn btn-secondary">
					<?php esc_html_e( 'View All Support Resources', 'renalinfo' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
	<?php
endif;
?>
