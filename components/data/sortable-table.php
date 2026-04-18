<?php

if (!function_exists('sortable_table_format_bytes')) {
	function sortable_table_format_bytes($bytes, $disk = false)
	{
		if ($bytes === null || $bytes === '') return '--';
		$units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
		$value = (float)$bytes;
		$unitIndex = 0;
		while (abs($value) >= 1024 && $unitIndex < count($units) - 1) {
			$value /= 1024;
			$unitIndex += 1;
		}
		$decimals = $disk ? ($unitIndex >= 4 ? 2 : ($unitIndex >= 1 ? 1 : 0)) : ($unitIndex === 0 ? 0 : 1);
		return number_format($value, $decimals) . ' ' . $units[$unitIndex];
	}
}

if (!function_exists('sortable_table_format_value')) {
	function sortable_table_format_value($value, $row, $column)
	{
		$format = $column['format'] ?? null;
		if (is_callable($format)) {
			return [$format($value, $row, $column), $value];
		}

		switch ($format) {
			case 'number':
				return [number_format((float)$value), (float)$value];
			case 'bytes':
				return [sortable_table_format_bytes($value, false), (float)$value];
			case 'disk-bytes':
				return [sortable_table_format_bytes($value, true), (float)$value];
			case 'percent':
				return [number_format((float)$value, 1) . '%', (float)$value];
			case 'duration-ms':
				$number = (float)$value;
				if ($number >= 1000) {
					return [number_format($number / 1000, $number >= 10000 ? 0 : 1) . ' s', $number];
				}
				return [number_format($number, $number >= 100 ? 0 : 1) . ' ms', $number];
			case 'bool':
				return [$value ? 'Yes' : 'No', $value ? 1 : 0];
			default:
				if (is_bool($value)) {
					return [$value ? 'Yes' : 'No', $value ? 1 : 0];
				}
				return [(string)$value, is_scalar($value) ? $value : ''];
		}
	}
}

return [
	'render' => function($prop) {
		include_js('js/u-format.js');
		include_js('js/u-sortable-table.js');

		$tableId = (string)($prop['id'] ?? ('sortable-table-' . uniqid()));
		$title = (string)($prop['title'] ?? '');
		$subtitle = (string)($prop['subtitle'] ?? '');
		$columns = is_array($prop['columns'] ?? null) ? $prop['columns'] : [];
		$rows = is_array($prop['rows'] ?? null) ? $prop['rows'] : [];
		$emptyLabel = (string)($prop['empty_label'] ?? 'No data available');
		$storageKey = (string)($prop['storage_key'] ?? ('starter.sort.' . $tableId));
		$sort = is_array($prop['sort'] ?? null) ? $prop['sort'] : [];
		$initOptions = ['storageKey' => $storageKey];
		if (isset($sort['column'])) {
			$initOptions['initialSort'] = [
				'column' => (int)$sort['column'],
				'direction' => (($sort['direction'] ?? 'asc') === 'desc') ? 'desc' : 'asc',
			];
		}
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
			<div class="dashboard-table-wrap">
				<table id="<?= asafe($tableId) ?>" class="u-sortable-table">
					<thead>
						<tr>
							<?php foreach ($columns as $column): ?>
							<?php
								$align = (string)($column['align'] ?? 'left');
								$sortable = !isset($column['sortable']) || $column['sortable'];
							?>
							<th scope="col" class="align-<?= asafe($align) ?>"<?= !$sortable ? ' data-sortable="false"' : '' ?>><?= safe((string)($column['label'] ?? $column['key'] ?? 'Column')) ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php if (!$rows): ?>
						<tr data-empty-row="1"><td colspan="<?= count($columns) ?: 1 ?>" class="muted"><?= safe($emptyLabel) ?></td></tr>
						<?php endif; ?>
						<?php foreach ($rows as $row): ?>
						<tr>
							<?php foreach ($columns as $column): ?>
							<?php
								$key = (string)($column['key'] ?? '');
								$value = $row[$key] ?? '';
								list($displayValue, $sortValue) = sortable_table_format_value($value, $row, $column);
								if (isset($column['sort_value']) && is_callable($column['sort_value'])) {
									$sortValue = $column['sort_value']($value, $row, $column);
								}
							?>
							<td class="align-<?= asafe((string)($column['align'] ?? 'left')) ?>" data-sort-value="<?= asafe((string)$sortValue) ?>"><?= safe((string)$displayValue) ?></td>
							<?php endforeach; ?>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<script>
		(function () {
			if (typeof USortableTable === 'undefined') return;
			USortableTable.init(<?= jsafe($tableId) ?>, <?= json_encode($initOptions, $jsonFlags) ?>);
		}());
		</script>
		<?php
		return ob_get_clean();
	},

	'about' => 'Lightweight sortable HTML table with remembered sort state and human-readable data formatting',
];