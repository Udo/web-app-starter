<?php return [
    'render' => function($prop) {
        ?>
        <!-- Cookie Consent Banner -->
        <div id="cookie-consent" style="
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--surface);
            border-top: 1px solid var(--border);
            box-shadow: var(--shadow-xl);
            z-index: 9998;
            padding: 1.5rem;
            transform: translateY(100%);
            transition: transform 0.3s ease;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        " aria-live="polite">
            <div style="
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1.5rem;
                flex-wrap: wrap;
            ">
                <div style="flex: 1; min-width: 300px;">
                    <div style="
                        display: flex;
                        align-items: center;
                        gap: 0.75rem;
                        margin-bottom: 0.5rem;
                    ">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="flex-shrink: 0;">
                            <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2M21 9V7L15 1L13 3L15 5H3V21A2 2 0 0 0 5 23H19A2 2 0 0 0 21 21V11L19 13V20H5V7H21M9 11V13H7V11H9M13 11V13H11V11H13M17 11V13H15V11H17M9 15V17H7V15H9M13 15V17H11V15H13M17 15V17H15V15H17Z"/>
                        </svg>
                        <h3 style="
                            margin: 0;
                            font-size: 1.1rem;
                            font-weight: 600;
                            color: var(--text-primary);
                        ">Cookie Notice</h3>
                    </div>
                    <p style="
                        margin: 0;
                        color: var(--text-secondary);
                        font-size: 0.9rem;
                        line-height: 1.5;
                    ">
                        <?= first($prop['text'], 
                            'We use cookies to enhance your browsing experience, serve personalized content, and analyze our traffic. 
                        By clicking "Accept All", you consent to our use of cookies.') ?>
                        <a href="#" id="cookie-policy-link" style="
                            color: var(--primary);
                            text-decoration: none;
                            border-bottom: 1px solid transparent;
                            transition: border-color 0.2s ease;
                        " 
                        onmouseover="this.style.borderColor='var(--primary)';"
                        onmouseout="this.style.borderColor='transparent';">
                            Learn more
                        </a>
                    </p>
                </div>
                <div style="
                    display: flex;
                    gap: 0.75rem;
                    flex-shrink: 0;
                    align-items: center;
                ">
                    <button id="cookie-reject" style="
                        padding: 0.75rem 1.5rem;
                        border: 1px solid var(--border);
                        background: transparent;
                        color: var(--text-secondary);
                        border-radius: 0.5rem;
                        cursor: pointer;
                        font-size: 0.875rem;
                        font-weight: 500;
                        transition: all 0.2s ease;
                        white-space: nowrap;
                    "
                    onmouseover="this.style.background='var(--surface-elevated)'; this.style.borderColor='var(--primary)'; this.style.color='var(--text-primary)';"
                    onmouseout="this.style.background='transparent'; this.style.borderColor='var(--border)'; this.style.color='var(--text-secondary)';">
                        Reject All
                    </button>
                    <button id="cookie-accept" style="
                        padding: 0.75rem 1.5rem;
                        border: 1px solid var(--primary);
                        background: var(--primary);
                        color: white;
                        border-radius: 0.5rem;
                        cursor: pointer;
                        font-size: 0.875rem;
                        font-weight: 500;
                        transition: all 0.2s ease;
                        white-space: nowrap;
                        box-shadow: var(--shadow-sm);
                    "
                    onmouseover="this.style.background='var(--primary-dark, #3b82f6)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='var(--shadow-md)';"
                    onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-sm)';">
                        Accept All
                    </button>
                </div>
            </div>
        </div>
        
        <script>
        (function() {
            // Cookie consent functionality
            let cookieConsent = document.getElementById('cookie-consent');
            let acceptButton = document.getElementById('cookie-accept');
            let rejectButton = document.getElementById('cookie-reject');
            let policyLink = document.getElementById('cookie-policy-link');
            
            if (!cookieConsent) return;
            
            let COOKIE_NAME = <?= jsafe(first($prop['cookie_name'], 'cookie_consent')) ?>;
            let COOKIE_EXPIRY_DAYS = <?= jsafe(first($prop['expiry_days'], 365)) ?>;
            
            // Check if user has already made a choice
            let existingConsent = getCookie(COOKIE_NAME);
            <?php 
            if($prop['reset'])
            {
                $_COOKIE[$COOKIE_NAME] = '';
                ?>existingConsent = false;<?php
            }
            ?>
            
            if (!existingConsent) {
                setTimeout(() => {
                    cookieConsent.style.transform = 'translateY(0)';
                }, 1);
            }
            
            // Handle accept button
            acceptButton.addEventListener('click', function() {
                setCookie(COOKIE_NAME, 'accepted', COOKIE_EXPIRY_DAYS);
                hideBanner();
                // Initialize analytics or other tracking here if needed
                console.log('Cookies accepted');
            });
            
            // Handle reject button
            rejectButton.addEventListener('click', function() {
                setCookie(COOKIE_NAME, 'rejected', COOKIE_EXPIRY_DAYS);
                hideBanner();
                // Disable tracking here if needed
                console.log('Cookies rejected');
            });
            
            // Handle policy link (you can customize this URL)
            policyLink.addEventListener('click', function(e) {
                e.preventDefault();
                // You can change this to your actual privacy policy URL
                window.open('/privacy-policy', '_blank');
            });
            
            function hideBanner() {
                cookieConsent.style.transform = 'translateY(100%)';
                setTimeout(() => {
                    cookieConsent.style.display = 'none';
                }, 300);
            }
            
            function setCookie(name, value, days) {
                let expires = new Date();
                expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
                document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/;SameSite=Lax';
            }
            
            function getCookie(name) {
                let nameEQ = name + '=';
                let ca = document.cookie.split(';');
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }
            
            // Keyboard accessibility
            cookieConsent.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Treat Escape as reject
                    rejectButton.click();
                }
            });
            
            // Focus management for accessibility
            if (!existingConsent) {
                setTimeout(() => {
                    acceptButton.focus();
                }, 1100);
            }
            
        })();
        </script>
        
        <style>
        /* Responsive adjustments for cookie consent */
        @media (max-width: 768px) {
            #cookie-consent > div {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 1rem !important;
            }
            
            #cookie-consent > div > div:last-child {
                justify-content: center !important;
            }
            
            #cookie-accept, #cookie-reject {
                flex: 1 !important;
                text-align: center !important;
            }
        }
        
        /* High contrast mode support */
        @media (prefers-contrast: high) {
            #cookie-consent {
                border-top-width: 2px !important;
            }
            
            #cookie-accept, #cookie-reject {
                border-width: 2px !important;
            }
        }
        
        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            #cookie-consent, #cookie-accept, #cookie-reject {
                transition: none !important;
            }
        }
        </style>
        <?php
    },
    
    'about' => 'A GDPR-compliant cookie consent banner that appears at the bottom of the page with accept/reject options'
];
