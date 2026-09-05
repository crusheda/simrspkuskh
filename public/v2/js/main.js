(() => {
'use strict';

/* ================================
   GLOBAL CONSTANTS
================================ */
const docEl = document.documentElement;

/*
 * Breakpoint mengikuti CSS:
 * >= 1480px       = desktop full
 * 1200 - 1479px   = desktop mini
 * < 1200px        = mobile
 */
const APP_SIDEBAR_DESKTOP_BREAKPOINT = 1480;
const APP_SIDEBAR_MOBILE_BREAKPOINT = 1200;


/* ================================
   SIDEBAR RESPONSIVE
================================ */
const updateSidebarResponsive = () => {
	const appMenubar = document.getElementById('appMenubar');

	if (!appMenubar) return;

	const width = window.innerWidth;

	/*
	 * MOBILE
	 * Sidebar menggunakan mode mini-hover
	 * dan ditutup terlebih dahulu.
	 */
	if (width < APP_SIDEBAR_MOBILE_BREAKPOINT) {
		docEl.setAttribute('data-app-sidebar', 'mini-hover');
		appMenubar.classList.remove('open');

		document.querySelectorAll('.app-toggler').forEach(toggler => {
			toggler.classList.remove('active');
		});

		return;
	}

	/*
	 * LAPTOP / RESOLUSI KECIL
	 * Sidebar otomatis menjadi mini.
	 */
	if (width < APP_SIDEBAR_DESKTOP_BREAKPOINT) {
		docEl.setAttribute('data-app-sidebar', 'mini');
		appMenubar.classList.remove('open');

		document.querySelectorAll('.app-toggler').forEach(toggler => {
			toggler.classList.remove('active');
		});

		return;
	}

	/*
	 * DESKTOP BESAR
	 * Jangan memaksa full jika user sedang
	 * menggunakan mode mini.
	 *
	 * Jika state belum ada, gunakan full.
	 */
	const currentState = docEl.getAttribute('data-app-sidebar');

	if (!currentState) {
		docEl.setAttribute('data-app-sidebar', 'full');
	}
};


/* ================================
   APP TOGGLER
================================ */
const initAppToggler = () => {
	const appTogglers = document.querySelectorAll('.app-toggler');
	const appMenubar = document.getElementById('appMenubar');

	if (!appTogglers.length || !appMenubar) return;

	appTogglers.forEach(toggler => {
		toggler.addEventListener('click', () => {

			const width = window.innerWidth;
			const isMobile = width < APP_SIDEBAR_MOBILE_BREAKPOINT;

			/*
			 * MOBILE
			 */
			if (isMobile) {

				const isOpen = appMenubar.classList.toggle('open');

				toggler.classList.toggle('active', isOpen);

				if (isOpen) {
					docEl.setAttribute(
						'data-app-sidebar',
						'full'
					);
				} else {
					docEl.setAttribute(
						'data-app-sidebar',
						'mini-hover'
					);
				}

				return;
			}

			/*
			 * DESKTOP / LAPTOP
			 */
			const current = docEl.getAttribute('data-app-sidebar');

			if (current === 'full') {

				docEl.setAttribute(
					'data-app-sidebar',
					'mini'
				);

				appMenubar.classList.remove('open');
				toggler.classList.remove('active');

			} else {

				docEl.setAttribute(
					'data-app-sidebar',
					'full'
				);

				appMenubar.classList.add('open');
				toggler.classList.add('active');
			}

		});
	});


	/*
	 * HOVER SIDEBAR
	 *
	 * Hanya bekerja pada mode mini.
	 */
	appMenubar.addEventListener('mouseenter', () => {

		if (
			window.innerWidth >= APP_SIDEBAR_MOBILE_BREAKPOINT &&
			docEl.getAttribute('data-app-sidebar') === 'mini'
		) {
			docEl.setAttribute(
				'data-app-sidebar',
				'mini-hover'
			);
		}

	});


	appMenubar.addEventListener('mouseleave', () => {

		if (
			window.innerWidth >= APP_SIDEBAR_MOBILE_BREAKPOINT &&
			docEl.getAttribute('data-app-sidebar') === 'mini-hover'
		) {
			docEl.setAttribute(
				'data-app-sidebar',
				'mini'
			);
		}

	});

};


/* ================================
   AUTO RESIZE SIDEBAR
================================ */
const initSidebarResponsive = () => {

	let resizeTimer = null;

	const handleResize = () => {

		clearTimeout(resizeTimer);

		resizeTimer = setTimeout(() => {

			const width = window.innerWidth;

			/*
			 * Ketika masuk MOBILE
			 */
			if (width < APP_SIDEBAR_MOBILE_BREAKPOINT) {

				docEl.setAttribute(
					'data-app-sidebar',
					'mini-hover'
				);

				const appMenubar =
					document.getElementById('appMenubar');

				if (appMenubar) {
					appMenubar.classList.remove('open');
				}

				document
					.querySelectorAll('.app-toggler')
					.forEach(toggler => {
						toggler.classList.remove('active');
					});

				return;
			}

			/*
			 * Ketika masuk LAPTOP / RESOLUSI KECIL
			 */
			if (width < APP_SIDEBAR_DESKTOP_BREAKPOINT) {

				docEl.setAttribute(
					'data-app-sidebar',
					'mini'
				);

				const appMenubar =
					document.getElementById('appMenubar');

				if (appMenubar) {
					appMenubar.classList.remove('open');
				}

				document
					.querySelectorAll('.app-toggler')
					.forEach(toggler => {
						toggler.classList.remove('active');
					});

				return;
			}

			/*
			 * DESKTOP BESAR
			 *
			 * Tidak memaksa sidebar menjadi full.
			 * Jika sebelumnya mini, tetap mini.
			 */
			const currentState =
				docEl.getAttribute('data-app-sidebar');

			if (
				currentState !== 'full' &&
				currentState !== 'mini' &&
				currentState !== 'mini-hover'
			) {
				docEl.setAttribute(
					'data-app-sidebar',
					'full'
				);
			}

		}, 100);
	};

	window.addEventListener(
		'resize',
		handleResize
	);

	/*
	 * Jalankan sekali ketika halaman pertama kali dibuka.
	 */
	handleResize();
};


/* ================================
   PASSWORD TOGGLE
================================ */
const passwordToggle = () => {
	document.addEventListener('click', (e) => {
		const btn = e.target.closest('.toggle-password');
		if (!btn) return;

		const input = btn.previousElementSibling;
		if (!input) return;

		const isPassword = input.type === 'password';
		input.type = isPassword ? 'text' : 'password';
		btn.classList.toggle('active', isPassword);
	});
};


/* ================================
   SEARCH LIST
================================ */
const saerchList = () => {
	let listItems = [];

	// JSON load
	$.getJSON("/v2/ajax/search.json", function(data) {
		listItems = data.listItems;
	});

	// Search functionality
	$("#searchInput").on("keyup", function() {
		let query = $(this).val().toLowerCase();
		let searchContainer = $("#searchContainer");
		searchContainer.empty();
		searchContainer.hide();

		$('#recentlyResults').hide();

		if (query.length === 0) {
			searchContainer.hide();
			$('#recentlyResults').show();
			return;
		}

		let matched = listItems.filter(item =>
			item.name.toLowerCase().includes(query) ||
			item.url.toLowerCase().includes(query)
		);

		if (matched.length > 0) {
			let grouped = {};
			matched.forEach(item => {
				if (!grouped[item.category]) grouped[item.category] = [];
				grouped[item.category].push(item);
			});

			for (let cat in grouped) {
				searchContainer.append(
					`<span class="text-uppercase text-2xs fw-semibold text-muted d-block mb-2">${cat}</span>`
				);
				let ul = $("<ul class='list-inline search-list'></ul>");
				grouped[cat].forEach(item => {
					ul.append(
						`<li>
							<a class="search-item" href="${item.url}">
								<i class="${item.icon}"></i> <span>${item.name}</span>
							</a>
						</li>`
					);
				});
				searchContainer.append(ul);
			}
			searchContainer.show();
		} else {
			searchContainer.append(`
				<div class="text-center pb-5 pt-4">
					<div class="avatar avatar-lg bg-danger-subtle shadow-secondary rounded-circle text-danger mb-3 m-auto">
						<i class="fi fi-rr-assessment"></i>
					</div>
					<h5 class="mb-1">No result found</h5>
					<div class="text-muted">Please try again with a different query</div>
				</div>
			`);
			searchContainer.show();
		}
	});
};


/* ================================
   CURRENT YEAR
================================ */
const currentYear = () => {
	document.querySelectorAll('.currentYear').forEach(el => {
		el.textContent = new Date().getFullYear();
	});
};


/* ================================
   DYNAMIC HEIGHTS
================================ */
const setElementHeight = () => {
	const footer = document.querySelector('.footer-wrapper');
	const chatBox = document.querySelector('.chat-wrapper');

	if (footer) {
		docEl.style.setProperty(
			'--footer-height',
			`${footer.offsetHeight}px`
		);
	}

	if (chatBox) {
		docEl.style.setProperty(
			'--chat-height',
			`${chatBox.offsetHeight}px`
		);
	}
};


/* ================================
   SELECT PICKER
================================ */
const initSelectPicker = () => {
	document.querySelectorAll('.select-status').forEach(dropdown => {
		const toggleBtn = dropdown.querySelector('.dropdown-toggle');
		const items = dropdown.querySelectorAll('.dropdown-item');

		if (!toggleBtn) return;

		const updateBtn = (text, cls) => {
			toggleBtn.classList.forEach(c => {
				if (/^btn-/.test(c) && !['btn-sm', 'btn-lg'].includes(c)) {
					toggleBtn.classList.remove(c);
				}
			});
			if (cls) toggleBtn.classList.add(...cls.split(' '));
			toggleBtn.textContent = text;
		};

		const defaultItem = dropdown.querySelector('[data-selected="true"]');
		if (defaultItem) {
			updateBtn(
				defaultItem.textContent.trim(),
				defaultItem.getAttribute('data-class')
			);
		}

		items.forEach(item => {
			item.addEventListener('click', e => {
				e.preventDefault();
				items.forEach(i => i.removeAttribute('data-selected'));
				item.setAttribute('data-selected', 'true');
				updateBtn(
					item.textContent.trim(),
					item.getAttribute('data-class')
				);
			});
		});
	});
};


/* ================================
   CHECKBOX SYNC
================================ */
const initSectionCheckboxSync = () => {
	document.querySelectorAll('.data-row-checkbox').forEach(section => {
		const master = section.querySelector('[data-row-checkbox]');
		const boxes = section.querySelectorAll('[data-checkbox]');
		if (!master || !boxes.length) return;

		master.addEventListener('change', () => {
			boxes.forEach(cb => cb.checked = master.checked);
		});

		boxes.forEach(cb => {
			cb.addEventListener('change', () => {
				master.checked = [...boxes].every(c => c.checked);
			});
		});
	});
};


/* ================================
   BOOTSTRAP TOOLTIPS
================================ */
function initTooltips() {
	if (!window.bootstrap) return;
	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');

	tooltipTriggerList.forEach(el => {

		const tooltip = new bootstrap.Tooltip(el);

		// click par tooltip hide + focus remove
		el.addEventListener('click', () => {
			tooltip.hide();
			el.blur();
		});

	});
}


/* ================================
   BOOTSTRAP POPOVER
================================ */
function initPopover() {
	if (!window.bootstrap) return;
	var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
	var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
		return new bootstrap.Popover(popoverTriggerEl)
	})
}


