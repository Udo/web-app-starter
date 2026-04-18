<?php return [
	'render' => function($prop) {
		$id = trim((string)($prop['id'] ?? ''));
		$class = trim((string)($prop['class'] ?? ''));
		$label = trim((string)($prop['label'] ?? $prop['text'] ?? ''));
		$variant = preg_replace('/[^a-z0-9_-]/i', '', strtolower(trim((string)($prop['variant'] ?? 'neutral'))));
		if ($variant === '') $variant = 'neutral';
		$title = trim((string)($prop['title'] ?? ''));
		ob_start();
		?>
		<span<?= $id !== '' ? ' id="' . asafe($id) . '"' : '' ?> class="ws-status-pill ws-status-pill-<?= asafe($variant) ?><?= $class !== '' ? ' ' . asafe($class) : '' ?>"<?= $title !== '' ? ' title="' . asafe($title) . '"' : '' ?>><?= safe($label) ?></span>
		<?php
		return ob_get_clean();
	},

	'about' => 'Compact semantic status badge for neutral, info, success, warning, and danger states',
];