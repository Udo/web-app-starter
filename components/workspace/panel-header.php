<?php return [
	'render' => function($prop) {
		$class = trim((string)($prop['class'] ?? ''));
		$title = (string)($prop['title'] ?? '');
		$subtitle = (string)($prop['subtitle'] ?? '');
		$titleId = trim((string)($prop['title_id'] ?? ''));
		$subtitleId = trim((string)($prop['subtitle_id'] ?? ''));
		$actionsHtml = (string)($prop['actions_html'] ?? '');
		ob_start();
		?>
		<div class="ws-panel-header<?= $class !== '' ? ' ' . asafe($class) : '' ?>">
			<div class="ws-panel-title-group">
				<h2<?= $titleId !== '' ? ' id="' . asafe($titleId) . '"' : '' ?>><?= safe($title) ?></h2>
				<?php if ($subtitle !== ''): ?>
					<p<?= $subtitleId !== '' ? ' id="' . asafe($subtitleId) . '"' : '' ?> class="ws-subtitle"><?= safe($subtitle) ?></p>
				<?php endif; ?>
			</div>
			<?php if ($actionsHtml !== ''): ?><div class="ws-header-actions"><?= $actionsHtml ?></div><?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	},

	'about' => 'Panel heading with title, optional subtitle, and actions slot',
];