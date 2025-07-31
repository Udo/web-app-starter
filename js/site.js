var enable_debug = true;

UI = {

    smoothScrollToNamedAnchors: function() {
        $('a[href^="#"]').each(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    },

	enablePageTransitions: function() {
		$(document.body).append(`<style>
			::view-transition-old(root),
			::view-transition-new(root) {
			animation-duration: 0.25s;
			}

			::view-transition-old(root) {
			animation-name: fade-out;
			}

			::view-transition-new(root) {
			animation-name: fade-in;
			}

			@keyframes fade-out {
			from { opacity: 1; }
			to { opacity: 0; }
			}

			@keyframes fade-in {
			from { opacity: 0; }
			to { opacity: 1; }
			}</style>`);

		document.addEventListener('click', e => {
			const link = e.target.closest('a[href]');
			if (!link) return;

			e.preventDefault();

			document.startViewTransition(() => {
				window.location.href = link.href;
			});
		});
	},

    init: function() {
		//UI.enablePageTransitions();
        UI.smoothScrollToNamedAnchors();
        document.body.classList.add('loaded');
    },

}

$.ready(UI.init);
