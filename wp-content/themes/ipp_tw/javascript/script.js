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

// Interactive SA province map (SVG)
( function () {
	const svg = document.getElementById( 'sa-map' );
	if ( ! svg ) {
		return;
	}

	// Fill paths in SVG document order → province slug mapping.
	// Index 0 = Lesotho (not a province, skip).
	const PROVINCE_MAP = [
		null,             // 0  – Lesotho
		'eastern-cape',   // 1  – mask-2
		'free-state',     // 2  – mask-3
		'gauteng',        // 3  – mask-4
		'limpopo',        // 4  – mask-5
		'mpumalanga',     // 5  – mask-6
		'nothern-cape',   // 6  – mask-7
		'kwazulu-natal',  // 7  – mask-8
		'north-west',     // 8  – mask-9
		'western-cape',   // 9  – mask-10
	];

	// Approximate visual centre of each province (SVG coords).
	const PIN_CENTERS = {
		'limpopo':       [ [ 693, 47 ], [ 612, 70 ], [ 675, 80 ], [ 632, 126 ], [ 708, 79 ] ],
		'gauteng':       [ [ 605, 205 ], [ 581, 218 ], [ 602, 225 ], [ 588, 240 ], [ 607, 244 ] ],
		'mpumalanga':    [ [ 727, 156 ], [ 711, 185 ], [ 661, 176 ], [ 659, 218 ], [ 704, 255 ] ],
		'north-west':    [ [ 502, 169 ], [ 500, 270 ], [ 431, 238 ], [ 448, 294 ], [ 381, 259 ] ],
		'free-state':    [ [ 550, 319 ], [ 576, 343 ], [ 503, 355 ], [ 518, 383 ], [ 479, 397 ] ],
		'kwazulu-natal': [ [ 744, 318 ], [ 767, 358 ], [ 718, 361 ], [ 742, 382 ], [ 703, 398 ] ],
		'nothern-cape':  [ [ 267, 387 ], [ 292, 421 ], [ 240, 425 ], [ 253, 464 ], [ 201, 483 ] ],
		'western-cape':  [ [ 225, 636 ], [ 239, 664 ], [ 193, 706 ], [ 182, 686 ], [ 166, 717 ] ],
		'eastern-cape':  [ [ 578, 521 ], [ 488, 529 ], [ 461, 571 ], [ 499, 626 ], [ 578, 576 ] ],
	};

	const fillPaths = svg.querySelectorAll( ':scope > path[fill="#EDEDED"]' );
	const buttons   = document.querySelectorAll( '.province-btn' );
	const sidebar   = {
		defaultEl : document.getElementById( 'sa-map-default' ),
		infoEl    : document.getElementById( 'sa-map-info' ),
		nameEl    : document.getElementById( 'sa-map-province-name' ),
		listEl    : document.getElementById( 'sa-map-flagship-list' ),
		btnEl     : document.getElementById( 'sa-map-flagship-btn' ),
		emptyEl   : document.getElementById( 'sa-map-no-projects' ),
	};

	let activeProvince = null;  // Currently locked province slug.
	const pathBySlug   = {};    // slug → fill <path> element.
	const pinBySlug    = {};    // slug → array of <circle> pin elements.

	// ── Initialise province paths & pins ──────────────────────────
	fillPaths.forEach( ( path, i ) => {
		const slug = PROVINCE_MAP[ i ];
		if ( ! slug ) {
			return; // Skip Lesotho.
		}

		path.classList.add( 'province-path' );
		path.dataset.province = slug;
		pathBySlug[ slug ] = path;

		// Create pin markers (one per coordinate pair).
		const coordsList = PIN_CENTERS[ slug ];
		if ( coordsList && coordsList.length ) {
			pinBySlug[ slug ] = [];
			coordsList.forEach( ( coords ) => {
				const pin = document.createElementNS( 'http://www.w3.org/2000/svg', 'circle' );
				pin.setAttribute( 'cx', coords[ 0 ] );
				pin.setAttribute( 'cy', coords[ 1 ] );
				pin.setAttribute( 'r', '3' );
				pin.setAttribute( 'fill', '#ffffff' );
				pin.setAttribute( 'stroke-width', '0' );
				pin.classList.add( 'map-pin' );
				pin.dataset.province = slug;
				svg.appendChild( pin );
				pinBySlug[ slug ].push( pin );
			} );
		}
	} );

	// ── Helpers ───────────────────────────────────────────────────
	function highlightProvince( slug ) {
		Object.values( pinBySlug ).forEach( ( pins ) => pins.forEach( ( p ) => p.classList.remove( 'visible' ) ) );
		Object.values( pathBySlug ).forEach( ( p ) => p.classList.remove( 'province-hover' ) );

		if ( slug && pinBySlug[ slug ] ) {
			pinBySlug[ slug ].forEach( ( p ) => p.classList.add( 'visible' ) );
		}
	}

	function activateButton( slug ) {
		buttons.forEach( ( btn ) => {
			if ( btn.dataset.province === slug ) {
				btn.classList.add( 'border-[#AA7040]', 'text-white' );
				btn.classList.remove( 'border-transparent', 'text-white/60' );
			} else {
				btn.classList.remove( 'border-[#AA7040]', 'text-white' );
				btn.classList.add( 'border-transparent', 'text-white/60' );
			}
		} );
	}

	function lockProvince( slug ) {
		// Toggle off if clicking the same province.
		if ( activeProvince === slug ) {
			activeProvince = null;
			Object.values( pathBySlug ).forEach( ( p ) => p.classList.remove( 'province-active' ) );
			Object.values( pinBySlug ).forEach( ( pins ) => pins.forEach( ( p ) => p.classList.remove( 'visible' ) ) );
			activateButton( null );
			sidebar.infoEl.classList.add( 'hidden' );
			sidebar.defaultEl.classList.remove( 'hidden' );
			sidebar.btnEl.classList.add( 'hidden' );
			sidebar.btnEl.innerHTML = '';
			return;
		}

		activeProvince = slug;

		// Visual feedback on map.
		Object.values( pathBySlug ).forEach( ( p ) => p.classList.remove( 'province-active' ) );
		if ( pathBySlug[ slug ] ) {
			pathBySlug[ slug ].classList.add( 'province-active' );
		}
		highlightProvince( slug );
		activateButton( slug );

		// Update sidebar.
		sidebar.defaultEl.classList.add( 'hidden' );
		sidebar.infoEl.classList.remove( 'hidden' );

		const data = ( window.ippMapData && window.ippMapData[ slug ] ) || null;
		sidebar.nameEl.textContent = data ? data.label : slug.replace( /-/g, ' ' );
		sidebar.listEl.innerHTML = '';
		sidebar.btnEl.innerHTML = '';
		sidebar.btnEl.classList.add( 'hidden' );

		if ( data && data.flagships && data.flagships.length ) {
			sidebar.emptyEl.classList.add( 'hidden' );
			const items = data.flagships.slice( 0, 5 );
			items.forEach( ( f ) => {
				const li = document.createElement( 'li' );
				li.className = 'inline-block';
				let html = '';
				if ( f.image ) {
					html += '<img src="' + escHtml( f.image ) + '" alt="' + escHtml( f.title ) + '" style="width:120px;height:130px;border-radius:8px;object-fit:cover;display:block;" />';
				}
				li.innerHTML = html;
				sidebar.listEl.appendChild( li );
			} );
			// Single button below all images
			const firstLink = items[ 0 ].permalink || '#';
			sidebar.btnEl.innerHTML = '<a href="' + escHtml( firstLink ) + '" style="display:inline-block;padding:20px;border-radius:10px;background-color:#AA7040;color:#fff;text-decoration:none;font-size:14px;font-weight:600;">Get More Details</a>';
			sidebar.btnEl.classList.remove( 'hidden' );
		} else {
			sidebar.emptyEl.classList.remove( 'hidden' );
		}
	}

	function escHtml( str ) {
		const d = document.createElement( 'div' );
		d.textContent = str;
		return d.innerHTML;
	}

	// ── Map path events ──────────────────────────────────────────
	Object.entries( pathBySlug ).forEach( ( [ slug, path ] ) => {
		path.addEventListener( 'mouseenter', () => {
			if ( activeProvince !== slug ) {
				highlightProvince( slug );
			}
		} );

		path.addEventListener( 'mouseleave', () => {
			if ( activeProvince && activeProvince !== slug ) {
				highlightProvince( activeProvince );
			} else if ( ! activeProvince ) {
				highlightProvince( null );
			}
		} );

		path.addEventListener( 'click', () => {
			lockProvince( slug );
		} );
	} );

	// ── Sidebar button events ────────────────────────────────────
	buttons.forEach( ( btn ) => {
		btn.addEventListener( 'click', () => {
			lockProvince( btn.dataset.province );
		} );
	} );

	// ── Activate Gauteng on page load ────────────────────────────
	lockProvince( 'gauteng' );
} )();

