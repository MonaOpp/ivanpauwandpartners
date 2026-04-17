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

$total   = count( $posts_data );
$visible = 3;
$pages   = max( 1, $total - $visible + 1 );
?>

<style>
	.news-carousel__slide { flex: 0 0 100%; max-width: 100%; }
	@media (min-width: 768px) { .news-carousel__slide { flex: 0 0 50%; max-width: 50%; } }
	@media (min-width: 1024px) { .news-carousel__slide { flex: 0 0 33.333%; max-width: 33.333%; } }
	.news-carousel__slide .slide-image { height: 220px; transition: height 0.4s ease; }
	.news-carousel__slide.slide-active .slide-image { height: 300px; }
	.news-carousel__slide:hover .slide-image { height: 300px; }
	.news-carousel__track { min-height: 300px; }
	.news-carousel__dot { width: 12px; height: 12px; border-radius: 50%; cursor: pointer; transition: all 0.2s ease; }
	.news-carousel__dot.dot-active { background-color: #AA7040; border: 3px solid #AA7040; }
	.news-carousel__dot.dot-inactive { background-color: transparent; border: 1px solid #AA7040; }
</style>

<section class="w-full bg-[#F7F2F2] py-16">
	<div class="layout-wrapper mx-auto px-6">

		<!-- Heading -->
		<h2 class="m-0 mb-10 text-5xl leading-tight tracking-wide md:text-6xl text-left">
			<span class="font-extrabold uppercase text-[#3A5F82]">LATEST </span>
			<span class="font-semibold uppercase italic text-[#3A5F82]">LEGAL NEWS &</span><br>
			<span class="font-semibold uppercase italic text-[#AA7040]">INSIGHTS</span>
		</h2>

		<!-- Carousel wrapper -->
		<div class="news-carousel relative overflow-hidden rounded-lg" data-total="<?php echo esc_attr( $total ); ?>">

			<!-- Track -->
			<div class="news-carousel__track flex items-start transition-transform duration-500 ease-in-out">
				<?php foreach ( $posts_data as $idx => $post_item ) : ?>
					<div class="news-carousel__slide <?php echo 0 === $idx ? 'slide-active' : ''; ?>" data-index="<?php echo esc_attr( $idx ); ?>">
						<a href="<?php echo esc_url( $post_item['permalink'] ); ?>" class="group block overflow-hidden no-underline">
							<div class="slide-image relative overflow-hidden bg-gray-200">
								<?php if ( $post_item['thumbnail'] ) : ?>
									<img
										src="<?php echo esc_url( $post_item['thumbnail'] ); ?>"
										alt="<?php echo esc_attr( $post_item['title'] ); ?>"
										class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
									>
								<?php endif; ?>

								<!-- Hover overlay with excerpt -->
								<div class="slide-hover-overlay absolute inset-x-0 bottom-0 translate-y-full bg-[#3A5F82] p-4 transition-transform duration-300 group-hover:translate-y-0">
									<h3 class="m-0 mb-1 text-sm font-semibold leading-snug text-white text-left">
										<?php echo esc_html( $post_item['title'] ); ?>
									</h3>
									<p class="m-0 text-sm leading-snug text-white text-left">
										<?php echo esc_html( $post_item['excerpt'] ); ?>
									</p>
									<span class="mt-2 inline-block text-sm text-[#18273A] bg-white text-left px-4 py-2 rounded-lg">Learn More</span>
								</div>

								<!-- Title bar at bottom -->
								<div class="slide-title-bar absolute inset-x-0 bottom-0 bg-[#3A5F82] px-4 py-3 transition-opacity duration-300 group-hover:opacity-0">
									<h3 class="m-0 text-sm font-semibold leading-snug text-white text-left">
										<?php echo esc_html( $post_item['title'] ); ?>
									</h3>
								</div>
							</div>
						</a>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Controls: right-aligned -->
			<div class="flex flex-col items-end mt-4 gap-2">
				<!-- Dots -->
				<div class="news-carousel__dots flex items-center gap-2">
					<?php for ( $i = 0; $i < $pages; $i++ ) : ?>
						<button
							class="news-carousel__dot <?php echo 0 === $i ? 'dot-active' : 'dot-inactive'; ?>"
							data-index="<?php echo esc_attr( $i ); ?>"
							aria-label="Go to slide group <?php echo esc_attr( $i + 1 ); ?>"
						></button>
					<?php endfor; ?>
				</div>

				<!-- Arrows -->
				<div class="flex gap-2">
					<button class="news-carousel__prev flex h-10 w-10 items-center justify-center rounded-full bg-[#3A5F82] text-white border-0 transition-colors hover:bg-[#2a4a66] cursor-pointer" aria-label="Previous">
						<img src="<?php echo esc_url( content_url( '/uploads/2026/04/next.png' ) ); ?>" alt="next" >
					</button>
					<button class="news-carousel__next flex h-10 w-10 items-center justify-center rounded-full bg-[#3A5F82] text-white border-0 transition-colors hover:bg-[#2a4a66] cursor-pointer" aria-label="Next">
						<img src="<?php echo esc_url( content_url( '/uploads/2026/04/prev.png' ) ); ?>" alt="previous" >
					</button>
				</div>
			</div>

		</div><!-- .news-carousel -->
	</div>
</section>
