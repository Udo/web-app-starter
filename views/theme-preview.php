<?php
	include_css('themes.css');
	URL::$route['page-title'] = 'Theme Preview';
	$currentThemeKey = (string)cfg('theme/key');
	$themeLabel = (string)first(cfg('theme/label'), $currentThemeKey);
	$themeMode = (string)cfg('theme/mode');
	$previewStats = [
		['label' => 'Current Theme', 'value' => $themeLabel],
		['label' => 'Mode', 'value' => ucfirst($themeMode)],
		['label' => 'Preview Route', 'value' => '/?theme-preview'],
	];
?>
<div class="theme-preview-shell">
	<section class="theme-preview-hero">
		<span class="theme-preview-kicker">Starter Theme Preview</span>
		<h1><?= safe($themeLabel) ?></h1>
		<p>This route renders the same neutral content inside each theme so downstream projects can compare layout, typography, color tokens, and chrome without switching between unrelated pages.</p>
		<div class="theme-preview-actions">
			<a class="btn" href="<?= URL::Link('themes', ['theme' => $currentThemeKey]) ?>">Back to Gallery</a>
			<a class="btn btn-secondary" href="<?= URL::Link('', ['theme' => $currentThemeKey]) ?>">Open Home in This Theme</a>
		</div>
	</section>

	<section class="theme-preview-grid">
		<div class="theme-preview-card">
			<h2>Tokens at a Glance</h2>
			<div class="theme-preview-stat-grid">
				<?php foreach($previewStats as $stat): ?>
					<div class="theme-preview-stat">
						<span><?= safe($stat['label']) ?></span>
						<strong><?= safe($stat['value']) ?></strong>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="theme-preview-swatches">
				<span style="background: var(--primary);">Primary</span>
				<span style="background: var(--secondary);">Secondary</span>
				<span style="background: var(--accent);">Accent</span>
				<span style="background: var(--surface-elevated, var(--surface)); color: var(--text-primary); border: 1px solid var(--border);">Surface</span>
			</div>
		</div>

		<div class="theme-preview-card">
			<h2>System Message States</h2>
			<div class="banner success">Success state keeps contrast and border semantics intact.</div>
			<div class="banner warning">Warning state checks how the theme handles warm accents and muted text.</div>
			<div class="banner error">Error state shows whether danger colors stay legible over each surface.</div>
		</div>

		<div class="theme-preview-card">
			<h2>Form Controls</h2>
			<form class="theme-preview-form">
				<div>
					<label for="preview-project">Project Name</label>
					<input id="preview-project" name="project" type="text" placeholder="Starter backport playground" />
				</div>
				<div>
					<label for="preview-owner">Owner</label>
					<input id="preview-owner" name="owner" type="email" placeholder="team@example.com" />
				</div>
				<div>
					<label for="preview-goal">Notes</label>
					<textarea id="preview-goal" name="goal" rows="4" placeholder="Describe the kind of product this theme should support."></textarea>
				</div>
				<div class="theme-preview-actions">
					<button type="button" class="btn">Primary Action</button>
					<button type="button" class="btn btn-outline">Secondary Action</button>
				</div>
			</form>
		</div>

		<div class="theme-preview-card">
			<h2>Dense Content Block</h2>
			<table>
				<thead>
					<tr>
						<th>Area</th>
						<th>Expectation</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Navigation</td>
						<td>Should remain readable when menus get long.</td>
						<td>Verified</td>
					</tr>
					<tr>
						<td>Cards</td>
						<td>Should keep spacing and hierarchy on both desktop and mobile.</td>
						<td>Verified</td>
					</tr>
					<tr>
						<td>Embeds</td>
						<td>Should render cleanly inside the gallery iframe.</td>
						<td>Verified</td>
					</tr>
				</tbody>
			</table>
		</div>
	</section>
</div>