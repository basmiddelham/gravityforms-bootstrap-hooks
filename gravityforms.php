<?php
/**
 * Gravity Forms Bootstrap Hooks
 *
 * Actions & filters for using Gravityforms in your Bootstrap 5 enabled theme.
 *
 * @package     WordPress
 * @subpackage  GravityForms
 * @link        https://github.com/basmiddelham/gravityforms-bootstrap-hooks
 */

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reset the Gravity Forms width classes in the form editor so only the column layout appears.
 */
function strt_reset_sizes() {
	echo '<style>
	.gform_wrapper.gravity-theme .gfield input.small, .gform_wrapper.gravity-theme .gfield select.small,
	.gform_wrapper.gravity-theme .gfield input.medium, .gform_wrapper.gravity-theme .gfield select.medium {
		width: 100% !important;
	}</style>';
}
add_action( 'admin_head', 'strt_reset_sizes' );

/**
 * Display zipcode before city in Address Fields
 *
 * @link https://docs.gravityforms.com/gform_address_display_format/
 */
function strt_address_format() {
	return 'zip_before_city';
}
add_filter( 'gform_address_display_format', 'strt_address_format', 10, 2 );

/**
 * Add Gravity Forms capabilities To Editor role.
 */
function strt_add_gf_capabilities() {
	$role = get_role( 'editor' );
	// $role->add_cap( 'gform_full_access' );
	// $role->remove_cap('gform_full_access');
}
add_action( 'init', 'strt_add_gf_capabilities' );

/** Remove legend. */
add_filter( 'gform_required_legend', '__return_empty_string' );

/**
 * Only apply on frontend
 */
