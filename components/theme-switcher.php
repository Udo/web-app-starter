<?php return [
    'render' => function($prop) {
        ?>
        <!-- Theme Switcher -->
        <div id="theme-switcher" style="position: fixed; bottom: 2rem; right: 2rem; z-index: 9999;">
            <button id="theme-toggle" style="
                width: 56px;
                height: 56px;
                border-radius: 50%;
                background: var(--surface);
                border: 1px solid var(--border);
                box-shadow: var(--shadow-lg);
                cursor: pointer;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--text-primary);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            " 
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-xl)'; this.style.borderColor='var(--primary)'; this.style.background='var(--surface-elevated)';"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-lg)'; this.style.borderColor='var(--border)'; this.style.background='var(--surface)';"
            aria-label="Toggle dark/light theme" title="Switch Theme">
                <svg id="sun-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; transition: all 0.3s ease;">
                    <circle cx="12" cy="12" r="5"/>
                    <path d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                </svg>
                <svg id="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; transition: all 0.3s ease; opacity: 0; transform: rotate(180deg) scale(0.5);">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>
        </div>
        
        <script>
        (function() {
            // Initialize theme switcher
            const themeToggle = document.getElementById('theme-toggle');
            const sunIcon = document.getElementById('sun-icon');
            const moonIcon = document.getElementById('moon-icon');
            
            if (!themeToggle) return;
            
            // Get current theme from localStorage or default to light
            let currentTheme = localStorage.getItem('theme') || 'light';
            
            // Apply current theme on load
            applyTheme(currentTheme);
            
            // Handle theme toggle click
            themeToggle.addEventListener('click', function() {
                currentTheme = currentTheme === 'light' ? 'dark' : 'light';
                applyTheme(currentTheme);
                localStorage.setItem('theme', currentTheme);
            });
            
            function applyTheme(theme) {
                const html = document.documentElement;
                const body = document.body;
                
                if (theme === 'dark') {
                    html.classList.add('dark-theme');
                    body.classList.add('dark-theme');
                    // Update icons for dark theme
                    sunIcon.style.opacity = '0';
                    sunIcon.style.transform = 'rotate(-180deg) scale(0.5)';
                    moonIcon.style.opacity = '1';
                    moonIcon.style.transform = 'rotate(0deg) scale(1)';
                    // Update CSS link to dark theme
                    updateThemeCSS('dark');
                } else {
                    html.classList.remove('dark-theme');
                    body.classList.remove('dark-theme');
                    // Update icons for light theme
                    sunIcon.style.opacity = '1';
                    sunIcon.style.transform = 'rotate(0deg) scale(1)';
                    moonIcon.style.opacity = '0';
                    moonIcon.style.transform = 'rotate(180deg) scale(0.5)';
                    // Update CSS link to light theme
                    updateThemeCSS('light');
                }
            }
            
            function updateThemeCSS(theme) {
                const cssLink = document.querySelector('link[href*="/themes/"]');
                if (cssLink) {
                    const currentHref = cssLink.href;
                    const newHref = currentHref.replace(/\/themes\/(light|dark)\//, `/themes/${theme}/`);
                    if (newHref !== currentHref) {
                        cssLink.href = newHref;
                    }
                }
            }
        })();
        </script>
        <?php
    },
    
    'about' => 'A floating theme switcher button that toggles between light and dark themes'
]; 
