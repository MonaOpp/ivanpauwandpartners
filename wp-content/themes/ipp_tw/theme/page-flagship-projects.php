<?php
/**
 * Template for the Flagship Projects page.
 *
 * Displays flagship posts grouped by the "flagship" taxonomy.
 * Left sidebar: taxonomy tabs. Right: grid of flagship cards.
 *
 * @package ipp_tw
 */

get_header();
?>

	<?php get_template_part( 'template-parts/content/content', 'banner' ); ?>

	<?php
	$provinces = get_terms(
		array(
			'taxonomy'   => 'flagship',
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);
	?>

	<section class="w-full bg-white py-16">
		<div class="layout-wrapper mx-auto px-6">
			<div class="flex flex-col gap-10 lg:flex-row">

				<!-- Left: taxonomy tabs -->
				<div class="w-full lg:w-1/4">
					<ul class="flagship-tabs list-none p-0 m-0 space-y-2">
						<li>
							<button class="flagship-tab flagship-tab--active" data-term="all" type="button">
								All Provinces
							</button>
						</li>
						<?php if ( ! is_wp_error( $provinces ) && ! empty( $provinces ) ) : ?>
							<?php foreach ( $provinces as $term ) : ?>
								<li>
									<button class="flagship-tab" data-term="<?php echo esc_attr( $term->slug ); ?>" type="button">
										<?php echo esc_html( $term->name ); ?>
									</button>
								</li>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</div>

				<!-- Right: flagship grid -->
				<div class="w-full lg:w-3/4">
					<div class="flagship-grid grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" id="flagship-grid">
						<?php
						$flagships = get_posts(
							array(
								'post_type'      => 'flagship',
								'posts_per_page' => -1,
								'post_status'    => 'publish',
								'orderby'        => 'menu_order date',
								'order'          => 'ASC',
							)
						);

						foreach ( $flagships as $flagship ) :
							$title    = get_field( 'flagship_title', $flagship->ID );
							$type     = get_field( 'type_of_flagship', $flagship->ID );
							$image    = get_field( 'fs_image', $flagship->ID );
							$img_url  = '';
							if ( is_array( $image ) ) {
								$img_url = $image['url'] ?? '';
							} elseif ( is_string( $image ) ) {
								$img_url = $image;
							}
							$display_title = $title ? $title : $flagship->post_title;

							// Get terms for this post to use as filter classes.
							$post_terms = wp_get_post_terms( $flagship->ID, 'flagship', array( 'fields' => 'slugs' ) );
							$term_slugs = is_wp_error( $post_terms ) ? '' : implode( ' ', $post_terms );
						?>
							<div class="flagship-card" data-terms="<?php echo esc_attr( $term_slugs ); ?>">
								<div class="flagship-card__image-wrap">
									<?php if ( $img_url ) : ?>
										<img src="<?php echo esc_url( $img_url ); ?>"
											 alt="<?php echo esc_attr( $display_title ); ?>"
											 class="flagship-card__image" />
									<?php endif; ?>
									<div class="flagship-card__overlay">
										<h3 class="flagship-card__title"><?php echo esc_html( $display_title ); ?></h3>
										<?php if ( $type ) : ?>
											<p class="flagship-card__type"><?php echo esc_html( $type ); ?></p>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>

			</div>
		</div>
	</section>

<?php
get_footer();