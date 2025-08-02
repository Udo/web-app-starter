<h1>Gauge Components Demo</h1>

<div class="demo-section" style="display: flex;gap: 1rem; flex-wrap: wrap;">
 
    <?= component('components/gauges/progressbar', [
        'id' => 'horizontal_demo',
        'title' => 'Horizontal Progress Bar',
        'layout' => 'horizontal',
        'style' => 'flex:1',
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
                    ['from' => -50, 'to' => 20, 'color' => 'gray'],
                    ['from' => 20, 'to' => 80, 'color' => 'lightblue'],
                    ['from' => 80, 'to' => 100, 'color' => 'darkorange'],
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
        'layout' => 'vertical',
        'style' => 'flex:1',
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
    
    <div class="control-panel">
        <div class="control-group">
            <h4>Event Binding</h4>
            <div class="slider-group">
                <label for="cpu-slider">CPU</label>
                <input type="range" id="cpu-slider" min="0" max="200" value="45" 
                    onchange="$.events.emit('value-broadcast', { name: 'cpu', value: this.value });">
            </div>
            <div class="slider-group">
                <label for="memory-slider">Memory</label>
                <input type="range" id="memory-slider" min="-50" max="100" value="92" 
                    onchange="$.events.emit('value-broadcast', { name: 'memory', value: this.value });">
            </div>
            <div class="slider-group">
                <label for="disk-slider">Disk I/O</label>
                <input type="range" id="disk-slider" min="0" max="100" value="28" 
                    onchange="$.events.emit('value-broadcast', { name: 'disk', value: this.value });">
            </div>
        </div>
    </div>
</div>

<div class="demo-section" style="display: flex;gap: 1rem; flex-wrap: wrap;">
 
    <?= component('components/gauges/needlegauge', [
        'title' => 'Needle Gauge',
        'style' => 'flex:1',
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
            'tick-color' => 'white',
            'color' => [
                ['from' => -50, 'to' => 10, 'color' => 'blue'],
                ['from' => 10, 'to' => 70, 'color' => 'lightgray'],
                ['from' => 70, 'to' => 90, 'color' => 'darkorange'],
                ['from' => 90, 'to' => 200, 'color' => 'red'],
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

