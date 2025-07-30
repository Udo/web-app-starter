<?= component('components/hero-section', [
    'title' => 'Dark Theme Demo',
    'subtitle' => 'Experience our beautiful dark mode with enhanced readability and modern aesthetics.',
    'cta_text' => 'Explore Features',
    'cta_link' => '#features'
]) ?>

<?= component('components/features-grid', [
    'features' => [
        [
            'icon' => '🌙',
            'title' => 'Dark Mode',
            'description' => 'Beautiful dark theme with carefully chosen colors for optimal readability.'
        ],
        [
            'icon' => '⚡',
            'title' => 'Performance',
            'description' => 'Optimized for speed with reduced eye strain in low-light environments.'
        ],
        [
            'icon' => '🎨',
            'title' => 'Design',
            'description' => 'Modern dark UI that adapts seamlessly across all components.'
        ]
    ]
]) ?>

<?= component('components/pricing-table', [
    'plans' => [
        [
            'name' => 'Dark Starter',
            'price' => 'Free',
            'period' => '',
            'description' => 'Perfect for trying out dark mode',
            'features' => [
                'Dark theme support',
                'Basic components',
                'Community support'
            ],
            'cta' => 'Try Dark Mode',
            'popular' => false
        ],
        [
            'name' => 'Dark Pro',
            'price' => '$19',
            'period' => '/month',
            'description' => 'Professional dark theme experience',
            'features' => [
                'Advanced dark components',
                'Theme customization',
                'Priority support',
                'Custom color schemes'
            ],
            'cta' => 'Go Dark Pro',
            'popular' => true
        ]
    ]
]) ?>

<div class="demo-section">
    <div class="demo-container">
        <h2>Dark Theme Components</h2>
        <p>See how beautiful our components look in dark mode</p>
        
        <div class="demo-grid">
            <div class="demo-card">
                <h3>Dark Forms</h3>
                <form class="demo-form">
                    <div>
                        <label>Name</label>
                        <input type="text" placeholder="Your name" />
                    </div>
                    <div>
                        <label>Email</label>
                        <input type="email" placeholder="your@email.com" />
                    </div>
                    <button type="submit">Submit</button>
                </form>
            </div>
            
            <div class="demo-card">
                <h3>Notifications</h3>
                <div class="notification-demo">
                    <div class="banner success">✓ Dark theme activated successfully</div>
                    <div class="banner warning">⚠ Theme preferences saved</div>
                    <div class="banner error">✗ Error example in dark mode</div>
                </div>
            </div>
        </div>
    </div>
</div>

    ]
]) ?>

<?= component('components/theme-switcher') ?>

<style>
.demo-section {
    padding: 4rem 0;
    background: var(--bg-color);
}

.demo-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    text-align: center;
}

.demo-container h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.demo-container > p {
    font-size: 1.25rem;
    color: var(--text-secondary);
    margin-bottom: 3rem;
}

.demo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.demo-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 2.5rem;
    box-shadow: var(--shadow-md);
    text-align: left;
}

.demo-card h3 {
    margin-bottom: 2rem;
    color: var(--text-primary);
    text-align: center;
}

.demo-form {
    max-width: none;
}

.notification-demo {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

@media (max-width: 768px) {
    .demo-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .demo-card {
        padding: 2rem 1.5rem;
    }
}
</style>