if ( ! is_admin() ) {

	/** Disable Gravity Forms CSS. */
	add_filter( 'pre_option_rg_gforms_disable_css', '__return_true' );

	/** Enable HTML5. */
	add_filter( 'pre_option_rg_gforms_enable_html5', '__return_true' );

	/**
	 * Register styles to be used on Gravity Forms preview pages.
	 *
	 * @param array $styles Array of style handles to be enqueued.
	 * @link https://docs.gravityforms.com/gform_preview_styles/
	 */
	function strt_preview_styles( $styles ) {
		wp_register_style( 'my_stylesheet', get_template_directory_uri() . '/assets/dist/css/frontend.css', null, '1.0' );
		$styles = array( 'my_stylesheet' );
		return $styles;
	}
	add_filter( 'gform_preview_styles', 'strt_preview_styles', 10, 2 );

	/**
	 * Add .row class to .gform_fields.
	 *
	 * @param string $form_string The form markup.
	 * @link https://docs.gravityforms.com/gform_get_form_filter/
	 */
	function strt_get_form_filter( $form_string ) {
		$form_string = str_replace( 'class=\'gform_fields', 'class=\'gform_fields row gx-2', $form_string );
		return $form_string;
	}
	add_filter( 'gform_get_form_filter', 'strt_get_form_filter', 10, 2 );

	/**
	 * Add column & margin classes to .gform_field.
	 *
	 * @param string $field_container The field container markup.
	 * @link https://docs.gravityforms.com/gform_field_container/
	 */
	function strt_field_container( $field_container ) {
		$field_container = str_replace( 'class="gfield', 'class="gfield mb-4', $field_container );
		$field_container = str_replace( 'gfield--width-quarter', 'gfield--width-quarter col-sm-6 col-md-3', $field_container );
		$field_container = str_replace( 'gfield--width-third', 'gfield--width-third col-md-4', $field_container );
		$field_container = str_replace( 'gfield--width-five-twelfths', 'gfield--width-five-twelfths col-md-5', $field_container );
		$field_container = str_replace( 'gfield--width-half', 'gfield--width-half col-md-6', $field_container );
		$field_container = str_replace( 'gfield--width-seven-twelfths', 'gfield--width-seven-twelfths col-md-7', $field_container );
		$field_container = str_replace( 'gfield--width-two-thirds', 'gfield--width-two-thirds col-md-8', $field_container );
		$field_container = str_replace( 'gfield--width-three-quarter', 'gfield--width-three-quarter col-md-9', $field_container );
		$field_container = str_replace( 'gfield--width-five-sixths', 'gfield--width-five-sixths col-md-10', $field_container );
		$field_container = str_replace( 'gfield--width-eleven-twelfths', 'gfield--width-eleven-twelfths col-md-11', $field_container );
		$field_container = str_replace( 'gfield--width-full', 'gfield--width-full col-12', $field_container );
		return $field_container;
	}
	add_filter( 'gform_field_container', 'strt_field_container', 10, 6 );

	/**
	 * Modify the field classes to Bootstrap classes.
	 *
	 * @param string $field_content Field content.
	 * @param object $field Field options.
	 * @link https://docs.gravityforms.com/gform_field_content/
	 */
	function strt_field_content( $field_content, $field ) {

		// Select fields.
		$field_content = str_replace( 'class=\'small gfield_select', 'class=\'gfield_select form-select form-select-sm', $field_content );
		$field_content = str_replace( 'class=\'medium gfield_select', 'class=\'gfield_select form-select', $field_content );
		$field_content = str_replace( 'class=\'large gfield_select', 'class=\'gfield_select form-select form-select-lg', $field_content );

		// Add .form-control to most inputs.
		$field_content = str_replace( 'class=\'small', 'class=\'form-control form-control-sm', $field_content );
		$field_content = str_replace( 'class=\'medium', 'class=\'form-control', $field_content );
		$field_content = str_replace( 'class=\'large', 'class=\'form-control form-control-lg', $field_content );

		// Textarea fields.
		$field_content = str_replace( 'class=\'textarea small', 'class=\'textarea form-control form-control-sm', $field_content );
		$field_content = str_replace( 'class=\'textarea medium', 'class=\'textarea form-control', $field_content );
		$field_content = str_replace( 'class=\'textarea large', 'class=\'textarea form-control form-control-lg', $field_content );
		$field_content = str_replace( 'rows=\'10\'', 'rows=\'4\'', $field_content );

		// Labels.
		if ( 'hidden_label' === $field['labelPlacement'] ) {
			$field_content = str_replace( 'gfield_label', 'gfield_label visually-hidden', $field_content );
		} else {
			$field_content = str_replace( 'gfield_label gform-field-label', 'gfield_label gform-field-label form-label fw-semibold small lh-sm d-block', $field_content );
		}

		// Sub-Labels.
		if ( 'hidden_label' === $field['subLabelPlacement'] ) {
			$field_content = str_replace( 'hidden_sub_label', 'hidden_sub_label visually-hidden', $field_content );
		} else {
			$field_content = str_replace( 'gform-field-label--type-sub', 'gform-field-label--type-sub fw-medium small lh-sm d-block', $field_content );
		}

		// Descriptions.
		$field_content = str_replace( 'class=\'gfield_description\'', 'class=\'gfield_description small text-body-secondary d-block lh-sm\'', $field_content );

		// Validation message.
		$field_content = str_replace( 'gfield_validation_message', 'gfield_validation_message alert alert-warning small p-1 my-2', $field_content );

		// Sections.
		$field_content = str_replace( 'gsection_title', 'gsection_title mt-3 pb-1 mb-1 border-bottom', $field_content );
		$field_content = str_replace( 'class=\'gsection_description', 'class=\'gsection_description text-body-secondary', $field_content );

		// Checkbox & Radio fields.
		if ( 'checkbox' === $field['type'] || 'radio' === $field['type'] || 'checkbox' === $field['inputType'] || 'radio' === $field['inputType'] ) {
			$field_content = str_replace( 'gchoice ', 'gchoice form-check ', $field_content );
			$field_content = str_replace( 'gfield-choice-input', 'gfield-choice-input form-check-input', $field_content );
			$field_content = str_replace( 'gform-field-label', 'gform-field-label form-check-label', $field_content );
			$field_content = str_replace( 'type="button"', 'type="button" class="btn btn-primary btn-sm mt-1"', $field_content ); // Checkbox 'Select All' option.
			$field_content = str_replace( 'gchoice_other_control', 'gchoice_other_control form-control form-control-sm mt-1', $field_content ); // Radio 'Other' option.
		}

		// Complex fields layout.
		$field_content = str_replace( 'gform-grid-row', 'gform-grid-row row gx-2', $field_content );

		// Email, Post Image & Address Fields.
		$field_content = str_replace( 'ginput_left', 'ginput_left col-sm-6', $field_content );
		$field_content = str_replace( 'ginput_right', 'ginput_right col-sm-6', $field_content );
		$field_content = str_replace( 'ginput_full', 'ginput_full col-12', $field_content );

		if ( 'date' === $field['type'] || 'time' === $field['type'] ) {
			$field_content = str_replace( 'gform-grid-col', 'gform-grid-col col', $field_content );
			$field_content = str_replace( 'hour_minute_colon', 'hour_minute_colon flex-grow-0 pt-1', $field_content );
			$field_content = str_replace( '<select', '<select class=\'form-select\'', $field_content );
			$field_content = str_replace( 'type=\'number\'', 'type=\'number\' class=\'form-control\'', $field_content );
			$field_content = str_replace( 'class=\'datepicker', 'class=\'datepicker form-control', $field_content );
		}

		// Address & Post Image fields.
		if ( 'address' === $field['type'] || 'post_image' === $field['type'] ) {
			$field_content = str_replace( 'gform-grid-col', 'gform-grid-col mb-2', $field_content );
		}

		// Name Fields.
		if ( 'name' === $field['type'] ) {
			$field_content = str_replace( 'gform-grid-col--size-auto', 'gform-grid-col--size-auto col-sm mb-2 mb-sm-0', $field_content );
			$field_content = str_replace( 'type=\'text\'', 'type=\'text\' class=\'form-control\'', $field_content );
			$field_content = str_replace( '<select ', '<select class=\'form-select\' ', $field_content );
		}

		// Email fields.
		if ( 'email' === $field['type'] ) {
			$field_content = str_replace( 'gform-grid-col--size-auto', 'gform-grid-col--size-auto mb-2 mb-sm-0', $field_content ); // Only with 'confirm email' enabled.
			$field_content = str_replace( '<input class=\'\'', '<input class=\'form-control\'', $field_content );
		}

		// Address Fields.
		if ( 'address' === $field['type'] ) {
			$field_content = str_replace( 'type=\'text\'', 'type=\'text\' class=\'form-control\'', $field_content );
			$field_content = str_replace( '<select ', '<select class=\'form-select\' ', $field_content );
			$field_content = str_replace( 'class=\'copy_values_option_container', 'class=\'copy_values_option_container form-check', $field_content );
			$field_content = str_replace( 'class=\'copy_values_activated', 'class=\'copy_values_activated form-check-input', $field_content );
			$field_content = str_replace( 'class=\'copy_values_option_label', 'class=\'copy_values_option_label form-check-label', $field_content );
		}

		// Consent fields.
		if ( 'consent' === $field['type'] ) {
			$field_content = str_replace( 'ginput_container_consent', 'ginput_container_consent form-check', $field_content );
			$field_content = str_replace( 'type=\'checkbox\'', 'type=\'checkbox\' class=\'form-check-input\' ', $field_content );
			$field_content = str_replace( 'gfield_consent_label', 'gfield_consent_label form-check-label', $field_content );
			$field_content = str_replace( ' gfield_consent_description\'', ' gfield_consent_description border mt-1 p-1 overflow-y-auto\' style=\' max-height: 100px;\'', $field_content );
		}

		// List fields.
		if ( 'list' === $field['type'] ) {
			$field_content = str_replace( 'gform-field-label', 'gform-field-label col-sm', $field_content );
			$field_content = str_replace( 'gfield_list_group ', 'gfield_list_group mb-sm-2 ', $field_content );
			$field_content = str_replace( 'gfield_list_group_item', 'gfield_list_group_item col-sm mb-2 mb-sm-0', $field_content );
			$field_content = str_replace( 'type=\'text\'', 'type=\'text\' class=\'form-control\'', $field_content );
			$field_content = str_replace( '<div class="gform-field-label', '<div class="gform-field-label small fw-medium lh-sm', $field_content );
		}

		// Fileupload & Post Image fields.
		if ( 'fileupload' === $field['type'] || 'post_image' === $field['type'] ) {
			$field_content = str_replace( 'type=\'text\'', 'type=\'text\' class=\'form-control form-control-sm\'', $field_content ); // Post Image meta fields.
			$field_content = str_replace( 'gform_fileupload_rules', 'gform_fileupload_rules small text-body-secondary d-block lh-sm', $field_content );

			// Mutli file upload.
			if ( true === $field['multipleFiles'] ) {
				$field_content = str_replace( 'class=\'gform_drop_area', 'class=\'gform_drop_area bg-light p-3 text-center border', $field_content );
				$field_content = str_replace( 'class=\'gform_drop_instructions', 'class=\'gform_drop_instructions d-block small', $field_content );
				$field_content = str_replace( 'validation_message--hidden-on-empty', 'validation_message--hidden-on-empty list-unstyled mb-0', $field_content );
				$field_content = str_replace( 'class=\'button', 'class=\'button btn btn-primary btn-sm', $field_content );
			}
		}

		// Product price.
		if ( 'product' === $field['type'] ) {
			$field_content = str_replace( 'class=\'ginput_product_price ', 'disabled class=\'ginput_product_price form-control ', $field_content );
			$field_content = str_replace( '\'ginput_quantity\'', '\'ginput_quantity form-control\'', $field_content );
			$field_content = str_replace( 'ginput_quantity_label', 'ginput_quantity_label form-label d-block small lh-sm', $field_content );
			$field_content = str_replace( 'ginput_product_price\'', 'ginput_product_price text-success fw-bold\'', $field_content );
		}

		// Product total.
		if ( 'total' === $field['type'] ) {
			$field_content = str_replace( 'ginput_total', 'ginput_total form-control', $field_content );
			$field_content = str_replace( 'readonly', 'readonly disabled', $field_content );
		}

		return $field_content;
	}
	add_filter( 'gform_field_content', 'strt_field_content', 10, 5 );

	/**
	 * Add classes to .ginput_counter.
	 *
	 * @param string $script The script (including <script> tag) to be filtered.
	 * @link https://docs.gravityforms.com/gform_counter_script//
	 */
	function strt_counter_script( $script ) {
		$script = str_replace( 'ginput_counter_tinymce', 'ginput_counter_tinymce position-absolute bottom-0', $script );
		$script = str_replace( 'ginput_counter gfield_description', 'ginput_counter gfield_description small lh-sm fw-medium', $script );
		return $script;
	}
	add_filter( 'gform_counter_script', 'strt_counter_script', 10, 5 );

	/**
	 * Change classes on Submit button.
	 *
	 * @param string $button The button html.
	 */
	function strt_submit_button( $button ) {
		$button = str_replace( 'class=\'gform_button', 'class=\'gform_button btn btn-primary', $button );
		return $button;
	}
	add_filter( 'gform_submit_button', 'strt_submit_button', 10, 2 );

	/**
	 * Change classes on Next button.
	 *
	 * @param string $button The button html.
	 */
	function strt_next_button( $button ) {
		$button = str_replace( 'class=\'gform_next_button', 'class=\'btn btn-primary gform_next_button', $button );
		return $button;
	}
	add_filter( 'gform_next_button', 'strt_next_button', 10, 2 );

	/**
	 * Change classes on Previous button.
	 *
	 * @param string $button The button html.
	 */
	function strt_previous_button( $button ) {
		$button = str_replace( 'class=\'gform_previous_button', 'class=\'btn btn-outline-primary gform_previous_button', $button );
		return $button;
	}
	add_filter( 'gform_previous_button', 'strt_previous_button', 10, 2 );

	/**
	 * Change classes on Save & Continue Later button.
	 *
	 * @param string $button The button html.
	 */
	function strt_savecontinue_link( $button ) {
		$button = str_replace( 'class=\'gform_save_link', 'class=\'btn btn-outline-secondary gform_save_link', $button );
		return $button;
	}
	add_filter( 'gform_savecontinue_link', 'strt_savecontinue_link', 10, 2 );

	/**
	 * Change classes on Progress bars.
	 *
	 * @param string $progress_bar The progressbar html.
	 * @link https://docs.gravityforms.com/gform_progress_bar/
	 */
	function strt_grogress_bar( $progress_bar ) {
		$progress_bar = str_replace( 'gf_progressbar_wrapper', 'gf_progressbar_wrapper mt-4 mb-3', $progress_bar );
		$progress_bar = str_replace( 'gf_progressbar_title', 'small text-body-secondary text-uppercase mb-1 gf_progressbar_title', $progress_bar );
		$progress_bar = str_replace( 'gf_progressbar ', 'progress gf_progressbar ', $progress_bar );
		$progress_bar = str_replace( 'gf_progressbar_percentage', 'progress-bar progress-bar-striped progress-bar-animated progress_percentage', $progress_bar );
		$progress_bar = str_replace( 'percentbar_blue', 'bg-primary percentbar_blue', $progress_bar );
		$progress_bar = str_replace( 'percentbar_gray', 'bg-secondary percentbar_gray', $progress_bar );
		$progress_bar = str_replace( 'percentbar_green', 'bg-success percentbar_green', $progress_bar );
		$progress_bar = str_replace( 'percentbar_orange', 'bg-warning percentbar_orange', $progress_bar );
		$progress_bar = str_replace( 'percentbar_red', 'bg-danger percentbar_red', $progress_bar );
		return $progress_bar;
	}
	add_filter( 'gform_progress_bar', 'strt_grogress_bar', 10, 3 );

	/**
	 * Change the main validation message.
	 *
	 * @param string $message The validation message.
	 */
	function strt_validation_message( $message ) {
		$message = str_replace( 'h2', 'h4', $message );
		return $message;
	}
	add_filter( 'gform_validation_message', 'strt_validation_message', 10, 2 );

	/**
	 * Hide Gravityforms Spinner.
	 */
	function strt_ajax_spinner_url() {
		return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
	}
	add_filter( 'gform_ajax_spinner_url', 'strt_ajax_spinner_url' );

	/**
	 * Load custom CSS for .gform_ajax_spinner in <head> tag.
	 */
	function strt_custom_styles() {
		echo '<style>
			.gform_ajax_spinner {
				border: 0.2em solid var(--bs-secondary);
				border-right-color: transparent;
				display: inline-block;
				width: 1rem;
				height: 1rem;
				margin-left: 0.75rem;
				vertical-align: middle;
				border-radius: 50%;
				animation: 0.75s linear infinite spinner-border;
			}

			:root {
				--chosen-input-height: 38px;
				--chosen-font-size: 15px;
			}

			.chosen-container {
				font-family: var(--bs-font-sans-serif) !important;
				font-size: var(--chosen-font-size) !important;
			}

			.chosen-container-single .chosen-single {
				height: var(--chosen-input-height) !important;
				line-height: var(--chosen-line-height) !important;
			}

			.chosen-container-multi .chosen-choices li.search-field input[type="text"] {
				font-family: var(--bs-font-sans-serif) !important;
				height: var(--chosen-input-height) !important;
			}

		</style>';
	}
	add_action( 'wp_head', 'strt_custom_styles' );
}
