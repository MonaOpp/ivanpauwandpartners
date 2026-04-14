<?php
/**
 * Template for the Insight (blog) page.
 *
 * URL: /resources/insight/
 * Left sidebar: search + category filters.
 * Right: 3-column grid of post cards with pagination.
 *
 * @package ipp_tw
 */

get_header();

// Current filter state.
$current_cat    = isset( $_GET['insight_cat'] ) ? sanitize_text_field( $_GET['insight_cat'] ) : '';
$search_query   = isset( $_GET['insight_s'] ) ? sanitize_text_field( $_GET['insight_s'] ) : '';
$paged          = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;

// Build query args.
$args = array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 9,
	'paged'          => $paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

if ( $current_cat ) {
	$args['category_name'] = $current_cat;
}

if ( $search_query ) {
	$args['s'] = $search_query;
}

$insight_query = new WP_Query( $args );

// Get categories for sidebar (exclude uncategorized).
$categories = get_categories(
	array(
		'hide_empty' => false,
		'exclude'    => array( 1 ), // Uncategorized.
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
?>

	<!-- Banner -->
	<section class="page-banner page-banner--no-image" style="background:#18273A;">
		<div class="page-banner__content layout-wrapper">
			<h1>Insight</h1>
		</div>
	</section>

	<!-- Blog content -->
	<section class="w-full bg-[#18273A]" style="width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;">
		<div class="layout-wrapper mx-auto px-6 py-12">
			<div class="flex flex-col gap-10 lg:flex-row">

				<!-- Left sidebar -->
				<aside class="w-full lg:w-1/5">
					<!-- Search -->
					<form class="insight-search-form mb-6" method="get" action="<?php echo esc_url( get_permalink() ); ?>">
						<?php if ( $current_cat ) : ?>
							<input type="hidden" name="insight_cat" value="<?php echo esc_attr( $current_cat ); ?>">
						<?php endif; ?>
						<div class="relative">
							<input type="text"
								   name="insight_s"
								   class="insight-search-input"
								   placeholder="Search..."
								   value="<?php echo esc_attr( $search_query ); ?>">
							<svg class="insight-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
							</svg>
						</div>
					</form>

					<!-- Category links -->
					<?php if ( ! empty( $categories ) ) : ?>
						<nav class="insight-cat-nav">
							<?php foreach ( $categories as $cat ) :
								$is_active = ( $current_cat === $cat->slug );
								$link      = add_query_arg( 'insight_cat', $cat->slug, get_permalink() );
								if ( $search_query ) {
									$link = add_query_arg( 'insight_s', $search_query, $link );
								}
							?>
								<a href="<?php echo esc_url( $link ); ?>"
								   class="insight-cat-link <?php echo $is_active ? 'insight-cat-link--active' : ''; ?>">
									<?php echo esc_html( $cat->name ); ?>
								</a>
							<?php endforeach; ?>

							<?php if ( $current_cat ) : ?>
								<a href="<?php echo esc_url( get_permalink() ); ?><?php echo $search_query ? '?insight_s=' . urlencode( $search_query ) : ''; ?>"
								   class="insight-cat-link insight-cat-link--clear">
									&times; Clear filter
								</a>
							<?php endif; ?>
						</nav>
					<?php endif; ?>
				</aside>

				<!-- Right: post grid -->
				<div class="w-full lg:w-4/5">
					<?php if ( $insight_query->have_posts() ) : ?>
						<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
							<?php while ( $insight_query->have_posts() ) : $insight_query->the_post(); ?>
								<article class="insight-card">
									<div class="insight-card__image">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
										<?php else : ?>
											<div class="insight-card__placeholder"></div>
										<?php endif; ?>
									</div>
									<div class="insight-card__body">
										<h3 class="insight-card__title">
											<?php the_title(); ?>
										</h3>
										<p class="insight-card__excerpt">
											<?php echo esc_html( wp_trim_words( get_the_excerpt(), 20, '...' ) ); ?>
										</p>
										<a href="<?php the_permalink(); ?>" class="insight-card__btn">
											Read More
										</a>
									</div>
								</article>
							<?php endwhile; ?>
						</div>

						<!-- Pagination -->
						<?php if ( $insight_query->max_num_pages > 1 ) : ?>
							<div class="insight-pagination mt-10">
								<?php
								echo paginate_links(
									array(
										'total'     => $insight_query->max_num_pages,
										'current'   => $paged,
										'prev_text' => '&laquo;',
										'next_text' => '&raquo;',
										'type'      => 'list',
									)
								);
								?>
							</div>
						<?php endif; ?>

						<!-- Load More (AJAX-ready placeholder) -->
						<?php if ( $insight_query->max_num_pages > $paged ) : ?>
							<div class="mt-6 text-left">
								<button class="insight-load-more-btn" data-page="<?php echo esc_attr( $paged ); ?>" data-max="<?php echo esc_attr( $insight_query->max_num_pages ); ?>">
									Load More
								</button>
							</div>
						<?php endif; ?>

					<?php else : ?>
						<p class="text-white/60 text-center py-12">No posts found.</p>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				</div>

			</div>
		</div>
	</section>

<?php
get_footer();
