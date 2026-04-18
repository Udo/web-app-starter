<?php return [
	'render' => function($prop) {
		$class = trim((string)($prop['class'] ?? ''));
		$title = (string)($prop['title'] ?? '');
		$actionsHtml = (string)($prop['actions_html'] ?? '');
		ob_start();
		?>
		<div class="ws-section-head<?= $class !== '' ? ' ' . asafe($class) : '' ?>">
			<h3><?= safe($title) ?></h3>
			<?php if ($actionsHtml !== ''): ?><div class="ws-header-actions"><?= $actionsHtml ?></div><?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	},

	'about' => 'Compact section heading for grouped workspace content',
];