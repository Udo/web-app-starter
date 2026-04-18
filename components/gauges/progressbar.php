<script>

window.ProgressbarComponents = window.ProgressbarComponents || { 

    start_listen : function(prop) {
        $.events.on('value-broadcast', function(data) {
            if(prop.items[data.name]) {
                // update value text
                let bar = Object.assign({}, prop.scale, prop.items[data.name]);
                $('#' + prop.id + '-' + data.name + '-value').text(data.value + (bar.unit || ''));
                // update bar width/height
                let vrange = (bar.max || 100) - (bar.min || 0);
                let pct_value = GaugeComponents.clampValue((data.value - (bar.min || 0)) / vrange * 100, 0, 100);
                if(Array.isArray(bar.color)) {
                    let colorMatch = GaugeComponents.pickEntryFromRange(bar.color, Number(data.value));
                    if(colorMatch && colorMatch.color)
                        $('#' + prop.id + '-' + data.name + '-bar').css('background', colorMatch.color);
                }
                if(prop.layout === 'horizontal') {
                    $('#' + prop.id + '-' + data.name + '-bar').css('width', pct_value + '%');
                } else {
                    $('#' + prop.id + '-' + data.name + '-bar').css('height', pct_value + '%');
                }
            }
        });
    },

}

</script><?php 

include_js('components/gauges/common.js');
include_css('themes/common/css/gauges.css');

