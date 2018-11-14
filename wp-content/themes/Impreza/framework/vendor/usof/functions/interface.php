<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

add_action( 'admin_menu', 'us_options_admin_menu', 9 );
function us_options_admin_menu() {
	add_menu_page( __( 'Theme Options', 'us' ), US_THEMENAME, 'manage_options', 'us-theme-options', 'us_theme_options_page', NULL, '59.001' );
	$usof_page = add_submenu_page( 'us-theme-options', US_THEMENAME, __( 'Theme Options', 'us' ), 'edit_theme_options', 'us-theme-options', 'us_theme_options_page' );
	add_action( 'admin_print_scripts-' . $usof_page, 'usof_print_scripts' );
	add_action( 'admin_print_styles-' . $usof_page, 'usof_print_styles' );
	add_action( 'admin_print_styles-' . $usof_page, 'usof_print_fonts' );

	add_action( 'admin_print_scripts-post-new.php', 'usof_print_scripts' );
	add_action( 'admin_print_scripts-post.php', 'usof_print_scripts' );

	add_action( 'admin_print_scripts-nav-menus.php', 'usof_print_scripts' );

	add_action( 'admin_notices', 'usof_hide_admin_notices_start', 1 );
	add_action( 'admin_notices', 'usof_hide_admin_notices_end', 1000 );
}

