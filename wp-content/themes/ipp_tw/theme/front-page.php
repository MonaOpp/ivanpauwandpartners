<?php
/**
 * The template for the front page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ipp_tw
 */

get_header();
?>

	<?php get_template_part( 'template-parts/content/content', 'home-slider' ); ?>

	<section id="primary">
		<main id="main">

			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<div <?php ipp_tw_content_class( 'entry-content' ); ?>>
					<?php the_content(); ?>
				</div>
			<?php endwhile; ?>

		</main>
	</section>

<?php
get_footer();