/* ================================
   SIDEBAR MENU (jQuery)
================================ */
const initSidebarMenu = () => {
	if (typeof jQuery === 'undefined') return;
	const $ = jQuery;

	/* =============================
	   MENU TOGGLE
	============================= */
	$('.app-navbar .menu-inner').hide();

	$('.app-navbar').on('click', 'li > a', function (e) {

		var $link = $(this);
		var $submenu = $link.next('.menu-inner');

		if ($submenu.length) {
			e.preventDefault();

			if ($link.hasClass('open')) {
				$link.removeClass('open');
				$submenu.slideUp();
			} else {
				$link.closest('.app-navbar')
					.find('a.open').removeClass('open')
					.next('.menu-inner').slideUp();

				$link.addClass('open');
				$submenu.slideDown();
			}
		}
	});

	/* =============================
	   CURRENT PAGE
	============================= */

    let currentPage = window.location.pathname.replace(/\/$/, '');

	if (!currentPage) currentPage = 'v2/dashboard';

	// reset
	$('.app-navbar a').removeClass('active open');
	$('.app-navbar li').removeClass('active');
	$('.app-menubar-tabs .menu-link').removeClass('active');
	$('.app-tab-content .tab-pane').removeClass('active show');

    const $activeLink = $('.app-navbar a').filter(function () {

        const href = $(this).attr('href');
        if (!href)
            return false;

        // abaikan tab bootstrap
        if (href.startsWith('#'))
            return false;

        // abaikan javascript
        if (href.startsWith('javascript'))
            return false;

        const url = new URL(href, window.location.origin);

        return url.pathname.replace(/\/$/, '') === currentPage;

    }).first();

    const hasActiveMenu = $activeLink.length > 0;
    const isMobile = window.innerWidth < APP_SIDEBAR_MOBILE_BREAKPOINT;

    if (isMobile) {

        // Mobile selalu menggunakan mini-hover
        $('html').attr('data-app-sidebar', 'mini-hover');
        $('#appMenubar').removeClass('open');

    } else if (window.innerWidth < APP_SIDEBAR_DESKTOP_BREAKPOINT) {

        // Laptop / resolusi kecil otomatis mini
        $('html').attr('data-app-sidebar', 'mini');
        $('#appMenubar').removeClass('open');

    } else {

        // Desktop besar
        $('html').attr(
            'data-app-sidebar',
            hasActiveMenu ? 'full' : 'mini'
        );

        $('#appMenubar').toggleClass(
            'open',
            hasActiveMenu
        );

    }

    if (hasActiveMenu) {

        $activeLink.addClass('active');
        $activeLink.parent('li').addClass('active');

        $activeLink
            .parents('.menu-inner')
            .show()
            .prev('a')
            .addClass('open active');

        const $tabPane = $activeLink.closest('.tab-pane');

        if ($tabPane.length) {
            $tabPane.addClass('active show');

            const tabId = $tabPane.attr('id');

            $('.app-menubar-tabs .menu-link[href="#' + tabId + '"]')
                .addClass('active');
        }

    } else {

        // Tetap tampilkan tab pertama agar konten sidebar tidak kosong
        const $firstTab = $('.app-menubar-tabs .menu-link').first();

        if ($firstTab.length) {
            $firstTab.addClass('active');
            $($firstTab.attr('href')).addClass('active show');
        }

    }
};