/* =================================================================
   Latest News Carousel
   ================================================================= */
( function () {
	const carousel = document.querySelector( '.news-carousel' );
	if ( ! carousel ) return;

	const track = carousel.querySelector( '.news-carousel__track' );
	const slides = carousel.querySelectorAll( '.news-carousel__slide' );
	const prevBtn = carousel.querySelector( '.news-carousel__prev' );
	const nextBtn = carousel.querySelector( '.news-carousel__next' );
	const dots = carousel.querySelectorAll( '.news-carousel__dot' );
	const total = slides.length;

	function getVisible() {
		if ( window.innerWidth >= 1024 ) return 3;
		if ( window.innerWidth >= 768 ) return 2;
		return 1;
	}

	let current = 0;

	function goTo( index ) {
		const visible = getVisible();
		const maxIndex = Math.max( 0, total - visible );
		// Loop: wrap around
		if ( index < 0 ) index = maxIndex;
		if ( index > maxIndex ) index = 0;
		current = index;

		const slideWidth = slides[ 0 ].offsetWidth;
		const gap = parseFloat( getComputedStyle( track ).gap ) || 0;
		const offset = current * ( slideWidth + gap );
		track.style.transform = `translateX(-${ offset }px)`;

		slides.forEach( ( slide, i ) => {
			slide.classList.toggle( 'slide-active', i === current );
		} );

		dots.forEach( ( dot, i ) => {
			if ( i === current ) {
				dot.classList.add( 'dot-active' );
				dot.classList.remove( 'dot-inactive' );
			} else {
				dot.classList.remove( 'dot-active' );
				dot.classList.add( 'dot-inactive' );
			}
		} );
	}

	prevBtn.addEventListener( 'click', () => goTo( current - 1 ) );
	nextBtn.addEventListener( 'click', () => goTo( current + 1 ) );
	dots.forEach( ( dot ) => {
		dot.addEventListener( 'click', () => goTo( Number( dot.dataset.index ) ) );
	} );

	goTo( 0 );
} )();
