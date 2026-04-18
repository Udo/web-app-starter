(function (root, factory) {
	if (typeof define === 'function' && define.amd) {
		define([], factory);
	} else if (typeof module === 'object' && module.exports) {
		module.exports = factory();
	} else {
		root.UWorkspaceShell = factory();
	}
}(typeof self !== 'undefined' ? self : this, function () {
	'use strict';
	const globalScope = typeof globalThis !== 'undefined' ? globalThis : (typeof window !== 'undefined' ? window : this);

	function getElement(target) {
		if (!target) return null;
		if (typeof target === 'string') return document.getElementById(target);
		return target && target.nodeType === 1 ? target : null;
	}

	function bindShell(options) {
		const sidebar = getElement(options.sidebarId || options.sidebar);
		const overlay = getElement(options.overlayId || options.overlay);
		const toggle = getElement(options.toggleButtonId || options.toggle);
		const closeOnNav = options.closeOnNav !== false;

		if (!sidebar || !overlay) return null;

		function setOpen(open) {
			sidebar.classList.toggle('is-open', !!open);
			overlay.classList.toggle('is-open', !!open);
		}

		function toggleOpen() {
			setOpen(!sidebar.classList.contains('is-open'));
		}

		if (toggle) {
			toggle.addEventListener('click', toggleOpen);
		}

		overlay.addEventListener('click', function () {
			setOpen(false);
		});

		if (closeOnNav) {
			sidebar.querySelectorAll('a').forEach(function (link) {
				link.addEventListener('click', function () {
					setOpen(false);
				});
			});
		}

		globalScope.addEventListener('resize', function () {
			if (globalScope.innerWidth > 860) {
				setOpen(false);
			}
		});

		return {
			open: function () { setOpen(true); },
			close: function () { setOpen(false); },
			toggle: toggleOpen,
		};
	}

	return {
		init: bindShell,
	};
}));