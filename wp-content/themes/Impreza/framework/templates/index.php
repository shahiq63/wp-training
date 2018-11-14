<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Index template (used for front page blog listing)
 */

$us_layout = US_Layout::instance();

get_header();

// Output Title Bar
us_load_template( 'templates/titlebar' );

// Get grid params from Theme Options
$template_vars = array(
	'items_layout' => us_get_option( 'blog_layout', 'blog_classic' ),
	'type' => us_get_option( 'blog_type', 'grid' ),
	'columns' => us_get_option( 'blog_cols', 1 ),
	'img_size' => us_get_option( 'blog_img_size', 'default' ),
	'items_gap' => us_get_option( 'blog_items_gap', 5 ) . 'rem',
	'pagination' => us_get_option( 'blog_pagination', 'regular' ),
	'pagination_btn_style' => us_get_option( 'blog_pagination_btn_style', '1' ),
);

?>
<div class="l-main">
	<div class="l-main-h i-cf">

		<main class="l-content"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="mainContentOfPage"' : ''; ?>>
			<section class="l-section<?php echo ( us_get_option( 'row_height' ) == 'small' ) ? ' height_small' : ''; ?>">
				<div class="l-section-h i-cf">

					<?php
					do_action( 'us_before_index' );
					global $us_grid_loop_running;
					$us_grid_loop_running = TRUE;
					us_load_template( 'templates/us_grid/listing', $template_vars );
					$us_grid_loop_running = FALSE;
					do_action( 'us_after_index' );
					?>

				</div>
			</section>
		</main>

		<?php us_load_template( 'templates/sidebar' ) ?>

	</div>
</div>

<?php get_footer() ?>
