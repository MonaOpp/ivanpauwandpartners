<?php
/**
 * Latest Legal News & Insights carousel section.
 *
 * Displays blog posts in a 3-at-a-time carousel with arrows and dots.
 *
 * @package ipp_tw
 */

$news_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 12,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

if ( ! $news_query->have_posts() ) {
	return;
}

$posts_data = array();
while ( $news_query->have_posts() ) {
	$news_query->the_post();
	$posts_data[] = array(
		'id'        => get_the_ID(),
		'title'     => get_the_title(),
		'permalink' => get_permalink(),
		'excerpt'   => wp_trim_words( get_the_excerpt(), 10, '…' ),
		'thumbnail' => get_the_post_thumbnail_url( get_the_ID(), 'large' ),
	);
}
wp_reset_postdata();

$total  = count( $posts_data );
$pages  = ceil( $total / 3 );
?>

<section class="w-full bg-[#F7F2F2] py-16">
	<div class="layout-wrapper mx-auto px-6">

		<!-- Heading -->
		<h2 class="m-0 mb-10 text-5xl leading-tight tracking-wide md:text-6xl">
			<span class="font-extrabold uppercase text-[#3A5F82]">LATEST </span>
			<span class="font-semibold uppercase italic text-[#3A5F82]">LEGAL NEWS &</span><br>
			<span class="font-semibold uppercase italic text-[#AA7040]">INSIGHTS</span>
		</h2>

		<!-- Carousel wrapper -->
		<div class="news-carousel relative" data-total="<?php echo esc_attr( $total ); ?>">

			<!-- Track -->
			<div class="news-carousel__track flex transition-transform duration-500 ease-in-out" ">
				<?php foreach ( $posts_data as $post_item ) : ?>
					<div class="news-carousel__slide w-full flex-shrink-0 md:w-[calc(33.333%-0.67rem)]">
						<a href="<?php echo esc_url( $post_item['permalink'] ); ?>" class="group block overflow-hidden rounded-lg no-underline">
							<div class="relative aspect-[4/3] overflow-hidden bg-gray-200">
								<?php if ( $post_item['thumbnail'] ) : ?>
									<img
										src="<?php echo esc_url( $post_item['thumbnail'] ); ?>"
										alt="<?php echo esc_attr( $post_item['title'] ); ?>"
										class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
									>
								<?php endif; ?>

								<!-- Hover overlay with excerpt -->
								<div class="absolute inset-x-0 bottom-0 translate-y-full bg-[#132E47]/80 p-4 transition-transform duration-300 group-hover:translate-y-0">
									<p class="m-0 text-sm leading-snug text-white">
										<?php echo esc_html( $post_item['excerpt'] ); ?>
									</p>
									<span class="mt-2 inline-block text-sm font-semibold text-[#AA7040]">Learn More &rarr;</span>
								</div>

								<!-- Title bar at bottom -->
								<div class="absolute inset-x-0 bottom-0 bg-[#132E47]/70 px-4 py-3 transition-opacity duration-300 group-hover:opacity-0">
									<h3 class="m-0 text-sm font-semibold leading-snug text-white">
										<?php echo esc_html( $post_item['title'] ); ?>
									</h3>
								</div>
							</div>
						</a>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Controls: dots + arrows -->
			<div class="mt-6 flex items-center justify-end gap-4">
				<!-- Dots -->
				<div class="news-carousel__dots flex items-center gap-2">
					<?php for ( $i = 0; $i < $pages; $i++ ) : ?>
						<button
							class="news-carousel__dot h-3 w-3 rounded-full border-0 transition-colors duration-200 <?php echo 0 === $i ? 'bg-[#AA7040]' : 'bg-gray-300'; ?>"
							data-index="<?php echo esc_attr( $i ); ?>"
							aria-label="Go to slide group <?php echo esc_attr( $i + 1 ); ?>"
						></button>
					<?php endfor; ?>
				</div>

				<!-- Arrows -->
				<div class="flex gap-2">
					<button class="news-carousel__prev flex h-10 w-10 items-center justify-center rounded-full bg-[#3A5F82] text-white border-0 transition-colors hover:bg-[#2a4a66] cursor-pointer" aria-label="Previous">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
					</button>
					<button class="news-carousel__next flex h-10 w-10 items-center justify-center rounded-full bg-[#3A5F82] text-white border-0 transition-colors hover:bg-[#2a4a66] cursor-pointer" aria-label="Next">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
					</button>
				</div>
			</div>

		</div><!-- .news-carousel -->
	</div>
</section>
