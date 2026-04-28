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
			sidebar.btnEl.innerHTML = '<a href="' + escHtml( window.ippFlagshipUrl || '/flagship-projects/' ) + '" style="display:inline-block;padding:20px;border-radius:10px;background-color:#AA7040;color:#fff;text-decoration:none;font-size:14px;font-weight:600;">View Projects</a>';
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

	// ── Looping intro animation ───────────────────────────────────
	// Provinces pulse one-by-one on a loop until a user clicks a province.
	// Gauteng sidebar content is shown immediately while the animation runs.
	const animOrder = [
		'limpopo', 'north-west', 'gauteng', 'mpumalanga',
		'free-state', 'kwazulu-natal', 'nothern-cape',
		'eastern-cape', 'western-cape',
	];
	const STEP_DELAY    = 700;  // ms between each province lighting up
	const HOLD_DURATION = 800;  // ms each province stays highlighted
	const LOOP_PAUSE    = 0;    // ms gap before the loop restarts

	let animRunning   = true;
	let animTimeouts  = [];
	let animLoopTimer = null;

	function clearAnimTimers() {
		animTimeouts.forEach( clearTimeout );
		animTimeouts = [];
		clearTimeout( animLoopTimer );
	}

	function stopAnimation() {
		animRunning = false;
		clearAnimTimers();
		// Remove any lingering animation highlights.
		animOrder.forEach( ( slug ) => {
			if ( pathBySlug[ slug ] ) {
				pathBySlug[ slug ].classList.remove( 'province-active' );
			}
		} );
	}

	function runAnimLoop() {
		if ( ! animRunning ) {
			return;
		}

		animOrder.forEach( ( slug, i ) => {
			if ( ! pathBySlug[ slug ] ) {
				return;
			}

			// Light up.
			const t1 = setTimeout( () => {
				if ( animRunning ) {
					pathBySlug[ slug ].classList.add( 'province-active' );
				}
			}, i * STEP_DELAY );

			// Dim down.
			const t2 = setTimeout( () => {
				if ( animRunning ) {
					pathBySlug[ slug ].classList.remove( 'province-active' );
				}
			}, i * STEP_DELAY + HOLD_DURATION );

			animTimeouts.push( t1, t2 );
		} );

		// Schedule the next loop iteration.
		animLoopTimer = setTimeout( () => {
			if ( animRunning ) {
				animTimeouts = [];
				runAnimLoop();
			}
		}, animOrder.length * STEP_DELAY + HOLD_DURATION + LOOP_PAUSE );
	}

	// Show Gauteng sidebar immediately without locking the map.
	( function showGautengSidebar() {
		const gautengData = ( window.ippMapData && window.ippMapData[ 'gauteng' ] ) || null;
		sidebar.defaultEl.classList.add( 'hidden' );
		sidebar.infoEl.classList.remove( 'hidden' );
		sidebar.nameEl.textContent = gautengData ? gautengData.label : 'Gauteng';
		sidebar.listEl.innerHTML   = '';
		sidebar.btnEl.innerHTML    = '';
		sidebar.btnEl.classList.add( 'hidden' );

		if ( gautengData && gautengData.flagships && gautengData.flagships.length ) {
			sidebar.emptyEl.classList.add( 'hidden' );
			gautengData.flagships.slice( 0, 5 ).forEach( ( f ) => {
				const li = document.createElement( 'li' );
				li.className = 'inline-block';
				let html = '';
				if ( f.image ) {
					html += '<img src="' + escHtml( f.image ) + '" alt="' + escHtml( f.title ) + '" style="width:120px;height:130px;border-radius:8px;object-fit:cover;display:block;" />';
				}
				li.innerHTML = html;
				sidebar.listEl.appendChild( li );
			} );
			sidebar.btnEl.innerHTML = '<a href="' + escHtml( window.ippFlagshipUrl || '/flagship-projects/' ) + '" style="display:inline-block;padding:20px;border-radius:10px;background-color:#AA7040;color:#fff;text-decoration:none;font-size:14px;font-weight:600;">View Projects</a>';
			sidebar.btnEl.classList.remove( 'hidden' );
		} else {
			sidebar.emptyEl.classList.remove( 'hidden' );
		}
	} )();

	// Start the looping animation.
	runAnimLoop();

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
			stopAnimation();
			lockProvince( slug );
		} );
	} );

	// ── Sidebar button events ────────────────────────────────────
	buttons.forEach( ( btn ) => {
		btn.addEventListener( 'click', () => {
			stopAnimation();
			lockProvince( btn.dataset.province );
		} );
	} );
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

