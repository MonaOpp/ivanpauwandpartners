<?php
/**
 * Template Name: About Us
 *
 * @package ipp_tw
 */

get_header();
?>

	<?php get_template_part( 'template-parts/content/content', 'banner' ); ?>

	<?php
	$title       = get_field( '2_column_title' );
	$description = get_field( '2_column_description' );
	?>

	<?php if ( $title || $description ) : ?>
		<section class="w-full bg-white">
			<div class="layout-wrapper mx-auto px-6 py-12">
				<div class="grid grid-cols-1 gap-8 md:grid-cols-[20%_1fr]">
					<div>
						<?php if ( $title ) : ?>
							<h2 class="text-left text-3xl font-bold text-[#132E47] md:text-4xl">
								<?php echo esc_html( $title ); ?>
							</h2>
						<?php endif; ?>
					</div>
					<div>
						<?php if ( $description ) : ?>
							<div class="prose max-w-none text-[#132E47]">
								<?php echo wp_kses_post( $description ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$visual_image       = get_field( '2_column_visual_image' );
	$visual_description = get_field( '2_column_visual_description' );
	$visual_image_url   = '';
	if ( is_array( $visual_image ) ) {
		$visual_image_url = $visual_image['url'] ?? '';
	} elseif ( is_string( $visual_image ) ) {
		$visual_image_url = $visual_image;
	}
	?>
	<?php get_template_part( 'template-parts/content/content', 'team-timeline' ); ?>

	<?php get_template_part( 'template-parts/content/content', 'team-grid' ); ?>
	
	<?php if ( $visual_image_url || $visual_description ) : ?>
		<section class="w-full bg-[#18273A]" style="width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;">
			<div class="layout-wrapper mx-auto px-6 py-16">
				<div class="grid grid-cols-1 items-center gap-10 md:grid-cols-2">
					<div>
						<?php if ( $visual_image_url ) : ?>
							<img src="<?php echo esc_url( $visual_image_url ); ?>"
								 alt="<?php echo esc_attr( $visual_image['alt'] ?? '' ); ?>"
								 class="w-full"
								 style="border-radius: 8px;" />
						<?php endif; ?>
					</div>
					<div>
						<?php if ( $visual_description ) : ?>
							<div class="prose max-w-none text-white [&_h3]:text-white [&_h3]:text-5xl [&_h3]:font-bold">
								<?php echo wp_kses_post( $visual_description ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	

	<?php get_template_part( 'template-parts/content/content', 'sa-map' ); ?>


<?php

get_footer();