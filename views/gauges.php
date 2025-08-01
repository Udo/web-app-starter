<h1>Gauge Components Demo</h1>

<div class="demo-section" style="display: flex;gap: 1rem; flex-wrap: wrap;">
 
    <?= component('components/gauges/progressbar', [
        'id' => 'horizontal_demo',
        'title' => 'System Resources (Horizontal)',
        'layout' => 'horizontal',
        'style' => 'flex:1',
        'high_water_mark' => ['enabled' => true, 'remember_time' => 60 * 60],
        'low_water_mark' => ['enabled' => true, 'remember_time' => 60 * 60],
        'listen' => true,
        'bars' => [
            'cpu' => [
                'value' => 45,
                'max' => 200,
                'label' => 'CPU',
                'tooltip' => 'CPU Usag',
                'current_high_water_mark' => 80,
                'current_low_water_mark' => 15,
            ],
            'memory' => [
                'value' => 92,
                'max' => 100,
                'label' => 'Memory',
                'tooltip' => 'Memory Usage',
                'current_high_water_mark' => 85,
                'current_low_water_mark' => 25,
            ],
            'disk' => [
                'value' => 28,
                'max' => 100,
                'label' => 'Disk I/O',
                'tooltip' => 'Disk I/O',
                'current_high_water_mark' => 65,
                'current_low_water_mark' => 10,
            ],
        ],
    ]) ?>
    
    <?= component('components/gauges/progressbar', [
        'id' => 'vertical_demo',
        'title' => 'System Resources (Vertical)',
        'layout' => 'vertical',
        'style' => 'flex:1',
        'height' => 350, 
        'high_water_mark' => ['enabled' => true, 'remember_time' => 60 * 60],
        'low_water_mark' => ['enabled' => true, 'remember_time' => 60 * 60],
        'listen' => true,
        'bars' => [
            'cpu' => [
                'value' => 45,
                'max' => 200,
                'label' => 'CPU',
                'tooltip' => 'CPU Usage',
                'current_high_water_mark' => 80,
                'current_low_water_mark' => 15,
            ],
            'memory' => [
                'value' => 92,
                'max' => 100,
                'label' => 'RAM',
                'tooltip' => 'Memory Usage',
                'current_high_water_mark' => 85,
                'current_low_water_mark' => 25,
            ],
            'disk' => [
                'value' => 28,
                'max' => 100,
                'label' => 'I/O',
                'tooltip' => 'Disk I/O',
                'current_high_water_mark' => 65,
                'current_low_water_mark' => 10,
            ],
        ],
    ]) ?>
</div>

<div class="demo-section">
    <h2>Interactive Controls</h2>
    <p>Test real-time updates with the controls below</p>
    
    <div class="control-panel">
        <div class="control-group">
            <h4>Horizontal Bars</h4>
            <div class="slider-group">
                <label for="cpu-slider">CPU</label>
                <input type="range" id="cpu-slider" min="0" max="200" value="45" onchange="$.events.emit('value-broadcast', { name: 'cpu', value: this.value });">
            </div>
            <div class="slider-group">
                <label for="memory-slider">Memory</label>
                <input type="range" id="memory-slider" min="0" max="100" value="62" onchange="$.events.emit('value-broadcast', { name: 'memory', value: this.value });">
            </div>
            <div class="slider-group">
                <label for="disk-slider">Disk I/O</label>
                <input type="range" id="disk-slider" min="0" max="100" value="28" onchange="$.events.emit('value-broadcast', { name: 'disk', value: this.value });">
            </div>
        </div>
    </div>
</div>
<style>
.slider-group {
    margin-bottom: 1rem;
}

.slider-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
    font-weight: 500;
}
</style>

