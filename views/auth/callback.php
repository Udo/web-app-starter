<?php

// OAuth Callback Handler
// This page handles the OAuth callback from external providers

$code = $_GET['code'] ?? null;
$state = $_GET['state'] ?? null;
$error = $_GET['error'] ?? null;
$error_description = $_GET['error_description'] ?? null;

// OAuth Configuration (in production, store these securely)
$oauth_config = [
    'google' => [
        'client_id' => 'YOUR_GOOGLE_CLIENT_ID',
        'client_secret' => 'YOUR_GOOGLE_CLIENT_SECRET',
        'token_url' => 'https://oauth2.googleapis.com/token',
        'userinfo_url' => 'https://www.googleapis.com/oauth2/v2/userinfo',
        'redirect_uri' => URL::Link('auth/callback')
    ]
];

// Check for OAuth errors
if ($error) {
    $error_message = $error_description ?: $error;
    URL::$fragments['error'] = "OAuth Error: " . htmlspecialchars($error_message);
    URL::$fragments['error_type'] = 'oauth_error';
} else if ($code && $state) {
    // Determine which OAuth provider this is for (you'd need to store this during the auth initiation)
    $service = $_SESSION['oauth_service'] ?? 'google';
    
    if (!isset($oauth_config[$service])) {
        URL::$fragments['error'] = "Unknown OAuth service: $service";
        URL::$fragments['error_type'] = 'invalid_service';
    } else {
        $config = $oauth_config[$service];
        
        // Check if configuration is complete
        if ($config['client_id'] === 'YOUR_GOOGLE_CLIENT_ID' || 
            $config['client_secret'] === 'YOUR_GOOGLE_CLIENT_SECRET') {
            
            URL::$fragments['demo_mode'] = true;
            URL::$fragments['success'] = "OAuth callback received successfully! (Demo Mode)";
            URL::$fragments['code'] = substr($code, 0, 20) . '...';
            URL::$fragments['state'] = $state;
            URL::$fragments['service'] = $service;
        } else {
            // STEP 1: Exchange authorization code for access token
            $token_response = HTTP::post($config['token_url'], [
                'client_id' => $config['client_id'],
                'client_secret' => $config['client_secret'],
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $config['redirect_uri']
            ]);
            
            if (!$token_response['success']) {
                URL::$fragments['error'] = "Token exchange failed: " . ($token_response['error'] ?? 'Unknown error');
                URL::$fragments['error_type'] = 'token_exchange_failed';
                URL::$fragments['debug'] = $token_response;
            } else {
                $tokens = $token_response['data'];
                $access_token = $tokens['access_token'];
                
                // STEP 2: Get user profile information
                $profile_response = HTTP::get_with_token($config['userinfo_url'], $access_token);
                
                if (!$profile_response['success']) {
                    URL::$fragments['error'] = "Failed to get user profile: " . ($profile_response['error'] ?? 'Unknown error');
                    URL::$fragments['error_type'] = 'profile_fetch_failed';
                    URL::$fragments['debug'] = $profile_response;
                } else {
                    $profile = $profile_response['data'];
                    
                    // STEP 3: Create or login user
                    // This is where you'd typically:
                    // 1. Check if user exists by email
                    // 2. Create new user if doesn't exist
                    // 3. Update user profile with OAuth data
                    // 4. Set session variables
                    
                    // For demo purposes, just set session data
                    $_SESSION['user_id'] = $profile['id'];
                    $_SESSION['user_email'] = $profile['email'];
                    $_SESSION['user_name'] = $profile['name'];
                    $_SESSION['user_picture'] = $profile['picture'] ?? null;
                    $_SESSION['oauth_provider'] = $service;
                    $_SESSION['logged_in_at'] = time();
                    
                    URL::$fragments['success'] = "Successfully logged in with " . ucfirst($service) . "!";
                    URL::$fragments['user_profile'] = $profile;
                    URL::$fragments['redirect_to'] = 'dashboard'; // Where to redirect after success
                }
            }
        }
    }
    
    // Clean up temporary OAuth session data
    unset($_SESSION['oauth_service']);
    unset($_SESSION['oauth_state']);
} else {
    URL::$fragments['error'] = "Invalid OAuth callback - missing required parameters";
    URL::$fragments['error_type'] = 'invalid_callback';
}

?>