function us_theme_options_page() {

	// For notices
	echo '<div class="wrap"><h2 class="hidden"></h2>';

	global $usof_options, $usof_img_sizes, $usof_enable_portfolio, $usof_primary_color;
	usof_load_options_once();
	$usof_options = array_merge( usof_defaults(), $usof_options );

	// Preserving values for hidden fields
	$hidden_fields_values = array();

	$visited_new_sections = array();
	if ( isset( $_COOKIE ) AND isset( $_COOKIE['usof_visited_new_sections'] ) ) {
		$visited_new_sections = explode( ',', $_COOKIE['usof_visited_new_sections'] );
	}

	$usof_img_sizes = array_flip( us_image_sizes_select_values() );
	$usof_enable_portfolio = us_get_option( 'enable_portfolio', 1 );
	$usof_primary_color = us_get_option( 'color_content_primary' );

	$config = us_config( 'theme-options', array(), TRUE );
	echo '<div class="usof-container';
	echo apply_filters( 'usof_container_classes', '' );
	if ( get_option( 'us_license_activated', 0 ) OR ( defined( 'US_DEV' ) AND US_DEV ) ) {
		echo ' theme_activated';
	}
	echo '" data-ajaxurl="' . esc_attr( admin_url( 'admin-ajax.php' ) ) . '">';
	echo '<form class="usof-form" method="post" action="#" autocomplete="off">';
	// Output _nonce and _wp_http_referer hidden fields for ajax secuirity checks
	wp_nonce_field( 'usof-actions' );
	echo '<div class="usof-header">';
	echo '<div class="usof-header-logo">';
	echo US_THEMENAME . ' <span>' . US_THEMEVERSION . '</span></div>';
	echo '<div class="usof-header-title"><span>' . __( 'Theme Options', 'us' ) . '&nbsp;&mdash;&nbsp;</span>';
	echo '<h2>' . us_translate_x( 'General', 'settings screen' ) . '</h2></div>';
	// Control for opening color schemes window
	echo '<div class="usof-control for_schemes hidden">';
	echo '<button class="usof-button" type="button">' . __( 'Color Schemes', 'us' ) . '</button>';
	echo '</div>';
	// Control for saving changes button
	echo '<div class="usof-control for_save status_clear">';
	echo '<button class="usof-button type_save" type="button"><span>' . us_translate( 'Save Changes' ) . '</span>';
	echo '<span class="usof-preloader"></span></button>';
	echo '<div class="usof-control-message"></div>';
	echo '</div>';
	echo '</div>';

	// Saving empty or outdated selects
	$empty_select_present = FALSE;

	$usof_options_fallback = array_merge( usof_defaults(), $usof_options );
	$updated_options = array();
	foreach ( $usof_options_fallback as $key => $val ) {
		$updated_options[$key] = $val;
	}

	foreach( $config as $section_id => $section ) {
		if ( isset( $section['fields'] ) ) {
			foreach ( $section['fields'] as $field_id => $field ) {
				if ( $field['type'] == 'select' ) {
					$field_values = array_keys( $field['options'] );
					if ( ! isset( $updated_options[$field_id] ) OR ! in_array( $updated_options[$field_id], $field_values ) ) {
						$empty_select_present = TRUE;
						$updated_options[$field_id] = array_shift( $field_values );
					}
				}
			}
		}
	}

	if ( $empty_select_present ) {
		usof_save_options( $updated_options );
	}

	// Sided Menu
	echo '<div class="usof-nav"><div class="usof-nav-bg"></div><ul class="usof-nav-list level_1">';
	foreach ( $config as $section_id => &$section ) {
		if ( isset( $section['place_if'] ) AND ! $section['place_if'] ) {
			continue;
		}
		if ( ! isset( $active_section ) ) {
			$active_section = $section_id;
		}
		echo '<li class="usof-nav-item level_1 id_' . $section_id . ( ( $section_id == $active_section ) ? ' current' : '' ) . '"';
		echo ' data-id="' . $section_id . '">';
		echo '<a class="usof-nav-anchor level_1" href="#' . $section_id . '">';
		echo '<img class="usof-nav-icon" src="' . $section['icon'] . '.png" srcset="' . $section['icon'] . '-2x.png 2x" alt="">';
		echo '<span class="usof-nav-title">' . $section['title'] . '</span>';
		echo '<span class="usof-nav-arrow"></span>';
		echo '</a>';
		if ( isset( $section['new'] ) AND $section['new'] AND ! in_array( $section_id, $visited_new_sections ) ) {
			echo '<span class="usof-nav-popup">' . __( 'New', 'us' ) . '</span>';
		}
		echo '</li>';
	}
	echo '<ul></div>';

	// Content
	echo '<div class="usof-content">';
	foreach ( $config as $section_id => &$section ) {
		if ( isset( $section['place_if'] ) AND ! $section['place_if'] ) {
			if ( isset( $section['fields'] ) ) {
				$hidden_fields_values = array_merge( $hidden_fields_values, array_intersect_key( $usof_options, $section['fields'] ) );
			}
			continue;
		}
		echo '<section class="usof-section ' . ( ( $section_id == $active_section ) ? 'current' : '' ) . '" data-id="' . $section_id . '">';
		echo '<div class="usof-section-header" data-id="' . $section_id . '">';
		echo '<h3>' . $section['title'] . '</h3><span class="usof-section-header-control"></span></div>';
		echo '<div class="usof-section-content" style="display: ' . ( ( $section_id == $active_section ) ? 'block' : 'none' ) . '">';
		if ( isset( $section['fields'] ) ) {
			foreach ( $section['fields'] as $field_name => &$field ) {
				if ( isset( $field['place_if'] ) AND ! $field['place_if'] ) {
					if ( isset( $usof_options[$field_name] ) ) {
						$hidden_fields_values[$field_name] = $usof_options[$field_name];
					}
					continue;
				}
				us_load_template(
					'vendor/usof/templates/field', array(
						'name' => $field_name,
						'id' => 'usof_' . $field_name,
						'field' => $field,
						'values' => &$usof_options,
					)
				);
				unset( $hidden_fields_values[$field_name] );
			}
		}
		echo '</div></section>';
	}
	echo '</div>';

	echo '</form>';
	echo '</div>';

	echo '</div>';
	echo '<div class="usof-hidden-fields"' . us_pass_data_to_js( $hidden_fields_values ) . '></div>';
}

