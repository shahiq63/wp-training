<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_testimonial
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['style'] string Quote style: '1' / '2' / '3' / '4'
 * @param $atts           ['author'] string Author name
 * @param $atts           ['company'] string Author subtitle
 * @param $atts           ['img'] int Author photo (ID from WP media library)
 * @param $atts           ['link'] string Author Link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:_blank|rel:nofollow'
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_testimonial' );

$classes = '';

if ( $atts['style'] == '' ) {
	$atts['style'] = '1';
}
$classes .= ' style_' . $atts['style'];

if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

$img_id = intval( $atts['img'] );
$image_html = '';
if ( $img_id ) {
	$image_html = us_get_attachment_image( $img_id, 'thumbnail' );
}

$link_start = $link_end = '';
$link = us_vc_build_link( $atts['link'] );

if ( ! empty( $link['url'] ) ) {
	$link_target = ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
	$link_rel = ( $link['rel'] == 'nofollow' ) ? ' rel="nofollow"' : '';
	$link_title = empty( $link['title'] ) ? '' : ( ' title="' . esc_attr( $link['title'] ) . '"' );
	$link_start = '<a href="' . esc_url( $link['url'] ) . '"' . $link_target . $link_rel . $link_title . '>';
	$link_end = '</a>';
}

$output = '<div class="w-testimonial' . $classes . '">';
$output .= '<blockquote class="w-testimonial-h">';
$output .= '<div class="w-testimonial-text">' . do_shortcode( $content ) . '</div>';
if ( ! empty( $image_html ) OR ! empty( $atts['author'] ) OR ! empty( $atts['company'] ) ) {
	$output .= $link_start . '<cite class="w-testimonial-author">' . $image_html;
	if ( ! empty( $atts['author'] ) ) {
		$output .= '<span class="w-testimonial-author-name">' . $atts['author'] . '</span>';
	}
	if ( ! empty( $atts['company'] ) ) {
		$output .= ' <span class="w-testimonial-author-role">' . $atts['company'] . '</span>';
	}
	$output .= '</cite>' . $link_end;
}
$output .= '</blockquote></div>';

echo $output;
