<?php
	include_css('themes.css');
	URL::$route['page-title'] = 'Themes';
	$themeOptions = cfg('theme/options');
	$currentTheme = (string)cfg('theme/key');
	$previewRoute = 'theme-preview';
?>
<div class="theme-gallery-shell">
	<section class="theme-gallery-hero card">
		<span class="theme-gallery-kicker">Starter Theme Gallery</span>
		<h1>Compare Themes Side by Side</h1>
		<p>The gallery renders the same preview content in each theme family. This makes it easier to judge shell fit, typography, token balance, and embed behavior before carrying a theme into a downstream app.</p>
	</section>

	<div class="theme-gallery-grid">
		<?php foreach($themeOptions as $themeKey => $themeInfo): ?>
			<section class="theme-gallery-card<?= $themeKey === $currentTheme ? ' is-active' : '' ?>">
				<div class="theme-gallery-head">
					<div>
						<h2><?= safe((string)$themeInfo['label']) ?></h2>
						<p><?= safe((string)first($themeInfo['description'] ?? false, 'Reusable starter theme family.')) ?></p>
					</div>
					<?php if($themeKey === $currentTheme): ?><span class="theme-gallery-badge">Active</span><?php endif; ?>
				</div>
				<div class="theme-gallery-actions">
					<a class="btn" href="<?= URL::Link($previewRoute, ['theme' => $themeKey]) ?>">Open Preview</a>
					<a class="btn btn-secondary" href="<?= URL::Link('', ['theme' => $themeKey]) ?>">Open Home</a>
				</div>
				<div class="theme-gallery-frame-wrap">
					<iframe title="<?= safe((string)$themeInfo['label']) ?> preview" src="<?= URL::Link($previewRoute, ['theme' => $themeKey, 'embed' => 1]) ?>"></iframe>
				</div>
			</section>
		<?php endforeach; ?>
	</div>
</div>