<?php
/**
 * Team timeline carousel section.
 *
 * Queries the "team-member" CPT and uses ACF fields:
 *   - tm_name      (text)
 *   - tm_year      (text)
 *   - tm_timeline_ (textarea / wysiwyg)
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

$total  = count( $team_members );
$groups = array_chunk( $team_members, 3 );
$pages  = count( $groups );
?>

<section class="team-timeline-section w-full bg-[#F7F2F2] py-16" style="width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;">
	<div class="layout-wrapper mx-auto px-6">
		<div class="flex flex-col gap-10 lg:flex-row lg:items-start">

			<!-- Left column: heading + pagination -->
			<div class="w-full lg:w-1/3">
				<h2 class="team-timeline__heading">
					<span class="block font-extrabold uppercase text-[#3A5F82]">OUR</span>
					<span class="block font-semibold uppercase italic text-[#AA7040]">TEAM</span>
				</h2>
				<p class="mt-4 text-sm leading-relaxed text-[#132E47]">
					Ivan Pauw &amp; Partner's commitment is reflected in the progression of key individuals within the organisation
				</p>

				<!-- Pagination controls -->
				<div class="mt-6 flex items-center gap-3">
					<button class="team-timeline__prev flex h-8 w-8 items-center justify-center rounded-full border border-[#3A5F82] text-[#3A5F82]" aria-label="Previous">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
					</button>
					<span class="team-timeline__counter text-sm text-[#132E47]">
						<strong class="team-timeline__current">1</strong> of <?php echo esc_html( $pages ); ?>
					</span>
					<button class="team-timeline__next flex h-8 w-8 items-center justify-center rounded-full border border-[#3A5F82] text-[#3A5F82]" aria-label="Next">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
					</button>
				</div>
			</div>

			<!-- Right column: timeline Swiper -->
			<div class="w-full overflow-hidden lg:w-2/3">
				<div class="swiper team-timeline-swiper">
					<div class="swiper-wrapper">
						<?php foreach ( $groups as $group_index => $group ) : ?>
							<div class="swiper-slide">

								<!-- Names + year -->
								<div class="team-timeline__row" style="grid-template-columns: repeat(<?php echo count( $group ); ?>, 1fr);">
									<?php foreach ( $group as $member ) :
										$name = get_field( 'tm_name', $member->ID );
										$year = get_field( 'tm_year', $member->ID );
									?>
										<div class="text-center">
											<h3 class="text-sm font-bold uppercase tracking-wide text-[#132E47] md:text-base">
												<?php echo esc_html( $name ? $name : $member->post_title ); ?>
											</h3>
											<p class="mt-1 text-xs font-semibold text-[#6B7280]">
												<?php echo esc_html( $year ); ?>
											</p>
										</div>
									<?php endforeach; ?>
								</div>

								<!-- Timeline track -->
								<div class="team-timeline__track">
									<div class="team-timeline__line"></div>
									<div class="team-timeline__row" style="grid-template-columns: repeat(<?php echo count( $group ); ?>, 1fr);">
										<?php foreach ( $group as $i => $member ) : ?>
											<div class="flex justify-center">
												<span class="team-timeline__dot" data-group="<?php echo esc_attr( $group_index ); ?>" data-index="<?php echo esc_attr( $i ); ?>"></span>
											</div>
										<?php endforeach; ?>
									</div>
								</div>

								<!-- Descriptions -->
								<div class="team-timeline__row" style="grid-template-columns: repeat(<?php echo count( $group ); ?>, 1fr);">
									<?php foreach ( $group as $member ) :
										$timeline = get_field( 'tm_timeline_', $member->ID );
									?>
										<div class="text-xs leading-relaxed text-[#6B7280] md:text-sm">
											<?php echo wp_kses_post( $timeline ); ?>
										</div>
									<?php endforeach; ?>
								</div>

							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
