<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ipp_tw
 */

?>

<header id="masthead" class="site-header ">

	<div class="header-inner layout-wrapper">

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
			<img
				src="<?php echo esc_url( content_url( '/uploads/2026/04/Vector-2.png' ) ); ?>"
				alt=""
				aria-hidden="true"
				>
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
			<img
				src="<?php echo esc_url( content_url( '/uploads/2026/04/Group-91.png' ) ); ?>"
				alt=""
				aria-hidden="true"
				>			
			</a>
			<a href="#" aria-label="<?php esc_attr_e( 'Instagram', 'ipp_tw' ); ?>">
				<img
					src="<?php echo esc_url( content_url( '/uploads/2026/04/Group-92.png' ) ); ?>"
					alt=""
					aria-hidden="true"
				>
			</a>
			<a href="tel:" aria-label="<?php esc_attr_e( 'Phone', 'ipp_tw' ); ?>">
				<img
					src="<?php echo esc_url( content_url( '/uploads/2026/04/phone.png' ) ); ?>"
					alt=""
					aria-hidden="true"
				>
			</a>
			<a href="mailto:" aria-label="<?php esc_attr_e( 'Email', 'ipp_tw' ); ?>">
				<img
					src="<?php echo esc_url( content_url( '/uploads/2026/04/mail.png' ) ); ?>"
					alt=""
					aria-hidden="true"
				>
			</a>
		</div>

	</nav><!-- #flyout-nav -->

</header><!-- #masthead -->
