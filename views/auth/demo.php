<?php include_css('marketing.css') ?>

<h1>Authentication Demo</h1>

<div class="card">
    <h2>OAuth Authentication</h2>
    <p>This component provides secure OAuth authentication with popular identity providers.</p>
    
    <?= component('components/auth/oauth-client', [
        'title' => 'Sign In to Your Account',
        'subtitle' => 'Choose your preferred authentication method to continue',
        'google_client_id' => 'YOUR_GOOGLE_CLIENT_ID',
        'github_client_id' => 'YOUR_GITHUB_CLIENT_ID', 
        'discord_client_id' => 'YOUR_DISCORD_CLIENT_ID',
        'twitch_client_id' => 'YOUR_TWITCH_CLIENT_ID',
        'callback_url' => URL::Link('auth/callback'),
        'debug' => true // Enable debug mode to see OAuth details
    ]) ?>
</div>

<div class="card">
    <h2>Setup Instructions</h2>
    <div>
        <div>
            <h3><strong>1.</strong> Create OAuth Applications</h3>
            <p>Create OAuth applications with your preferred providers:</p>
            <ul>
                <li><strong>Google:</strong> <a href="https://console.developers.google.com/" target="_blank">Google Cloud Console</a> - Enable Google+ API, create OAuth 2.0 credentials</li>
                <li><strong>GitHub:</strong> <a href="https://github.com/settings/applications/new" target="_blank">GitHub Developer Settings</a> - Create new OAuth App</li>
                <li><strong>Discord:</strong> <a href="https://discord.com/developers/applications" target="_blank">Discord Developer Portal</a> - Create new application with OAuth2</li>
                <li><strong>Twitch:</strong> <a href="https://dev.twitch.tv/console/apps" target="_blank">Twitch Developer Console</a> - Register new application</li>
            </ul>
            <p>For all providers, add callback URL: <code><?= URL::Link('auth/callback') ?></code></p>
        </div>
        
        <div>
            <h3><strong>2.</strong> Configure OAuth Component</h3>
            <p>Update the OAuth component with your client IDs from each provider:</p>
            <div class="code-block-container">
                <div class="terminal-header primary-gradient"></div>
                <div class="terminal-controls">
                    <div class="window-dot red"></div>
                    <div class="window-dot yellow"></div>
                    <div class="window-dot green"></div>
                    <span class="mono-text">views/account.php</span>
                </div>
                <pre class="code-block"><code><span style="color: var(--accent);">&lt;?php</span> <span style="color: var(--secondary);">component</span>(<span style="color: var(--success);">'components/auth/oauth-client'</span>, [
    <span style="color: var(--primary);">'google_client_id'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'your-google-client-id'</span>,
    <span style="color: var(--primary);">'github_client_id'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'your-github-client-id'</span>,
    <span style="color: var(--primary);">'discord_client_id'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'your-discord-client-id'</span>,
    <span style="color: var(--primary);">'twitch_client_id'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'your-twitch-client-id'</span>,
    <span style="color: var(--primary);">'callback_url'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--secondary);">URL::Link</span>(<span style="color: var(--success);">'auth/callback'</span>)
]); <span style="color: var(--accent);">?&gt;</span></code></pre>
            </div>
            <p><em>Note: Only providers with valid client IDs will appear as login options.</em></p>
        </div>
        
        <div>
            <h3><strong>3.</strong> Implement Backend Handler</h3>
            <p>Create <code>views/account/callback.php</code> to handle the OAuth callback and exchange the authorization code for tokens:</p>
            <div class="code-block-container">
                <div class="terminal-header success-gradient"></div>
                <div class="terminal-controls">
                    <div class="window-dot red"></div>
                    <div class="window-dot yellow"></div>
                    <div class="window-dot green"></div>
                                <span class="mono-text">views/account/callback.php</span>
                </div>
                <pre class="code-block"><code><span style="color: var(--accent);">&lt;?php</span>
