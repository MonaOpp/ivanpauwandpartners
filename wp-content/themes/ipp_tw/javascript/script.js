/**
 * Front-end JavaScript
 *
 * The JavaScript code you place here will be processed by esbuild. The output
 * file will be created at `../theme/js/script.min.js` and enqueued in
 * `../theme/functions.php`.
 *
 * For esbuild documentation, please see:
 * https://esbuild.github.io/
 */

// Flyout navigation
const menuToggle  = document.getElementById( 'menu-toggle' );
const menuClose   = document.getElementById( 'menu-close' );
const flyoutNav   = document.getElementById( 'flyout-nav' );
const navBackdrop = document.getElementById( 'nav-backdrop' );

function openMenu() {
	flyoutNav.classList.add( 'is-open' );
	navBackdrop.classList.add( 'is-visible' );
	menuToggle.setAttribute( 'aria-expanded', 'true' );
	flyoutNav.setAttribute( 'aria-hidden', 'false' );
	document.body.style.overflow = 'hidden';
	menuClose.focus();
}

function closeMenu() {
	flyoutNav.classList.remove( 'is-open' );
	navBackdrop.classList.remove( 'is-visible' );
	menuToggle.setAttribute( 'aria-expanded', 'false' );
	flyoutNav.setAttribute( 'aria-hidden', 'true' );
	document.body.style.overflow = '';
	menuToggle.focus();
}

menuToggle?.addEventListener( 'click', openMenu );
menuClose?.addEventListener( 'click', closeMenu );
navBackdrop?.addEventListener( 'click', closeMenu );

document.addEventListener( 'keydown', ( e ) => {
	if ( e.key === 'Escape' && flyoutNav.classList.contains( 'is-open' ) ) {
		closeMenu();
	}
} );

// Submenu toggles inside flyout
document.querySelectorAll( '#flyout-menu .menu-item-has-children > a' ).forEach( ( link ) => {
	const toggle = document.createElement( 'button' );
	toggle.className = 'submenu-toggle';
	toggle.setAttribute( 'aria-expanded', 'false' );
	toggle.setAttribute( 'aria-label', 'Toggle submenu' );
	toggle.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>';

	link.parentElement.insertBefore( toggle, link.nextSibling );

	toggle.addEventListener( 'click', ( e ) => {
		e.preventDefault();
		const isOpen = toggle.getAttribute( 'aria-expanded' ) === 'true';
		toggle.setAttribute( 'aria-expanded', String( ! isOpen ) );
		link.parentElement.classList.toggle( 'submenu-open', ! isOpen );
	} );
} );

// Interactive SA province map
( function () {
	const buttons = document.querySelectorAll( '.province-btn' );
	const images = document.querySelectorAll( '[data-province-img]' );

	if ( ! buttons.length ) {
		return;
	}

	function activate( key ) {
		// Show matching image, hide others.
		images.forEach( ( img ) => {
			img.style.opacity = img.dataset.provinceImg === key ? '1' : '0';
		} );

		// Highlight active button.
		buttons.forEach( ( btn ) => {
			if ( btn.dataset.province === key ) {
				btn.classList.add( 'border-[#AA7040]', 'text-white' );
				btn.classList.remove( 'border-transparent', 'text-white/60' );
			} else {
				btn.classList.remove( 'border-[#AA7040]', 'text-white' );
				btn.classList.add( 'border-transparent', 'text-white/60' );
			}
		} );
	}

	function deactivate() {
		images.forEach( ( img ) => {
			img.style.opacity = '0';
		} );
		buttons.forEach( ( btn ) => {
			btn.classList.remove( 'border-[#AA7040]', 'text-white' );
			btn.classList.add( 'border-transparent', 'text-white/60' );
		} );
	}

	buttons.forEach( ( btn ) => {
		btn.addEventListener( 'mouseenter', () => {
			activate( btn.dataset.province );
		} );
		btn.addEventListener( 'mouseleave', () => {
			deactivate();
		} );
	} );
} )();
