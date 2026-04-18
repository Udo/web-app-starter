<?php return [
	'render' => function($prop) {
		$id = trim((string)($prop['id'] ?? ''));
		$class = trim((string)($prop['class'] ?? ''));
		$attrs = trim((string)($prop['attrs'] ?? ''));
		$header = (string)($prop['header_html'] ?? '');
		$body = (string)($prop['body_html'] ?? '');
		ob_start();
		?>
		<section<?= $id !== '' ? ' id="' . asafe($id) . '"' : '' ?> class="ws-panel<?= $class !== '' ? ' ' . asafe($class) : '' ?>"<?= $attrs !== '' ? ' ' . $attrs : '' ?>>
			<?= $header ?>
			<?= $body ?>
		</section>
		<?php
		return ob_get_clean();
	},

	'about' => 'Flexible workspace panel container with separate header and body slots',
];