<?php return [
    'render' => function($prop) {
		$themes = cfg('theme/options');
		$currentTheme = (string)cfg('theme/key');
		$currentLabel = (string)first(cfg('theme/label'), $themes[$currentTheme]['label'] ?? $currentTheme);
		$routePath = (string)(URL::$route['l-path'] ?? '');
		$buildThemeLink = function($themeKey) use($routePath) {
			if(cfg('url/pretty')) {
				return URL::Link($routePath, ['theme' => $themeKey]);
			}
			if($routePath !== '') {
				return URL::Link($routePath, ['theme' => $themeKey]);
			}
			return cfg('url/root') . '?' . http_build_query(['theme' => $themeKey]);
		};
        ?>
        <div id="theme-switcher" style="position: fixed; right: 1.5rem; bottom: 1.5rem; z-index: 9999; font-family: inherit;">
            <style>
                #theme-switcher .theme-launcher {
                    min-width: 56px;
                    height: 56px;
                    padding: 0 1rem;
                    border-radius: 999px;
                    background: var(--surface, #fff);
                    border: 1px solid var(--border, rgba(0,0,0,0.15));
                    box-shadow: var(--shadow-lg, 0 14px 32px rgba(0,0,0,0.18));
                    cursor: pointer;
                    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, background 0.2s ease;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.65rem;
                    color: var(--text-primary, #111827);
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                    font: inherit;
                    font-size: 0.9rem;
                    font-weight: 700;
                }
                #theme-switcher .theme-launcher:hover {
                    transform: translateY(-2px);
                    box-shadow: var(--shadow-xl, 0 22px 42px rgba(0,0,0,0.22));
                    border-color: var(--primary, #2563eb);
                    background: var(--surface-elevated, var(--surface, #fff));
                }
                #theme-switcher .theme-launcher-label {
                    display: none;
                    white-space: nowrap;
                }
                #theme-switcher .theme-menu {
                    position: absolute;
                    right: 0;
                    bottom: 68px;
                    width: min(280px, calc(100vw - 2rem));
                    padding: 0.6rem;
                    border-radius: 14px;
                    border: 1px solid var(--border, rgba(0,0,0,0.15));
                    background: color-mix(in srgb, var(--surface, #fff) 92%, transparent 8%);
                    box-shadow: var(--shadow-xl, 0 22px 42px rgba(0,0,0,0.22));
                }
                #theme-switcher .theme-menu[hidden] {
                    display: none;
                }
                #theme-switcher .theme-menu-title {
                    margin: 0 0 0.45rem;
                    padding: 0.1rem 0.25rem 0.35rem;
                    color: var(--text-secondary, #6b7280);
                    font-size: 0.74rem;
                    font-weight: 700;
                    letter-spacing: 0.06em;
                    text-transform: uppercase;
                }
                #theme-switcher .theme-menu-list {
                    display: grid;
                    gap: 0.35rem;
                }
                #theme-switcher .theme-option {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 0.75rem;
                    padding: 0.7rem 0.8rem;
                    border-radius: 10px;
                    border: 1px solid transparent;
                    color: var(--text-primary, #111827);
                    text-decoration: none;
                    font-size: 0.9rem;
                    font-weight: 600;
                }
                #theme-switcher .theme-option:hover {
                    background: color-mix(in srgb, var(--primary, #2563eb) 10%, transparent 90%);
                    border-color: color-mix(in srgb, var(--primary, #2563eb) 22%, transparent 78%);
                    text-decoration: none;
                }
                #theme-switcher .theme-option.is-active {
                    background: color-mix(in srgb, var(--primary, #2563eb) 16%, transparent 84%);
                    border-color: color-mix(in srgb, var(--primary, #2563eb) 28%, transparent 72%);
                }
                #theme-switcher .theme-option small {
                    color: var(--text-secondary, #6b7280);
                    font-size: 0.76rem;
                    font-weight: 600;
                }
                @media (min-width: 860px) {
                    #theme-switcher .theme-launcher-label {
                        display: inline;
                    }
                }
            </style>
            <button id="theme-toggle" class="theme-launcher" aria-haspopup="true" aria-expanded="false" aria-label="Switch theme" title="Switch Theme" type="button">
                <i class="fas fa-palette" aria-hidden="true"></i>
                <span class="theme-launcher-label"><?= safe($currentLabel) ?></span>
            </button>
            <div id="theme-menu" class="theme-menu" hidden>
                <div class="theme-menu-title">Available Themes</div>
                <div class="theme-menu-list">
                    <?php foreach($themes as $themeKey => $themeInfo): ?>
                        <a class="theme-option<?= $themeKey === $currentTheme ? ' is-active' : '' ?>" href="<?= asafe($buildThemeLink($themeKey)) ?>">
                            <span><?= safe((string)$themeInfo['label']) ?></span>
                            <?php if($themeKey === $currentTheme): ?><small>Active</small><?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <script>
        (function() {
            const themeToggle = document.getElementById('theme-toggle');
            const themeMenu = document.getElementById('theme-menu');
            
            if (!themeToggle || !themeMenu) return;

            themeToggle.addEventListener('click', function() {
                const isOpen = !themeMenu.hasAttribute('hidden');
                themeMenu.toggleAttribute('hidden', isOpen);
                themeToggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
            });

            document.addEventListener('click', function(event) {
                if (!themeMenu.hasAttribute('hidden') && !document.getElementById('theme-switcher').contains(event.target)) {
                    themeMenu.setAttribute('hidden', 'hidden');
                    themeToggle.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && !themeMenu.hasAttribute('hidden')) {
                    themeMenu.setAttribute('hidden', 'hidden');
                    themeToggle.setAttribute('aria-expanded', 'false');
                }
            });
        })();
        </script>
        <?php
    },
    
    'about' => 'A floating theme switcher button that toggles between light and dark themes'
]; 
