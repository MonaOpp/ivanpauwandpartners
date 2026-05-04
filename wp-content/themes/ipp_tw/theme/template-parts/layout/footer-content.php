<?php
/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ipp_tw
 */

$facebook_link  = function_exists( 'get_field' ) ? get_field( 'facebook_link', 'option' ) : '';
$linkedin_link  = function_exists( 'get_field' ) ? get_field( 'linkedin', 'option' ) : '';
$instagram_link = function_exists( 'get_field' ) ? get_field( 'instagram_link', 'option' ) : '';
$tel_number     = function_exists( 'get_field' ) ? get_field( 'tel_number', 'option' ) : '';
$email          = function_exists( 'get_field' ) ? get_field( 'email_', 'option' ) : '';

// ACF link fields may return arrays — extract the URL.
if ( is_array( $facebook_link ) ) {
	$facebook_link = $facebook_link['url'] ?? '';
}
if ( is_array( $linkedin_link ) ) {
	$linkedin_link = $linkedin_link['url'] ?? '';
}
if ( is_array( $instagram_link ) ) {
	$instagram_link = $instagram_link['url'] ?? '';
}
if ( is_array( $tel_number ) ) {
	$tel_number = $tel_number['url'] ?? ( $tel_number['value'] ?? '' );
}
if ( is_array( $email ) ) {
	$email = $email['url'] ?? ( $email['value'] ?? '' );
}
?>

<?php get_template_part( 'template-parts/content/content', 'cta-banner' ); ?>

<div class="footer-marquee">
	<div class="footer-marquee-track">
		<span>Experience. Quality. Solutions.</span>
		<span>Experience. Quality. Solutions.</span>
		<span>Experience. Quality. Solutions.</span>
		<span>Experience. Quality. Solutions.</span>
	</div>
</div>

<footer id="colophon" class="site-footer">

	<div class="layout-wrapper">

		<!-- Top row: logo left, social icons right -->
		<div class="footer-top">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo" aria-label="<?php bloginfo( 'name' ); ?>">
				<img
					src="<?php echo esc_url( content_url( '/uploads/2026/04/Logo-Container.png' ) ); ?>"
					alt="<?php bloginfo( 'name' ); ?>"
				>
			</a>

			<div class="footer-social">
				<?php if ( $facebook_link ) : ?>
					<a href="<?php echo esc_url( $facebook_link ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Facebook', 'ipp_tw' ); ?>">
						<img src="<?php echo esc_url( content_url( '/uploads/2026/04/facebook.png' ) ); ?>" alt="Facebook">
					</a>
				<?php endif; ?>
				<?php if ( $linkedin_link ) : ?>
					<a href="<?php echo esc_url( $linkedin_link ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn', 'ipp_tw' ); ?>">
						<img src="<?php echo esc_url( content_url( '/uploads/2026/05/linkedin.png' ) ); ?>" alt="LinkedIn">
					</a>
				<?php endif; ?>
				<?php if ( $instagram_link ) : ?>
					<a href="<?php echo esc_url( $instagram_link ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Instagram', 'ipp_tw' ); ?>">
						<img src="<?php echo esc_url( content_url( '/uploads/2026/04/Subtract.png' ) ); ?>" alt="Instagram">
					</a>
				<?php endif; ?>
			</div>
		</div>

		<!-- Divider -->
		<hr class="footer-divider">

		<!-- Bottom row: copyright left, contact right -->
		<div class="footer-bottom">
			<p class="footer-copyright">
				&copy; Ivan Pauw &amp; Partners. 2025 All rights reserved - Creatively Brought to Life By Starbright
			</p>

			<div class="footer-contact">
				<?php if ( $tel_number ) : ?>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $tel_number ) ); ?>" class="footer-contact-item">
						<img src="<?php echo esc_url( content_url( '/uploads/2026/04/Vector.png' ) ); ?>" alt="Phone">
						<span><?php echo esc_html( $tel_number ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<a href="mailto:<?php echo esc_attr( $email ); ?>" class="footer-contact-item">
						<img src="<?php echo esc_url( content_url( '/uploads/2026/04/Vector-1.png' ) ); ?>" alt="Email">
						<span><?php echo esc_html( $email ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>

	</div>
</footer><!-- #colophon -->
