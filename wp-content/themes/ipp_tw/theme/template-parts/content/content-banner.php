<?php
/**
 * Template part for displaying the page banner.
 *
 * Uses ACF fields: banner_image (background) and page_title (H1 overlay).
 *
 * @package ipp_tw
 */

$banner_image = function_exists( 'get_field' ) ? get_field( 'banner_image' ) : '';
$page_title   = function_exists( 'get_field' ) ? get_field( 'page_title' ) : '';
$heading_text = ! empty( $page_title ) ? $page_title : get_the_title();

// ACF image field may return array or URL string.
if ( is_array( $banner_image ) ) {
	$banner_image = $banner_image['url'] ?? '';
}
?>

<?php if ( $banner_image ) : ?>
<section class="page-banner" style="background-image: url('<?php echo esc_url( $banner_image ); ?>');">
	<div class="page-banner__overlay"></div>
	<div class="page-banner__content layout-wrapper">
		<h1><?php echo esc_html( $heading_text ); ?></h1>
	</div>
</section>
<?php else : ?>
<section class="page-banner page-banner--no-image">
	<div class="page-banner__content layout-wrapper">
		<h1><?php echo esc_html( $heading_text ); ?></h1>
	</div>
</section>
<?php endif; ?>
