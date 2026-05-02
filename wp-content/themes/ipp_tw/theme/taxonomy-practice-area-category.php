<?php
/**
 * Taxonomy archive template for practice-area-category.
 *
 * URL structure: /practice-area/{term-slug}/
 *
 * @package ipp_tw
 */

get_header();

$term         = get_queried_object();
$banner_text  = function_exists( 'get_field' ) ? get_field( 'practice_area_category_banner_text', $term ) : '';
$banner_image = function_exists( 'get_field' ) ? get_field( 'practice_area_banner', $term ) : '';

// Strip wrapping <p> tags from WYSIWYG output.
if ( $banner_text ) {
	$banner_text = str_replace( array( '</p><p>', '</p>' . "\n" . '<p>', '</p>' ), '<br>', $banner_text );
	$banner_text = str_replace( '<p>', '', $banner_text );
	$banner_text = preg_replace( '/<br\s*\/?>$/', '', trim( $banner_text ) );
}

$banner_url = '';
if ( is_array( $banner_image ) ) {
	$banner_url = $banner_image['url'] ?? '';
} elseif ( is_string( $banner_image ) ) {
	$banner_url = $banner_image;
}

// Get all practice area posts in this taxonomy term.
$practice_areas = get_posts(
	array(
		'post_type'      => 'practice-area',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_key'       => 'practice_area_order',
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC',
		'tax_query'      => array(
			array(
				'taxonomy' => 'practice-area-category',
				'field'    => 'term_id',
				'terms'    => $term->term_id,
			),
		),
	)
);
?>

	<!-- Banner -->
	<?php if ( $banner_url ) : ?>
		<section class="page-banner" style="background-image: url('<?php echo esc_url( $banner_url ); ?>');">
			<div class="page-banner__overlay"></div>
			<div class="page-banner__content layout-wrapper">
				<?php if ( $banner_text ) : ?>
					<h1><?php echo wp_kses_post( $banner_text ); ?></h1>
				<?php else : ?>
					<h1><?php echo esc_html( $term->name ); ?></h1>
				<?php endif; ?>
			</div>
		</section>
	<?php else : ?>
		<section class="page-banner page-banner--no-image">
			<div class="page-banner__content layout-wrapper">
				<?php if ( $banner_text ) : ?>
					<h1><?php echo wp_kses_post( $banner_text ); ?></h1>
				<?php else : ?>
					<h1><?php echo esc_html( $term->name ); ?></h1>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<!-- Two-column intro: taxonomy name + description -->
	<?php if ( $term->description ) : ?>
		<section class="w-full bg-[#18273A]" style="width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;">
			<div class="layout-wrapper mx-auto px-6 py-20">
				<div class="grid grid-cols-1 items-start gap-10 lg:grid-cols-[30%_1fr]">
					<div>
						<h2 class="text-lg font-extrabold uppercase text-white md:text-2xl text-left">
							<?php echo esc_html( $term->name ); ?>
						</h2>
					</div>
					<div>
						<div class="prose max-w-none text-white text-sm leading-relaxed text-xl">
							<?php echo wp_kses_post( $term->description ); ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- Practice area tab buttons (anchors) -->
	<?php if ( ! empty( $practice_areas ) ) : ?>
		<section class="w-full bg-[#F7F7F7] py-6 sticky top-0 z-40" style="width:100vw;margin-left:calc(-50vw + 50%);margin-right:calc(-50vw + 50%);">
			<div class="layout-wrapper mx-auto px-6">
				<div class="flex flex-wrap justify-center gap-3">
					<?php foreach ( $practice_areas as $i => $pa ) :
						$pa_title = get_field( 'practice_area_title', $pa->ID );
						$display  = $pa_title ? $pa_title : $pa->post_title;
						$slug     = sanitize_title( $display );
					?>
						<a href="#pa-<?php echo esc_attr( $slug ); ?>"
						   class="pa-tab-btn <?php echo 0 === $i ? 'pa-tab-btn--active' : ''; ?>">
							<?php echo esc_html( $display ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- Practice area detail sections -->
	<?php if ( ! empty( $practice_areas ) ) : ?>
		<?php
		$pa_index = 0;
		foreach ( $practice_areas as $pa ) :
			$pa_title = get_field( 'practice_area_title', $pa->ID );
			$pa_desc  = get_field( 'practice_area_description', $pa->ID );
			$pa_info  = get_field( 'practice_area_info', $pa->ID );
			$display  = $pa_title ? $pa_title : $pa->post_title;
			$slug     = sanitize_title( $display );
			$is_odd   = ( $pa_index % 2 === 0 ); // 0-based: first item (0) is "odd" visually = white
			$bg_class = $is_odd ? 'bg-white' : 'bg-[#18273A]';
			$txt_class = $is_odd ? 'text-[#3A5F82]' : 'text-[#AA7040]';
			$desc_class = $is_odd ? 'text-[#374151]' : 'text-white/80';
			$title_class = $is_odd ? 'text-[#18273A]' : 'text-white';
			$num_class = $is_odd ? 'text-[#000]' : 'text-white';
			$info_desc_class = $is_odd ? 'text-[#6B7280]' : 'text-white/70';
			$border_class = $is_odd ? 'border-[#E5E7EB]' : 'border-white/20';
		?>
		<section class="w-full <?php echo $bg_class; ?> pa-section-wrapper" style="width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;">
			<div class="layout-wrapper mx-auto px-6">
				<div class="pa-section" id="pa-<?php echo esc_attr( $slug ); ?>">
					<div class="grid grid-cols-1 gap-10 lg:grid-cols-[30%_1fr]">

						<!-- Left: title + description -->
						<div class="pa-section__left">
							<h2 class="text-lg font-bold uppercase <?php echo $txt_class; ?> md:text-xl text-left">
								<?php echo esc_html( $display ); ?>
							</h2>
							<?php if ( $pa_desc ) : ?>
								<div class="mt-4 text-sm leading-relaxed <?php echo $desc_class; ?>">
									<?php echo wp_kses_post( $pa_desc ); ?>
								</div>
							<?php endif; ?>
						</div>

						<!-- Right: repeater info (scrollable) -->
						<?php if ( is_array( $pa_info ) && ! empty( $pa_info ) ) : ?>
							<div class="pa-section__right">
								<?php foreach ( $pa_info as $index => $row ) : ?>
									<div class="pa-info-row" style="border-color: <?php echo $is_odd ? '#E5E7EB' : 'rgba(255,255,255,0.2)'; ?>;">
										<span class="pa-info-row__number <?php echo $num_class; ?>">
											<?php echo esc_html( str_pad( $index + 1, 2, '0', STR_PAD_LEFT ) ); ?>.
										</span>
										<span class="pa-info-row__title <?php echo $title_class; ?>">
											<?php echo esc_html( $row['practice_area_info_title'] ?? '' ); ?>
										</span>
										<?php if ( ! empty( $row['practice_area_info_description'] ) ) : ?>
											<span class="pa-info-row__desc <?php echo $info_desc_class; ?>">
												<?php echo wp_kses_post( $row['practice_area_info_description'] ); ?>
											</span>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

					</div>
				</div>
			</div>
		</section>
		<?php
			$pa_index++;
		endforeach;
		wp_reset_postdata();
		?>
	<?php endif; ?>

<?php
get_footer();