function usof_print_scripts() {
	global $usof_directory_uri;
	if ( ! did_action( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}
	wp_enqueue_script( 'usof-colorpicker', $usof_directory_uri . '/js/colpick.js', array( 'jquery' ), '1.0', TRUE );
	wp_enqueue_script( 'usof-select2', $usof_directory_uri . '/js/select2.min.js', array( 'jquery' ), '4.0.5', TRUE );
	wp_enqueue_script( 'usof-scripts', $usof_directory_uri . '/js/usof.js', array( 'jquery' ), US_THEMEVERSION, TRUE );
	do_action( 'usof_print_scripts' );
}

function usof_print_styles() {
	global $usof_directory_uri, $us_template_directory_uri;
	wp_enqueue_style( 'usof-styles', $usof_directory_uri . '/css/usof.css', array(), US_THEMEVERSION );
	do_action( 'usof_print_styles' );
}

function usof_print_fonts() {
	$prefixes = array( 'heading', 'body' );
	$font_options = array();
	$fonts = array();

	$uploaded_fonts = us_get_option( 'uploaded_fonts', array() );
	$uploaded_font_names = array();
	if ( is_array( $uploaded_fonts ) AND count( $uploaded_fonts ) > 0 ) {
		foreach ( $uploaded_fonts as $uploaded_font ) {
			$uploaded_font_names[] = esc_attr( strip_tags( $uploaded_font['name'] ) );
		}
	}

	foreach ( $prefixes as $prefix ) {
		$font_option = explode( '|', us_get_option( $prefix . '_font_family', 'none' ), 2 );
		if ( in_array( $font_option[0], $uploaded_font_names ) ) {
			continue;
		}
		$font_options[] = $font_option;
	}

	$custom_fonts = us_get_option( 'custom_font', array() );
	if ( is_array( $custom_fonts ) AND count( $custom_fonts ) > 0 ) {
		foreach ( $custom_fonts as $custom_font ) {
			$font_options[] = explode( '|', $custom_font['font_family'], 2 );
		}
	}

	foreach ( $font_options as $font ) {
		if ( ! isset( $font[1] ) OR empty( $font[1] ) ) {
			// Fault tolerance for missing font-variants
			$font[1] = '400,700';
		}
		$selected_font_variants = explode( ',', $font[1] );
		// Empty font or web safe combination selected
		if ( $font[0] == 'none' OR strpos( $font[0], ',' ) !== FALSE ) {
			continue;
		}

		$font[0] = str_replace( ' ', '+', $font[0] );
		if ( ! isset( $fonts[$font[0]] ) ) {
			$fonts[$font[0]] = array();
		}

		foreach ( $selected_font_variants as $font_variant ) {
			$fonts[$font[0]][] = $font_variant;
		}
	}

	$protocol = is_ssl() ? 'https' : 'http';
	$subset = '&subset=' . us_get_option( 'font_subset', 'latin' );
	$font_index = 1;
	foreach ( $fonts as $font_name => $font_variants ) {
		if ( count( $font_variants ) == 0 ) {
			continue;
		}
		$font_variants = array_unique( $font_variants );

		// Google font url
		$font_url = $protocol . '://fonts.googleapis.com/css?family=' . $font_name . ':' . implode( ',', $font_variants ) . $subset;
		wp_enqueue_style( 'us-font-' . $font_index, $font_url );
		$font_index ++;
	}

	// Generate font-face for Uploaded Fonts
	$uploaded_fonts = us_get_option( 'uploaded_fonts', array() );
	if ( is_array( $uploaded_fonts ) AND count( $uploaded_fonts ) > 0 ) {
		echo '<style>';
		foreach ( $uploaded_fonts as $uploaded_font ) {
			$files = explode( ',', $uploaded_font['files'] );
			$urls = array();
			foreach ( $files as $file ) {
				$url = wp_get_attachment_url( $file );
				if ( $url ) {
					$urls[] = 'url(' . esc_url( $url ) . ') format("' . pathinfo( $url, PATHINFO_EXTENSION ) . '")';
				}
			}
			if ( count( $urls ) ) {
				$src = implode( ', ', $urls );
				echo '@font-face {';
				echo 'font-style: normal;';
				echo 'font-family:"' . strip_tags( $uploaded_font['name'] ) . '";';
				echo 'font-weight:' . $uploaded_font['weight'] . ';';
				echo 'src:' . $src . ';';
				echo '}';
			}
		}
		echo '</style>';
	}
}

function usof_hide_admin_notices_start() {
	?><div class="hidden"><?php
}

function usof_hide_admin_notices_end() {
	?></div><?php
}
