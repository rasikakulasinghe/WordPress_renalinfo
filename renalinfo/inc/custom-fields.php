<?php
/**
 * Custom Fields Registration
 *
 * @package RenalInfo
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom meta boxes for Article post type
 *
 * @since 1.0.0
 */
function renalinfo_add_article_meta_boxes() {
	add_meta_box(
		'renalinfo_article_details',
		__( 'Article Details', 'renalinfo' ),
		'renalinfo_render_article_details_meta_box',
		'article',
		'normal',
		'high'
	);

	add_meta_box(
		'renalinfo_article_content',
		__( 'Article Content Options', 'renalinfo' ),
		'renalinfo_render_article_content_meta_box',
		'article',
		'normal',
		'default'
	);

	add_meta_box(
		'renalinfo_article_faqs',
		__( 'FAQ Items', 'renalinfo' ),
		'renalinfo_render_article_faq_meta_box',
		'article',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'renalinfo_add_article_meta_boxes' );

/**
 * Render Article Details meta box
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 */
function renalinfo_render_article_details_meta_box( $post ) {
	wp_nonce_field( 'renalinfo_article_details', 'renalinfo_article_details_nonce' );

	$template            = get_post_meta( $post->ID, '_article_template', true );
	$reading_level       = get_post_meta( $post->ID, '_reading_level', true );
	$audience            = get_post_meta( $post->ID, '_audience', true );
	$medical_review_date = get_post_meta( $post->ID, '_medical_review_date', true );
	$medical_reviewer    = get_post_meta( $post->ID, '_medical_reviewer', true );
	$version_note        = get_post_meta( $post->ID, '_version_note', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="article_template"><?php esc_html_e( 'Article Template', 'renalinfo' ); ?></label></th>
			<td>
				<select name="article_template" id="article_template" style="width: 100%;">
					<option value="condition-explainer" <?php selected( $template, 'condition-explainer' ); ?>>
						<?php esc_html_e( 'Condition Explainer', 'renalinfo' ); ?>
					</option>
					<option value="treatment-procedure" <?php selected( $template, 'treatment-procedure' ); ?>>
						<?php esc_html_e( 'Treatment Procedure', 'renalinfo' ); ?>
					</option>
					<option value="professional-article" <?php selected( $template, 'professional-article' ); ?>>
						<?php esc_html_e( 'Professional Article', 'renalinfo' ); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="reading_level"><?php esc_html_e( 'Reading Level (Flesch-Kincaid)', 'renalinfo' ); ?></label></th>
			<td>
				<input type="text" name="reading_level" id="reading_level" value="<?php echo esc_attr( $reading_level ); ?>" style="width: 100px;" />
				<p class="description"><?php esc_html_e( 'Target grade 8-9 for family content', 'renalinfo' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="audience"><?php esc_html_e( 'Primary Audience', 'renalinfo' ); ?></label></th>
			<td>
				<select name="audience" id="audience" style="width: 200px;">
					<option value="family" <?php selected( $audience, 'family' ); ?>><?php esc_html_e( 'Family', 'renalinfo' ); ?></option>
					<option value="professional" <?php selected( $audience, 'professional' ); ?>><?php esc_html_e( 'Professional', 'renalinfo' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="medical_review_date"><?php esc_html_e( 'Medical Review Date', 'renalinfo' ); ?></label></th>
			<td>
				<input type="date" name="medical_review_date" id="medical_review_date" value="<?php echo esc_attr( $medical_review_date ); ?>" />
			</td>
		</tr>
		<tr>
			<th><label for="medical_reviewer"><?php esc_html_e( 'Medical Reviewer', 'renalinfo' ); ?></label></th>
			<td>
				<input type="text" name="medical_reviewer" id="medical_reviewer" value="<?php echo esc_attr( $medical_reviewer ); ?>" style="width: 100%;" />
			</td>
		</tr>
		<tr>
			<th><label for="version_note"><?php esc_html_e( 'Version Note', 'renalinfo' ); ?></label></th>
			<td>
				<textarea name="version_note" id="version_note" rows="3" style="width: 100%;"><?php echo esc_textarea( $version_note ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Describe what changed in this update', 'renalinfo' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Render Article Content Options meta box
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 */
function renalinfo_render_article_content_meta_box( $post ) {
	wp_nonce_field( 'renalinfo_article_content', 'renalinfo_article_content_nonce' );

	$key_takeaways     = get_post_meta( $post->ID, '_key_takeaways', true );
	$related_articles  = get_post_meta( $post->ID, '_related_articles', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="key_takeaways"><?php esc_html_e( 'Key Takeaways', 'renalinfo' ); ?></label></th>
			<td>
				<textarea name="key_takeaways" id="key_takeaways" rows="5" style="width: 100%;"><?php echo esc_textarea( $key_takeaways ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Enter bullet points (one per line, start with •)', 'renalinfo' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="related_articles"><?php esc_html_e( 'Related Article IDs', 'renalinfo' ); ?></label></th>
			<td>
				<input type="text" name="related_articles" id="related_articles" value="<?php echo esc_attr( $related_articles ); ?>" style="width: 100%;" />
				<p class="description"><?php esc_html_e( 'Comma-separated article IDs (e.g., 123, 456, 789)', 'renalinfo' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Render FAQ meta box for condition explainer articles
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 */
function renalinfo_render_article_faq_meta_box( $post ) {
	wp_nonce_field( 'renalinfo_article_faq', 'renalinfo_article_faq_nonce' );

	$faq_items = get_post_meta( $post->ID, '_faq_items', true );
	if ( ! is_array( $faq_items ) ) {
		$faq_items = array();
	}
	?>
	<div id="renalinfo-faq-container">
		<?php foreach ( $faq_items as $index => $faq ) : ?>
			<div class="renalinfo-faq-item" data-index="<?php echo absint( $index ); ?>">
				<p>
					<label><?php esc_html_e( 'Question:', 'renalinfo' ); ?></label><br>
					<input type="text" name="faq_question[]" value="<?php echo esc_attr( $faq['question'] ?? '' ); ?>" style="width: 100%;" />
				</p>
				<p>
					<label><?php esc_html_e( 'Answer:', 'renalinfo' ); ?></label><br>
					<textarea name="faq_answer[]" rows="3" style="width: 100%;"><?php echo esc_textarea( $faq['answer'] ?? '' ); ?></textarea>
				</p>
				<p>
					<button type="button" class="button renalinfo-remove-faq"><?php esc_html_e( 'Remove FAQ', 'renalinfo' ); ?></button>
				</p>
				<hr>
			</div>
		<?php endforeach; ?>
	</div>
	<p>
		<button type="button" id="renalinfo-add-faq" class="button"><?php esc_html_e( 'Add FAQ Item', 'renalinfo' ); ?></button>
	</p>
	<script>
	jQuery(document).ready(function($) {
		$('#renalinfo-add-faq').on('click', function() {
			var index = $('.renalinfo-faq-item').length;
			var html = '<div class="renalinfo-faq-item" data-index="' + index + '">' +
				'<p><label><?php esc_html_e( 'Question:', 'renalinfo' ); ?></label><br>' +
				'<input type="text" name="faq_question[]" value="" style="width: 100%;" /></p>' +
				'<p><label><?php esc_html_e( 'Answer:', 'renalinfo' ); ?></label><br>' +
				'<textarea name="faq_answer[]" rows="3" style="width: 100%;"></textarea></p>' +
				'<p><button type="button" class="button renalinfo-remove-faq"><?php esc_html_e( 'Remove FAQ', 'renalinfo' ); ?></button></p>' +
				'<hr></div>';
			$('#renalinfo-faq-container').append(html);
		});
		$(document).on('click', '.renalinfo-remove-faq', function() {
			$(this).closest('.renalinfo-faq-item').remove();
		});
	});
	</script>
	<?php
}

/**
 * Save Article meta box data
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 */
function renalinfo_save_article_meta_boxes( $post_id ) {
	// Check autosave.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check post type.
	if ( 'article' !== get_post_type( $post_id ) ) {
		return;
	}

	// Check permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save Article Details.
	if ( isset( $_POST['renalinfo_article_details_nonce'] ) && wp_verify_nonce( $_POST['renalinfo_article_details_nonce'], 'renalinfo_article_details' ) ) {
		if ( isset( $_POST['article_template'] ) ) {
			update_post_meta( $post_id, '_article_template', sanitize_text_field( $_POST['article_template'] ) );
		}
		if ( isset( $_POST['reading_level'] ) ) {
			update_post_meta( $post_id, '_reading_level', sanitize_text_field( $_POST['reading_level'] ) );
		}
		if ( isset( $_POST['audience'] ) ) {
			update_post_meta( $post_id, '_audience', sanitize_text_field( $_POST['audience'] ) );
		}
		if ( isset( $_POST['medical_review_date'] ) ) {
			update_post_meta( $post_id, '_medical_review_date', sanitize_text_field( $_POST['medical_review_date'] ) );
		}
		if ( isset( $_POST['medical_reviewer'] ) ) {
			update_post_meta( $post_id, '_medical_reviewer', sanitize_text_field( $_POST['medical_reviewer'] ) );
		}
		if ( isset( $_POST['version_note'] ) ) {
			update_post_meta( $post_id, '_version_note', sanitize_textarea_field( $_POST['version_note'] ) );
		}
	}

	// Save Article Content Options.
	if ( isset( $_POST['renalinfo_article_content_nonce'] ) && wp_verify_nonce( $_POST['renalinfo_article_content_nonce'], 'renalinfo_article_content' ) ) {
		if ( isset( $_POST['key_takeaways'] ) ) {
			update_post_meta( $post_id, '_key_takeaways', sanitize_textarea_field( $_POST['key_takeaways'] ) );
		}
		if ( isset( $_POST['related_articles'] ) ) {
			update_post_meta( $post_id, '_related_articles', sanitize_text_field( $_POST['related_articles'] ) );
		}
	}

	// Save FAQ Items.
	if ( isset( $_POST['renalinfo_article_faq_nonce'] ) && wp_verify_nonce( $_POST['renalinfo_article_faq_nonce'], 'renalinfo_article_faq' ) ) {
		$faq_items = array();
		if ( isset( $_POST['faq_question'] ) && isset( $_POST['faq_answer'] ) ) {
			$questions = $_POST['faq_question'];
			$answers   = $_POST['faq_answer'];
			for ( $i = 0; $i < count( $questions ); $i++ ) {
				if ( ! empty( $questions[ $i ] ) ) {
					$faq_items[] = array(
						'question' => sanitize_text_field( $questions[ $i ] ),
						'answer'   => sanitize_textarea_field( $answers[ $i ] ),
					);
				}
			}
		}
		update_post_meta( $post_id, '_faq_items', $faq_items );
	}

	// Auto-calculate reading time.
	$reading_time = renalinfo_get_reading_time( $post_id );
	update_post_meta( $post_id, '_reading_time', $reading_time );
}
add_action( 'save_post', 'renalinfo_save_article_meta_boxes' );

/**
 * Register custom meta boxes for Journey post type
 *
 * @since 1.0.0
 */
function renalinfo_add_journey_meta_boxes() {
	add_meta_box(
		'renalinfo_journey_details',
		__( 'Journey Details', 'renalinfo' ),
		'renalinfo_render_journey_details_meta_box',
		'journey',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'renalinfo_add_journey_meta_boxes' );

/**
 * Render Journey Details meta box
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 */
function renalinfo_render_journey_details_meta_box( $post ) {
	wp_nonce_field( 'renalinfo_journey_details', 'renalinfo_journey_details_nonce' );

	$journey_articles = get_post_meta( $post->ID, '_journey_articles', true );
	$journey_audience = get_post_meta( $post->ID, '_journey_audience', true );
	$estimated_time   = get_post_meta( $post->ID, '_estimated_time', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="journey_articles"><?php esc_html_e( 'Article IDs (Ordered)', 'renalinfo' ); ?></label></th>
			<td>
				<input type="text" name="journey_articles" id="journey_articles" value="<?php echo esc_attr( $journey_articles ); ?>" style="width: 100%;" />
				<p class="description"><?php esc_html_e( 'Comma-separated article IDs in order (e.g., 101, 102, 103)', 'renalinfo' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="journey_audience"><?php esc_html_e( 'Target Audience', 'renalinfo' ); ?></label></th>
			<td>
				<select name="journey_audience" id="journey_audience" style="width: 200px;">
					<option value="family" <?php selected( $journey_audience, 'family' ); ?>><?php esc_html_e( 'Family', 'renalinfo' ); ?></option>
					<option value="professional" <?php selected( $journey_audience, 'professional' ); ?>><?php esc_html_e( 'Professional', 'renalinfo' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="estimated_time"><?php esc_html_e( 'Estimated Time (minutes)', 'renalinfo' ); ?></label></th>
			<td>
				<input type="number" name="estimated_time" id="estimated_time" value="<?php echo esc_attr( $estimated_time ); ?>" style="width: 100px;" />
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Save Journey meta box data
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 */
function renalinfo_save_journey_meta_boxes( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'journey' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['renalinfo_journey_details_nonce'] ) && wp_verify_nonce( $_POST['renalinfo_journey_details_nonce'], 'renalinfo_journey_details' ) ) {
		if ( isset( $_POST['journey_articles'] ) ) {
			update_post_meta( $post_id, '_journey_articles', sanitize_text_field( $_POST['journey_articles'] ) );
		}
		if ( isset( $_POST['journey_audience'] ) ) {
			update_post_meta( $post_id, '_journey_audience', sanitize_text_field( $_POST['journey_audience'] ) );
		}
		if ( isset( $_POST['estimated_time'] ) ) {
			update_post_meta( $post_id, '_estimated_time', absint( $_POST['estimated_time'] ) );
		}
	}
}
add_action( 'save_post', 'renalinfo_save_journey_meta_boxes' );

/**
 * Register custom meta boxes for Staff post type
 *
 * @since 1.0.0
 */
function renalinfo_add_staff_meta_boxes() {
	add_meta_box(
		'renalinfo_staff_details',
		__( 'Staff Details', 'renalinfo' ),
		'renalinfo_render_staff_details_meta_box',
		'staff',
		'normal',
		'high'
	);

	add_meta_box(
		'renalinfo_staff_contact',
		__( 'Contact Information', 'renalinfo' ),
		'renalinfo_render_staff_contact_meta_box',
		'staff',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'renalinfo_add_staff_meta_boxes' );

/**
 * Render Staff Details meta box
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 */
function renalinfo_render_staff_details_meta_box( $post ) {
	wp_nonce_field( 'renalinfo_staff_details', 'renalinfo_staff_details_nonce' );

	$staff_role             = get_post_meta( $post->ID, '_staff_role', true );
	$staff_credentials      = get_post_meta( $post->ID, '_staff_credentials', true );
	$staff_personal_bio     = get_post_meta( $post->ID, '_staff_personal_bio', true );
	$staff_professional_bio = get_post_meta( $post->ID, '_staff_professional_bio', true );
	$staff_languages        = get_post_meta( $post->ID, '_staff_languages', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="staff_role"><?php esc_html_e( 'Role / Job Title', 'renalinfo' ); ?></label></th>
			<td>
				<input type="text" name="staff_role" id="staff_role" value="<?php echo esc_attr( $staff_role ); ?>" style="width: 100%;" />
			</td>
		</tr>
		<tr>
			<th><label for="staff_credentials"><?php esc_html_e( 'Professional Credentials', 'renalinfo' ); ?></label></th>
			<td>
				<input type="text" name="staff_credentials" id="staff_credentials" value="<?php echo esc_attr( $staff_credentials ); ?>" style="width: 100%;" />
				<p class="description"><?php esc_html_e( 'e.g., MBBS, MD, MRCP(UK), MRCPCH', 'renalinfo' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="staff_personal_bio"><?php esc_html_e( 'Personal Bio', 'renalinfo' ); ?></label></th>
			<td>
				<textarea name="staff_personal_bio" id="staff_personal_bio" rows="4" style="width: 100%;"><?php echo esc_textarea( $staff_personal_bio ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Friendly, approachable information', 'renalinfo' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="staff_professional_bio"><?php esc_html_e( 'Professional Bio', 'renalinfo' ); ?></label></th>
			<td>
				<?php
				wp_editor(
					$staff_professional_bio,
					'staff_professional_bio',
					array(
						'textarea_rows' => 5,
						'media_buttons' => false,
					)
				);
				?>
				<p class="description"><?php esc_html_e( 'Professional background and expertise', 'renalinfo' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="staff_languages"><?php esc_html_e( 'Languages Spoken', 'renalinfo' ); ?></label></th>
			<td>
				<input type="text" name="staff_languages" id="staff_languages" value="<?php echo esc_attr( $staff_languages ); ?>" style="width: 100%;" />
				<p class="description"><?php esc_html_e( 'Comma-separated (e.g., English, Sinhala, Tamil)', 'renalinfo' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Render Staff Contact meta box
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 */
function renalinfo_render_staff_contact_meta_box( $post ) {
	wp_nonce_field( 'renalinfo_staff_contact', 'renalinfo_staff_contact_nonce' );

	$staff_email        = get_post_meta( $post->ID, '_staff_email', true );
	$staff_phone        = get_post_meta( $post->ID, '_staff_phone', true );
	$staff_office_hours = get_post_meta( $post->ID, '_staff_office_hours', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="staff_email"><?php esc_html_e( 'Email', 'renalinfo' ); ?></label></th>
			<td>
				<input type="email" name="staff_email" id="staff_email" value="<?php echo esc_attr( $staff_email ); ?>" style="width: 100%;" />
			</td>
		</tr>
		<tr>
			<th><label for="staff_phone"><?php esc_html_e( 'Phone', 'renalinfo' ); ?></label></th>
			<td>
				<input type="text" name="staff_phone" id="staff_phone" value="<?php echo esc_attr( $staff_phone ); ?>" style="width: 100%;" />
			</td>
		</tr>
		<tr>
			<th><label for="staff_office_hours"><?php esc_html_e( 'Office Hours', 'renalinfo' ); ?></label></th>
			<td>
				<textarea name="staff_office_hours" id="staff_office_hours" rows="3" style="width: 100%;"><?php echo esc_textarea( $staff_office_hours ); ?></textarea>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Save Staff meta box data
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 */
function renalinfo_save_staff_meta_boxes( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'staff' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['renalinfo_staff_details_nonce'] ) && wp_verify_nonce( $_POST['renalinfo_staff_details_nonce'], 'renalinfo_staff_details' ) ) {
		if ( isset( $_POST['staff_role'] ) ) {
			update_post_meta( $post_id, '_staff_role', sanitize_text_field( $_POST['staff_role'] ) );
		}
		if ( isset( $_POST['staff_credentials'] ) ) {
			update_post_meta( $post_id, '_staff_credentials', sanitize_text_field( $_POST['staff_credentials'] ) );
		}
		if ( isset( $_POST['staff_personal_bio'] ) ) {
			update_post_meta( $post_id, '_staff_personal_bio', sanitize_textarea_field( $_POST['staff_personal_bio'] ) );
		}
		if ( isset( $_POST['staff_professional_bio'] ) ) {
			update_post_meta( $post_id, '_staff_professional_bio', wp_kses_post( $_POST['staff_professional_bio'] ) );
		}
		if ( isset( $_POST['staff_languages'] ) ) {
			update_post_meta( $post_id, '_staff_languages', sanitize_text_field( $_POST['staff_languages'] ) );
		}
	}

	if ( isset( $_POST['renalinfo_staff_contact_nonce'] ) && wp_verify_nonce( $_POST['renalinfo_staff_contact_nonce'], 'renalinfo_staff_contact' ) ) {
		if ( isset( $_POST['staff_email'] ) ) {
			update_post_meta( $post_id, '_staff_email', sanitize_email( $_POST['staff_email'] ) );
		}
		if ( isset( $_POST['staff_phone'] ) ) {
			update_post_meta( $post_id, '_staff_phone', sanitize_text_field( $_POST['staff_phone'] ) );
		}
		if ( isset( $_POST['staff_office_hours'] ) ) {
			update_post_meta( $post_id, '_staff_office_hours', sanitize_textarea_field( $_POST['staff_office_hours'] ) );
		}
	}
}
add_action( 'save_post', 'renalinfo_save_staff_meta_boxes' );

/**
 * Register custom meta boxes for Medical Term post type
 *
 * @since 1.0.0
 */
function renalinfo_add_medical_term_meta_boxes() {
	add_meta_box(
		'renalinfo_medical_term_details',
		__( 'Medical Term Details', 'renalinfo' ),
		'renalinfo_render_medical_term_details_meta_box',
		'medical_term',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'renalinfo_add_medical_term_meta_boxes' );

/**
 * Render Medical Term Details meta box
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 */
function renalinfo_render_medical_term_details_meta_box( $post ) {
	wp_nonce_field( 'renalinfo_medical_term_details', 'renalinfo_medical_term_details_nonce' );

	$term_abbreviation          = get_post_meta( $post->ID, '_term_abbreviation', true );
	$term_full_name             = get_post_meta( $post->ID, '_term_full_name', true );
	$term_synonyms              = get_post_meta( $post->ID, '_term_synonyms', true );
	$term_simple_definition     = get_post_meta( $post->ID, '_term_simple_definition', true );
	$term_technical_definition  = get_post_meta( $post->ID, '_term_technical_definition', true );
	$term_pronunciation         = get_post_meta( $post->ID, '_term_pronunciation', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="term_abbreviation"><?php esc_html_e( 'Abbreviation', 'renalinfo' ); ?></label></th>
			<td>
				<input type="text" name="term_abbreviation" id="term_abbreviation" value="<?php echo esc_attr( $term_abbreviation ); ?>" style="width: 200px;" />
			</td>
		</tr>
		<tr>
			<th><label for="term_full_name"><?php esc_html_e( 'Full Medical Term', 'renalinfo' ); ?></label></th>
			<td>
				<input type="text" name="term_full_name" id="term_full_name" value="<?php echo esc_attr( $term_full_name ); ?>" style="width: 100%;" />
			</td>
		</tr>
		<tr>
			<th><label for="term_synonyms"><?php esc_html_e( 'Synonyms / Alternative Terms', 'renalinfo' ); ?></label></th>
			<td>
				<textarea name="term_synonyms" id="term_synonyms" rows="2" style="width: 100%;"><?php echo esc_textarea( $term_synonyms ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Comma-separated alternative terms', 'renalinfo' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="term_simple_definition"><?php esc_html_e( 'Simple Definition (Family)', 'renalinfo' ); ?></label></th>
			<td>
				<textarea name="term_simple_definition" id="term_simple_definition" rows="3" style="width: 100%;"><?php echo esc_textarea( $term_simple_definition ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Family-friendly explanation', 'renalinfo' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="term_technical_definition"><?php esc_html_e( 'Technical Definition (Professional)', 'renalinfo' ); ?></label></th>
			<td>
				<textarea name="term_technical_definition" id="term_technical_definition" rows="3" style="width: 100%;"><?php echo esc_textarea( $term_technical_definition ); ?></textarea>
			</td>
		</tr>
		<tr>
			<th><label for="term_pronunciation"><?php esc_html_e( 'Pronunciation', 'renalinfo' ); ?></label></th>
			<td>
				<input type="text" name="term_pronunciation" id="term_pronunciation" value="<?php echo esc_attr( $term_pronunciation ); ?>" style="width: 100%;" />
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Save Medical Term meta box data
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 */
function renalinfo_save_medical_term_meta_boxes( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'medical_term' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['renalinfo_medical_term_details_nonce'] ) && wp_verify_nonce( $_POST['renalinfo_medical_term_details_nonce'], 'renalinfo_medical_term_details' ) ) {
		if ( isset( $_POST['term_abbreviation'] ) ) {
			update_post_meta( $post_id, '_term_abbreviation', sanitize_text_field( $_POST['term_abbreviation'] ) );
		}
		if ( isset( $_POST['term_full_name'] ) ) {
			update_post_meta( $post_id, '_term_full_name', sanitize_text_field( $_POST['term_full_name'] ) );
		}
		if ( isset( $_POST['term_synonyms'] ) ) {
			update_post_meta( $post_id, '_term_synonyms', sanitize_textarea_field( $_POST['term_synonyms'] ) );
		}
		if ( isset( $_POST['term_simple_definition'] ) ) {
			update_post_meta( $post_id, '_term_simple_definition', sanitize_textarea_field( $_POST['term_simple_definition'] ) );
		}
		if ( isset( $_POST['term_technical_definition'] ) ) {
			update_post_meta( $post_id, '_term_technical_definition', sanitize_textarea_field( $_POST['term_technical_definition'] ) );
		}
		if ( isset( $_POST['term_pronunciation'] ) ) {
			update_post_meta( $post_id, '_term_pronunciation', sanitize_text_field( $_POST['term_pronunciation'] ) );
		}
	}
}
add_action( 'save_post', 'renalinfo_save_medical_term_meta_boxes' );

/**
 * Add custom admin columns for Medical Term post type
 *
 * @since 1.0.0
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function renalinfo_medical_term_admin_columns( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;
		if ( 'title' === $key ) {
			$new_columns['abbreviation'] = __( 'Abbreviation', 'renalinfo' );
			$new_columns['full_name']    = __( 'Full Name', 'renalinfo' );
			$new_columns['synonyms']     = __( 'Synonyms', 'renalinfo' );
		}
	}
	return $new_columns;
}
add_filter( 'manage_medical_term_posts_columns', 'renalinfo_medical_term_admin_columns' );

/**
 * Populate custom admin columns for Medical Term post type
 *
 * @since 1.0.0
 * @param string $column Column name.
 * @param int    $post_id Post ID.
 */
function renalinfo_medical_term_admin_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'abbreviation':
			echo esc_html( get_post_meta( $post_id, '_term_abbreviation', true ) );
			break;
		case 'full_name':
			echo esc_html( get_post_meta( $post_id, '_term_full_name', true ) );
			break;
		case 'synonyms':
			echo esc_html( get_post_meta( $post_id, '_term_synonyms', true ) );
			break;
	}
}
add_action( 'manage_medical_term_posts_custom_column', 'renalinfo_medical_term_admin_column_content', 10, 2 );

/**
 * Make custom admin columns sortable for Medical Term post type
 *
 * @since 1.0.0
 * @param array $columns Sortable columns.
 * @return array Modified sortable columns.
 */
function renalinfo_medical_term_sortable_columns( $columns ) {
	$columns['abbreviation'] = 'abbreviation';
	$columns['full_name']    = 'full_name';
	return $columns;
}
add_filter( 'manage_edit-medical_term_sortable_columns', 'renalinfo_medical_term_sortable_columns' );
