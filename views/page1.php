<h1>Component System</h1>

<div class="card">
<h2>Component Declaration</h2>

<?php include_css('marketing.css') ?>

<div class="code-block-container">
    <div class="terminal-header primary-gradient"></div>
    <div class="terminal-controls">
        <div class="window-dot red"></div>
        <div class="window-dot yellow"></div>
        <div class="window-dot green"></div>
        <span class="mono-text">component.php</span>
    </div>
<pre class="code-block"><code><span style="color: var(--accent);">&lt;?php</span> <span style="color: var(--secondary);">return</span> [

    <span style="color: var(--primary);">'render'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--secondary);">function</span>(<span style="color: var(--accent);">$prop</span>) {
        <span style="color: var(--text-muted);">// render the component</span> 
    },
    
    <span style="color: var(--primary);">'about'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'A floating theme switcher button that toggles between light and dark themes'</span>,

]; 
</code></pre>
</div>
</div>

<div class="card">
<h2>Example Components</h2>
<div class="components-grid">
    <div class="component-card">
        <h4>hero-section</h4>
        <p>Landing page hero with CTA buttons</p>
    </div>
    <div class="component-card">
        <h4>features-grid</h4>
        <p>3-column feature showcase</p>
    </div>
    <div class="component-card">
        <h4>stats-section</h4>
        <p>Animated statistics display</p>
    </div>
    <div class="component-card">
        <h4>testimonials</h4>
        <p>Customer testimonial carousel</p>
    </div>
    <div class="component-card">
        <h4>cta-section</h4>
        <p>Call-to-action with background</p>
    </div>
    <div class="component-card">
        <h4>pricing-table</h4>
        <p>Responsive pricing tiers</p>
    </div>
    <div class="component-card">
        <h4>brands-showcase</h4>
        <p>Logo grid with animations</p>
    </div>
    <div class="component-card">
        <h4>theme-switcher</h4>
        <p>Light/dark theme toggle</p>
    </div>
</div>
</div>

<div class="card">
<h2>Usage Examples</h2>
<div class="code-block-container">
    <div class="terminal-header success-gradient"></div>
    <div class="terminal-controls">
        <div class="window-dot red"></div>
        <div class="window-dot yellow"></div>
        <div class="window-dot green"></div>
        <span class="mono-text">usage-examples.php</span>
    </div>
<pre class="code-block"><code><span style="color: var(--text-muted);">// Basic component</span>
<span style="color: var(--accent);">&lt;?php</span> <span style="color: var(--secondary);">component</span>(<span style="color: var(--success);">'components/example/hero-section'</span>); <span style="color: var(--accent);">?&gt;</span>

<span style="color: var(--text-muted);">// Component with data</span>
<span style="color: var(--accent);">&lt;?php</span> <span style="color: var(--secondary);">component</span>(<span style="color: var(--success);">'components/example/stats-section'</span>, [
    <span style="color: var(--primary);">'title'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'Our Growth'</span>,
    <span style="color: var(--primary);">'stats'</span> <span style="color: var(--text-muted);">=></span> [
        [<span style="color: var(--primary);">'number'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'50K+'</span>, <span style="color: var(--primary);">'label'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'Users'</span>],
        [<span style="color: var(--primary);">'number'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'99.9%'</span>, <span style="color: var(--primary);">'label'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'Uptime'</span>],
        [<span style="color: var(--primary);">'number'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'24/7'</span>, <span style="color: var(--primary);">'label'</span> <span style="color: var(--text-muted);">=></span> <span style="color: var(--success);">'Support'</span>]
    ]
]); <span style="color: var(--accent);">?&gt;</span></code></pre>
</div>
</div>

<div class="card">
<h2>Theme Info</h2>
<p>This starter pack includes a light and a dark theme</p>
<div class="theme-grid">
    <div class="success-card">
        <h4>Light Theme</h4>
        <code>themes/light/css/style.css</code>
    </div>
    <div class="component-card">
        <h4>Dark Theme</h4>
        <code>themes/dark/css/style.css</code>
    </div>
</div>

<div class="code-block-container">
    <div class="terminal-header warning-gradient"></div>
    <div class="terminal-controls">
        <div class="window-dot red"></div>
        <div class="window-dot yellow"></div>
        <div class="window-dot green"></div>
        <span class="mono-text">variables.css</span>
    </div>
<h4 style="margin: 0 0 1rem 0; color: var(--text-primary);">CSS Variables</h4>
<pre class="code-block"><code><span style="color: var(--text-muted);">/* Core theme variables */</span>
<span style="color: var(--primary);">--bg-color</span>, <span style="color: var(--primary);">--bg-secondary</span>, <span style="color: var(--primary);">--surface</span>
<span style="color: var(--secondary);">--text-primary</span>, <span style="color: var(--secondary);">--text-secondary</span>, <span style="color: var(--secondary);">--text-muted</span>
<span style="color: var(--accent);">--primary</span>, <span style="color: var(--accent);">--primary-dark</span>, <span style="color: var(--accent);">--primary-light</span>
<span style="color: var(--success);">--border</span>, <span style="color: var(--success);">--border-hover</span>
<span style="color: var(--warning);">--shadow-sm</span>, <span style="color: var(--warning);">--shadow-md</span>, <span style="color: var(--warning);">--shadow-lg</span></code></pre>
</div>
</div>


</div>

