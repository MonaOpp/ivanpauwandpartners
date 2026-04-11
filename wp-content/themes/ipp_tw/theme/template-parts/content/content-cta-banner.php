<?php
/**
 * Call to Action banner — appears above the footer on every page.
 *
 * ACF fields (per page / post):
 *   - cta_background : select — "dark" (#18273A) or "gold" (#AA7040)
 *   - cta_text       : text   — the call-to-action copy
 *
 * @package ipp_tw
 */

$cta_bg   = function_exists( 'get_field' ) ? get_field( 'cta_background' ) : '';
$cta_text = function_exists( 'get_field' ) ? get_field( 'cta_text' ) : '';

// Defaults.
if ( ! $cta_bg ) {
	$cta_bg = 'dark';
}
if ( ! $cta_text ) {
	$cta_text = 'Get in touch with us today';
}

$bg_color = 'gold' === $cta_bg ? '#AA7040' : '#18273A';

// Contact page URL.
$contact_page = get_permalink( get_page_by_path( 'contact-us' ) );
if ( ! $contact_page ) {
	$contact_page = home_url( '/contact-us/' );
}
?>

<section class="cta-banner" style="background-color: <?php echo esc_attr( $bg_color ); ?>;">
	<div class="layout-wrapper mx-auto px-6">
		<div class="cta-banner__inner">
			<div class="cta-banner__text"><?php echo wp_kses_post( $cta_text ); ?></div>
			<a href="<?php echo esc_url( $contact_page ); ?>" class="cta-banner__btn">Contact Us</a>
		</div>
	</div>
</section>