<span style="color: var(--text-muted);">// Handle OAuth callback</span>
<span style="color: var(--secondary);">if</span> (<span style="color: var(--accent);">$_GET</span>[<span style="color: var(--success);">'code'</span>]) {
    <span style="color: var(--text-muted);">// Exchange code for access token</span>
    <span style="color: var(--text-muted);">// Get user profile from provider</span>
    <span style="color: var(--text-muted);">// Create/login user account</span>
    <span style="color: var(--text-muted);">// Set session and redirect</span>
}
<span style="color: var(--accent);">?&gt;</span></code></pre>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <h2>Built-in Provider Support</h2>
    <p>The OAuth component comes with built-in support for popular providers:</p>
    
    <div class="components-grid">
        <div class="component-card">
            <h4><i class="fab fa-google" style="color: #4285f4;"></i> Google OAuth</h4>
            <div class="code-block-container">
                <div class="terminal-header success-gradient"></div>
                <div class="terminal-controls">
                    <div class="window-dot red"></div>
                    <div class="window-dot yellow"></div>
                    <div class="window-dot green"></div>
                    <span class="mono-text">google.config</span>
                </div>
                <pre class="code-block"><code><span style="color: var(--text-muted);">// Scope: openid, email, profile</span>
<span style="color: var(--text-muted);">// Additional params: access_type=offline</span>
<span style="color: var(--success);">'google_client_id'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'your-client-id'</span></code></pre>
            </div>
        </div>
        
        <div class="component-card">
            <h4><i class="fab fa-github" style="color: #333;"></i> GitHub OAuth</h4>
            <div class="code-block-container">
                <div class="terminal-header warning-gradient"></div>
                <div class="terminal-controls">
                    <div class="window-dot red"></div>
                    <div class="window-dot yellow"></div>
                    <div class="window-dot green"></div>
                    <span class="mono-text">github.config</span>
                </div>
                <pre class="code-block"><code><span style="color: var(--text-muted);">// Scope: user:email</span>
<span style="color: var(--text-muted);">// Additional params: allow_signup=true</span>
<span style="color: var(--success);">'github_client_id'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'your-client-id'</span></code></pre>
            </div>
        </div>
        
        <div class="component-card">
            <h4><i class="fab fa-discord" style="color: #5865f2;"></i> Discord OAuth</h4>
            <div class="code-block-container">
                <div class="terminal-header primary-gradient"></div>
                <div class="terminal-controls">
                    <div class="window-dot red"></div>
                    <div class="window-dot yellow"></div>
                    <div class="window-dot green"></div>
                    <span class="mono-text">discord.config</span>
                </div>
                <pre class="code-block"><code><span style="color: var(--text-muted);">// Scope: identify, email</span>
<span style="color: var(--text-muted);">// Additional params: prompt=consent</span>
<span style="color: var(--success);">'discord_client_id'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'your-client-id'</span></code></pre>
            </div>
        </div>
        
        <div class="component-card">
            <h4><i class="fab fa-twitch" style="color: #9146ff;"></i> Twitch OAuth</h4>
            <div class="code-block-container">
                <div class="terminal-header warning-gradient"></div>
                <div class="terminal-controls">
                    <div class="window-dot red"></div>
                    <div class="window-dot yellow"></div>
                    <div class="window-dot green"></div>
                    <span class="mono-text">twitch.config</span>
                </div>
                <pre class="code-block"><code><span style="color: var(--text-muted);">// Scope: user:read:email</span>
<span style="color: var(--text-muted);">// Additional params: force_verify=true</span>
<span style="color: var(--success);">'twitch_client_id'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'your-client-id'</span></code></pre>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <h2>Current Session Status</h2>
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="success-card">
            <h3>✓ Logged In</h3>
            <p>User ID: <?= safe($_SESSION['user_id']) ?></p>
            <?php if (isset($_SESSION['user_email'])): ?>
                <p>Email: <?= safe($_SESSION['user_email']) ?></p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="component-card">
            <h3>ⓘ Not Logged In</h3>
            <p>Use the OAuth component above to sign in with Google, GitHub, Discord, or Twitch.</p>
        </div>
    <?php endif; ?>
</div>