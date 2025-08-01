<style>
    
    .horizontal .progressbar-item {
        display: flex;
        min-width: 100%;
        padding: 5px;
    }

    .progressbar-container.vertical {
        display: flex;
    }

    .vertical .progressbar-item {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 5px;
        text-align: center;
    }

    .progressbar-label {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: bold;
    }

    .horizontal .progressbar-label, .horizontal .progressbar-value {
        flex: 0 0 auto;
        margin-right: 10px;
    }

    .horizontal .progressbar-label {
        min-width: 50px;
    }

    .vertical .progressbar-label {
        text-align: center;
    }

    .horizontal .progressbar-value {
        min-width: 50px;
        text-align: right;
    }

    .progressbar-background {
        flex: 1;
        background: var(--bg-color);
        padding: 2px;
        display: flex;
    }

    .vertical .progressbar-background {
        flex-direction: column;
        justify-content: flex-end;
    }

    .progressbar-bar {
        transition: all 0.3s ease;
    }
    
</style>
<script>

ProgressbarComponents = { 

    start_listen : function(prop) {
        $.events.on('value-broadcast', function(data) {
            if(prop.bars[data.name]) {
                // update value text
                $('#' + prop.id + '-' + data.name + '-value').text(data.value + (prop.bars[data.name].unit || ''));
                // update bar width/height
                let pct_value = clamp(data.value / (prop.bars[data.name].max || 100) * 100, 0, 100);
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

return [

    'render' => function($prop) {
        $auto_color_counter = 1;
        ?>        
        <div class="block progressbar" id="<?= $prop['id'] ?>" style="<?= $prop['style'] ?>">
            <?php if(isset($prop['title'])) { ?>
                <h3><?= safe($prop['title']) ?></h3>
            <?php } ?>
            <?php if(first($prop['layout'], 'horizontal') == 'horizontal') { ?>
            <div class="progressbar-container horizontal">
                <?php foreach($prop['bars'] as $bar_id => $bar) 
                { 
                    $bar['pct-value'] = clamp($bar['value'] / first($bar['max'], 100) * 100, 0, 100);
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
                        </div>
                        <?= $bar['after'] ?>
                    </div>
                <?php } ?>
            </div>
            <?php } else { ?>
            <div class="progressbar-container vertical" style="height: <?= safe($prop['height']) ?>px;">
                <?php 
                if($prop['bars'] && sizeof($prop['bars']) > 0)
                {
                    $item_width = first($prop['item-width'], (100/sizeof($prop['bars'])).'%');
                    if($prop['pad-right'] !== false && !$prop['item-width']) $item_width = '100px';
                    foreach($prop['bars'] as $bar_id => $bar) 
                    { 
                        $bar['pct-value'] = clamp($bar['value'] / first($bar['max'], 100) * 100, 0, 100);
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