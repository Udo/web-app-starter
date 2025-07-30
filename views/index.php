<?= component('components/hero-section', [
    'title' => 'Build Amazing Impressive Stunning Web Apps',
    'subtitle' => 'Experience the power of super bloated PHP development with our gigantic and truly unwieldy component-based framework. 
		Fast, secure, and developer-friendly.',
    'cta_text' => 'Get Started Free(mium)',
    'cta_link' => '#features'
]) ?>

<?= component('components/features-grid') ?>

<?= component('components/stats-section', [
    'stats' => [
        ['number' => '10K+', 'label' => 'Happy Vibe Coders'],
        ['number' => '<1s', 'label' => 'Page Load Time'],
        ['number' => '99.9%', 'label' => 'Uptime'],
        ['number' => '24/7', 'label' => 'Support']
    ]
]) ?>

<?= component('components/brands-showcase') ?>

<?= component('components/testimonials') ?>

<?= component('components/pricing-table') ?>

<div class="demo-section">
    <div class="demo-container">
        <h2>Demo</h2>
        <p>Try our UNBELIEVABLE components in action</p>
        
        <div class="demo-grid">
            <div class="demo-card">
                <h3>Modern Forms</h3>
                <form class="demo-form">
                    <div>
                        <label>Full Name</label>
                        <input type="text" placeholder="Enter your name" />
                    </div>
                    <div>
                        <label>Email Address</label>
                        <input type="email" placeholder="you@example.com" />
                    </div>
                    <div>
                        <label>Message</label>
                        <textarea placeholder="Tell us what you think..." rows="4"></textarea>
                    </div>
                    <button type="submit">Send Message</button>
                </form>
            </div>
            
            <div class="demo-card">
                <h3>Button Variations</h3>
                <div class="button-showcase">
                    <button class="btn">Primary Button</button>
                    <button class="btn btn-secondary">Secondary</button>
                    <button class="btn btn-outline">Outline</button>
                    <button class="btn btn-large">Large Button</button>
                </div>
                
                <div class="notification-demo">
                    <div class="banner success">✓ Success message example</div>
                    <div class="banner warning">⚠ Warning message example</div>
                    <div class="banner error">✗ Error message example</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= component('components/cta-section', [
    'title' => 'Ready to Transform Your Development?',
    'subtitle' => 'Join thousands of developers who have already modernized their workflow with our framework.',
    'cta_text' => 'Start Your Project',
    'secondary_text' => 'View GitHub'
]) ?>

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
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
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

.button-showcase {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
    justify-content: center;
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
    
    .button-showcase {
        flex-direction: column;
        align-items: center;
    }
    
    .button-showcase .btn {
        width: 100%;
        max-width: 250px;
    }
}
</style>

<?= component('components/theme-switcher') ?>
