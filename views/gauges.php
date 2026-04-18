<?php include_css('themes/common/css/gauges.css'); ?>
<?php URL::$route['page-title'] = 'Gauges'; ?>

<h1>Gauge Components Demo</h1>

<div class="demo-section gauge-demo-row">
 
    <?= component('components/gauges/progressbar', [
        'id' => 'horizontal_demo',
        'title' => 'Horizontal Progress Bar',
        'subtitle' => 'The original bar gauges, restyled to share the same card and token language as the new arc gauges.',
        'layout' => 'horizontal',
        'style' => 'flex:1 1 24rem',
        'listen' => true,
        'markers' => [
            'zero' => [
                'value' => 0,
                'label' => 'Zero',
                'color' => 'var(--bg-color)',
            ],
            'high' => [
                'value' => 100,
                'label' => 'High',
            ],
        ],
        'items' => [
            'cpu' => [
                'value' => 45,
                'min' => 0,
                'max' => 200,
                'label' => 'CPU',
                'tooltip' => 'CPU Usage',
                'color' => [
                    ['from' => -50, 'to' => 20, 'color' => 'var(--text-muted)'],
                    ['from' => 20, 'to' => 80, 'color' => 'var(--primary)'],
                    ['from' => 80, 'to' => 100, 'color' => 'var(--warning)'],
                ],
            ],
            'memory' => [
                'value' => 92,
                'min' => -50,
                'max' => 100,
                'label' => 'Memory',
                'tooltip' => 'Memory Usage',
            ],
            'disk' => [
                'value' => 28,
                'min' => 0,
                'max' => 100,
                'label' => 'Disk I/O',
                'tooltip' => 'Disk I/O',
            ],
        ],
    ]) ?>
    
    <?= component('components/gauges/progressbar', [
        'id' => 'vertical_demo',
        'title' => 'Vertical Progress Bar',
        'subtitle' => 'Same abstraction, but stacked as compact KPI cards.',
        'layout' => 'vertical',
        'style' => 'flex:1 1 24rem',
        'height' => 350, 
        'listen' => true,
        'markers' => [
            'zero' => [
                'value' => 0,
                'label' => 'Zero',
                'color' => 'var(--bg-color)',
            ],
            'high' => [
                'value' => 100,
                'label' => 'High',
            ],
        ],
        'items' => [
            'cpu' => [
                'value' => 45,
                'min' => 0,
                'max' => 200,
                'label' => 'CPU',
                'tooltip' => 'CPU Usage',
            ],
            'memory' => [
                'value' => 92,
                'min' => -50,
                'max' => 100,
                'label' => 'RAM',
                'tooltip' => 'Memory Usage',
            ],
            'disk' => [
                'value' => 28,
                'min' => 0,
                'max' => 100,
                'label' => 'I/O',
                'tooltip' => 'Disk I/O',
            ],
        ],
    ]) ?>
    
    <div class="gauge-control-panel">
        <div class="control-group">
            <h4>Event Binding</h4>
            <div class="gauge-slider-group">
                <label for="cpu-slider">CPU</label>
                <input type="range" id="cpu-slider" min="0" max="200" value="45" 
                    onchange="$.events.emit('value-broadcast', { name: 'cpu', value: this.value });">
            </div>
            <div class="gauge-slider-group">
                <label for="memory-slider">Memory</label>
                <input type="range" id="memory-slider" min="-50" max="100" value="92" 
                    onchange="$.events.emit('value-broadcast', { name: 'memory', value: this.value });">
            </div>
            <div class="gauge-slider-group">
                <label for="disk-slider">Disk I/O</label>
                <input type="range" id="disk-slider" min="0" max="100" value="28" 
                    onchange="$.events.emit('value-broadcast', { name: 'disk', value: this.value });">
            </div>
        </div>
    </div>
</div>

