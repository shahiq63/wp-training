<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_pricing
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['style'] string Table style: '1' / '2'
 * @param $atts           ['items'] string Pricing table items
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_pricing' );

$classes = $items_html = '';

if ( empty( $atts['items'] ) ) {
	$atts['items'] = array();
} else {
	$atts['items'] = json_decode( urldecode( $atts['items'] ), TRUE );
	if ( ! is_array( $atts['items'] ) ) {
		$atts['items'] = array();
	}
}

$classes .= ' style_' . $atts['style'];
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}
if ( count( $atts['items'] ) > 0 ) {
	$classes .= ' items_' . count( $atts['items'] );
}

foreach ( $atts['items'] as $index => $item ) {
	/**
	 * Filtering the included items
	 *
	 * @param $item ['title'] string Item title
	 * @param $item ['type'] string Item type: 'default' / 'featured'
	 * @param $item ['price'] string Item price
	 * @param $item ['substring'] string Price substring
	 * @param $item ['features'] string Comma-separated list of features
	 * @param $item ['btn_text'] string Button label
	 * @param $item ['btn_link'] string Button link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:_blank|rel:nofollow'
	 * @param $item ['btn_style'] string Button style: 'primary' / 'secondary' / 'light' / 'contrast' / 'black' / 'white'
	 * @param $item ['btn_size'] string Button size
	 * @param $item ['btn_icon'] string Button icon
	 * @param $item ['btn_iconpos'] string Icon position: 'left' / 'right'
	 */
	$item['type'] = ( isset( $item['type'] ) ) ? $item['type'] : 'default';
	$item['btn_icon'] = ( isset( $item['btn_icon'] ) ) ? $item['btn_icon'] : '';
	$item['btn_link'] = ( isset( $item['btn_link'] ) ) ? $item['btn_link'] : '';
	$item['btn_iconpos'] = ( isset( $item['btn_iconpos'] ) ) ? $item['btn_iconpos'] : 'left';

	$items_html .= '<div class="w-pricing-item type_' . $item['type'] . '"><div class="w-pricing-item-h"><div class="w-pricing-item-header">';
	if ( ! empty( $item['title'] ) ) {
		$items_html .= '<h5 class="w-pricing-item-title">' . $item['title'] . '</h5>';
	}
	$items_html .= '<div class="w-pricing-item-price">';
	if ( ! empty( $item['price'] ) ) {
		$items_html .= $item['price'];
	}
	if ( ! empty( $item['substring'] ) ) {
		$items_html .= '<small>' . $item['substring'] . '</small>';
	}
	$items_html .= '</div></div>';
	if ( ! empty( $item['features'] ) ) {
		$items_html .= '<ul class="w-pricing-item-features">';
		$features = explode( "\n", trim( $item['features'] ) );
		foreach ( $features as $feature ) {
			$items_html .= '<li class="w-pricing-item-feature">' . $feature . '</li>';
		}
		$items_html .= '</ul>';
	}
	if ( ! empty( $item['btn_text'] ) ) {
		$btn_classes = $icon_html = '';

		$btn_classes .= ' us-btn-style_' . $item['btn_style'];

		if ( ! isset( $item['btn_size'] ) OR trim( $item['btn_size'] ) == ( us_get_option( 'body_fontsize' ) . 'px' ) ) {
			$item['btn_size'] = '';
		}
		$btn_inline_css = us_prepare_inline_css(
			array(
				'font-size' => $item['btn_size'],
				// 'background-color' => isset( $item['btn_bg_color'] ) ? $item['btn_bg_color'] : '',
				// 'color' => isset( $item['btn_text_color'] ) ? $item['btn_text_color'] : '',
			)
		);

		if ( ! empty( $item['btn_icon'] ) ) {
			$icon_html = us_prepare_icon_tag( $item['btn_icon'] );
			$btn_classes .= ' icon_at' . $item['btn_iconpos'];
		} else {
			$btn_classes .= ' icon_none';
		}
		$btn_link = us_vc_build_link( $item['btn_link'] );
		$btn_link_attr = ( $btn_link['target'] == '_blank' ) ? ' target="_blank"' : '';
		$btn_link_attr .= ( $btn_link['rel'] == 'nofollow' ) ? ' rel="nofollow"' : '';
		$btn_link_attr .= empty( $btn_link['title'] ) ? '' : ( ' title="' . esc_attr( $btn_link['title'] ) . '"' );

		$items_html .= '<div class="w-pricing-item-footer">';
		$items_html .= '<a class="w-btn' . $btn_classes . '" href="' . esc_url( $btn_link['url'] ) . '"';
		$items_html .= $btn_link_attr . $btn_inline_css . '>';
		$items_html .= ( $item['btn_iconpos'] == 'left' ) ? $icon_html : '';
		$items_html .= '<span class="w-btn-label">' . strip_tags( $item['btn_text'], '<br>' ) . '</span>';
		$items_html .= ( $item['btn_iconpos'] == 'right' ) ? $icon_html : '';
		$items_html .= '</a>';
		$items_html .= '</div>';
	}
	$items_html .= '</div></div>';
}

// Output the element
$output = '<div class="w-pricing' . $classes . '">' . $items_html . '</div>';
echo $output;
