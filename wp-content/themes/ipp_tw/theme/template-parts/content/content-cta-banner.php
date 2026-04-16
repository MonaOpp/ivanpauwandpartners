<?php
/**
 * Call to Action banner - appears above the footer on every page.
 *
 * ACF fields (per page / post):
 *   - cta_background_color : color_picker
 *   - cta_title            : wysiwyg
 *   - call_to_action       : wysiwyg
 *
 * @package ipp_tw
 */

$post_id   = get_queried_object_id();
$cta_bg    = function_exists( 'get_field' ) ? get_field( 'cta_background_color', $post_id ) : '';
$cta_title = function_exists( 'get_field' ) ? get_field( 'cta_title', $post_id ) : '';
$cta_text  = function_exists( 'get_field' ) ? get_field( 'call_to_action', $post_id ) : '';

// On taxonomy archives, pull fields from the term.
if ( function_exists( 'get_field' ) && is_tax() ) {
$term_obj = get_queried_object();
$term_ref = $term_obj->taxonomy . '_' . $term_obj->term_id;
if ( ! $cta_bg )    $cta_bg    = get_field( 'cta_background_color', $term_ref );
if ( ! $cta_title ) $cta_title = get_field( 'cta_title', $term_ref );
if ( ! $cta_text )  $cta_text  = get_field( 'call_to_action', $term_ref );
}

// Do not display if no CTA content.
if ( empty( $cta_title ) && empty( $cta_text ) ) {
return;
}

// Contact page URL.
$contact_page = get_permalink( get_page_by_path( 'contact-us' ) );
if ( ! $contact_page ) {
$contact_page = home_url( '/contact-us/' );
}
?>

<section class="cta-banner"<?php echo $cta_bg ? ' style="background-color: ' . esc_attr( $cta_bg ) . ';"' : ''; ?>>
<div class="layout-wrapper mx-auto px-6">
<div class="cta-banner__inner">
<?php if ( $cta_title ) : ?>
<h4 class="cta-banner__title"><?php echo wp_kses_post( strip_tags( $cta_title ) ); ?></h4>
<?php endif; ?>
<?php if ( $cta_text ) : ?>
<div class="cta-banner__text"><?php echo wp_kses_post( $cta_text ); ?></div>
<?php endif; ?>
<?php if ( ! is_page( 'contact-us' ) ) : ?>
<a href="<?php echo esc_url( $contact_page ); ?>" class="cta-banner__btn">Contact Us</a>
<?php endif; ?>
</div>
</div>
</section>
