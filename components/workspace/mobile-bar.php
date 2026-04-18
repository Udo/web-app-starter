<?php return [
	'render' => function($prop) {
		$id = trim((string)($prop['id'] ?? ''));
		$class = trim((string)($prop['class'] ?? ''));
		$buttonId = trim((string)($prop['button_id'] ?? ''));
		$buttonClass = trim((string)($prop['button_class'] ?? ''));
		$titleId = trim((string)($prop['title_id'] ?? ''));
		$titleClass = trim((string)($prop['title_class'] ?? ''));
		$title = (string)($prop['title'] ?? '');
		$icon = trim((string)($prop['icon_class'] ?? 'fas fa-bars'));
		ob_start();
		?>
		<div<?= $id !== '' ? ' id="' . asafe($id) . '"' : '' ?> class="ws-mobile-bar<?= $class !== '' ? ' ' . asafe($class) : '' ?>">
			<button<?= $buttonId !== '' ? ' id="' . asafe($buttonId) . '"' : '' ?> class="ws-mobile-toggle<?= $buttonClass !== '' ? ' ' . asafe($buttonClass) : '' ?>" title="Toggle sidebar" type="button">
				<i class="<?= asafe($icon) ?>"></i>
			</button>
			<span<?= $titleId !== '' ? ' id="' . asafe($titleId) . '"' : '' ?> class="ws-mobile-title<?= $titleClass !== '' ? ' ' . asafe($titleClass) : '' ?>"><?= safe($title) ?></span>
		</div>
		<?php
		return ob_get_clean();
	},

	'about' => 'Compact mobile header bar for workspace layouts with a sidebar toggle',
];