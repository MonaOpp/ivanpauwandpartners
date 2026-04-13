<?php
/**
 * Call to Action banner — appears above the footer on every page.
 *
 * ACF fields (per page / post):
 *   - cta_background_color : color_picker — background colour
 *   - call_to_action       : wysiwyg      — the call-to-action copy
 *
 * @package ipp_tw
 */

$post_id  = get_queried_object_id();
$cta_bg   = function_exists( 'get_field' ) ? get_field( 'cta_background_color', $post_id ) : '';
$cta_text = function_exists( 'get_field' ) ? get_field( 'call_to_action', $post_id ) : '';

// Contact page URL.
$contact_page = get_permalink( get_page_by_path( 'contact-us' ) );
if ( ! $contact_page ) {
	$contact_page = home_url( '/contact-us/' );
}
?>

<section class="cta-banner"<?php echo $cta_bg ? ' style="background-color: ' . esc_attr( $cta_bg ) . ';"' : ''; ?>>
	<div class="layout-wrapper mx-auto px-6">
		<div class="cta-banner__inner">
			<div class="cta-banner__text"><?php echo wp_kses_post( $cta_text ); ?></div>
			<a href="<?php echo esc_url( $contact_page ); ?>" class="cta-banner__btn">Contact Us</a>
		</div>
	</div>
</section>
