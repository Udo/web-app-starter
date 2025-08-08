<script>

NeedlegaugeComponents = { 

	start_listen : function(prop) {
		$.events.on('value-broadcast', function(data) {
			if(prop.items[data.name]) {
				// update value text
				let item = Object.assign({}, prop.scale, prop.items[data.name]);
				$('#' + prop.id + '-' + data.name + '-value').text(data.value + (item.unit || ''));
				let vrange = (item.max || 100) - (item.min || 0);
				let pct_value = clamp((data.value - (item.min || 0)) / vrange, 0, 1);
				let angle = -Math.PI + item.angle_start + (pct_value * (item.angle_end - item.angle_start));
				$('#' + prop.id + '-' + data.name + '-needle').css('transform', 'rotate(' + angle + 'rad)');
				
			}
		});
	},

}

</script><?php 

include_js('components/gauges/common.js');
include_css('components/gauges/common.css');

return [

	'render' => function($prop) {
		$prop['id'] = $prop['id'] ?: 'needlegauge-' . uniqid();
		$prop['size'] = first($prop['size'], 200);
		$prop['scale']['angle_start'] = first($prop['scale']['angle_start'], -pi());
		$prop['scale']['angle_end'] = first($prop['scale']['angle_end'], 0);
		$prop['img_height'] = first($prop['img_height'], 
			$prop['size'] * max(cos(first($prop['scale']['angle_start'], -pi())), cos(first($prop['scale']['angle_end'], 0))));
		?>        
		<div class="block needlegauge" id="<?= $prop['id'] ?>" style="<?= $prop['style'] ?>">
			<?php if(isset($prop['title'])) { ?>
				<h3><?= safe($prop['title']) ?></h3>
			<?php } ?>
			<div class="needlegauge-container" style="">
			<?php
			foreach($prop['items'] as $item_id => $item) 
			{
				$item = array_merge($prop['scale'], $item);
				$vrange = (first($item['max'], 100) - first($item['min'], 0));
				$pct_value = clamp(($item['value'] - first($item['min'], 0)) / $vrange, 0, 1);
				$needle_angle = -pi() + $item['angle_start'] + ($pct_value * ($item['angle_end'] - $item['angle_start']));
				$needle_color = first($item['color'], '#888888');
				if(is_array($item['color'])) {
					$needle_color = pick_entry_from_range($item['color'], $item['value'])['color'];
				}
				$tick_interval = first($item['ticks-every'], $vrange / 20);
				$label_interval = first($item['value-labels-every'], $vrange / 4);
				$tick_number = 0;
				$ticks_html = '';
				$min_angle = first($item['angle_start']); 
				$max_angle = first($item['angle_end']);
				$tick_color = first($item['tick-color'], '#888888');

				for($v = $item['min']; $v <= $item['max']; $v += $tick_interval) 
				{
					$tick_number++;
					$angle = $min_angle + (($v-$item['min'])/$vrange) * ($max_angle - $min_angle);
					
					$x1 = $prop['size']/2 + cos($angle) * $prop['size']*0.38;
					$y1 = $prop['size']/2 + sin($angle) * $prop['size']*0.38;
					$x2 = $prop['size']/2 + cos($angle) * $prop['size']*0.41;
					$y2 = $prop['size']/2 + sin($angle) * $prop['size']*0.41;

					$ticks_html .= '<line x1="'.($x1).'" y1="'.($y1).'" x2="'.($x2).'" y2="'.($y2).'" 
						stroke="'. $tick_color .'" stroke-width="1"/>';
					
				}

				for($v = $item['min']; $v <= $item['max']; $v += $label_interval) 
				{
					$tick_number++;
					$angle = $min_angle + (($v-$item['min'])/$vrange) * ($max_angle - $min_angle);
					
					$x1 = $prop['size']/2 + cos($angle) * $prop['size']*0.35;
					$y1 = $prop['size']/2 + sin($angle) * $prop['size']*0.35;
					$x2 = $prop['size']/2 + cos($angle) * $prop['size']*0.41;
					$y2 = $prop['size']/2 + sin($angle) * $prop['size']*0.41;

					$ticks_html .= '<line x1="'.($x1).'" y1="'.($y1).'" x2="'.($x2).'" y2="'.($y2).'" 
						stroke="'. $tick_color .'" stroke-width="3"/>';
					
					$lx = $prop['size']/2 + cos($angle) * $prop['size']*0.45;
					$ly = $prop['size']/2 + sin($angle) * $prop['size']*0.45;
					$ticks_html .= '<text x="'.($lx).'" y="'.($ly).'" text-anchor="middle" dominant-baseline="central" 
						font-size="10" fill="'. $tick_color .'">'.($v == 0 ? '0' : $v).'</text>';
				}

			?>
			<div class="needlegauge-item" style="text-align: center;">
				
				<svg id="<?= $prop['id'] ?>-<?= $item_id ?>-svg" width="<?= $prop['size'] ?>" height="<?= $prop['img_height'] ?>" class="needlegauge-svg"
					viewBox="0 0 <?= $prop['size'] ?> <?= $prop['img_height'] ?>" style="max-width: 100%;">

					<?php
					if(is_array($item['color']))  
					{
						foreach($item['color'] as $range)
						{
							$angle_from = $min_angle + ((max($range['from'], $item['min']) - $item['min']) / $vrange) * ($max_angle - $min_angle);
							$angle_to = $min_angle + ((min($range['to'], $item['max']) - $item['min']) / $vrange) * ($max_angle - $min_angle);
							$color = $range['color'] ?? 'rgba(120,120,120,0.5)';
							SVG::circle_segment($prop['size']/2, $prop['size']/2, $prop['size']*0.4, 
								$angle_from, $angle_to, $color, 8, 'rgba(0,0,0,0)', 'opacity:0.25');
						}
					}
					else
					{
						SVG::circle_segment($prop['size']/2, $prop['size']/2, $prop['size']*0.4, 
							$min_angle, $max_angle, $tick_color, 8, 'rgba(0,0,0,0)', 'opacity:0.25');
					}
					?>

					<g class="ticks">
						<?= $ticks_html ?>
					</g>
					
					<line id="<?= $prop['id'] ?>-<?= $item_id ?>-needle" class="needle"
						x1="<?= $prop['size']*0.55 ?>" y1="<?= $prop['size']*0.5 ?>" x2="<?= $prop['size']*0.1 ?>" y2="<?= $prop['size']*0.5 ?>" 
						stroke="<?= ($tick_color) ?>" stroke-width="3" stroke-linecap="round"
						style="transform-origin: <?= $prop['size']/2 ?>px <?= $prop['size']/2 ?>px; 
							transform: rotate(<?= ($needle_angle) ?>rad); transition: transform 0.3s ease;"/>

					<circle cx="<?= $prop['size']/2 ?>" cy="<?= $prop['size']/2 ?>" r="6" fill="<?= $tick_color ?>"/>

				</svg>
				
				<div class="needlegauge-info" style="margin-top: -50px;">
					<div class="needlegauge-value" id="<?= $prop['id'] ?>-<?= $item_id ?>-value" 
						 title="<?= asafe($item['tooltip']) ?>">
						<?= safe($item['value']) ?><?= safe($item['unit']) ?>
					</div>
					<?php if(isset($item['label'])) { ?>
						<div class="needlegauge-label" style="font-weight: bold;">
							<?= safe($item['label']) ?>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
			}
			?>
			</div>
		</div>
		<?php
		if($prop['listen'])
		{
			?><script>
			NeedlegaugeComponents.start_listen(<?= jsafe($prop) ?>);    
			</script><?php
		}
	}

];
