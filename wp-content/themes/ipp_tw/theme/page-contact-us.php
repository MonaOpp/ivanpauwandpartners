<?php
/**
 * Template for the Contact Us page.
 *
 * URL: /contact-us/
 *
 * Left column:  ACF "Get in Touch" section, page description, contact details, practice area categories.
 * Right column: Gravity Forms contact form.
 *
 * @package ipp_tw
 */

get_header();

// ACF fields.
$description = get_field( 'description' );

// Use the page content (post_content from the editor) as additional description.
$page_description = get_the_content();

// Contact details from the options page (same as footer).
$phone = function_exists( 'get_field' ) ? get_field( 'tel_number', 'option' ) : '';
$email = function_exists( 'get_field' ) ? get_field( 'email_', 'option' ) : '';

if ( is_array( $phone ) ) {
	$phone = $phone['url'] ?? ( $phone['value'] ?? '' );
}
if ( is_array( $email ) ) {
	$email = $email['url'] ?? ( $email['value'] ?? '' );
}

// Get practice-area-category terms.
$pa_categories = get_terms(
	array(
		'taxonomy'   => 'practice-area-category',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
?>

	<!-- Banner -->
	<?php get_template_part( 'template-parts/content/content', 'banner' ); ?>

	<!-- Contact section -->
	<section class="w-full bg-white" style="width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;">
		<div class="layout-wrapper mx-auto px-6 py-16">
			<div class="grid grid-cols-1 gap-12 lg:grid-cols-2">

				<!-- Left: Get in Touch + Practice Areas -->
				<div class="contact-info">

					<?php if ( $description ) : ?>
						<div class="contact-info__desc">
							<?php echo wp_kses_post( $description ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $phone ) : ?>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="contact-info__item">
							<svg class="contact-info__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
							<span><?php echo esc_html( $phone ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( $email ) : ?>
						<a href="mailto:<?php echo esc_attr( $email ); ?>" class="contact-info__item">
							<svg class="contact-info__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
							<span><?php echo esc_html( $email ); ?></span>
						</a>
					<?php endif; ?>

					<div class="contact-info__item">
						<svg class="contact-info__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
						<span>448 Sussex Ave, Lynnwood, Pretoria, 0081</span>
					</div>

					<!-- Practice Area Categories -->
					<?php if ( ! is_wp_error( $pa_categories ) && ! empty( $pa_categories ) ) : ?>
						<div class="contact-pa-grid">
							<?php foreach ( $pa_categories as $pa_cat ) : ?>
								<div class="contact-pa-card">
									<h3 class="contact-pa-card__title">
											<?php echo esc_html( $pa_cat->name ); ?>
									</h3>
									<?php if ( $pa_cat->description ) : ?>
										<p class="contact-pa-card__desc">
											<?php echo esc_html( wp_trim_words( wp_strip_all_tags( $pa_cat->description ), 30, '...' ) ); ?>
										</p>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- Right: Gravity Form -->
				<div class="contact-form">
					<?php echo do_shortcode( '[gravityform id="1" title="false" description="false" ajax="true"]' ); ?>
				</div>

			</div>

			<!-- Google Map -->
			<div style="margin-top: 3rem;">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3593.144235778963!2d28.2675923!3d-25.765798999999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1e9560f6de96d53d%3A0xa08425c28e8e8469!2sIvan%20Pauw%20%26%20Partners!5e0!3m2!1sen!2sza!4v1776339614264!5m2!1sen!2sza" width="100%" height="450" style="border:0;border-radius:8px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>

		</div>
	</section>

<?php
get_footer();