/* ================================
   CHECKABLE ITEMS
================================ */
const initCheckable = () => {
	document.querySelectorAll('.checkable-wrapper').forEach(wrapper => {
		const all = wrapper.querySelector('.checkable-check-all');
		const boxes = wrapper.querySelectorAll('.checkable-check-input');

		if (all) {
			all.addEventListener('change', () => {
				boxes.forEach(b => {
					b.checked = all.checked;
					b.closest('.checkable-item')?.classList.toggle('is-checked', all.checked);
				});
			});
		}

		wrapper.addEventListener('change', e => {
			if (!e.target.classList.contains('checkable-check-input')) return;

			e.target.closest('.checkable-item')?.classList.toggle(
				'is-checked',
				e.target.checked
			);

			if (all) {
				all.checked = !wrapper.querySelector(
					'.checkable-check-input:not(:checked)'
				);
			}
		});
	});
};


/* ================================
   EMAIL + CHAT SIDEBAR
================================ */
const initEmailSidebarToggle = () => {
	const t = document.querySelector('.mail-sidebar-toggler');
	const s = document.querySelector('.mail-sidebar');
	const o = document.querySelector('.sidebar-mobile-overlay');

	if (!t || !s || !o) return;

	t.onclick = () => {
		s.classList.toggle('open');
		o.classList.toggle(
			'show',
			s.classList.contains('open')
		);
	};

	o.onclick = () => {
		s.classList.remove('open');
		o.classList.remove('show');
	};
};


