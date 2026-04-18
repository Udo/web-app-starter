<?php return [
	'render' => function($prop) {
		$tag = trim((string)($prop['tag'] ?? 'button'));
		if (!in_array($tag, ['button', 'span', 'a'], true)) $tag = 'button';
		$id = trim((string)($prop['id'] ?? ''));
		$class = trim((string)($prop['class'] ?? ''));
		$title = trim((string)($prop['title'] ?? ''));
		$icon = trim((string)($prop['icon_class'] ?? ''));
		$text = (string)($prop['text'] ?? '');
		$attrs = trim((string)($prop['attrs'] ?? ''));
		$href = trim((string)($prop['href'] ?? ''));
		$type = trim((string)($prop['type'] ?? 'button'));
		ob_start();
		?>
		<<?= $tag ?><?= $id !== '' ? ' id="' . asafe($id) . '"' : '' ?> class="ws-icon-btn<?= $class !== '' ? ' ' . asafe($class) : '' ?>"<?= $title !== '' ? ' title="' . asafe($title) . '"' : '' ?><?= $tag === 'button' ? ' type="' . asafe($type) . '"' : '' ?><?= $tag === 'a' && $href !== '' ? ' href="' . asafe($href) . '"' : '' ?><?= $attrs !== '' ? ' ' . $attrs : '' ?>><?php if ($icon !== ''): ?><i class="<?= asafe($icon) ?>"></i><?php endif; ?><?php if ($text !== ''): ?><span><?= safe($text) ?></span><?php endif; ?></<?= $tag ?>>
		<?php
		return ob_get_clean();
	},

	'about' => 'Small utility icon button for shell toolbars and compact actions',
];