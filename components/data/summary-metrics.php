<?php return [
	'render' => function($prop) {
		$items = is_array($prop['items'] ?? null) ? $prop['items'] : [];
		$title = (string)($prop['title'] ?? '');
		$subtitle = (string)($prop['subtitle'] ?? '');
		ob_start();
		?>
		<div class="dashboard-panel">
			<?php if ($title !== '' || $subtitle !== ''): ?>
			<div class="dashboard-panel-header">
				<?php if ($title !== ''): ?><h2><?= safe($title) ?></h2><?php endif; ?>
				<?php if ($subtitle !== ''): ?><p><?= safe($subtitle) ?></p><?php endif; ?>
			</div>
			<?php endif; ?>
			<div class="dashboard-stat-grid">
				<?php foreach ($items as $item): ?>
				<?php
					$tone = preg_replace('/[^a-z0-9_-]+/i', '', (string)($item['tone'] ?? 'info'));
					$tag = !empty($item['href']) ? 'a' : 'div';
					$href = !empty($item['href']) ? ' href="' . asafe((string)$item['href']) . '"' : '';
				?>
				<<?= $tag ?> class="dashboard-stat-card tone-<?= asafe($tone) ?>"<?= $href ?>>
					<div class="dashboard-stat-label"><?= safe((string)($item['label'] ?? 'Metric')) ?></div>
					<div class="dashboard-stat-value"><?= safe((string)($item['value'] ?? '--')) ?></div>
					<?php if (!empty($item['meta'])): ?>
					<div class="dashboard-stat-meta"><?= safe((string)$item['meta']) ?></div>
					<?php endif; ?>
				</<?= $tag ?>>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	},

	'about' => 'Responsive metric cards extracted from dashboard-style pages and adapted for generic starter overviews',
];