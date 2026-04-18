<?php return [
	'render' => function($prop) {
		include_css('themes/common/css/workspace.css');
		include_js('js/u-workspace-shell.js');

		$id = trim((string)($prop['id'] ?? ''));
		$class = trim((string)($prop['class'] ?? ''));
		$sidebar = (string)($prop['sidebar_html'] ?? '');
		$main = (string)($prop['main_html'] ?? '');
		$overlayId = trim((string)($prop['overlay_id'] ?? ''));
		ob_start();
		?>
		<div<?= $id !== '' ? ' id="' . asafe($id) . '"' : '' ?> class="ws-app<?= $class !== '' ? ' ' . asafe($class) : '' ?>">
			<?= $sidebar ?>
			<div<?= $overlayId !== '' ? ' id="' . asafe($overlayId) . '"' : '' ?> class="ws-sidebar-overlay"></div>
			<main class="ws-main">
				<?= $main ?>
			</main>
		</div>
		<?php
		return ob_get_clean();
	},

	'about' => 'Generic workspace app shell with sidebar, overlay, and main content area',
];