const initChatSidebarToggle = () => {
	const t = document.querySelector('.chat-sidebar-toggler');
	const s = document.querySelector('.chat-sidebar');
	const o = document.querySelector('.sidebar-mobile-overlay');
	const c = document.querySelector('.btn-close');

	if (!t || !s || !o) return;

	const close = () => {
		s.classList.remove('open');
		o.classList.remove('show');
	};

	t.onclick = () => {
		s.classList.toggle('open');
		o.classList.toggle(
			'show',
			s.classList.contains('open')
		);
	};

	o.onclick = close;
	c && (c.onclick = close);
};


/* ================================
   BOOKMARKS
================================ */
const initBookmarks = () => {
	document.addEventListener('click', e => {
		const bm = e.target.closest('.mail-item-bookmark');
		bm && bm.classList.toggle('active');
	});
};


/* ================================
   THEME SWITCHER
================================ */
const ThemeSwitcher = () => {
	'use strict';

	const docEl = document.documentElement;

	const getCookie = (name) => {
		const match = document.cookie.match(
			new RegExp('(^| )' + name + '=([^;]+)')
		);

		return match ? match[2] : null;
	};

	const setCookie = (name, value, days = 365) => {
		const expires = new Date(
			Date.now() + days * 864e5
		).toUTCString();

		document.cookie =
			`${name}=${value}; expires=${expires}; path=/`;
	};

	// Observer
	const observer = new MutationObserver(() => {

		const theme =
			docEl.getAttribute('data-bs-theme');

		setCookie('theme', theme);

		$('.theme-btn').toggleClass(
			'active',
			theme === 'dark'
		);

	});

	observer.observe(docEl, {
		attributes: true,
		attributeFilter: ['data-bs-theme']
	});

	// Inisialisasi
	const saved = getCookie('theme');

	if (saved) {
		docEl.setAttribute(
			'data-bs-theme',
			saved
		);
	}

	$('.theme-btn').on('click', function () {

		const next =
			docEl.getAttribute('data-bs-theme') === 'dark'
				? 'light'
				: 'dark';

		docEl.setAttribute(
			'data-bs-theme',
			next
		);

	});

};