<div class="demo-section gauge-demo-row">
 
    <?= component('components/gauges/needlegauge', [
        'title' => 'Needle Gauge',
        'subtitle' => 'The original analog gauge now uses the same elevated panels, typography, and theme-token palette as the arc gauges.',
        'style' => 'flex:1 1 24rem',
        'listen' => true,
        'label' => 'CPU',
        'tooltip' => 'CPU Usage',
        'scale' => [
            'angle_start' => -1.2*pi(),
            'angle_end' => 0.2*pi(),
            'max' => 100,
            'unit' => '%',
            'ticks-every' => 10,
            'value-labels-every' => 20,
            'tick-color' => 'var(--text-muted)',
            'color' => [
                ['from' => -50, 'to' => 10, 'color' => 'var(--primary)'],
                ['from' => 10, 'to' => 70, 'color' => 'var(--text-muted)'],
                ['from' => 70, 'to' => 90, 'color' => 'var(--warning)'],
                ['from' => 90, 'to' => 200, 'color' => 'var(--error)'],
            ],
        ],
        'items' => [
            'cpu' => [
                'max' => 200,
                'value' => 45,
                'label' => 'CPU',
            ],
            'memory' => [
                'value' => 92,
                'min' => -50,
                'label' => 'Memory',
                'tooltip' => 'Memory Usage',
            ],
            'disk' => [
                'value' => 28,
                'label' => 'Disk I/O',
                'tooltip' => 'Disk I/O',
            ],
        ],
    ]) ?>
    
</div>

<div class="demo-section gauge-demo-row">
    <?= component('components/gauges/arcgauge', [
        'id' => 'arc_demo',
        'title' => 'SVG Arc Gauges',
        'subtitle' => 'Backported from the llm2 overview as reusable KPI-style gauges with optional watermark tracking.',
        'style' => 'flex: 2 1 36rem',
        'listen' => true,
        'items' => [
            'load' => [
                'label' => 'System Load',
                'value' => 1.8,
                'max' => 8,
                'precision' => 1,
                'caption' => 'LOAD 1M',
                'meta' => '4 cores available',
                'watermark_prefix' => 'loadDemo',
                'color' => [
                    ['from' => 0, 'to' => 3.5, 'color' => 'var(--success, #10b981)'],
                    ['from' => 3.5, 'to' => 6, 'color' => 'var(--warning, #f59e0b)'],
                    ['from' => 6, 'to' => 8, 'color' => 'var(--error, #ef4444)'],
                ],
            ],
            'memory_arc' => [
                'label' => 'Memory',
                'value' => 62,
                'max' => 100,
                'precision' => 0,
                'unit' => '%',
                'caption' => 'MEMORY',
                'meta' => '9.9 / 16 GB',
                'watermark_prefix' => 'memoryDemo',
            ],
            'network' => [
                'label' => 'Network',
                'value' => 18,
                'max' => 100,
                'precision' => 0,
                'unit' => ' MB/s',
                'caption' => 'THROUGHPUT',
                'meta' => 'Inbound + outbound',
                'watermark_prefix' => 'networkDemo',
            ],
        ],
    ]) ?>

    <div class="gauge-control-panel">
        <h4>Arc Gauge Controls</h4>
        <div class="gauge-slider-group">
            <label for="load-slider">Load</label>
            <input type="range" id="load-slider" min="0" max="8" value="1.8" step="0.1"
                oninput="$.events.emit('value-broadcast', { name: 'load', value: this.value, meta: this.value + ' / 8.0 load' });">
        </div>
        <div class="gauge-slider-group">
            <label for="memory-arc-slider">Memory</label>
            <input type="range" id="memory-arc-slider" min="0" max="100" value="62" step="1"
                oninput="$.events.emit('value-broadcast', { name: 'memory_arc', value: this.value, meta: this.value + '% used' });">
        </div>
        <div class="gauge-slider-group">
            <label for="network-slider">Network Throughput</label>
            <input type="range" id="network-slider" min="0" max="100" value="18" step="1"
                oninput="$.events.emit('value-broadcast', { name: 'network', value: this.value, meta: this.value + ' MB/s aggregate' });">
        </div>
    </div>
</div>

