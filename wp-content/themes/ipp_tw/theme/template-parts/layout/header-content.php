<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ipp_tw
 */

?>

<header id="masthead" class="site-header">

	<div class="header-inner">

		<!-- Logo -->
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="<?php bloginfo( 'name' ); ?>">
			<img
				src="<?php echo esc_url( content_url( '/uploads/2026/04/Logo-Container.png' ) ); ?>"
				alt="<?php bloginfo( 'name' ); ?>"
			>
		</a>

		<!-- Burger Menu Button -->
		<button
			id="menu-toggle"
			class="burger-btn"
			aria-expanded="false"
			aria-controls="flyout-nav"
			aria-label="<?php esc_attr_e( 'Open menu', 'ipp_tw' ); ?>"
		>
			<img
				src="<?php echo esc_url( content_url( '/uploads/2026/04/Frame-76.png' ) ); ?>"
				alt=""
				aria-hidden="true"
			>
		</button>

	</div>

	<!-- Backdrop -->
	<div id="nav-backdrop" class="nav-backdrop" aria-hidden="true"></div>

	<!-- Flyout Navigation Panel -->
	<nav id="flyout-nav" class="flyout-nav" aria-label="<?php esc_attr_e( 'Main Navigation', 'ipp_tw' ); ?>" aria-hidden="true">

		<!-- Close Button -->
		<button
			id="menu-close"
			class="menu-close"
			aria-label="<?php esc_attr_e( 'Close menu', 'ipp_tw' ); ?>"
		>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" aria-hidden="true">
				<line x1="18" y1="6" x2="6" y2="18"/>
				<line x1="6" y1="6" x2="18" y2="18"/>
			</svg>
		</button>

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'flyout-menu',
				'container'      => false,
				'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			)
		);
		?>

		<!-- Social Icons -->
		<div class="flyout-social">
			<a href="#" aria-label="<?php esc_attr_e( 'Facebook', 'ipp_tw' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="22" height="22" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
			</a>
			<a href="#" aria-label="<?php esc_attr_e( 'Instagram', 'ipp_tw' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="22" height="22" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
			</a>
			<a href="tel:" aria-label="<?php esc_attr_e( 'Phone', 'ipp_tw' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="22" height="22" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.63 3.38 2 2 0 0 1 3.6 1.17l3-.04a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.8a16 16 0 0 0 6 6l.9-.9a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z"/></svg>
			</a>
			<a href="mailto:" aria-label="<?php esc_attr_e( 'Email', 'ipp_tw' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="22" height="22" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
			</a>
		</div>

	</nav><!-- #flyout-nav -->

</header><!-- #masthead -->
