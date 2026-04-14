<?php
/**
 * Team members grid carousel with bio popup.
 *
 * Queries the "team-member" CPT and uses ACF fields:
 *   - tm_name        (text)
 *   - tm_job_title   (text)
 *   - tm_image       (image — array)
 *   - tm_description (wysiwyg / textarea)
 *
 * @package ipp_tw
 */

$team_members = get_posts(
	array(
		'post_type'      => 'team-member',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

if ( empty( $team_members ) ) {
	return;
}

$total = count( $team_members );
$pages = ceil( $total / 4 );
?>

<section class="team-grid-section w-full bg-[#D9D9D9] py-16" style="width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;">
	<div class="layout-wrapper mx-auto px-6">

		<!-- Heading -->
		<h2 class="mb-10 text-left text-4xl font-bold uppercase leading-tight text-[#132E47] md:text-5xl">
			<span class="text-[#3A5F82]">OUR</span> <span class="italic text-[#AA7040]">TEAM OF<br>ATTORNEYS</span>
		</h2>

		<!-- Swiper carousel -->
		<div class="swiper team-grid-swiper">
			<div class="swiper-wrapper">
				<?php foreach ( $team_members as $index => $member ) :
					$name      = get_field( 'tm_name', $member->ID );
					$job_title = get_field( 'tm_job_title', $member->ID );
					$image     = get_field( 'tm_image', $member->ID );
					$desc      = get_field( 'tm_description', $member->ID );
					$image_url = '';
					$image_alt = '';
					if ( is_array( $image ) ) {
						$image_url = $image['url'] ?? '';
						$image_alt = $image['alt'] ?? '';
					} elseif ( is_string( $image ) ) {
						$image_url = $image;
					}
					$display_name = $name ? $name : $member->post_title;
				?>
					<div class="swiper-slide">
						<div class="team-card" data-team-index="<?php echo esc_attr( $index ); ?>">
							<!-- Image -->
							<div class="team-card__image-wrap">
								<?php if ( $image_url ) : ?>
									<img src="<?php echo esc_url( $image_url ); ?>"
										 alt="<?php echo esc_attr( $image_alt ); ?>"
										 class="team-card__image" />
								<?php endif; ?>

								<!-- Hover overlay -->
								<div class="team-card__overlay">
									<button class="team-card__bio-btn" type="button"
											data-name="<?php echo esc_attr( $display_name ); ?>"
											data-title="<?php echo esc_attr( $job_title ); ?>"
											data-image="<?php echo esc_attr( $image_url ); ?>"
											data-desc="<?php echo esc_attr( $desc ); ?>">
										Read Bio
									</button>
								</div>
							</div>

							<!-- Info below image -->
							<div class="team-card__info">
								<h3 class="team-card__name"><?php echo esc_html( $display_name ); ?></h3>
								<p class="team-card__title"><?php echo esc_html( $job_title ); ?></p>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Pagination -->
		<div class="mt-6 flex items-center gap-3">
			<button class="team-grid__prev flex h-8 w-8 items-center justify-center rounded-full border border-[#3A5F82] text-[#3A5F82]" aria-label="Previous">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
			</button>
			<span class="team-grid__counter text-sm text-[#132E47]">
				<strong class="team-grid__current">1</strong> / <?php echo esc_html( $pages ); ?>
			</span>
			<button class="team-grid__next flex h-8 w-8 items-center justify-center rounded-full border border-[#3A5F82] text-[#3A5F82]" aria-label="Next">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
			</button>
		</div>

	</div>
</section>

<!-- Bio Popup Modal -->
<div class="team-popup" id="team-popup" aria-hidden="true">
	<div class="team-popup__backdrop"></div>
	<div class="team-popup__dialog">
		<button class="team-popup__close" type="button" aria-label="Close">&times;</button>
		<div class="team-popup__inner">
			<!-- Left: static image -->
			<div class="team-popup__image-col">
				<img id="team-popup-image" src="" alt="" class="team-popup__image" />
			</div>
			<!-- Right: scrollable info -->
			<div class="team-popup__info-col" id="team-popup-info">
				<h3 id="team-popup-name" class="team-popup__name"></h3>
				<p id="team-popup-title" class="team-popup__job-title"></p>
				<div id="team-popup-desc" class="team-popup__desc"></div>
			</div>
		</div>
	</div>
</div>
