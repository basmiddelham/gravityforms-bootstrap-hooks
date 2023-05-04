<?php
/**
 * Gravity Forms Bootstrap Hooks
 *
 * Actions & filters for using Gravityforms in your Bootstrap 5 enabled theme.
 *
 * @package     WordPress
 * @subpackage  GravityForms
 * @link        https://github.com/MoshCat/gravityforms-bootstrap-hooks
 */

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'GFCommon' ) ) {
	/** Remove legend */
	add_filter( 'gform_required_legend', '__return_empty_string' );

	/** Disable Gravity Forms CSS. */
	add_filter( 'pre_option_rg_gforms_disable_css', '__return_true' );

	/** Enable HTML5. */
	add_filter( 'pre_option_rg_gforms_enable_html5', '__return_true' );

	/** Modify the fields classes to Bootstrap classes. */
	add_filter(
		'gform_field_content',
		function ( $content, $field ) {

			// Exclude these fieldtypes for later customisation.
			$exclude_formcontrol = array(
				'hidden',
				'email',
				'select',
				'multiselect',
				'checkbox',
				'radio',
				'password',
				'fileupload',
				'list',
				'html',
				'address',
				'post_image',
				'post_category',
				'product',
				'option',
			);

			// Add .form-control to most inputs except those listed.
			if ( ! in_array( $field['type'], $exclude_formcontrol, true ) ) {
				$content = str_replace( 'class=\'small', 'class=\'form-control form-control-sm', $content );
				$content = str_replace( 'class=\'medium', 'class=\'form-control', $content );
				$content = str_replace( 'class=\'large', 'class=\'form-control form-control-lg', $content );
			}

			// Labels.
			$content = str_replace( 'gfield_label', 'form-label gfield_label', $content );

			// Descriptions.
			$content = str_replace( 'class=\'gfield_description', 'class=\'small text-muted gfield_description', $content );

			// Validation message.
			$content = str_replace( 'class=\'small text-muted gfield_description validation_message', 'class=\'alert alert-warning p-1 small gfield_description validation_message', $content );

			// Sections.
			$content = str_replace( 'class=\'gsection_description', 'class=\'gsection_description small text-muted', $content );

			// Number fields.
			$content = str_replace( 'ginput_quantity ', 'form-control ginput_quantity ', $content );
			$content = str_replace( 'ginput_amount ', 'form-control ginput_amount ', $content );

			// Select fields.
			$content = str_replace( 'gfield_select', 'form-select', $content );
			if ( 'select' === $field['type'] || 'post_category' === $field['type'] ) {
				$content = str_replace( 'class=\'small form-select', 'class=\'form-select form-select-sm', $content );
				$content = str_replace( 'class=\'medium form-select', 'class=\'form-select', $content );
				$content = str_replace( 'class=\'large form-select', 'class=\'form-select form-select-lg', $content );
			}

			// Textarea fields.
			if ( 'textarea' === $field['type'] || 'post_content' === $field['type'] || 'post_excerpt' === $field['type'] ) {
				$content = str_replace( 'class=\'textarea small', 'class=\'form-control form-control-sm textarea', $content );
				$content = str_replace( 'class=\'textarea medium', 'class=\'form-control textarea', $content );
				$content = str_replace( 'class=\'textarea large', 'class=\'form-control form-control-lg textarea', $content );
				$content = str_replace( 'rows=\'10\'', 'rows=\'4\'', $content );
			}

			// Checkbox fields.
			if ( 'checkbox' === $field['type'] || 'checkbox' === $field['inputType'] ) {
				$content = str_replace( 'gchoice ', 'form-check gchoice ', $content );
				$content = str_replace( '<input class=\'gfield-choice-input', '<input class=\'form-check-input gfield-choice-input', $content );
				$content = str_replace( '<label for', '<label class=\'form-check-label\' for', $content );
				$content = str_replace( 'type="button"', 'type="button" class="btn btn-primary btn-sm"', $content ); // 'Other' option.
			}

			// Radio fields.
			if ( 'radio' === $field['type'] || 'radio' === $field['inputType'] ) {
				$content = str_replace( 'gchoice ', 'gchoice form-check ', $content );
				$content = str_replace( '<input class=\'gfield-choice-input', '<input class=\'form-check-input gfield-choice-input\'', $content );
				$content = str_replace( '<label class=\'form-radio-label', '<label class=\'form-check-label form-radio-label', $content );
				$content = str_replace( 'gchoice_other_control', 'form-control form-control-sm', $content ); // 'Other' option.
			}

			// Post Image meta data fields.
			if ( 'post_image' === $field['type'] ) {
				$content = str_replace( 'type=\'text\'', 'type=\'text\' class=\'form-control form-control-sm\'', $content );
			}

			// Date fields.
			if ( 'date' === $field['type'] ) {
				$content = str_replace( '<select', '<select class=\'form-select\'', $content );
				$content = str_replace( 'ginput_complex', 'row g-2 ginput_complex', $content );
				$content = str_replace( 'ginput_container_date', 'col ginput_container_date', $content );
				$content = str_replace( 'type=\'number\'', 'type=\'number\' class=\'form-control\'', $content );
				$content = str_replace( 'label for=', 'label class="small text-muted" for=', $content );
				$content = str_replace( 'class=\'datepicker', 'class=\'form-control datepicker', $content );
			}

			// Date & Time fields.
			if ( 'time' === $field['type'] ) {
				$content = str_replace( '<select', '<select class=\'form-select\'', $content );
				$content = str_replace( 'ginput_complex', 'row g-2 ginput_complex', $content );
				$content = str_replace( 'ginput_container_time', 'col ginput_container_time', $content );
				$content = str_replace( 'hour_minute_colon', 'd-none hour_minute_colon', $content );
				$content = str_replace( 'type=\'number\'', 'type=\'number\' class=\'form-control\'', $content );
				$content = str_replace( 'label class=\'hour_label', 'label class=\'small text-muted hour_label', $content );
				$content = str_replace( 'label class=\'minute_label', 'label class=\'small text-muted minute_label', $content );
			}

			// Complex fields.
			if ( 'name' === $field['type'] || 'address' === $field['type'] || 'email' === $field['type'] || 'password' === $field['type'] ) {
				$content = str_replace( 'class=\'ginput_complex', 'class=\'row g-2 ginput_complex', $content );
				$content = str_replace( 'class=\'ginput_left', 'class=\'col-12 col-md-6 ginput_left', $content );
				$content = str_replace( 'class=\'ginput_right', 'class=\'col-12 col-md-6 ginput_right', $content );
				$content = str_replace( 'class=\'ginput_full', 'class=\'col-12 col-md-12 ginput_full', $content );
			}

			// Password fields.
			if ( 'password' === $field['type'] ) {
				$content = str_replace( 'type=\'password\'', 'type=\'password\' class=\'form-control\' ', $content );
				$content = str_replace( '<label for', '<label class=\'small muted\' for', $content );
			}

			// Email fields.
			if ( 'email' === $field['type'] ) {
				$content = str_replace( 'small\'', 'form-control form-control-sm\'', $content );
				$content = str_replace( 'medium\'', 'form-control\'', $content );
				$content = str_replace( 'large\'', 'form-control form-control-lg\'', $content );
				$content = str_replace( '<input class=\'\'', '<input class=\'form-control\'', $content ); // email with confirm email.
				$content = str_replace( '<label for', '<label class=\'small muted\' for', $content );
			}

			// Name & Address fields.
			if ( 'name' === $field['type'] || 'address' === $field['type'] ) {
				$content = str_replace( 'class=\'name_', 'class=\'col name_', $content );
				$content = str_replace( 'type=\'text\'', 'type=\'text\' class=\'form-control\'', $content );
				$content = str_replace( '<select ', '<select class=\'form-select\' ', $content );
				$content = str_replace( 'label for=', 'label class=\'small text-muted\' for=', $content );
			}

			// Consent fields.
			if ( 'consent' === $field['type'] ) {
				$content = str_replace( 'ginput_container_consent', 'form-check ginput_container_consent', $content );
				$content = str_replace( 'gfield_consent_label', 'form-check-label gfield_consent_label', $content );
				$content = str_replace( 'type=\'checkbox\'', 'type=\'checkbox\' class=\'form-check-input\' ', $content );
			}

			// List fields.
			if ( 'list' === $field['type'] ) {
				$content = str_replace( 'type=\'text\'', 'type=\'text\' class=\'form-control\'', $content );
				$content = str_replace( 'gform-field-label', 'gform-field-label small', $content );
			}

			// Fileupload fields. Add class 'preview' to the field to enable the image preview.
			if ( 'fileupload' === $field['type'] || 'post_image' === $field['type'] ) {
				// Single file uploads.
				$content = str_replace( 'type=\'file\' class=\'medium\'', 'type=\'file\' class=\'form-control\'', $content );
				$content = str_replace( 'gform_fileupload_rules', 'small text-muted gform_fileupload_rules', $content );
				$content = str_replace( 'validation_message', 'text-danger small list-unstyled validation_message', $content );
				$content = str_replace( 'id=\'extensions_message', 'class=\'small text-muted\' id=\'extensions_message', $content );
				$content = str_replace( 'label for=', 'label class=\'small text-muted\' for=', $content );

				// Mutli file upload.
				if ( true === $field['multipleFiles'] ) {
					$content = str_replace( 'class=\'button', 'class=\'btn btn-primary btn-sm', $content );
				}
			}

			// Product price.
			if ( 'product' === $field['type'] ) {
				$content = str_replace( 'class=\'ginput_product_price ', 'class=\'form-control ginput_product_price ', $content );
				$content = str_replace( 'ginput_product_price_label', 'small text-muted ginput_product_price_label', $content );
				$content = str_replace( 'class=\'ginput_product_price\' id=\'ginput_base', 'class=\'form-control ginput_product_price\' id=\'ginput_base', $content );
				$content = str_replace( 'class=\'ginput_quantity\'', 'class=\'form-control ginput_quantity\'', $content );
				$content = str_replace( 'class=\'ginput_quantity_label\'', 'class=\'small ginput_quantity_label\'', $content );
				$content = str_replace( 'class=\'ginput_product_price\'', 'class=\'text-success ginput_product_price\'', $content );
			}

			// Product total.
			if ( 'total' === $field['type'] ) {
				$content = str_replace( 'ginput_total', 'form-control ginput_total', $content );
			}

			return $content;
		},
		10,
		5
	);

	/** Change the main validation message. */
	add_filter(
		'gform_validation_message',
		function ( $message, $form ) {
			return '<div class=\'validation_error\'>' . esc_html__( 'There was a problem with your submission.', 'gravityforms' ) . ' ' . esc_html__( 'Errors have been highlighted below.', 'gravityforms' ) . '</div>'; // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		},
		10,
		2
	);

	/** Change classes on Submit button. */
	add_filter(
		'gform_submit_button',
		function ( $button, $form ) {
			$button = str_replace( 'class=\'gform_button', 'class=\'gform_button btn btn-primary', $button );
			return $button;
		},
		10,
		2
	);

	/** Change classes on Next button. */
	add_filter(
		'gform_next_button',
		function ( $button, $form ) {
			$button = str_replace( 'class=\'gform_next_button', 'class=\'gform_next_button btn btn-secondary', $button );
			return $button;
		},
		10,
		2
	);

	/** Change classes on Previous button. */
	add_filter(
		'gform_previous_button',
		function ( $button, $form ) {
			$button = str_replace( 'class=\'gform_previous_button', 'class=\'gform_previous_button btn btn-outline-secondary', $button );
			return $button;
		},
		10,
		2
	);

	/** Change classes on Save & Continue Later button. */
	add_filter(
		'gform_savecontinue_link',
		function ( $button, $form ) {
			$button = str_replace( 'class=\'gform_save_link', 'class=\'btn btn-outline-secondary gform_save_link', $button );
			return $button;
		},
		10,
		2
	);

	/** Change classes on progressbars */
	add_filter(
		'gform_progress_bar',
		function ( $progress_bar, $form, $confirmation_message ) {
			$progress_bar = str_replace( 'gf_progressbar ', 'progress gf_progressbar ', $progress_bar );
			$progress_bar = str_replace( 'gf_progressbar_percentage', 'progress-bar progress-bar-striped progress-bar-animated progress_percentage', $progress_bar );
			$progress_bar = str_replace( 'percentbar_blue', 'bg-primary percentbar_blue', $progress_bar );
			$progress_bar = str_replace( 'percentbar_gray', 'bg-secondary percentbar_gray', $progress_bar );
			$progress_bar = str_replace( 'percentbar_green', 'bg-success percentbar_green', $progress_bar );
			$progress_bar = str_replace( 'percentbar_orange', 'bg-warning percentbar_orange', $progress_bar );
			$progress_bar = str_replace( 'percentbar_red', 'bg-danger percentbar_red', $progress_bar );
			return $progress_bar;
		},
		10,
		3
	);

	/** Hide Gravityforms Spinner. */
	add_filter(
		'gform_ajax_spinner_url',
		function () {
			return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
		}
	);
}
