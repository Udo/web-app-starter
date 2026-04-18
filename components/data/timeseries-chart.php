<?php return [
	'render' => function($prop) {
		include_js('js/u-format.js');
		include_js('js/u-timeseries-chart.js');

		$chartId = (string)($prop['id'] ?? ('ts-chart-' . uniqid()));
		$canvasId = $chartId . '-canvas';
		$title = (string)($prop['title'] ?? '');
		$subtitle = (string)($prop['subtitle'] ?? '');
		$height = max(180, (int)($prop['height'] ?? 320));
		$series = array_values(is_array($prop['series'] ?? null) ? $prop['series'] : []);
		$xLabels = array_values(is_array($prop['x_labels'] ?? null) ? $prop['x_labels'] : []);
		$jsonFlags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
		ob_start();
		?>
		<div class="dashboard-panel">
			<?php if ($title !== '' || $subtitle !== ''): ?>
			<div class="dashboard-panel-header">
				<?php if ($title !== ''): ?><h2><?= safe($title) ?></h2><?php endif; ?>
				<?php if ($subtitle !== ''): ?><p><?= safe($subtitle) ?></p><?php endif; ?>
			</div>
			<?php endif; ?>
			<canvas id="<?= asafe($canvasId) ?>" class="dashboard-chart-canvas" style="height: <?= $height ?>px"></canvas>
		</div>
		<script>
		(function () {
			if (typeof UTimeSeriesChart === 'undefined') return;
			var chart = new UTimeSeriesChart(<?= jsafe($canvasId) ?>, {
				xAxisLabel: <?= jsafe((string)($prop['x_axis_label'] ?? 'Time')) ?>,
				yAxisLeftLabel: <?= jsafe((string)($prop['y_axis_left_label'] ?? 'Value')) ?>,
				yAxisRightLabel: <?= jsafe((string)($prop['y_axis_right_label'] ?? '')) ?>,
				yAxisLeftFormat: <?= jsafe((string)($prop['y_axis_left_format'] ?? 'number')) ?>,
				yAxisRightFormat: <?= jsafe((string)($prop['y_axis_right_format'] ?? 'number')) ?>
			});
			chart.setData(<?= json_encode($series, $jsonFlags) ?>, <?= json_encode($xLabels, $jsonFlags) ?>);
			window[<?= jsafe('chart_' . $chartId) ?>] = chart;
		}());
		</script>
		<?php
		return ob_get_clean();
	},

	'about' => 'Canvas-based multi-series time-series chart component backported from a production dashboard',
];