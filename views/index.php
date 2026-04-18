<?php
    include_css('marketing.css');
    $themeOptions = (array)cfg('theme/options');
    $featuredThemeKeys = array('portal-light', 'portal-dark', 'localfirst');
?>

<?= component('components/example/hero-section', [
    'title' => 'Stunning Apps',
    'subtitle' => 'Experience the power of super bloated PHP development with our gigantic and truly unwieldy component-based framework. 
		Seriously though this page only serves as an example repository of
        different styles and blocks.',
    'cta_text' => 'Get Started Free(mium)',
    'cta_link' => '#features'
]) ?>

<?= component('components/example/features-grid') ?>

<?= component('components/example/stats-section', [
    'stats' => [
        ['number' => '10K+', 'label' => 'Happy Vibe Coders'],
        ['number' => '<1s', 'label' => 'Page Load Time'],
        ['number' => '99.9%', 'label' => 'Uptime'],
        ['number' => '24/7', 'label' => 'Support']
    ]
]) ?>

<?= component('components/example/brands-showcase') ?>

<?= component('components/example/testimonials') ?>

<?= component('components/example/pricing-table') ?>

<div class="demo-section">
    <div class="demo-container">
        <h2>Demo</h2>
        <p>Try our UNBELIEVABLE components in action</p>
        
        <div class="demo-grid">
            <div class="demo-card">
                <h3>Modern Forms</h3>
                <form class="demo-form">
                    <div>
                        <label for="demo-full-name">Full Name</label>
                        <input id="demo-full-name" name="full_name" type="text" placeholder="Enter your name" autocomplete="name" />
                    </div>
                    <div>
                        <label for="demo-email-address">Email Address</label>
                        <input id="demo-email-address" name="email" type="email" placeholder="you@example.com" autocomplete="email" />
                    </div>
                    <div>
                        <label for="demo-message">Message</label>
                        <textarea id="demo-message" name="message" placeholder="Tell us what you think..." rows="4"></textarea>
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

<div class="card">
<h2>Theme Families</h2>
<p>Compare the starter’s built-in theme families and jump to the live gallery.</p>
<div class="theme-grid">
    <?php foreach($featuredThemeKeys as $themeKey): $themeInfo = $themeOptions[$themeKey] ?? null; if(!$themeInfo) continue; ?>
    <div class="component-card">
        <h3><?= safe((string)$themeInfo['label']) ?></h3>
        <p><?= safe((string)first($themeInfo['description'] ?? false, 'Reusable starter theme family.')) ?></p>
        <a class="btn" href="<?= URL::Link('themes', ['theme' => $themeKey]) ?>">Preview Theme</a>
    </div>
    <?php endforeach; ?>
    <div class="component-card">
        <h3>Starter Themes</h3>
        <p>The original light and dark starter skins remain available in the same gallery for side-by-side checks.</p>
        <a class="btn btn-secondary" href="<?= URL::Link('themes') ?>">Open Gallery</a>
    </div>
</div>
</div>

<?= component('components/example/cta-section', [
    'title' => 'Ready to Transform Your Development?',
    'subtitle' => 'Join thousands of developers who have already modernized their workflow with our framework.',
    'cta_text' => 'Start Your Project',
    'secondary_text' => 'View GitHub'
]) ?>

<div class="card">
<h2>Component Development Guidelines</h2>
<div class="guidelines-grid">
    <div class="guideline-item" style="border-left-color: var(--primary);">
        <span class="guideline-icon">🏷️</span>
        <span>Use semantic HTML5 elements</span>
    </div>
    <div class="guideline-item" style="border-left-color: var(--secondary);">
        <span class="guideline-icon">🎨</span>
        <span>Implement CSS custom properties for theming</span>
    </div>
    <div class="guideline-item" style="border-left-color: var(--accent);">
        <span class="guideline-icon">📱</span>
        <span>Add responsive design with mobile-first approach</span>
    </div>
    <div class="guideline-item" style="border-left-color: var(--success);">
        <span class="guideline-icon">♿</span>
        <span>Include accessibility attributes (ARIA, alt text)</span>
    </div>
    <div class="guideline-item" style="border-left-color: var(--warning);">
        <span class="guideline-icon">⚡</span>
        <span>Use progressive enhancement for JavaScript features</span>
    </div>
</div>
</div>

<div class="card">
<h2>Simple Data Table (ag-Grid)</h2>
<p>Basic data table with auto-generated columns, sorting, and filtering:</p>
<?= component('components/data/table', [
    'items' => [
        ['name' => 'John Doe', 'age' => 25, 'gender' => 'male', 'department' => 'Engineering', 'salary' => 75000, 'active' => true],
        ['name' => 'Jane Smith', 'age' => 30, 'gender' => 'female', 'department' => 'Design', 'salary' => 82000, 'active' => true],
        ['name' => 'Bob Johnson', 'age' => 35, 'gender' => 'male', 'department' => 'Marketing', 'salary' => 68000, 'active' => false],
        ['name' => 'Alice Brown', 'age' => 40, 'gender' => 'female', 'department' => 'Engineering', 'salary' => 95000, 'active' => true],
        ['name' => 'Dave Wilson', 'age' => 45, 'gender' => 'male', 'department' => 'Sales', 'salary' => 72000, 'active' => true],
        ['name' => 'Eve Davis', 'age' => 50, 'gender' => 'non-binary', 'department' => 'Management', 'salary' => 110000, 'active' => true],
        ['name' => 'Charlie Miller', 'age' => 28, 'gender' => 'male', 'department' => 'Engineering', 'salary' => 78000, 'active' => true],
        ['name' => 'Sarah Taylor', 'age' => 33, 'gender' => 'female', 'department' => 'Design', 'salary' => 85000, 'active' => false],
    ],
    'height' => '350px'
]) ?>
</div>

