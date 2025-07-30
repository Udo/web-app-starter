<h1>Component System</h1>

<?= component('components/theme-switcher:render') ?>

<div class="card">
<h2>Component Declaration</h2>

<div style="background: linear-gradient(135deg, var(--surface) 0%, var(--surface-elevated) 100%); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border); margin: 1rem 0; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 50%, var(--accent) 100%);"></div>
    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
        <div style="width: 12px; height: 12px; background: #ff5f56; border-radius: 50%; margin-right: 8px;"></div>
        <div style="width: 12px; height: 12px; background: #ffbd2e; border-radius: 50%; margin-right: 8px;"></div>
        <div style="width: 12px; height: 12px; background: #27ca3f; border-radius: 50%; margin-right: 1rem;"></div>
        <span style="font-family: 'SF Mono', 'Monaco', 'Menlo', monospace; font-size: 0.875rem; color: var(--text-muted);">component.php</span>
    </div>
<pre style="margin: 0; font-family: 'SF Mono', 'Monaco', 'Menlo', monospace; font-size: 0.9rem; line-height: 1.6; color: var(--text-primary); background: none;"><code><span style="color: var(--accent);">&lt;?php</span> <span style="color: var(--secondary);">return</span> [

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
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin: 1rem 0;">
    <div style="background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h4>hero-section</h4>
        <p>Landing page hero with CTA buttons</p>
    </div>
    <div style="background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h4>features-grid</h4>
        <p>3-column feature showcase</p>
    </div>
    <div style="background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h4>stats-section</h4>
        <p>Animated statistics display</p>
    </div>
    <div style="background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h4>testimonials</h4>
        <p>Customer testimonial carousel</p>
    </div>
    <div style="background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h4>cta-section</h4>
        <p>Call-to-action with background</p>
    </div>
    <div style="background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h4>pricing-table</h4>
        <p>Responsive pricing tiers</p>
    </div>
    <div style="background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h4>brands-showcase</h4>
        <p>Logo grid with animations</p>
    </div>
    <div style="background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h4>theme-switcher</h4>
        <p>Light/dark theme toggle</p>
    </div>
</div>
</div>

<div class="card">
<h2>📝 Usage Examples</h2>
<div style="background: linear-gradient(135deg, var(--surface) 0%, var(--surface-elevated) 100%); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border); margin: 1rem 0; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--success) 0%, var(--primary) 50%, var(--secondary) 100%);"></div>
    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
        <div style="width: 12px; height: 12px; background: #ff5f56; border-radius: 50%; margin-right: 8px;"></div>
        <div style="width: 12px; height: 12px; background: #ffbd2e; border-radius: 50%; margin-right: 8px;"></div>
        <div style="width: 12px; height: 12px; background: #27ca3f; border-radius: 50%; margin-right: 1rem;"></div>
        <span style="font-family: 'SF Mono', 'Monaco', 'Menlo', monospace; font-size: 0.875rem; color: var(--text-muted);">usage-examples.php</span>
    </div>
<pre style="margin: 0; font-family: 'SF Mono', 'Monaco', 'Menlo', monospace; font-size: 0.9rem; line-height: 1.6; color: var(--text-primary); background: none;"><code><span style="color: var(--text-muted);">// Basic component</span>
<span style="color: var(--accent);">&lt;?php</span> <span style="color: var(--secondary);">component</span>(<span style="color: var(--success);">'components/hero-section'</span>); <span style="color: var(--accent);">?&gt;</span>

<span style="color: var(--text-muted);">// Component with data</span>
<span style="color: var(--accent);">&lt;?php</span> <span style="color: var(--secondary);">component</span>(<span style="color: var(--success);">'components/stats-section'</span>, [
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
<h2>Theme System</h2>
<p>Components automatically adapt to the current theme using CSS custom properties:</p>
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 1rem 0;">
    <div style="background: var(--success-bg); border: 1px solid var(--success-border); padding: 1rem; border-radius: var(--radius);">
        <h4>Light Theme</h4>
        <code>themes/light/css/style.css</code>
    </div>
    <div style="background: var(--surface-elevated); border: 1px solid var(--border); padding: 1rem; border-radius: var(--radius);">
        <h4>Dark Theme</h4>
        <code>themes/dark/css/style.css</code>
    </div>
</div>

<div style="background: linear-gradient(135deg, var(--surface) 0%, var(--surface-elevated) 100%); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border); margin: 1rem 0; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--warning) 0%, var(--accent) 50%, var(--primary) 100%);"></div>
    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
        <div style="width: 12px; height: 12px; background: #ff5f56; border-radius: 50%; margin-right: 8px;"></div>
        <div style="width: 12px; height: 12px; background: #ffbd2e; border-radius: 50%; margin-right: 8px;"></div>
        <div style="width: 12px; height: 12px; background: #27ca3f; border-radius: 50%; margin-right: 1rem;"></div>
        <span style="font-family: 'SF Mono', 'Monaco', 'Menlo', monospace; font-size: 0.875rem; color: var(--text-muted);">variables.css</span>
    </div>
<h4 style="margin: 0 0 1rem 0; color: var(--text-primary);">CSS Variables</h4>
<pre style="margin: 0; font-family: 'SF Mono', 'Monaco', 'Menlo', monospace; font-size: 0.9rem; line-height: 1.6; color: var(--text-primary); background: none;"><code><span style="color: var(--text-muted);">/* Core theme variables */</span>
<span style="color: var(--primary);">--bg-color</span>, <span style="color: var(--primary);">--bg-secondary</span>, <span style="color: var(--primary);">--surface</span>
<span style="color: var(--secondary);">--text-primary</span>, <span style="color: var(--secondary);">--text-secondary</span>, <span style="color: var(--secondary);">--text-muted</span>
<span style="color: var(--accent);">--primary</span>, <span style="color: var(--accent);">--primary-dark</span>, <span style="color: var(--accent);">--primary-light</span>
<span style="color: var(--success);">--border</span>, <span style="color: var(--success);">--border-hover</span>
<span style="color: var(--warning);">--shadow-sm</span>, <span style="color: var(--warning);">--shadow-md</span>, <span style="color: var(--warning);">--shadow-lg</span></code></pre>
</div>
</div>

<div class="card">
<h2>⚡ JavaScript Enhancements</h2>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
    <div style="background: var(--primary); color: var(--bg-color); padding: 1rem; border-radius: var(--radius); text-align: center;">
        <h4>Intersection Observer</h4>
        <p>Scroll animations</p>
    </div>
    <div style="background: var(--secondary); color: var(--bg-color); padding: 1rem; border-radius: var(--radius); text-align: center;">
        <h4>Theme Switching</h4>
        <p>localStorage persistence</p>
    </div>
    <div style="background: var(--accent); color: var(--bg-color); padding: 1rem; border-radius: var(--radius); text-align: center;">
        <h4>Form Validation</h4>
        <p>Real-time enhancement</p>
    </div>
    <div style="background: var(--success); color: var(--bg-color); padding: 1rem; border-radius: var(--radius); text-align: center;">
        <h4>Navigation Effects</h4>
        <p>Scroll-based styling</p>
    </div>
</div>
</div>

<div class="card">
<h2>Component Development Guidelines</h2>
<div style="display: grid; gap: 0.5rem;">
    <div style="display: flex; align-items: center; background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border-left: 4px solid var(--primary);">
        <span style="font-size: 1.5rem; margin-right: 1rem;">🏷️</span>
        <span>Use semantic HTML5 elements</span>
    </div>
    <div style="display: flex; align-items: center; background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border-left: 4px solid var(--secondary);">
        <span style="font-size: 1.5rem; margin-right: 1rem;">🎨</span>
        <span>Implement CSS custom properties for theming</span>
    </div>
    <div style="display: flex; align-items: center; background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border-left: 4px solid var(--accent);">
        <span style="font-size: 1.5rem; margin-right: 1rem;">📱</span>
        <span>Add responsive design with mobile-first approach</span>
    </div>
    <div style="display: flex; align-items: center; background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border-left: 4px solid var(--success);">
        <span style="font-size: 1.5rem; margin-right: 1rem;">♿</span>
        <span>Include accessibility attributes (ARIA, alt text)</span>
    </div>
    <div style="display: flex; align-items: center; background: var(--surface-elevated); padding: 1rem; border-radius: var(--radius); border-left: 4px solid var(--warning);">
        <span style="font-size: 1.5rem; margin-right: 1rem;">⚡</span>
        <span>Use progressive enhancement for JavaScript features</span>
    </div>
</div>
</div>
</div>

