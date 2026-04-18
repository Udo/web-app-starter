<?php return [
	'render' => function($prop) {
		$id = trim((string)($prop['id'] ?? ''));
		$class = trim((string)($prop['class'] ?? ''));
		$icon = trim((string)($prop['icon_class'] ?? ''));
		$text = (string)($prop['text'] ?? '');
		ob_start();
		?>
		<div<?= $id !== '' ? ' id="' . asafe($id) . '"' : '' ?> class="ws-list-state<?= $class !== '' ? ' ' . asafe($class) : '' ?>"><?php if ($icon !== ''): ?><i class="<?= asafe($icon) ?>"></i><?php endif; ?><span><?= safe($text) ?></span></div>
		<?php
		return ob_get_clean();
	},

	'about' => 'Compact sidebar/list placeholder state with optional icon',
];