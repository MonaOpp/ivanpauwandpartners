<?php
/**
 * Template part for the home page hero slider.
 *
 * Uses ACF Options page "Home Slider" with repeater field: slider_banner
 * Sub-fields: banner_image, banner_text.
 * Powered by Swiper.js.
 *
 * @package ipp_tw
 */

if ( ! function_exists( 'have_rows' ) || ! have_rows( 'slider_banner', 'option' ) ) {
	return;
}

$facebook_link  = function_exists( 'get_field' ) ? get_field( 'facebook_link', 'option' ) : '';
$linkedin_link  = function_exists( 'get_field' ) ? get_field( 'linkedin_link', 'option' ) : '';
$instagram_link = function_exists( 'get_field' ) ? get_field( 'instagram_link', 'option' ) : '';
$tel_number     = function_exists( 'get_field' ) ? get_field( 'tel_number', 'option' ) : '';
$email          = function_exists( 'get_field' ) ? get_field( 'email_', 'option' ) : '';

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
	$tel_number = is_array( $tel_number ) ? ( $tel_number['url'] ?? ( $tel_number['value'] ?? '' ) ) : $tel_number;
}
if ( is_array( $email ) ) {
	$email = is_array( $email ) ? ( $email['url'] ?? ( $email['value'] ?? '' ) ) : $email;
}
?>

<section class="home-slider">
	<div class="swiper home-swiper">
		<div class="swiper-wrapper">
			<?php
			$slide_index = 0;
			while ( have_rows( 'slider_banner', 'option' ) ) :
				the_row();
				$image = get_sub_field( 'banner_image' );
				$text  = get_sub_field( 'banner_text' );

				if ( ! $image ) {
					continue;
				}

				$image_url = is_array( $image ) ? ( $image['url'] ?? '' ) : $image;
				if ( ! $image_url ) {
					continue;
				}

				$heading_tag = ( 0 === $slide_index ) ? 'h1' : 'h2';
				?>
				<div class="swiper-slide">
					<div class="home-slide" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
						<div class="home-slide__overlay"></div>
						<?php if ( $text ) : ?>
							<?php
							// Strip <p> tags from WYSIWYG, convert closing </p> to <br>, then clean up.
							$clean_text = str_replace( array( '</p><p>', '</p>' . "\n" . '<p>', '</p>' ), '<br>', $text );
							$clean_text = str_replace( '<p>', '', $clean_text );
							$clean_text = preg_replace( '/<br\s*\/?>$/', '', trim( $clean_text ) ); // Remove trailing <br>.
							?>
							<div class="home-slide__content layout-wrapper">
								<<?php echo $heading_tag; ?>><?php echo wp_kses( $clean_text, array( 'span' => array( 'style' => true, 'class' => true ), 'em' => array( 'style' => true ), 'i' => array( 'style' => true ), 'strong' => array( 'style' => true ), 'b' => array( 'style' => true ), 'br' => array() ) ); ?></<?php echo $heading_tag; ?>>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php
				$slide_index++;
			endwhile;
			?>
		</div>

		<!-- Bottom bar: Prev/Next left, Social icons right -->
		<div class="slider-bottom-bar layout-wrapper">
			<div class="slider-nav">
				<button class="slider-prev" aria-label="<?php esc_attr_e( 'Previous slide', 'ipp_tw' ); ?>">Prev</button>
				<span class="slider-nav-line"></span>
				<button class="slider-next" aria-label="<?php esc_attr_e( 'Next slide', 'ipp_tw' ); ?>">Next</button>
			</div>

			<?php
			$contact_page = get_page_by_path( 'contact-us' );
			$contact_url  = $contact_page ? get_permalink( $contact_page ) : home_url( '/contact-us/' );
			?>
			<a href="<?php echo esc_url( $contact_url ); ?>" class="slider-cta">
				<?php esc_html_e( 'Contact Us', 'ipp_tw' ); ?>
			</a>

			<div class="slider-social">
				<?php if ( $facebook_link ) : ?>
					<a href="<?php echo esc_url( $facebook_link ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Facebook', 'ipp_tw' ); ?>">
						<img src="<?php echo esc_url( content_url( '/uploads/2026/04/facebook-1.png' ) ); ?>" alt="Facebook">
					</a>
				<?php endif; ?>
				<?php if ( $linkedin_link ) : ?>
					<a href="<?php echo esc_url( $linkedin_link ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn', 'ipp_tw' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#fff" width="24" height="24" aria-hidden="true"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
					</a>
				<?php endif; ?>
				<?php if ( $instagram_link ) : ?>
					<a href="<?php echo esc_url( $instagram_link ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Instagram', 'ipp_tw' ); ?>">
						<img src="<?php echo esc_url( content_url( '/uploads/2026/04/Subtract-1.png' ) ); ?>" alt="Instagram">
					</a>
				<?php endif; ?>
				<?php if ( $tel_number ) : ?>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $tel_number ) ); ?>" aria-label="<?php esc_attr_e( 'Phone', 'ipp_tw' ); ?>">
						<img src="<?php echo esc_url( content_url( '/uploads/2026/04/phone-2.png' ) ); ?>" alt="Phone">
					</a>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<a href="mailto:<?php echo esc_attr( $email ); ?>" aria-label="<?php esc_attr_e( 'Email', 'ipp_tw' ); ?>">
						<img src="<?php echo esc_url( content_url( '/uploads/2026/04/mail-2.png' ) ); ?>" alt="Email">
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
