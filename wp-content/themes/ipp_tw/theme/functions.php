<?php
/**
 * ipp_tw functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ipp_tw
 */

if ( ! defined( 'IPP_TW_VERSION' ) ) {
	/*
	 * Set the theme’s version number.
	 *
	 * This is used primarily for cache busting. If you use `npm run bundle`
	 * to create your production build, the value below will be replaced in the
	 * generated zip file with a timestamp, converted to base 36.
	 */
	define( 'IPP_TW_VERSION', '0.1.0' );
}

if ( ! defined( 'IPP_TW_TYPOGRAPHY_CLASSES' ) ) {
	/*
	 * Set Tailwind Typography classes for the front end, block editor and
	 * classic editor using the constant below.
	 *
	 * For the front end, these classes are added by the `ipp_tw_content_class`
	 * function. You will see that function used everywhere an `entry-content`
	 * or `page-content` class has been added to a wrapper element.
	 *
	 * For the block editor, these classes are converted to a JavaScript array
	 * and then used by the `./javascript/block-editor.js` file, which adds
	 * them to the appropriate elements in the block editor (and adds them
	 * again when they’re removed.)
	 *
	 * For the classic editor (and anything using TinyMCE, like Advanced Custom
	 * Fields), these classes are added to TinyMCE’s body class when it
	 * initializes.
	 */
	define(
		'IPP_TW_TYPOGRAPHY_CLASSES',
		'prose prose-neutral max-w-none prose-a:text-primary'
	);
}

if ( ! function_exists( 'ipp_tw_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function ipp_tw_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on ipp_tw, use a find and replace
		 * to change 'ipp_tw' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'ipp_tw', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'menu-1' => __( 'Primary', 'ipp_tw' ),
				'menu-2' => __( 'Footer Menu', 'ipp_tw' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Remove support for block templates.
		remove_theme_support( 'block-templates' );
	}
endif;
add_action( 'after_setup_theme', 'ipp_tw_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ipp_tw_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'ipp_tw' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your footer.', 'ipp_tw' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'ipp_tw_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function ipp_tw_scripts() {
	wp_enqueue_style( 'ipp_tw-style', get_stylesheet_uri(), array(), IPP_TW_VERSION );
	wp_enqueue_script( 'ipp_tw-script', get_template_directory_uri() . '/js/script.min.js', array(), IPP_TW_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ipp_tw_scripts' );

/**
 * Enqueue the block editor script.
 */
function ipp_tw_enqueue_block_editor_script() {
	$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if (
		$current_screen &&
		$current_screen->is_block_editor() &&
		'widgets' !== $current_screen->id
	) {
		wp_enqueue_script(
			'ipp_tw-editor',
			get_template_directory_uri() . '/js/block-editor.min.js',
			array(
				'wp-blocks',
				'wp-edit-post',
			),
			IPP_TW_VERSION,
			true
		);
		wp_add_inline_script( 'ipp_tw-editor', "tailwindTypographyClasses = '" . esc_attr( IPP_TW_TYPOGRAPHY_CLASSES ) . "'.split(' ');", 'before' );
	}
}
add_action( 'enqueue_block_assets', 'ipp_tw_enqueue_block_editor_script' );

/**
 * Add the Tailwind Typography classes to TinyMCE.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function ipp_tw_tinymce_add_class( $settings ) {
	$settings['body_class'] = IPP_TW_TYPOGRAPHY_CLASSES;
	return $settings;
}
add_filter( 'tiny_mce_before_init', 'ipp_tw_tinymce_add_class' );

/**
 * Limit the block editor to heading levels supported by Tailwind Typography.
 *
 * @param array  $args Array of arguments for registering a block type.
 * @param string $block_type Block type name including namespace.
 * @return array
 */
function ipp_tw_modify_heading_levels( $args, $block_type ) {
	if ( 'core/heading' !== $block_type ) {
		return $args;
	}

	// Remove <h1>, <h5> and <h6>.
	$args['attributes']['levelOptions']['default'] = array( 2, 3, 4 );

	return $args;
}
add_filter( 'register_block_type_args', 'ipp_tw_modify_heading_levels', 10, 2 );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * ACF field groups for banners and home slider.
 */
require get_template_directory() . '/inc/acf-fields.php';

/**
 * Enqueue Swiper.js on the front page.
 */
function ipp_tw_enqueue_swiper() {
	if ( is_front_page() ) {
		wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11' );
		wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11', true );
		wp_add_inline_script(
			'swiper',
			'document.addEventListener("DOMContentLoaded",function(){new Swiper(".home-swiper",{loop:true,autoplay:{delay:5000,disableOnInteraction:false},effect:"fade",fadeEffect:{crossFade:true},navigation:{prevEl:".slider-prev",nextEl:".slider-next"}})});'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'ipp_tw_enqueue_swiper' );

/**
 * Pass flagship data grouped by province to the front-end map script.
 */
function ipp_tw_localize_map_data() {
	if ( ! is_front_page() ) {
		return;
	}

	$provinces = array(
		'limpopo',
		'gauteng',
		'mpumalanga',
		'north-west',
		'free-state',
		'kwazulu-natal',
		'nothern-cape',
		'western-cape',
		'eastern-cape',
	);

	$map_data = array();

	foreach ( $provinces as $slug ) {
		$term = get_term_by( 'slug', $slug, 'flagship' );
		$label = $term ? $term->name : ucwords( str_replace( '-', ' ', $slug ) );

		$flagships = array();

		if ( $term && ! is_wp_error( $term ) ) {
			$posts = get_posts(
				array(
					'post_type'      => 'flagship',
					'posts_per_page' => -1,
					'tax_query'      => array(
						array(
							'taxonomy' => 'flagship',
							'field'    => 'slug',
							'terms'    => $slug,
						),
					),
				)
			);

			foreach ( $posts as $post ) {
				$title = get_field( 'flagship_title', $post->ID );
				$type  = get_field( 'type_of_flagship', $post->ID );

				$flagships[] = array(
					'title' => $title ? $title : $post->post_title,
					'type'  => $type ? $type : '',
				);
			}
		}

		$map_data[ $slug ] = array(
			'label'     => $label,
			'flagships' => $flagships,
		);
	}

	wp_localize_script( 'ipp_tw-script', 'ippMapData', $map_data );
}
add_action( 'wp_enqueue_scripts', 'ipp_tw_localize_map_data' );
