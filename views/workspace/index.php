<?php
	URL::$route['page-title'] = 'Workspace';

	$section = trim((string)(URL::$route['param'] ?? 'overview'));
	if ($section === '') $section = 'overview';

	$sections = [
		'overview' => [
			'title' => 'Workspace overview',
			'subtitle' => 'A generic app-shell pattern for tools, admin consoles, and internal products.',
			'status' => ['label' => 'Ready', 'variant' => 'success'],
			'description' => 'This slice comes from the uh-ai portal app: a reusable workspace shell with sidebar navigation, compact mobile controls, and semantic panel primitives.',
			'highlights' => [
				['title' => 'Shell layout', 'text' => 'Sidebar plus main panel, responsive overlay behavior, and a compact mobile header.'],
				['title' => 'Semantic primitives', 'text' => 'Panels, section heads, status pills, list states, and empty-state placeholders.'],
				['title' => 'Starter-friendly', 'text' => 'Backported against the starter theme variables instead of portal-specific branding tokens.'],
			],
		],
		'projects' => [
			'title' => 'Projects queue',
			'subtitle' => 'Nested path fallback lets one controller serve multiple panes cleanly.',
			'status' => ['label' => '3 active', 'variant' => 'info'],
			'description' => 'This page is served from views/workspace/index.php, while the route remains /workspace/projects. That path fallback was backported from uh-ai as part of this slice.',
			'highlights' => [
				['title' => 'Content review', 'text' => 'Design a shared docs/workspace explorer for internal tools.'],
				['title' => 'Component extraction', 'text' => 'Promote shell primitives into stable starter components.'],
				['title' => 'Routing cleanup', 'text' => 'Use nested routes without multiplying single-file views.'],
			],
		],
		'activity' => [
			'title' => 'Recent activity',
			'subtitle' => 'Sidebar-first tools often need a live or recent-events pane.',
			'status' => ['label' => 'Monitoring', 'variant' => 'warn'],
			'description' => 'The workspace shell is a better fit than the marketing-style home page when the app is navigation-heavy and stateful.',
			'highlights' => [
				['title' => 'Deploy preview', 'text' => 'Workspace layout validated on desktop and mobile widths.'],
				['title' => 'Shell behavior', 'text' => 'Sidebar toggle is abstracted in js/u-workspace-shell.js.'],
				['title' => 'Surface consistency', 'text' => 'All blocks inherit existing starter color and radius tokens.'],
			],
		],
	];

	if (!isset($sections[$section])) {
		$section = 'overview';
	}

	$current = $sections[$section];

	$navItems = [
		['key' => 'overview', 'label' => 'Overview', 'icon' => 'fas fa-compass', 'meta' => 'Shell'],
		['key' => 'projects', 'label' => 'Projects', 'icon' => 'fas fa-folder-tree', 'meta' => 'Routes'],
		['key' => 'activity', 'label' => 'Activity', 'icon' => 'fas fa-wave-square', 'meta' => 'State'],
	];

	ob_start();
	?>
	<div class="ws-nav-group-label">Workspace areas</div>
	<div class="ws-nav-list">
		<?php foreach ($navItems as $item): ?>
		<?php $active = $item['key'] === $section; ?>
		<a class="ws-nav-item<?= $active ? ' is-active' : '' ?>" href="<?= URL::link('workspace/' . $item['key']) ?>">
			<span class="ws-nav-item-inner">
				<span class="ws-nav-icon"><i class="<?= asafe($item['icon']) ?>"></i></span>
				<span class="ws-nav-item-text">
					<span class="ws-nav-title"><?= safe($item['label']) ?></span>
					<span class="ws-nav-meta"><?= safe($item['meta']) ?></span>
				</span>
			</span>
		</a>
		<?php endforeach; ?>
	</div>
	<div class="ws-demo-sidebar-copy">
		<p>This sidebar and mobile shell pattern was extracted from the AI portal app and normalized against starter theme tokens.</p>
	</div>
	<?= component('components/workspace/list-state', [
		'icon_class' => 'fas fa-circle-info',
		'text' => 'Use /workspace/overview, /workspace/projects, or /workspace/activity',
	]) ?>
	<?php
	$sidebarBody = ob_get_clean();

	$sidebarTop = component('components/workspace/sidebar-toolbar', [
		'action_html' => '<a class="ws-sidebar-action-btn" href="' . asafe(URL::link('workspace/overview')) . '"><i class="fas fa-grid-2"></i><span>Open shell</span></a>',
		'search_input_id' => 'workspace-demo-search',
		'search_input_name' => 'workspace_demo_search',
		'search_placeholder' => 'Search workspace sections',
	]);

	$sidebar = component('components/workspace/sidebar-shell', [
		'id' => 'workspace-demo-sidebar',
		'top_html' => $sidebarTop,
		'body_html' => $sidebarBody,
	]);

	$mobileBar = component('components/workspace/mobile-bar', [
		'button_id' => 'workspace-demo-toggle',
		'title' => 'Starter Workspace',
	]);

	$header = component('components/workspace/panel-header', [
		'title' => $current['title'],
		'subtitle' => $current['subtitle'],
		'actions_html' => component('components/workspace/status-pill', [
			'label' => $current['status']['label'],
			'variant' => $current['status']['variant'],
		]),
	]);

	$stats = [
		['label' => 'Sidebar pattern', 'value' => 'Responsive', 'meta' => 'Overlay on mobile'],
		['label' => 'Routing mode', 'value' => 'Nested', 'meta' => 'Parent-path fallback'],
		['label' => 'Source app', 'value' => 'uh-ai', 'meta' => 'Portal shell'],
	];

	ob_start();
	?>
	<div class="ws-stat-grid">
		<?php foreach ($stats as $stat): ?>
		<div class="ws-stat-card">
			<div class="ws-stat-card-label"><?= safe($stat['label']) ?></div>
			<div class="ws-stat-card-value"><?= safe($stat['value']) ?></div>
			<div class="ws-stat-card-meta"><?= safe($stat['meta']) ?></div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php
	$statsHtml = ob_get_clean();

	ob_start();
	?>
	<div class="ws-detail-grid">
		<?php foreach ($current['highlights'] as $highlight): ?>
		<div class="ws-detail-card">
			<h4><?= safe($highlight['title']) ?></h4>
			<p><?= safe($highlight['text']) ?></p>
		</div>
		<?php endforeach; ?>
	</div>
	<?php
	$detailHtml = ob_get_clean();

	$mainBody =
		component('components/workspace/section', [
			'header_html' => component('components/workspace/section-head', ['title' => 'Why this belongs in the starter']),
			'body_html' => '<p class="ws-section-copy">' . safe($current['description']) . '</p>' . $statsHtml,
		]) .
		component('components/workspace/section', [
			'header_html' => component('components/workspace/section-head', ['title' => 'Starter demo content']),
			'body_html' => $detailHtml . '<p class="ws-inline-note">Try visiting <strong>' . safe(URL::link('workspace/projects')) . '</strong> or <strong>' . safe(URL::link('workspace/activity')) . '</strong> to see the nested route fallback in action.</p>',
		]);

	if ($section === 'activity') {
		$mainBody .= component('components/workspace/empty-state', [
			'icon_class' => 'fas fa-clock-rotate-left',
			'title' => 'No live stream wired yet',
			'text' => 'The shell is generic. Add your own websocket, polling, or event-driven runtime behind it when a real product needs one.',
			'action_html' => '<a class="ws-primary-btn" href="' . asafe(URL::link('dashboard')) . '">Open dashboard demo</a>',
		]);
	}

	$main = $mobileBar . component('components/workspace/panel', [
		'header_html' => $header,
		'body_html' => $mainBody,
	]);

	echo component('components/workspace/app-frame', [
		'id' => 'workspace-demo-shell',
		'overlay_id' => 'workspace-demo-overlay',
		'sidebar_html' => $sidebar,
		'main_html' => $main,
	]);
	?>
	<script>
	(function () {
		if (typeof UWorkspaceShell === 'undefined') return;
		UWorkspaceShell.init({
			sidebarId: 'workspace-demo-sidebar',
			overlayId: 'workspace-demo-overlay',
			toggleButtonId: 'workspace-demo-toggle'
		});
	}());
	</script>