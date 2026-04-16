<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the `#content` element and all content thereafter.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ipp_tw
 */

?>

	</div><!-- #content -->

	<?php get_template_part( 'template-parts/layout/footer', 'content' ); ?>

	<!-- Scroll to Top -->
	<button id="scroll-to-top" class="scroll-to-top" aria-label="<?php esc_attr_e( 'Scroll to top', 'ipp_tw' ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
	</button>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
