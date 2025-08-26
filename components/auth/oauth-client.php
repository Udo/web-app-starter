<?php return [
    'render' => function($prop) {
        $services = $prop['services'] ?? [
            'google' => [
                'name' => 'Google',
                'color' => '#4285f4',
                'icon' => 'fab fa-google',
                'scope' => 'openid email profile',
                'auth_url' => 'https://accounts.google.com/oauth/authorize',
                'token_url' => 'https://oauth2.googleapis.com/token',
                'user_info_url' => 'https://www.googleapis.com/oauth2/v2/userinfo'
            ],
            'github' => [
                'name' => 'GitHub',
                'color' => '#333333',
                'icon' => 'fab fa-github',
                'scope' => 'user:email',
                'auth_url' => 'https://github.com/login/oauth/authorize',
                'token_url' => 'https://github.com/login/oauth/access_token',
                'user_info_url' => 'https://api.github.com/user'
            ],
            'discord' => [
                'name' => 'Discord',
                'color' => '#5865f2',
                'icon' => 'fab fa-discord',
                'scope' => 'identify email',
                'auth_url' => 'https://discord.com/api/oauth2/authorize',
                'token_url' => 'https://discord.com/api/oauth2/token',
                'user_info_url' => 'https://discord.com/api/users/@me'
            ],
            'twitch' => [
                'name' => 'Twitch',
                'color' => '#9146ff',
                'icon' => 'fab fa-twitch',
                'scope' => 'user:read:email',
                'auth_url' => 'https://id.twitch.tv/oauth2/authorize',
                'token_url' => 'https://id.twitch.tv/oauth2/token',
                'user_info_url' => 'https://api.twitch.tv/helix/users'
            ]
        ];
        $title = $prop['title'] ?? 'Sign in with OAuth';
        $subtitle = $prop['subtitle'] ?? 'Choose your preferred authentication method';
        $callback_url = $prop['callback_url'] ?? URL::Link('auth/callback');
        
        ?>
        <form class="card">
            <div>
                <h2><?= safe($title) ?></h2>
                <label><?= safe($subtitle) ?></label>
            </div>
            
            <?php foreach($services as $service_key => $service): ?>
            <div data-service="<?= safe($service_key) ?>">
                <button type="button" class="btn" onclick="initiateOAuth('<?= safe($service_key) ?>')">
                    <i class="<?= safe($service['icon']) ?>" style="color: <?= safe($service['color']) ?>"></i>
                    Continue with <?= safe($service['name']) ?>
                </button>
                
                <div class="loading" style="display: none;">
                    <span>Connecting...</span>
                </div>
                
                <label>Secure authentication via <?= safe($service['name']) ?></label>
            </div>
            <?php endforeach; ?>
            
            <div class="banner" id="oauth-status" style="display: none;">
                <div class="status-icon"></div>
                <div class="status-message"></div>
            </div>
        </form>
        
        <div class="card" id="oauth-debug" style="display: none;">
            <h4>Debug Information</h4>
            <pre id="oauth-debug-content"></pre>
        </div>
        
        <script>
        // OAuth configuration for different services
        const oauthConfig = {
            <?php foreach($services as $service_key => $service): ?>
            <?= $service_key ?>: {
                clientId: '<?= safe($prop[$service_key . '_client_id'] ?? 'YOUR_' . strtoupper($service_key) . '_CLIENT_ID') ?>',
                clientSecret: '<?= safe($prop[$service_key . '_client_secret'] ?? '') ?>', // Only needed for server-side flow
                redirectUri: '<?= safe($callback_url) ?>',
                scope: '<?= safe($service['scope']) ?>',
                authUrl: '<?= safe($service['auth_url']) ?>',
                tokenUrl: '<?= safe($service['token_url']) ?>',
                userInfoUrl: '<?= safe($service['user_info_url']) ?>',
                additionalParams: {
                    <?php 
                    // Add service-specific parameters
                    switch ($service_key) {
                        case 'google':
                            echo "'access_type': 'offline', 'prompt': 'select_account'";
                            break;
                        case 'discord':
                            echo "'prompt': 'consent'";
                            break;
                        case 'github':
                            echo "'allow_signup': 'true'";
                            break;
                        case 'twitch':
                            echo "'force_verify': 'true'";
                            break;
                    }
                    ?>
                }
            },
            <?php endforeach; ?>
        };
        
        // Global OAuth functions
        window.initiateOAuth = function(service) {
            const $serviceDiv = $(`[data-service="${service}"]`);
            const $button = $serviceDiv.find('.btn');
            const $loading = $serviceDiv.find('.loading');
            const $status = $('#oauth-status');
            const $debug = $('#oauth-debug');
            
            // Show loading state
            $button.hide();
            $loading.show();
            
            // Hide previous status/debug info
            $status.hide();
            $debug.hide();
            
            if (oauthConfig[service]) {
                initiateOAuthFlow(service);
            } else {
                showError('Unsupported OAuth service: ' + service);
                resetButton(service);
            }
            
            function resetButton(service) {
                setTimeout(() => {
                    $(`[data-service="${service}"] .btn`).show();
                    $(`[data-service="${service}"] .loading`).hide();
                }, 1000);
            }
        };
        
        function initiateOAuthFlow(service) {
            const config = oauthConfig[service];
            
            if (config.clientId.includes('YOUR_')) {
                showError(`${service.charAt(0).toUpperCase() + service.slice(1)} OAuth not configured. Please set ${service}_client_id in the component properties.`);
                $(`[data-service="${service}"] .btn`).show();
                $(`[data-service="${service}"] .loading`).hide();
                return;
            }
            
            // Build OAuth URL
            const params = new URLSearchParams({
                client_id: config.clientId,
                redirect_uri: config.redirectUri,
                scope: config.scope,
                response_type: 'code',
                state: generateState()
            });
            
            // Add service-specific parameters
            for (const [key, value] of Object.entries(config.additionalParams || {})) {
                params.set(key, value);
            }
            
            const authUrl = `${config.authUrl}?${params.toString()}`;
            
            // Store state in session storage for verification
            sessionStorage.setItem('oauth_state', params.get('state'));
            sessionStorage.setItem('oauth_service', service);
            
            // Also store in PHP session for callback handler
            <?php if (session_status() === PHP_SESSION_ACTIVE): ?>
            $.post('<?= URL::Link('auth/store-oauth-session') ?>', {
                oauth_service: service,
                oauth_state: params.get('state')
            }).catch(console.error);
            <?php endif; ?>
            
            // Debug information
            showDebug({
                service: service,
                authUrl: authUrl,
                clientId: config.clientId,
                redirectUri: config.redirectUri,
                scope: config.scope,
                state: params.get('state')
            });
            
            // Redirect to OAuth provider
            window.location.href = authUrl;
        }
        
        function generateState() {
            return btoa(Math.random().toString(36).substring(2, 15) + 
                       Math.random().toString(36).substring(2, 15)).replace(/[^a-zA-Z0-9]/g, '');
        }
        
        function showStatus(message, type = 'loading') {
            const $status = $('#oauth-status');
            const $icon = $status.find('.status-icon');
            const $message = $status.find('.status-message');
            
            $status.removeClass('success error loading').addClass(type).show();
            
            let icon = '';
            switch(type) {
                case 'success': icon = '✓'; break;
                case 'error': icon = '✗'; break;
                case 'loading': icon = '⏳'; break;
            }
            
            $icon.text(icon);
            $message.text(message);
        }
        
        function showError(message) {
            showStatus(message, 'error');
        }
        
        function showSuccess(message) {
            showStatus(message, 'success');
        }
        
        function showDebug(data) {
            $('#oauth-debug-content').text(JSON.stringify(data, null, 2));
            $('#oauth-debug').show();
        }
        
        // Check for OAuth callback parameters on page load
        $.ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const code = urlParams.get('code');
            const state = urlParams.get('state');
            const error = urlParams.get('error');
            
            if (error) {
                showError('OAuth failed: ' + (urlParams.get('error_description') || error));
                return;
            }
            
            if (code && state) {
                const storedState = sessionStorage.getItem('oauth_state');
                const service = sessionStorage.getItem('oauth_service');
                
                if (state !== storedState) {
                    showError('Invalid OAuth state. Possible security issue.');
                    return;
                }
                
                showStatus('Authorization successful! Processing...', 'success');
                
                showDebug({
                    step: 'callback_received',
                    service: service,
                    code: code.substring(0, 20) + '...',
                    state: state,
                    next_steps: [
                        'Send code to backend OAuth handler',
                        'Exchange code for access token',
                        'Get user profile from OAuth provider',
                        'Create or login user account',
                        'Set session and redirect to dashboard'
                    ]
                });
                
                // Clean up session storage
                sessionStorage.removeItem('oauth_state');
                sessionStorage.removeItem('oauth_service');
                
                // Remove OAuth parameters from URL for cleaner display
                const cleanUrl = window.location.pathname;
                window.history.replaceState({}, document.title, cleanUrl);
            }
        });
        </script>
        <?php
    },
    
    'about' => 'OAuth authentication component with support for Google and other providers. Handles the complete OAuth flow including state verification and callback processing.'
];