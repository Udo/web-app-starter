<?php return [
	'render' => function($prop) {
		$id = trim((string)($prop['id'] ?? ''));
		$class = trim((string)($prop['class'] ?? ''));
		$actionHtml = (string)($prop['action_html'] ?? '');
		$searchId = trim((string)($prop['search_id'] ?? ''));
		$searchClass = trim((string)($prop['search_class'] ?? ''));
		$searchInputId = trim((string)($prop['search_input_id'] ?? ''));
		$searchInputName = trim((string)($prop['search_input_name'] ?? 'search'));
		$searchInputClass = trim((string)($prop['search_input_class'] ?? ''));
		$searchPlaceholder = (string)($prop['search_placeholder'] ?? 'Search...');
		ob_start();
		?>
		<div<?= $id !== '' ? ' id="' . asafe($id) . '"' : '' ?> class="ws-sidebar-top<?= $class !== '' ? ' ' . asafe($class) : '' ?>">
			<?= $actionHtml ?>
			<div<?= $searchId !== '' ? ' id="' . asafe($searchId) . '"' : '' ?> class="ws-search-wrap<?= $searchClass !== '' ? ' ' . asafe($searchClass) : '' ?>">
				<i class="fas fa-search ws-search-icon"></i>
				<input type="search"<?= $searchInputId !== '' ? ' id="' . asafe($searchInputId) . '"' : '' ?> name="<?= asafe($searchInputName) ?>" class="ws-search-input<?= $searchInputClass !== '' ? ' ' . asafe($searchInputClass) : '' ?>" placeholder="<?= asafe($searchPlaceholder) ?>" autocomplete="off">
			</div>
		</div>
		<?php
		return ob_get_clean();
	},

	'about' => 'Sidebar toolbar with optional action area and compact search field',
];