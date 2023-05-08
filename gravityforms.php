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
	if ( ! is_admin() ) {
		/** Remove legend. */
		add_filter( 'gform_required_legend', '__return_empty_string' );

		/** Disable Gravity Forms CSS. */
		add_filter( 'pre_option_rg_gforms_disable_css', '__return_true' );

		/** Enable HTML5. */
		add_filter( 'pre_option_rg_gforms_enable_html5', '__return_true' );

		/** Prevent the IP address being saved. */
		add_filter( 'gform_ip_address', '__return_empty_string' );

		/**
		 * Register styles to be used on Gravity Forms preview pages.
		 *
		 * @param array $styles The registerd stylesheet.
		 */
		function strt_preview_styles( $styles ) {
			wp_register_style( 'my_stylesheet', get_template_directory_uri() . '/assets/dist/css/frontend.css', null, '1.0' );
			$styles = array( 'my_stylesheet' );
			return $styles;
		}
		add_filter( 'gform_preview_styles', 'strt_preview_styles', 10, 2 );

		/**
		 * Add Gravity Forms capabilities To Editor role.
		 */
		function strt_add_gf_capabilities() {
			$role = get_role( 'editor' );
			// $role->add_cap( 'gform_full_access' );
			// $role->remove_cap('gform_full_access');
		}
		add_action( 'init', 'strt_add_gf_capabilities' );

		/**
		 * Modify the fields classes to Bootstrap classes.
		 *
		 * @param string $content Field content.
		 * @param object $field Field options.
		 */
		function strt_field_content( $content, $field ) {
			// Exclude these fieldtypes for later customisation.
			$exclude_formcontrol = array(
				'hidden',
				'select',
				'multiselect',
				'checkbox',
				'radio',
				'list',
				'html',
				'address',
				'post_category',
				'product',
			);

			// Add .form-control to most inputs except those listed.
			if ( ! in_array( $field['type'], $exclude_formcontrol, true ) ) {
				$content = str_replace( 'class=\'small', 'class=\'form-control form-control-sm', $content );
				$content = str_replace( 'class=\'medium', 'class=\'form-control', $content );
				$content = str_replace( 'class=\'large', 'class=\'form-control form-control-lg', $content );
			}

			// Labels.
			$content = str_replace( 'gfield_label ', 'gfield_label form-label fw-semibold small lh-sm ', $content );
			$content = str_replace( 'gform-field-label--type-sub', 'gform-field-label--type-sub small fw-medium lh-sm d-block ', $content );

			// Descriptions.
			$content = str_replace( 'class=\'gfield_description', 'class=\'gfield_description small text-body-secondary d-block lh-sm', $content );

			// Select fields.
			$content = str_replace( 'class=\'small gfield_select', 'class=\'gfield_select form-select form-select-sm', $content );
			$content = str_replace( 'class=\'medium gfield_select', 'class=\'gfield_select form-select', $content );
			$content = str_replace( 'class=\'large gfield_select', 'class=\'gfield_select form-select form-select-lg', $content );

			// Textarea fields.
			$content = str_replace( 'class=\'textarea small', 'class=\'textarea form-control form-control-sm', $content );
			$content = str_replace( 'class=\'textarea medium', 'class=\'textarea form-control', $content );
			$content = str_replace( 'class=\'textarea large', 'class=\'textarea form-control form-control-lg', $content );
			$content = str_replace( 'rows=\'10\'', 'rows=\'4\'', $content );

			// Validation message.
			$content = str_replace( 'gfield_validation_message', 'gfield_validation_message alert alert-warning p-1 my-2', $content );

			// Sections.
			$content = str_replace( 'gsection_title', 'gsection_title mt-3 pb-1 mb-1 border-bottom', $content );
			$content = str_replace( 'class=\'gsection_description', 'class=\'gsection_description text-body-secondary', $content );

			// Checkbox & Radio fields.
			if ( 'checkbox' === $field['type'] || 'radio' === $field['type'] || 'checkbox' === $field['inputType'] || 'radio' === $field['inputType'] ) {
				$content = str_replace( 'gchoice ', 'gchoice form-check ', $content );
				$content = str_replace( 'gfield-choice-input', 'gfield-choice-input form-check-input', $content );
				$content = str_replace( 'gform-field-label', 'gform-field-label form-check-label', $content );
				$content = str_replace( 'type="button"', 'type="button" class="btn btn-primary btn-sm"', $content ); // Checkbox 'Select All' option.
				$content = str_replace( 'gchoice_other_control', 'gchoice_other_control form-control form-control-sm', $content ); // Radio 'Other' option.
			}

			// Complex fields layout.
			$content = str_replace( 'gform-grid-row', 'gform-grid-row row gx-2', $content );
			$content = str_replace( 'gform-grid-col', 'gform-grid-col col', $content );
			$content = str_replace( 'ginput_left', 'col-6 ginput_left', $content );
			$content = str_replace( 'ginput_right', 'col-6 ginput_right', $content );
			$content = str_replace( 'ginput_full', 'col-12 ginput_full', $content );

			// Complex fields: Name, Address & Time fields.
			if ( 'name' === $field['type'] || 'address' === $field['type'] || 'time' === $field['type'] ) {
				$content = str_replace( 'type=\'text\'', 'type=\'text\' class=\'form-control\'', $content );
				$content = str_replace( 'type=\'number\'', 'type=\'number\' class=\'form-control\'', $content );
				$content = str_replace( '<select ', '<select class=\'form-select\' ', $content );
				$content = str_replace( 'hour_minute_colon', 'hour_minute_colon flex-grow-0 pt-1', $content );
			}

			// Complex fields: Date fields.
			if ( 'date' === $field['type'] ) {
				$content = str_replace( '<select', '<select class=\'form-select\'', $content );
				$content = str_replace( 'type=\'number\'', 'type=\'number\' class=\'form-control\'', $content );
				$content = str_replace( 'class=\'datepicker', 'class=\'datepicker form-control', $content );
			}

			// Complex fields: Add margins.
			if ( 'address' === $field['type'] || 'post_image' === $field['type'] ) {
				$content = str_replace( 'gform-grid-col', 'gform-grid-col mb-2', $content );
			}

			// Email fields.
			if ( 'email' === $field['type'] ) {
				$content = str_replace( '<input class=\'\'', '<input class=\'form-control\'', $content ); // Email Field with confirm email enabled.
			}

			// Consent fields.
			if ( 'consent' === $field['type'] ) {
				$content = str_replace( 'ginput_container_consent', 'ginput_container_consent form-check', $content );
				$content = str_replace( 'type=\'checkbox\'', 'type=\'checkbox\' class=\'form-check-input\' ', $content );
				$content = str_replace( 'gfield_consent_label', 'gfield_consent_label form-check-label', $content );
				$content = str_replace( 'class=\'gfield_description', 'style=\' max-height: 100px;\' class=\'gfield_description border mt-1 p-1 overflow-y-auto', $content );
			}

			// List fields.
			if ( 'list' === $field['type'] ) {
				$content = str_replace( 'type=\'text\'', 'type=\'text\' class=\'form-control\'', $content );
				$content = str_replace( 'gfield_header_item--icons gform-grid-col col', 'gfield_header_item--icons gform-grid-col', $content ); // Remove 'col' class.
				$content = str_replace( 'gfield_list_icons gform-grid-col col', 'gfield_list_icons gform-grid-col', $content ); // Remove 'col' class.
				$content = str_replace( 'gform-field-label', 'small fw-medium lh-sm gform-field-label', $content );
				$content = str_replace( 'gfield_list_group ', 'gfield_list_group mb-2 ', $content );
			}

			// Fileupload & Post Image fields.
			if ( 'fileupload' === $field['type'] || 'post_image' === $field['type'] ) {
				$content = str_replace( 'type=\'text\'', 'type=\'text\' class=\'form-control form-control-sm\'', $content ); // Post Image meta fields.

				// Mutli file upload.
				if ( true === $field['multipleFiles'] ) {
					$content = str_replace( 'class=\'gform_drop_area', 'class=\'gform_drop_area bg-light p-3 text-center border', $content );
					$content = str_replace( 'class=\'gform_drop_instructions', 'class=\'gform_drop_instructions d-block small', $content );
					$content = str_replace( 'validation_message', 'list-unstyled validation_message', $content );
					$content = str_replace( 'class=\'button', 'class=\'btn btn-primary btn-sm', $content );
				}
			}

			// Product price.
			if ( 'product' === $field['type'] ) {
				$content = str_replace( 'class=\'ginput_product_price ', 'disabled class=\'ginput_product_price form-control ', $content );
				$content = str_replace( '\'ginput_quantity\'', '\'ginput_quantity form-control\'', $content );
				$content = str_replace( 'ginput_quantity_label', 'ginput_quantity_label form-label d-block small lh-sm', $content );
				$content = str_replace( 'ginput_amount', 'ginput_amount form-control', $content );
			}

			// Product total.
			if ( 'total' === $field['type'] ) {
				$content = str_replace( 'ginput_total', 'form-control ginput_total', $content );
				$content = str_replace( 'readonly', 'disabled readonly', $content );
			}

			return $content;
		}
		add_filter( 'gform_field_content', 'strt_field_content', 10, 5 );

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
	}

	/**
	 * Reset the Gavity Forms widths classes in the form editor so only column layout appears.
	 */
	function strt_reset_sizes() {
		echo '<style>
		.gform_wrapper.gravity-theme .gfield input.small, .gform_wrapper.gravity-theme .gfield select.small,
		.gform_wrapper.gravity-theme .gfield input.medium, .gform_wrapper.gravity-theme .gfield select.medium {
			width: 100% !important;
		}</style>';
	}
	add_action( 'admin_head', 'strt_reset_sizes' );
}
