<script>

window.ArcgaugeComponents = window.ArcgaugeComponents || {
	start_listen : function(prop) {
		$.events.on('value-broadcast', function(data) {
			if(!prop.items || !prop.items[data.name]) return;
			const item = Object.assign({}, prop.scale || {}, prop.items[data.name]);
			GaugeComponents.updateArcGauge({
				arcId: prop.id + '-' + data.name + '-arc',
				textId: prop.id + '-' + data.name + '-text',
				metaId: prop.id + '-' + data.name + '-meta',
				value: Number(data.value),
				max: Number(item.max || 100),
				suffix: item.unit || '',
				precision: item.precision,
				watermarkPrefix: item.watermark_prefix || data.name,
				color: item.color,
				meta: data.meta != null ? data.meta : null,
			});
		});
	}

};

</script><?php

include_js('components/gauges/common.js');
include_css('themes/common/css/gauges.css');

return [

	'render' => function($prop) {
		$prop['id'] = !empty($prop['id']) ? $prop['id'] : 'arcgauge-'.uniqid();
		$prop['scale'] = $prop['scale'] ?? array();
		$prop['items'] = $prop['items'] ?? array();
		?>
		<div class="arcgauge-set" id="<?= asafe($prop['id']) ?>" style="<?= asafe((string)($prop['style'] ?? '')) ?>">
			<?php if(!empty($prop['title'])) { ?>
				<div class="arcgauge-set-header">
					<h3><?= safe((string)$prop['title']) ?></h3>
					<?php if(!empty($prop['subtitle'])) { ?><p><?= safe((string)$prop['subtitle']) ?></p><?php } ?>
				</div>
			<?php } ?>
			<div class="arcgauge-grid">
				<?php foreach($prop['items'] as $item_id => $item) {
					$item = array_merge($prop['scale'], $item);
					$value = (float)first($item['value'], 0);
					$max = (float)first($item['max'], 100);
					$precision = isset($item['precision']) ? (int)$item['precision'] : 1;
					$pct = $max > 0 ? clamp(($value / $max) * 100, 0, 100) : 0;
					$arc_length = round(($pct / 100) * 157.08, 1);
					$display_value = number_format($value, $precision).safe((string)($item['unit'] ?? ''));
					$resolved_color = '#10b981';
					if(isset($item['color']))
					{
						if(is_array($item['color']))
						{
							$color_entry = pick_entry_from_range($item['color'], $value);
							$resolved_color = first($color_entry['color'] ?? false, $resolved_color);
						}
						else
						{
							$resolved_color = (string)$item['color'];
						}
					}
					else if($pct >= 85)
					{
						$resolved_color = 'var(--error, #ef4444)';
					}
					else if($pct >= 60)
					{
						$resolved_color = 'var(--warning, #f59e0b)';
					}
					else
					{
						$resolved_color = 'var(--success, #10b981)';
					}
					?>
					<section class="arcgauge-card">
						<div class="arcgauge-label"><?= safe((string)first($item['label'], ucfirst((string)$item_id))) ?></div>
						<svg class="arcgauge-svg" viewBox="0 0 120 68" aria-hidden="true">
							<path class="arcgauge-track" d="M 10 60 A 50 50 0 0 1 110 60" fill="none" stroke-width="5" stroke-linecap="round"/>
							<path id="<?= asafe($prop['id']) ?>-<?= asafe($item_id) ?>-arc" class="arcgauge-arc-dyn" d="M 10 60 A 50 50 0 0 1 110 60" fill="none" stroke="<?= asafe($resolved_color) ?>" stroke-width="5" stroke-linecap="round" stroke-dasharray="<?= asafe((string)$arc_length) ?> 157.08"/>
							<?php if(!empty($item['watermark_prefix'])) { ?>
								<line id="<?= asafe($item['watermark_prefix']) ?>WmLo" class="arcgauge-watermark arcgauge-watermark-lo" x1="0" y1="0" x2="0" y2="0" opacity="0"/>
								<line id="<?= asafe($item['watermark_prefix']) ?>WmHi" class="arcgauge-watermark arcgauge-watermark-hi" x1="0" y1="0" x2="0" y2="0" opacity="0"/>
							<?php } ?>
							<text id="<?= asafe($prop['id']) ?>-<?= asafe($item_id) ?>-text" class="arcgauge-value" x="60" y="47" text-anchor="middle"><?= safe($display_value) ?></text>
							<text class="arcgauge-caption" x="60" y="62" text-anchor="middle"><?= safe((string)first($item['caption'], '')) ?></text>
						</svg>
						<div class="arcgauge-meta" id="<?= asafe($prop['id']) ?>-<?= asafe($item_id) ?>-meta"><?= safe((string)first($item['meta'], '--')) ?></div>
					</section>
				<?php } ?>
			</div>
		</div>
		<?php
		if(!empty($prop['listen']))
		{
			?><script>
			ArcgaugeComponents.start_listen(<?= jsafe($prop) ?>);
			</script><?php
		}
	}

];