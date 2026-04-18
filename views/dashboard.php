<?php
	URL::$route['page-title'] = 'Dashboard';
	include_css('dashboard.css');

	$trafficSeries = [
		[
			'key' => 'requests',
			'label' => 'Requests',
			'color' => '#60a5fa',
			'axis' => 'left',
			'format' => 'count',
			'decimals' => 0,
			'values' => [240, 268, 294, 322, 301, 356, 388],
		],
		[
			'key' => 'latency',
			'label' => 'Latency',
			'color' => '#f59e0b',
			'axis' => 'right',
			'format' => 'duration-ms',
			'values' => [182, 176, 191, 204, 188, 166, 159],
		],
	];

	$serviceRows = [
		['service' => 'router', 'uptime' => '12 days', 'requests' => 142890, 'memory' => 402653184, 'p95_latency' => 148, 'healthy' => true],
		['service' => 'queue-worker', 'uptime' => '8 days', 'requests' => 98214, 'memory' => 654311424, 'p95_latency' => 231, 'healthy' => true],
		['service' => 'vector-index', 'uptime' => '5 days', 'requests' => 44892, 'memory' => 1241513984, 'p95_latency' => 312, 'healthy' => true],
		['service' => 'sandbox', 'uptime' => '19 hours', 'requests' => 12810, 'memory' => 295698432, 'p95_latency' => 418, 'healthy' => false],
	];
?>

<h1>Dashboard Primitives</h1>

<div class="card dashboard-intro">
	<p>
		This page is the first backport slice from the LocalAI dashboard frontend. It keeps the parts that are generic enough for the starter itself:
		metric cards, a canvas time-series chart, and a lightweight sortable table that remembers its last sort choice.
	</p>
</div>

<?= component('components/data/summary-metrics', [
	'title' => 'Starter-Friendly Overview Cards',
	'subtitle' => 'Small summary tiles work well across admin pages, internal tools, and SSR dashboards.',
	'items' => [
		['label' => '24h Requests', 'value' => '18,420', 'meta' => '+12.8% vs yesterday', 'tone' => 'info'],
		['label' => 'Median Latency', 'value' => '182 ms', 'meta' => 'stable over last 7 samples', 'tone' => 'success'],
		['label' => 'Resident Memory', 'value' => '2.4 GB', 'meta' => 'combined across workers', 'tone' => 'warning'],
		['label' => 'Healthy Services', 'value' => '3 / 4', 'meta' => 'one degraded background worker', 'tone' => 'danger'],
	],
]) ?>

<?= component('components/data/timeseries-chart', [
	'id' => 'dashboard-demo-traffic',
	'title' => 'Requests vs Latency',
	'subtitle' => 'Same generic chart primitive can track throughput, job backlog, token volume, or queue time.',
	'height' => 320,
	'x_axis_label' => 'Last 7 Hours',
	'y_axis_left_label' => 'Requests',
	'y_axis_right_label' => 'Latency',
	'y_axis_left_format' => 'count',
	'y_axis_right_format' => 'duration-ms',
	'x_labels' => ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00'],
	'series' => $trafficSeries,
]) ?>

<?= component('components/data/sortable-table', [
	'id' => 'dashboard-service-table',
	'title' => 'Service Snapshot',
	'subtitle' => 'Vanilla HTML table enhancement for cases where ag-Grid is overkill.',
	'storage_key' => 'starter.dashboard.services',
	'sort' => ['column' => 2, 'direction' => 'desc'],
	'columns' => [
		['key' => 'service', 'label' => 'Service'],
		['key' => 'uptime', 'label' => 'Uptime'],
		['key' => 'requests', 'label' => 'Requests', 'align' => 'right', 'format' => 'number'],
		['key' => 'memory', 'label' => 'Memory', 'align' => 'right', 'format' => 'bytes'],
		['key' => 'p95_latency', 'label' => 'P95 Latency', 'align' => 'right', 'format' => 'duration-ms'],
		['key' => 'healthy', 'label' => 'Healthy', 'align' => 'center', 'format' => 'bool'],
	],
	'rows' => $serviceRows,
]) ?>

<div class="card">
	<h2>What Was Backported</h2>
	<p class="dashboard-note">
		The charting logic and data-formatting ideas come directly from the more complex dashboard frontend on <code>uh-llm2</code>, but the starter version is stripped down to generic building blocks.
		That keeps the repo useful as a baseline instead of baking in LocalAI-specific assumptions.
	</p>
</div>