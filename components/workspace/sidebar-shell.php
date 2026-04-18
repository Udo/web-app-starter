<?php return [
	'render' => function($prop) {
		$id = trim((string)($prop['id'] ?? ''));
		$class = trim((string)($prop['class'] ?? ''));
		$top = (string)($prop['top_html'] ?? '');
		$body = (string)($prop['body_html'] ?? '');
		ob_start();
		?>
		<aside<?= $id !== '' ? ' id="' . asafe($id) . '"' : '' ?> class="ws-sidebar<?= $class !== '' ? ' ' . asafe($class) : '' ?>">
			<?= $top ?>
			<?= $body ?>
		</aside>
		<?php
		return ob_get_clean();
	},

	'about' => 'Generic workspace sidebar wrapper with separate top and body slots',
];