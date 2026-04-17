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
$search_query    = isset( $_GET['dl_search'] ) ? sanitize_text_field( $_GET['dl_search'] ) : '';
$current_cat     = isset( $_GET['dl_cat'] ) ? sanitize_text_field( $_GET['dl_cat'] ) : '';
$current_province = isset( $_GET['dl_province'] ) ? sanitize_text_field( $_GET['dl_province'] ) : '';
$paged           = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;

// Get all download categories.
$dl_categories = get_terms(
	array(
		'taxonomy'   => 'download_category',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

// Separate categories into: Court Judgments, Province (parent + children), and the rest.
$court_cat_slug    = 'interesting-court-judgments';
$province_slug     = 'province';
$court_cat         = null;
$province_parent   = null;
$province_cats     = array();
$main_cats         = array();

if ( ! is_wp_error( $dl_categories ) ) {
	// First pass: find special parent terms.
	foreach ( $dl_categories as $cat ) {
		if ( $court_cat_slug === $cat->slug ) {
			$court_cat = $cat;
		} elseif ( $province_slug === $cat->slug ) {
			$province_parent = $cat;
		}
	}

	// Second pass: sort remaining categories.
	foreach ( $dl_categories as $cat ) {
		if ( $court_cat && $cat->term_id === $court_cat->term_id ) {
			continue;
		}
		if ( $province_parent && ( $cat->term_id === $province_parent->term_id || $cat->parent === $province_parent->term_id ) ) {
			if ( $cat->term_id !== $province_parent->term_id ) {
				$province_cats[] = $cat;
			}
			continue;
		}
		$main_cats[] = $cat;
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

// Build tax_query: always exclude court judgments, optionally filter by province and/or category.
$tax_queries = array( 'relation' => 'AND' );

if ( $court_cat ) {
	$tax_queries[] = array(
		'taxonomy' => 'download_category',
		'field'    => 'term_id',
		'terms'    => $court_cat->term_id,
		'operator' => 'NOT IN',
	);
}

if ( $current_province ) {
	$tax_queries[] = array(
		'taxonomy' => 'download_category',
		'field'    => 'slug',
		'terms'    => $current_province,
	);
}

if ( $current_cat ) {
	$tax_queries[] = array(
		'taxonomy' => 'download_category',
		'field'    => 'slug',
		'terms'    => $current_cat,
	);
}

if ( count( $tax_queries ) > 1 ) {
	$main_args['tax_query'] = $tax_queries;
}

if ( $search_query ) {
	$main_args['meta_query'] = array(
		array(
			'key'     => 'donwload_name',
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
					<?php if ( ! empty( $province_cats ) ) : ?>
					<div class="dl-filters__select-wrap">
						<select name="dl_province" class="dl-filters__select" onchange="this.form.submit()">
							<option value="">Select Province</option>
							<?php foreach ( $province_cats as $prov ) : ?>
								<option value="<?php echo esc_attr( $prov->slug ); ?>" <?php selected( $current_province, $prov->slug ); ?>>
									<?php echo esc_html( $prov->name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<?php endif; ?>
					<div class="dl-filters__select-wrap">
						<select name="dl_cat" class="dl-filters__select" onchange="this.form.submit()">
							<option value="">Select Category</option>
							<?php foreach ( $main_cats as $cat ) : ?>
								<option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( $current_cat, $cat->slug ); ?>>
									<?php echo esc_html( $cat->name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</form>

			<!-- Download rows -->
			<?php if ( $main_query->have_posts() ) : ?>
				<div class="dl-list">
					<?php while ( $main_query->have_posts() ) : $main_query->the_post();
						$dl_name = get_field( 'donwload_name' );
						$dl_pdf  = get_field( 'download_pdf' );
						$dl_cats = get_the_terms( get_the_ID(), 'download_category' );
						$cat_name = ( $dl_cats && ! is_wp_error( $dl_cats ) ) ? $dl_cats[0]->name : '';
						$file_url = is_array( $dl_pdf ) ? $dl_pdf['url'] : $dl_pdf;
					?>
						<div class="dl-row">
							<span class="dl-row__name"><?php echo esc_html( $dl_name ? $dl_name : get_the_title() ); ?></span>
							<span class="dl-row__cat"><?php echo esc_html( $cat_name ); ?></span>
							<?php if ( $file_url ) : ?>
								<a href="<?php echo esc_url( $file_url ); ?>" target="_blank" rel="noopener noreferrer" class="dl-row__btn">Download File</a>
							<?php endif; ?>
						</div>
					<?php endwhile; ?>
				</div>

				<!-- Pagination + Read More -->
				<div class="dl-bottom">
					<?php if ( $main_query->max_num_pages > $paged ) : ?>
						<a href="<?php echo esc_url( get_pagenum_link( $paged + 1 ) ); ?>" class="dl-readmore-btn">Load More</a>
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
									'prev_next' => false,
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
	<section class="w-full bg-[#3A5F82]" style="width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;">
		<div class="layout-wrapper mx-auto px-6 py-12">

			<h2 class="dl-section-heading text-white text-left">
				<span class="font-extrabold text-left">INTERESTING</span>
				<span class="italic font-semibold text-left">COURT JUDGMENTS</span>
			</h2>

			<div class="dl-list dl-list--dark">
				<?php while ( $court_query->have_posts() ) : $court_query->the_post();
					$dl_name = get_field( 'donwload_name' );
					$dl_pdf  = get_field( 'download_pdf' );
					$dl_cats = get_the_terms( get_the_ID(), 'download_category' );
					$cat_name = ( $dl_cats && ! is_wp_error( $dl_cats ) ) ? $dl_cats[0]->name : '';
					$file_url = is_array( $dl_pdf ) ? $dl_pdf['url'] : $dl_pdf;
				?>
					<div class="dl-row" style="border-color: rgba(255,255,255,0.2);">
						<span class="dl-row__name text-white"><?php echo esc_html( $dl_name ? $dl_name : get_the_title() ); ?></span>
						<span class="dl-row__cat text-white/70"><?php echo esc_html( $cat_name ); ?></span>
						<?php if ( $file_url ) : ?>
							<a href="<?php echo esc_url( $file_url ); ?>" target="_blank" rel="noopener noreferrer" class="dl-row__btn">Download File</a>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>

			<!-- Court Pagination + Read More -->
			<div class="dl-bottom">
				<?php if ( $court_query->max_num_pages > $court_paged ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'court_page', $court_paged + 1 ) ); ?>" class="dl-readmore-btn">Read More</a>
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
								'prev_next' => false,
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
