<?php return [
	'render' => function($prop) {
		$id = trim((string)($prop['id'] ?? ''));
		$class = trim((string)($prop['class'] ?? ''));
		$icon = trim((string)($prop['icon_class'] ?? 'fas fa-layer-group'));
		$title = (string)($prop['title'] ?? '');
		$text = (string)($prop['text'] ?? '');
		$actionHtml = (string)($prop['action_html'] ?? '');
		ob_start();
		?>
		<div<?= $id !== '' ? ' id="' . asafe($id) . '"' : '' ?> class="ws-empty-state<?= $class !== '' ? ' ' . asafe($class) : '' ?>">
			<div class="ws-empty-icon"><i class="<?= asafe($icon) ?>"></i></div>
			<h2><?= safe($title) ?></h2>
			<p><?= safe($text) ?></p>
			<?= $actionHtml ?>
		</div>
		<?php
		return ob_get_clean();
	},

	'about' => 'Centered empty-state block for shell panels and placeholder screens',
];