<div class="oauth-callback-page">
    <div class="callback-container">
        <?php if (isset(URL::$fragments['error'])): ?>
            <div class="callback-result error">
                <div class="result-icon">✗</div>
                <h2>Authentication Failed</h2>
                <p><?= safe(URL::$fragments['error']) ?></p>
                <div class="callback-actions">
                    <a href="<?= URL::Link('auth') ?>" class="btn btn-primary">Try Again</a>
                    <a href="<?= URL::Link('') ?>" class="btn btn-outline">Go Home</a>
                </div>
            </div>
        <?php elseif (isset(URL::$fragments['success'])): ?>
            <div class="callback-result success">
                <div class="result-icon">✓</div>
                <h2>Authentication Successful</h2>
                <p><?= safe(URL::$fragments['success']) ?></p>
                
                <?php if (isset(URL::$fragments['user_profile'])): ?>
                    <div class="user-profile">
                        <h3>Welcome back!</h3>
                        <div class="profile-info">
                            <?php if (URL::$fragments['user_profile']['picture']): ?>
                                <img src="<?= safe(URL::$fragments['user_profile']['picture']) ?>" alt="Profile Picture" class="profile-picture">
                            <?php endif; ?>
                            <div class="profile-details">
                                <p><strong>Name:</strong> <?= safe(URL::$fragments['user_profile']['name']) ?></p>
                                <p><strong>Email:</strong> <?= safe(URL::$fragments['user_profile']['email']) ?></p>
                                <p><strong>Provider:</strong> <?= safe(ucfirst($_SESSION['oauth_provider'])) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="callback-actions">
                        <a href="<?= URL::Link(URL::$fragments['redirect_to'] ?? '') ?>" class="btn btn-primary">Continue to Dashboard</a>
                        <a href="<?= URL::Link('auth') ?>" class="btn btn-outline">Back to Auth Demo</a>
                    </div>
                    
                <?php elseif (isset(URL::$fragments['demo_mode'])): ?>
                    <div class="callback-details">
                        <h3>Demo Mode - Next Steps for Implementation:</h3>
                        <div class="demo-info">
                            <p><strong>Service:</strong> <?= safe(URL::$fragments['service']) ?></p>
                            <p><strong>Code:</strong> <?= safe(URL::$fragments['code']) ?></p>
                            <p><strong>State:</strong> <?= safe(URL::$fragments['state']) ?></p>
                        </div>
                        
                        <ol>
                            <li><strong>Configure OAuth credentials</strong>
                                <div class="code-snippet">
                                    <code>Replace 'YOUR_GOOGLE_CLIENT_ID' and 'YOUR_GOOGLE_CLIENT_SECRET' with actual values</code>
                                </div>
                            </li>
                            <li><strong>Exchange authorization code for access token</strong></li>
                            <li><strong>Use access token to get user profile</strong></li>
                            <li><strong>Create or login user account</strong></li>
                            <li><strong>Set session variables</strong></li>
                            <li><strong>Redirect to dashboard/profile</strong></li>
                        </ol>
                        
                        <div class="implementation-example">
                            <h4>Complete Implementation Example:</h4>
                            <div class="code-block-container">
                                <div class="terminal-header primary-gradient"></div>
                                <div class="terminal-controls">
                                    <div class="window-dot red"></div>
                                    <div class="window-dot yellow"></div>
                                    <div class="window-dot green"></div>
                                    <span class="mono-text">oauth-handler.php</span>
                                </div>
                                <pre class="code-block"><code><?= htmlspecialchars('// Exchange code for token
$token_response = HTTP::post("https://oauth2.googleapis.com/token", [
    "client_id" => $client_id,
    "client_secret" => $client_secret, 
    "code" => $code,
    "grant_type" => "authorization_code",
    "redirect_uri" => $redirect_uri
]);

// Get user profile
$profile_response = HTTP::get_with_token(
    "https://www.googleapis.com/oauth2/v2/userinfo",
    $token_response["data"]["access_token"]
);

// Login/create user
$profile = $profile_response["data"];
$user = User::FindByEmail($profile["email"]) ?: User::Create([
    "email" => $profile["email"],
    "name" => $profile["name"],
    "picture" => $profile["picture"],
    "oauth_provider" => "google",
    "oauth_id" => $profile["id"]
]);

// Set session
$_SESSION["user_id"] = $user["id"];
$_SESSION["user_email"] = $user["email"];
$_SESSION["logged_in_at"] = time();

// Redirect to dashboard
URL::Redirect("dashboard");') ?></code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="callback-actions">
                        <a href="<?= URL::Link('auth') ?>" class="btn btn-primary">Back to Auth Demo</a>
                        <a href="<?= URL::Link('') ?>" class="btn btn-outline">Go Home</a>
                    </div>
                <?php endif; ?>
                
                <?php if (isset(URL::$fragments['debug'])): ?>
                    <div class="debug-section">
                        <h4>Debug Information</h4>
                        <pre class="debug-content"><?= htmlspecialchars(json_encode(URL::$fragments['debug'], JSON_PRETTY_PRINT)) ?></pre>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.oauth-callback-page {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.callback-container {
    max-width: 800px;
    width: 100%;
}

.callback-result {
    background: var(--surface);
    border-radius: var(--radius-lg);
    padding: 3rem;
    text-align: center;
    border: 1px solid;
    box-shadow: var(--shadow-lg);
}

.callback-result.success {
    border-color: var(--success, #22c55e);
    background: var(--success-bg, rgba(34, 197, 94, 0.05));
}

.callback-result.error {
    border-color: var(--error, #ef4444);
    background: var(--error-bg, rgba(239, 68, 68, 0.05));
}

.result-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    font-weight: bold;
}

.callback-result.success .result-icon {
    color: var(--success, #22c55e);
}

.callback-result.error .result-icon {
    color: var(--error, #ef4444);
}

.callback-result h2 {
    margin-bottom: 1rem;
    color: var(--text-primary);
    font-size: 1.8rem;
}

.callback-result > p {
    margin-bottom: 2rem;
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.callback-details {
    text-align: left;
    background: var(--bg-secondary);
    border-radius: var(--radius-md);
    padding: 2rem;
    margin: 2rem 0;
    border: 1px solid var(--border);
}

.callback-details h3 {
    margin-bottom: 1rem;
    color: var(--text-primary);
    font-size: 1.2rem;
}

.callback-details ol {
    margin-bottom: 2rem;
    padding-left: 1.5rem;
}

.callback-details li {
    margin-bottom: 1rem;
    color: var(--text-secondary);
    line-height: 1.5;
}

.code-snippet {
    margin-top: 0.5rem;
    padding: 0.5rem;
    background: var(--bg-color);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    font-family: 'Courier New', monospace;
}

.code-snippet code {
    color: var(--text-primary);
    font-size: 0.9rem;
}

.implementation-example h4 {
    margin-bottom: 1rem;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.callback-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.callback-actions .btn {
    min-width: 140px;
}

.user-profile {
    text-align: left;
    background: var(--bg-secondary);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    margin: 2rem 0;
    border: 1px solid var(--border);
}

.user-profile h3 {
    margin-bottom: 1rem;
    color: var(--text-primary);
    text-align: center;
}

.profile-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.profile-picture {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 2px solid var(--border);
    object-fit: cover;
}

.profile-details {
    flex: 1;
}

.profile-details p {
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.profile-details strong {
    color: var(--text-primary);
}

.demo-info {
    background: var(--bg-color);
    padding: 1rem;
    border-radius: var(--radius-sm);
    margin-bottom: 1rem;
    border: 1px solid var(--border);
}

.demo-info p {
    margin-bottom: 0.5rem;
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.demo-info strong {
    color: var(--text-primary);
}

.debug-section {
    margin-top: 2rem;
    padding: 1rem;
    background: var(--bg-color);
    border-radius: var(--radius-md);
    border: 1px solid var(--border);
}

.debug-section h4 {
    margin-bottom: 1rem;
    color: var(--text-primary);
    font-size: 1rem;
}

.debug-content {
    background: none;
    margin: 0;
    padding: 0;
    font-size: 0.75rem;
    color: var(--text-secondary);
    white-space: pre-wrap;
    word-break: break-all;
    font-family: 'Courier New', monospace;
}

@media (max-width: 600px) {
    .profile-info {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-picture {
        align-self: center;
    }
}
    .oauth-callback-page {
        padding: 1rem;
    }
    
    .callback-result {
        padding: 2rem;
    }
    
    .callback-details {
        padding: 1.5rem;
    }
    
    .result-icon {
        font-size: 3rem;
    }
    
    .callback-result h2 {
        font-size: 1.5rem;
    }
    
    .callback-actions {
        flex-direction: column;
    }
    
    .callback-actions .btn {
        width: 100%;
    }
}
</style>

<script>
// Auto-redirect after successful authentication (optional)
$(document).ready(function() {
    const isSuccess = <?= isset(URL::$fragments['success']) ? 'true' : 'false' ?>;
    
    if (isSuccess) {
        // You could add a countdown timer here
        // setTimeout(() => {
        //     window.location.href = "<?= URL::Link('') ?>";
        // }, 5000);
    }
});
</script>