// ── Team Timeline Navigation ────────────────────────────────
( function () {
	const dots      = document.querySelectorAll( '.team-timeline__dot' );
	const names     = document.querySelectorAll( '.team-timeline__name-col' );
	const descs     = document.querySelectorAll( '.team-timeline__desc' );
	const sliders   = document.querySelectorAll( '.team-timeline__slider' );
	const prevBtn   = document.querySelector( '.team-timeline__prev' );
	const nextBtn   = document.querySelector( '.team-timeline__next' );
	const counterEl = document.querySelector( '.team-timeline__current' );

	if ( ! dots.length ) {
		return;
	}

	const total   = dots.length;
	let active    = 0;

	function getVisible() {
		return window.innerWidth >= 1024 ? 3 : 1;
	}

	function slideWindow() {
		var visible = getVisible();
		var windowStart = Math.min( active, Math.max( 0, total - visible ) );
		if ( active > windowStart + visible - 1 ) {
			windowStart = active - visible + 1;
		}
		if ( windowStart < 0 ) {
			windowStart = 0;
		}

		var pct = -( windowStart / total ) * 100;
		sliders.forEach( function ( el ) {
			el.style.transform = 'translateX(' + pct + '%)';
		} );
	}

	function goToMember( idx ) {
		if ( idx < 0 || idx >= total ) {
			return;
		}
		active = idx;

		// Update counter
		if ( counterEl ) {
			counterEl.textContent = idx + 1;
		}

		// Update dots
		dots.forEach( ( dot, i ) => {
			dot.classList.toggle( 'team-timeline__dot--active', i === idx );
		} );

		// Update names — active is full opacity, others faded
		names.forEach( ( col, i ) => {
			if ( i === idx ) {
				col.classList.remove( 'opacity-30' );
			} else {
				col.classList.add( 'opacity-30' );
			}
		} );

		// Update descriptions — active visible, others greyed out
		descs.forEach( ( desc, i ) => {
			if ( i === idx ) {
				desc.classList.remove( 'opacity-30' );
			} else {
				desc.classList.add( 'opacity-30' );
			}
		} );

		slideWindow();
	}

	// Dot click navigation
	dots.forEach( ( dot, i ) => {
		dot.addEventListener( 'click', () => {
			goToMember( i );
		} );
	} );

	// Prev / Next buttons
	if ( prevBtn ) {
		prevBtn.addEventListener( 'click', () => {
			goToMember( active - 1 );
		} );
	}
	if ( nextBtn ) {
		nextBtn.addEventListener( 'click', () => {
			goToMember( active + 1 );
		} );
	}

	// Size the grid columns so 3 fit in the container width.
	// Each column = containerWidth / 3, so total grid width = (total/3) * 100%.
	function sizeGrid() {
		var visible = getVisible();
		var widthPct = ( total / visible ) * 100;
		sliders.forEach( function ( el ) {
			el.style.width = widthPct + '%';
		} );
		slideWindow();
	}
	sizeGrid();
	window.addEventListener( 'resize', sizeGrid );

	// Init first member
	goToMember( 0 );
} )();

// ── Team Grid Carousel ──────────────────────────────────────
( function () {
	const gridContainer = document.querySelector( '.team-grid-swiper' );
	if ( ! gridContainer || typeof Swiper === 'undefined' ) {
		return;
	}

	const counterEl = document.querySelector( '.team-grid__current' );

	// eslint-disable-next-line no-undef
	const teamGridSwiper = new Swiper( '.team-grid-swiper', {
		slidesPerView: 2,
		slidesPerGroup: 2,
		spaceBetween: 16,
		navigation: {
			prevEl: '.team-grid__prev',
			nextEl: '.team-grid__next',
		},
		breakpoints: {
			768: {
				slidesPerView: 3,
				slidesPerGroup: 3,
				spaceBetween: 20,
			},
			1024: {
				slidesPerView: 4,
				slidesPerGroup: 4,
				spaceBetween: 24,
			},
		},
		on: {
			slideChange: function () {
				if ( counterEl ) {
					const perGroup = this.params.slidesPerGroup;
					const page = Math.floor( this.activeIndex / perGroup ) + 1;
					counterEl.textContent = page;
				}
			},
		},
	} );

	// ── Bio Popup ──────────────────────────────────────────
} )();

