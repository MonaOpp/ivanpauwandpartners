<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ipp_tw
 */

get_header();

while ( have_posts() ) :
	the_post();

	$featured_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
?>

	<!-- Banner with featured image -->
	<?php if ( $featured_url ) : ?>
	<section class="page-banner" style="background-image: url('<?php echo esc_url( $featured_url ); ?>');">
		<div class="page-banner__overlay"></div>
		<div class="page-banner__content layout-wrapper">
			<h1><?php the_title(); ?></h1>
		</div>
	</section>
	<?php else : ?>
	<section class="page-banner page-banner--no-image">
		<div class="page-banner__content layout-wrapper">
			<h1><?php the_title(); ?></h1>
		</div>
	</section>
	<?php endif; ?>

	<!-- Article content -->
	<section class="w-full" style="width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;">
		<div class="layout-wrapper mx-auto px-6 py-12">
			<div class="flex flex-col gap-10 lg:flex-row">

				<!-- Left sidebar: Table of Contents -->
				<aside style="width:15%;flex-shrink:0;">
					<nav id="single-toc" class="single-toc" aria-label="Table of contents">
						<!-- Populated by JS from H2/H3 headings in .entry-content -->
					</nav>
				</aside>

				<!-- Right: post body -->
				<div style="flex:1;min-width:0;">
					<div class="entry-content single-post-content">
						<?php the_content(); ?>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!-- Related Articles -->
	<?php
	$categories = get_the_category();
	$cat_ids    = wp_list_pluck( $categories, 'term_id' );

	$related_args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 4,
		'post__not_in'   => array( get_the_ID() ),
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	if ( ! empty( $cat_ids ) ) {
		$related_args['category__in'] = $cat_ids;
	}

	$related_query = new WP_Query( $related_args );

	if ( $related_query->have_posts() ) :
	?>
	<section class="w-full bg-[#EEEEEE]" style="width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw;">
		<div class="layout-wrapper mx-auto px-6 py-16">
			<h3 class="related-articles__heading">Related Articles</h3>

			<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
				<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
					<article class="insight-card">
						<div class="insight-card__image">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
							<?php else : ?>
								<div class="insight-card__placeholder"></div>
							<?php endif; ?>
						</div>
						<div class="insight-card__body">
							<h4 class="insight-card__title">
								<?php the_title(); ?>
							</h4>
							<p class="insight-card__excerpt">
								<?php echo esc_html( wp_trim_words( get_the_excerpt(), 15, '...' ) ); ?>
							</p>
							<a href="<?php the_permalink(); ?>" class="insight-card__btn">
								Read More
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		</div>
	</section>
	<?php
		wp_reset_postdata();
	endif;
	?>

<?php endwhile; ?>

<?php
get_footer();
