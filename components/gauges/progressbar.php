<script>

ProgressbarComponents = { 

    start_listen : function(prop) {
        $.events.on('value-broadcast', function(data) {
            if(prop.items[data.name]) {
                // update value text
                let bar = Object.assign({}, prop.scale, prop.items[data.name]);
                $('#' + prop.id + '-' + data.name + '-value').text(data.value + (bar.unit || ''));
                // update bar width/height
                let vrange = (bar.max || 100) - (bar.min || 0);
                let pct_value = clamp((data.value - (bar.min || 0)) / vrange * 100, 0, 100);
                if(Array.isArray(bar.color)) {
                    let bcolor = pick_entry_from_range(bar.color, data.value).color;
                    $('#' + prop.id + '-' + data.name + '-bar').css('background', bcolor);
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
include_css('components/gauges/common.css');

return [

    'render' => function($prop) {
        $auto_color_counter = 1;
        if(!$prop['scale']) $prop['scale'] = [];
        ?>        
        <div class="block progressbar" id="<?= $prop['id'] ?>" style="<?= $prop['style'] ?>">
            <?php if(isset($prop['title'])) { ?>
                <h3><?= safe($prop['title']) ?></h3>
            <?php } ?>
            <?php if(first($prop['layout'], 'horizontal') == 'horizontal') { ?>
            <div class="progressbar-container horizontal">
                <?php foreach($prop['items'] as $bar_id => $bar) 
                { 
                    $bar = array_merge($prop['scale'], $bar);
                    $vrange = (first($bar['max'], 100) - first($bar['min'], 0));
                    $bar['pct-value'] = clamp(($bar['value'] - first($bar['min'], 0)) / $vrange * 100, 0, 100);
                    if(is_array($bar['color']))
                        $bar['color'] = pick_entry_from_range($bar['color'], $bar['value'])['color'];
                    if(!$bar['color'])
                        $bar['color'] = first($prop['bar-color'], 'var(--color-'.($auto_color_counter++).', #4488bb)');
                    ?>
                    <div class="progressbar-item" id="<?= $prop['id'] ?>-<?= safe($bar_id) ?>"
                        title="<?= asafe($bar['tooltip']) ?>"
                        style="<?= $prop['item-style'] ?>;<?= $bar['style'] ?>">
                        <?= $bar['before'] ?>
                        <div class="progressbar-label" style="<?= $prop['label-style'] ?>
                            width: <?= isset($bar['label_width']) ? safe($bar['label_width']) : '100px' ?>;">
                            <?= safe($bar['label']) ?>
                        </div>
                        <div class="progressbar-value" style="<?= $prop['value-style'] ?>"
                            id="<?= $prop['id'] ?>-<?= safe($bar_id) ?>-value">
                            <?= safe($bar['value']) ?><?= safe($bar['unit']) ?>
                        </div>
                        <div class="progressbar-background" 
                            style=""> 
                            <div class="progressbar-bar" id="<?= $prop['id'] ?>-<?= safe($bar_id) ?>-bar"
                                style="background-color: <?= safe($bar['color']) ?>;
                                    <?= $prop['bar-style'] ?>
                                    opacity: <?= isset($bar['opacity']) ? safe($bar['opacity']) : '0.75' ?>;
                                    width: <?= safe($bar['pct-value']) ?>%;
                                    min-height: <?= isset($bar['size']) ? safe($bar['size']) : '20px' ?>;">
                            </div>
                            <?php 
                            if(isset($prop['markers'])) {
                                foreach($prop['markers'] as $marker_id => $marker) {
                                    $marker_pct = clamp(($marker['value'] - first($bar['min'], 0)) / $vrange * 100, 0, 100);
                                    ?>
                                    <div class="progressbar-marker" 
                                        title="<?= asafe($marker['label']) ?>"
                                        style="left: <?= ($marker_pct) ?>%; 
                                               background: <?= first($marker['color'], 'var(--primary-light)') ?>;">                                        
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <?= $bar['after'] ?>
                    </div>
                <?php } ?>
            </div>
            <?php } else { ?>
            <div class="progressbar-container vertical" style="height: <?= safe($prop['height']) ?>px;">
                <?php 
                if($prop['items'] && sizeof($prop['items']) > 0)
                {
                    $item_width = first($prop['item-width'], (100/sizeof($prop['items'])).'%');
                    if($prop['pad-right'] !== false && !$prop['item-width']) $item_width = '100px';
                    foreach($prop['items'] as $bar_id => $bar) 
                    {
                        $bar = array_merge($prop['scale'], $bar);
                        $vrange = (first($bar['max'], 100) - first($bar['min'], 0));
                        $bar['pct-value'] = clamp(($bar['value'] - first($bar['min'], 0)) / $vrange * 100, 0, 100);
                        if(is_array($bar['color']))
                            $bar['color'] = pick_entry_from_range($bar['color'], $bar['value'])['color'];
                        if(!$bar['color'])
                            $bar['color'] = first($prop['bar-color'], 'var(--color-'.($auto_color_counter++).', #4488bb)');
                        ?>
                        <div class="progressbar-item" id="<?= $prop['id'] ?>-<?= safe($bar_id) ?>"
                            title="<?= asafe($bar['tooltip']) ?>"
                            style="width: <?= $item_width ?>;<?= $prop['item-style'] ?>;<?= $bar['style'] ?>">
                            <?= $bar['before'] ?>
                            
                            <div class="progressbar-background" 
                                style=""> 
                                <div class="progressbar-bar" id="<?= $prop['id'] ?>-<?= safe($bar_id) ?>-bar"
                                    style="background-color: <?= safe($bar['color']) ?>;
                                        <?= $prop['bar-style'] ?>
                                        opacity: <?= isset($bar['opacity']) ? safe($bar['opacity']) : '0.75' ?>;
                                        height: <?= safe($bar['pct-value']) ?>%;
                                        min-width: <?= isset($bar['size']) ? safe($bar['size']) : '20px' ?>;">
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
                                                   background: <?= first($marker['color'], 'var(--primary-light)') ?>;">
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>

                            <div class="progressbar-value" style="<?= $prop['value-style'] ?>"
                                id="<?= $prop['id'] ?>-<?= safe($bar_id) ?>-value">
                                <?= safe($bar['value']) ?><?= safe($bar['unit']) ?>
                            </div>

                            <div class="progressbar-label" style="<?= $prop['label-style'] ?>">
                                <?= safe($bar['label']) ?>
                            </div>
                            
                            <?= $bar['after'] ?>
                        </div>
                    <?php 
                    }
                    if($prop['pad-right'] !== false) 
                    {
                        ?>
                        <div class="progressbar-item" style="flex:1;"></div>
                        <?php
                    }
                }
                ?>
            </div>
            <?php } ?>
        </div>
        <?php
        if($prop['listen'])
        {
            ?><script>
            ProgressbarComponents.start_listen(<?= jsafe($prop) ?>);    
            </script><?php
        }
    }

];