( function () {
	const popup     = document.getElementById( 'team-popup' );
	const popupImg  = document.getElementById( 'team-popup-image' );
	const popupName = document.getElementById( 'team-popup-name' );
	const popupTitle = document.getElementById( 'team-popup-title' );
	const popupDesc = document.getElementById( 'team-popup-desc' );

	if ( ! popup ) {
		return;
	}

	function openPopup( btn ) {
		popupImg.src         = btn.dataset.image || '';
		popupImg.alt         = btn.dataset.name || '';
		popupName.textContent = btn.dataset.name || '';
		popupTitle.textContent = btn.dataset.title || '';
		popupDesc.innerHTML   = btn.dataset.desc || '';
		popup.classList.add( 'is-open' );
		popup.setAttribute( 'aria-hidden', 'false' );
		document.body.style.overflow = 'hidden';
	}

	function closePopup() {
		popup.classList.remove( 'is-open' );
		popup.setAttribute( 'aria-hidden', 'true' );
		document.body.style.overflow = '';
	}

	// Delegate click on bio buttons
	document.addEventListener( 'click', function ( e ) {
		const bioBtn = e.target.closest( '.team-card__bio-btn' );
		if ( bioBtn ) {
			e.preventDefault();
			openPopup( bioBtn );
			return;
		}

		if ( e.target.closest( '.team-popup__close' ) || e.target.closest( '.team-popup__backdrop' ) ) {
			closePopup();
		}
	} );

	// Close on Escape
	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' && popup.classList.contains( 'is-open' ) ) {
			closePopup();
		}
	} );
} )();

// ── Flagship Projects Tab Filtering ─────────────────────────
( function () {
	const tabs  = document.querySelectorAll( '.flagship-tab' );
	const cards = document.querySelectorAll( '.flagship-card' );

	if ( ! tabs.length || ! cards.length ) {
		return;
	}

	tabs.forEach( function ( tab ) {
		tab.addEventListener( 'click', function () {
			const term = this.dataset.term;

			// Update active tab
			tabs.forEach( function ( t ) {
				t.classList.remove( 'flagship-tab--active' );
			} );
			this.classList.add( 'flagship-tab--active' );

			// Filter cards
			cards.forEach( function ( card ) {
				if ( term === 'all' ) {
					card.classList.remove( 'is-hidden' );
				} else {
					const cardTerms = card.dataset.terms ? card.dataset.terms.split( ' ' ) : [];
					if ( cardTerms.indexOf( term ) !== -1 ) {
						card.classList.remove( 'is-hidden' );
					} else {
						card.classList.add( 'is-hidden' );
					}
				}
			} );
		} );
	} );
} )();

/* =========================================================
   Practice Area Tabs – active state + smooth scroll
   ========================================================= */
( function () {
	var tabBtns = document.querySelectorAll( '.pa-tab-btn' );
	if ( ! tabBtns.length ) return;

	tabBtns.forEach( function ( btn ) {
		btn.addEventListener( 'click', function ( e ) {
			e.preventDefault();

			// Toggle active class
			tabBtns.forEach( function ( b ) {
				b.classList.remove( 'pa-tab-btn--active' );
			} );
			this.classList.add( 'pa-tab-btn--active' );

			// Smooth-scroll to target section
			var target = document.querySelector( this.getAttribute( 'href' ) );
			if ( target ) {
				target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
			}
		} );
	} );
} )();

// =========================================================
// Single Post — Table of Contents (TOC) from H2 / H3
// =========================================================
( function () {
	var toc = document.getElementById( 'single-toc' );
	var content = document.querySelector( '.single-post-content' );

	if ( ! toc || ! content ) {
		return;
	}

	var headings = content.querySelectorAll( 'h2, h3' );
	if ( ! headings.length ) {
		return;
	}

	var fragment = document.createDocumentFragment();

	headings.forEach( function ( heading, index ) {
		// Generate an ID if the heading doesn't have one.
		if ( ! heading.id ) {
			heading.id = 'section-' + index;
		}

		var link = document.createElement( 'a' );
		link.href = '#' + heading.id;
		link.textContent = heading.textContent;
		link.setAttribute( 'data-level', heading.tagName === 'H3' ? '3' : '2' );

		link.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			var target = document.getElementById( heading.id );
			if ( target ) {
				target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
				history.replaceState( null, '', '#' + heading.id );
			}
		} );

		fragment.appendChild( link );
	} );

	toc.appendChild( fragment );

	// Highlight active TOC link on scroll.
	var tocLinks = toc.querySelectorAll( 'a' );
	var observer = new IntersectionObserver(
		function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					tocLinks.forEach( function ( l ) {
						l.classList.remove( 'toc-active' );
					} );
					var active = toc.querySelector( 'a[href="#' + entry.target.id + '"]' );
					if ( active ) {
						active.classList.add( 'toc-active' );
					}
				}
			} );
		},
		{ rootMargin: '0px 0px -70% 0px', threshold: 0 }
	);

	headings.forEach( function ( heading ) {
		observer.observe( heading );
	} );
} )();

/* =============================================================
   Scroll to Top Button
   ============================================================= */
( function () {
	var btn = document.getElementById( 'scroll-to-top' );
	if ( ! btn ) return;

	window.addEventListener( 'scroll', function () {
		if ( window.scrollY > 400 ) {
			btn.classList.add( 'visible' );
		} else {
			btn.classList.remove( 'visible' );
		}
	} );

	btn.addEventListener( 'click', function () {
		window.scrollTo( { top: 0, behavior: 'smooth' } );
	} );
} )();