/* ================================
   SIDEBAR PANEL
================================ */
function initSidebarPanel() {
	document.addEventListener('click', function(e) {
		const toggler = e.target.closest('.sidebar-panel-toggler');
		const closeBtn = e.target.closest('.sidebar-close');

		if (!toggler || closeBtn) return;

		if (toggler) {
			const panel =
				document.querySelector('.app-sidebar-panel');

			if (panel) {
				panel.classList.toggle('show');
			}
		}

		if (closeBtn) {
			document
				.querySelectorAll('.app-sidebar-panel')
				.forEach(panel => {
					panel.classList.remove('show');
				});
		}
	});
}


/* ================================
   PRICE SWITCH
================================ */
function initPriceSwitch() {
	const priceSwitch =
		document.querySelector("#priceSwitchCheck");

	if (!priceSwitch) return;

	if (priceSwitch) {
		priceSwitch.addEventListener(
			"change",
			function () {

				const isYearly = this.checked;

				const monthlyPrices =
					document.querySelectorAll(".price-monthly");

				const yearlyPrices =
					document.querySelectorAll(".price-yearly");

				monthlyPrices.forEach(price =>
					price.classList.toggle(
						"d-none",
						isYearly
					)
				);

				yearlyPrices.forEach(price =>
					price.classList.toggle(
						"d-none",
						!isYearly
					)
				);

			}
		);
	}
}


/* ================================
   PLUGINS DATATABLE
================================ */
function initPluginsDataTable() {
	if ($('.dataTable').length > 0) {
		$('.dataTable').each(function() {

			const dtInstance =
				$(this).DataTable();

			dtInstance.on(
				'draw.dt',
				function() {
					initSelectPicker();
					initSectionCheckboxSync();
				}
			);

		});
	}
}


/* ================================
   FLAT PICKR DATE
================================ */
function initFlatpickrDate() {
	if ($('.flatpickr-date').length > 0) {
		$(".flatpickr-date").flatpickr({
			enableTime: false,
			dateFormat: "Y-m-d H:i",
		});
	}
}


/* ================================
   INITIALIZATION
================================ */
document.addEventListener("DOMContentLoaded", () => {
	try {

		Waves.init();

		/*
		 * Sidebar menu dijalankan terlebih dahulu
		 * agar active menu diketahui.
		 */
		initSidebarMenu();

		/*
		 * Toggler setelah state sidebar ditentukan.
		 */
		initAppToggler();

		/*
		 * Responsive listener.
		 *
		 * Ini yang membuat sidebar mengikuti
		 * perubahan ukuran browser / browser zoom.
		 */
		initSidebarResponsive();

		passwordToggle();
		saerchList();
		setElementHeight();
		currentYear();
		initSectionCheckboxSync();
		initSelectPicker();
		initTooltips();
		initPopover();
		initCheckable();
		initEmailSidebarToggle();
		initChatSidebarToggle();
		initBookmarks();
		ThemeSwitcher();
		initSidebarPanel();
		initPriceSwitch();
		initPluginsDataTable();
		initFlatpickrDate();

	} catch (e) {
		console.error('Init Error:', e);
	}
});

})();