return [

    'render' => function($prop) {
        $prop['id'] = !empty($prop['id']) ? $prop['id'] : 'progressbar-'.uniqid();
        $prop['style'] = (string)($prop['style'] ?? '');
        $prop['item-style'] = (string)($prop['item-style'] ?? '');
        $prop['label-style'] = (string)($prop['label-style'] ?? '');
        $prop['value-style'] = (string)($prop['value-style'] ?? '');
        $prop['bar-style'] = (string)($prop['bar-style'] ?? '');
        $prop['items'] = (array)($prop['items'] ?? array());
        $default_palette = [
            'var(--success, #10b981)',
            'var(--primary, #60a5fa)',
            'var(--accent, #22d3ee)',
            'var(--warning, #f59e0b)',
        ];
        $auto_color_counter = 0;
        if(empty($prop['scale'])) $prop['scale'] = [];
        $layout = first($prop['layout'] ?? false, 'horizontal');
        ?>        
        <section class="gauge-set progressbar-set progressbar-set-<?= asafe($layout) ?>" id="<?= $prop['id'] ?>" style="<?= $prop['style'] ?>">
            <?php if(isset($prop['title'])) { ?>
                <div class="gauge-set-header">
                    <h3><?= safe($prop['title']) ?></h3>
                    <?php if(!empty($prop['subtitle'])) { ?><p><?= safe((string)$prop['subtitle']) ?></p><?php } ?>
                </div>
            <?php } ?>
            <?php if($layout == 'horizontal') { ?>
            <div class="progressbar-grid progressbar-grid-horizontal">
                <?php foreach($prop['items'] as $bar_id => $bar) 
                { 
                    $bar = array_merge($prop['scale'], $bar);
                    $bar['style'] = (string)($bar['style'] ?? '');
                    $bar['tooltip'] = (string)($bar['tooltip'] ?? '');
                    $bar['before'] = $bar['before'] ?? '';
                    $bar['after'] = $bar['after'] ?? '';
                    $bar['unit'] = (string)($bar['unit'] ?? '');
                    $bar['color'] = $bar['color'] ?? false;
                    $vrange = (first($bar['max'], 100) - first($bar['min'], 0));
                    $bar['pct-value'] = clamp(($bar['value'] - first($bar['min'], 0)) / $vrange * 100, 0, 100);
                    if(is_array($bar['color']))
                    {
                        $color_entry = pick_entry_from_range($bar['color'], $bar['value']);
                        $bar['color'] = $color_entry['color'] ?? false;
                    }
                    if(!$bar['color'])
                        $bar['color'] = first($prop['bar-color'] ?? false, $default_palette[$auto_color_counter++ % sizeof($default_palette)]);
                    ?>
                    <section class="gauge-card progressbar-card progressbar-card-horizontal" id="<?= $prop['id'] ?>-<?= safe($bar_id) ?>"
                        title="<?= asafe($bar['tooltip']) ?>"
                        style="<?= $prop['item-style'] ?>;<?= $bar['style'] ?>">
                        <?= $bar['before'] ?>
                        <div class="progressbar-card-head">
                            <div class="gauge-metric-label progressbar-label" style="<?= $prop['label-style'] ?>">
                                <?= safe($bar['label']) ?>
                            </div>
                            <div class="gauge-metric-value progressbar-value" style="<?= $prop['value-style'] ?>"
                                id="<?= $prop['id'] ?>-<?= safe($bar_id) ?>-value">
                                <?= safe($bar['value']) ?><?= safe($bar['unit']) ?>
                            </div>
                        </div>
                        <div class="progressbar-background progressbar-background-horizontal"> 
                            <div class="progressbar-bar" id="<?= $prop['id'] ?>-<?= safe($bar_id) ?>-bar"
                                style="background-color: <?= safe($bar['color']) ?>;
                                    <?= $prop['bar-style'] ?>
                                    opacity: <?= isset($bar['opacity']) ? safe($bar['opacity']) : '0.75' ?>;
                                    width: <?= safe($bar['pct-value']) ?>%;">
                            </div>
                            <?php 
                            if(isset($prop['markers'])) {
                                foreach($prop['markers'] as $marker_id => $marker) {
                                    $marker_pct = clamp(($marker['value'] - first($bar['min'], 0)) / $vrange * 100, 0, 100);
                                    ?>
                                    <div class="progressbar-marker" 
                                        title="<?= asafe($marker['label']) ?>"
                                        style="left: <?= ($marker_pct) ?>%; 
                                               background: <?= first($marker['color'] ?? false, 'var(--primary-light)') ?>;">										
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <?php if($bar['tooltip'] !== '') { ?><div class="gauge-metric-meta progressbar-meta"><?= safe($bar['tooltip']) ?></div><?php } ?>
                        <?= $bar['after'] ?>
                    </section>
                <?php } ?>
            </div>
            <?php } else { ?>
            <div class="progressbar-grid progressbar-grid-vertical" style="--progressbar-height: <?= safe((string)first($prop['height'] ?? false, 240)) ?>px;">
                <?php 
                if(sizeof($prop['items']) > 0)
                {
                    foreach($prop['items'] as $bar_id => $bar) 
                    {
                        $bar = array_merge($prop['scale'], $bar);
                        $bar['style'] = (string)($bar['style'] ?? '');
                        $bar['tooltip'] = (string)($bar['tooltip'] ?? '');
                        $bar['before'] = $bar['before'] ?? '';
                        $bar['after'] = $bar['after'] ?? '';
                        $bar['unit'] = (string)($bar['unit'] ?? '');
                        $bar['color'] = $bar['color'] ?? false;
                        $vrange = (first($bar['max'], 100) - first($bar['min'], 0));
                        $bar['pct-value'] = clamp(($bar['value'] - first($bar['min'], 0)) / $vrange * 100, 0, 100);
                        if(is_array($bar['color']))
                        {
                            $color_entry = pick_entry_from_range($bar['color'], $bar['value']);
                            $bar['color'] = $color_entry['color'] ?? false;
                        }
                        if(!$bar['color'])
                            $bar['color'] = first($prop['bar-color'] ?? false, $default_palette[$auto_color_counter++ % sizeof($default_palette)]);
                        ?>
                        <section class="gauge-card progressbar-card progressbar-card-vertical" id="<?= $prop['id'] ?>-<?= safe($bar_id) ?>"
                            title="<?= asafe($bar['tooltip']) ?>"
                            style="<?= $prop['item-style'] ?>;<?= $bar['style'] ?>">
                            <?= $bar['before'] ?>
                            <div class="gauge-metric-label progressbar-label" style="<?= $prop['label-style'] ?>">
                                <?= safe($bar['label']) ?>
                            </div>
                            
                            <div class="progressbar-background progressbar-background-vertical"> 
                                <div class="progressbar-bar" id="<?= $prop['id'] ?>-<?= safe($bar_id) ?>-bar"
                                    style="background-color: <?= safe($bar['color']) ?>;
                                        <?= $prop['bar-style'] ?>
                                        opacity: <?= isset($bar['opacity']) ? safe($bar['opacity']) : '0.75' ?>;
                                        height: <?= safe($bar['pct-value']) ?>%;">
                                </div>
                                <?php 
                                // Render markers for vertical layout
                                if(isset($prop['markers'])) {
                                    foreach($prop['markers'] as $marker_id => $marker) {
                                        $marker_pct = clamp(($marker['value'] - first($bar['min'], 0)) / $vrange * 100, 0, 100);
                                        ?>
                                        <div class="progressbar-marker" 
                                            title="<?= asafe($marker['label']) ?>"
                                            style="bottom: <?= ($marker_pct) ?>%; 
                                                   background: <?= first($marker['color'] ?? false, 'var(--primary-light)') ?>;">
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>

                            <div class="gauge-metric-value progressbar-value" style="<?= $prop['value-style'] ?>"
                                id="<?= $prop['id'] ?>-<?= safe($bar_id) ?>-value">
                                <?= safe($bar['value']) ?><?= safe($bar['unit']) ?>
                            </div>
                            <?php if($bar['tooltip'] !== '') { ?><div class="gauge-metric-meta progressbar-meta"><?= safe($bar['tooltip']) ?></div><?php } ?>
                            
                            <?= $bar['after'] ?>
                        </section>
                    <?php 
                    }
                }
                ?>
            </div>
            <?php } ?>
        </section>
        <?php
        if(!empty($prop['listen']))
        {
            ?><script>
            ProgressbarComponents.start_listen(<?= jsafe($prop) ?>);    
            </script><?php
        }
    }

];