<?php
/**
 * Template for the Downloads page.
 *
 * URL: /resources/downloads/
 *
 * @package ipp_tw
 */

get_header();

// Current filter state.
$search_query = isset( $_GET['dl_search'] ) ? sanitize_text_field( $_GET['dl_search'] ) : '';
$current_cat  = isset( $_GET['dl_cat'] ) ? sanitize_text_field( $_GET['dl_cat'] ) : '';
$paged        = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;

// Get all download categories.
$dl_categories = get_terms(
	array(
		'taxonomy'   => 'download_category',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

// Separate "Interesting Court Judgments" category.
$court_cat_slug = 'interesting-court-judgments';
$court_cat      = null;
$main_cats      = array();

if ( ! is_wp_error( $dl_categories ) ) {
	foreach ( $dl_categories as $cat ) {
		if ( $court_cat_slug === $cat->slug ) {
			$court_cat = $cat;
		} else {
			$main_cats[] = $cat;
		}
	}
}

// ---- Main downloads query (exclude court judgments) ----
$main_args = array(
	'post_type'      => 'download',
	'post_status'    => 'publish',
	'posts_per_page' => 6,
	'paged'          => $paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

if ( $court_cat ) {
	$main_args['tax_query'] = array(
		array(
			'taxonomy' => 'download_category',
			'field'    => 'term_id',
			'terms'    => $court_cat->term_id,
			'operator' => 'NOT IN',
		),
	);
}

if ( $current_cat ) {
	$main_args['tax_query'] = array(
		array(
			'taxonomy' => 'download_category',
			'field'    => 'slug',
			'terms'    => $current_cat,
		),
	);
}

if ( $search_query ) {
	$main_args['meta_query'] = array(
		array(
			'key'     => 'download_name',
			'value'   => $search_query,
			'compare' => 'LIKE',
		),
	);
}

$main_query = new WP_Query( $main_args );

// ---- Court judgments query ----
$court_paged = isset( $_GET['court_page'] ) ? absint( $_GET['court_page'] ) : 1;
$court_args  = array(
	'post_type'      => 'download',
	'post_status'    => 'publish',
	'posts_per_page' => 6,
	'paged'          => $court_paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

if ( $court_cat ) {
	$court_args['tax_query'] = array(
		array(
			'taxonomy' => 'download_category',
			'field'    => 'term_id',
			'terms'    => $court_cat->term_id,
		),
	);
}

$court_query = new WP_Query( $court_args );
?>

	<!-- Banner -->
	<?php get_template_part( 'template-parts/content/content', 'banner' ); ?>

	<!-- Main Downloads -->
	<section class="w-full bg-white" style="width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;">
		<div class="layout-wrapper mx-auto px-6 py-12">

			<!-- Filters -->
			<form class="dl-filters" method="get" action="<?php echo esc_url( get_permalink() ); ?>">
				<div class="dl-filters__row">
					<div class="dl-filters__search">
						<input type="text" name="dl_search" placeholder="Search..." value="<?php echo esc_attr( $search_query ); ?>" class="dl-filters__input">
					</div>
					<div class="dl-filters__select-wrap">
						<select name="dl_cat" class="dl-filters__select" onchange="this.form.submit()">
							<option value="">Select Category</option>
							<?php if ( ! is_wp_error( $dl_categories ) ) : ?>
								<?php foreach ( $main_cats as $cat ) : ?>
									<option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( $current_cat, $cat->slug ); ?>>
										<?php echo esc_html( $cat->name ); ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
				</div>
			</form>

			<!-- Download rows -->
			<?php if ( $main_query->have_posts() ) : ?>
				<div class="dl-list">
					<?php while ( $main_query->have_posts() ) : $main_query->the_post();
						$dl_name = get_field( 'download_name' );
						$dl_file = get_field( 'download_file' );
						$dl_cats = get_the_terms( get_the_ID(), 'download_category' );
						$cat_name = ( $dl_cats && ! is_wp_error( $dl_cats ) ) ? $dl_cats[0]->name : '';
					?>
						<div class="dl-row">
							<span class="dl-row__name"><?php echo esc_html( $dl_name ? $dl_name : get_the_title() ); ?></span>
							<span class="dl-row__cat"><?php echo esc_html( $cat_name ); ?></span>
							<?php if ( $dl_file ) : ?>
								<a href="<?php echo esc_url( $dl_file ); ?>" target="_blank" rel="noopener noreferrer" class="dl-row__btn">Download File</a>
							<?php endif; ?>
						</div>
					<?php endwhile; ?>
				</div>

				<!-- Pagination + Load More -->
				<div class="dl-bottom">
					<?php if ( $main_query->max_num_pages > $paged ) : ?>
						<button class="insight-load-more-btn">Load More</button>
					<?php else : ?>
						<div></div>
					<?php endif; ?>

					<?php if ( $main_query->max_num_pages > 1 ) : ?>
						<div class="dl-pagination">
							<?php
							echo paginate_links(
								array(
									'total'     => $main_query->max_num_pages,
									'current'   => $paged,
									'prev_text' => '&laquo;',
									'next_text' => '&raquo;',
									'type'      => 'list',
								)
							);
							?>
						</div>
					<?php endif; ?>
				</div>

			<?php else : ?>
				<p class="text-gray-500 py-8">No downloads found.</p>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</section>

	<!-- Interesting Court Judgments -->
	<?php if ( $court_cat && $court_query->have_posts() ) : ?>
	<section class="w-full bg-[#F7F2F2]" style="width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;">
		<div class="layout-wrapper mx-auto px-6 py-12">

			<h2 class="dl-section-heading">
				<span class="font-extrabold">INTERESTING</span>
				<span class="italic font-semibold">COURT JUDGMENTS</span>
			</h2>

			<div class="dl-list">
				<?php while ( $court_query->have_posts() ) : $court_query->the_post();
					$dl_name = get_field( 'download_name' );
					$dl_file = get_field( 'download_file' );
					$dl_cats = get_the_terms( get_the_ID(), 'download_category' );
					$cat_name = ( $dl_cats && ! is_wp_error( $dl_cats ) ) ? $dl_cats[0]->name : '';
				?>
					<div class="dl-row">
						<span class="dl-row__name"><?php echo esc_html( $dl_name ? $dl_name : get_the_title() ); ?></span>
						<span class="dl-row__cat"><?php echo esc_html( $cat_name ); ?></span>
						<?php if ( $dl_file ) : ?>
							<a href="<?php echo esc_url( $dl_file ); ?>" target="_blank" rel="noopener noreferrer" class="dl-row__btn">Download File</a>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>

			<!-- Court Pagination + Load More -->
			<div class="dl-bottom">
				<?php if ( $court_query->max_num_pages > $court_paged ) : ?>
					<button class="insight-load-more-btn">Load More</button>
				<?php else : ?>
					<div></div>
				<?php endif; ?>

				<?php if ( $court_query->max_num_pages > 1 ) : ?>
					<div class="dl-pagination">
						<?php
						echo paginate_links(
							array(
								'total'     => $court_query->max_num_pages,
								'current'   => $court_paged,
								'prev_text' => '&laquo;',
								'next_text' => '&raquo;',
								'format'    => '?court_page=%#%',
								'type'      => 'list',
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</section>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>

<?php
get_footer();
