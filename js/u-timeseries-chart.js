(function (root, factory) {
	if (typeof define === 'function' && define.amd) {
		define([], factory);
	} else if (typeof module === 'object' && module.exports) {
		module.exports = factory();
	} else {
		root.UTimeSeriesChart = factory();
	}
}(typeof self !== 'undefined' ? self : this, function () {
	'use strict';
	const globalScope = typeof globalThis !== 'undefined' ? globalThis : (typeof window !== 'undefined' ? window : this);

	class TimeSeriesChart {
		constructor(canvasOrId, options = {}) {
			this.canvas = typeof canvasOrId === 'string' ? document.getElementById(canvasOrId) : canvasOrId;
			if (!this.canvas || !this.canvas.getContext) {
				throw new Error('UTimeSeriesChart requires a canvas element');
			}

			this.ctx = this.canvas.getContext('2d');
			const theme = this.resolveThemeColors();
			this.options = Object.assign({
				xAxisLabel: 'Time',
				yAxisLeftLabel: 'Value',
				yAxisRightLabel: '',
				yAxisLeftFormat: 'number',
				yAxisRightFormat: 'number',
				padding: { top: 18, right: 62, bottom: 34, left: 58 },
				gridColor: theme.gridColor,
				axisColor: theme.axisColor,
				textColor: theme.textColor,
				legendBackground: theme.legendBackground,
				legendTextColor: theme.legendTextColor,
				tooltipBackground: theme.tooltipBackground,
				tooltipBorder: theme.tooltipBorder,
				tooltipTitleColor: theme.tooltipTitleColor,
				tooltipTextColor: theme.tooltipTextColor,
				hoverLineColor: 'rgba(255,255,255,0.35)',
			}, options);

			this.series = [];
			this.xLabels = [];
			this.hoverIndex = null;
			this.hoverX = 0;
			this.width = 0;
			this.height = 0;

			this.onMouseMove = this.onMouseMove.bind(this);
			this.onMouseLeave = this.onMouseLeave.bind(this);
			this.onResize = this.onResize.bind(this);

			this.canvas.addEventListener('mousemove', this.onMouseMove);
			this.canvas.addEventListener('mouseleave', this.onMouseLeave);
			globalScope.addEventListener('resize', this.onResize);
			if (typeof ResizeObserver !== 'undefined') {
				this.resizeObserver = new ResizeObserver(this.onResize);
				this.resizeObserver.observe(this.canvas);
			}

			this.resize();
		}

		resolveThemeColors() {
			const styles = globalScope.getComputedStyle ? globalScope.getComputedStyle(document.documentElement) : null;
			const pick = function (name, fallback) {
				if (!styles) return fallback;
				const value = styles.getPropertyValue(name).trim();
				return value || fallback;
			};
			return {
				gridColor: pick('--border', 'rgba(148, 163, 184, 0.20)'),
				axisColor: pick('--border-hover', pick('--border', 'rgba(148, 163, 184, 0.42)')),
				textColor: pick('--text-secondary', '#64748b'),
				legendBackground: pick('--surface', 'rgba(15, 23, 42, 0.72)'),
				legendTextColor: pick('--text-primary', '#0f172a'),
				tooltipBackground: pick('--surface', '#0f172a'),
				tooltipBorder: pick('--border', 'rgba(148, 163, 184, 0.30)'),
				tooltipTitleColor: pick('--primary', '#3b82f6'),
				tooltipTextColor: pick('--text-primary', '#0f172a'),
			};
		}

		destroy() {
			this.canvas.removeEventListener('mousemove', this.onMouseMove);
			this.canvas.removeEventListener('mouseleave', this.onMouseLeave);
			globalScope.removeEventListener('resize', this.onResize);
			if (this.resizeObserver) {
				this.resizeObserver.disconnect();
			}
		}

		onResize() {
			this.resize();
		}

		onMouseLeave() {
			this.hoverIndex = null;
			this.draw();
		}

		resize() {
			const rect = this.canvas.getBoundingClientRect();
			const cssWidth = Math.max(280, Math.round(rect.width || this.canvas.width || 640));
			const cssHeight = Math.max(180, Math.round(rect.height || this.canvas.height || 320));
			const pixelRatio = globalScope.devicePixelRatio || 1;

			this.width = cssWidth;
			this.height = cssHeight;
			this.canvas.width = Math.round(cssWidth * pixelRatio);
			this.canvas.height = Math.round(cssHeight * pixelRatio);
			this.ctx.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
			this.draw();
		}

		setData(series, xLabels = []) {
			this.series = (Array.isArray(series) ? series : []).map((item) => ({
				key: item.key || '',
				label: item.label || 'Series',
				color: item.color || '#60a5fa',
				axis: item.axis === 'right' ? 'right' : 'left',
				unit: item.unit || '',
				format: item.format || '',
				maxHint: Number(item.maxHint || 0),
				decimals: Number.isInteger(item.decimals) ? item.decimals : 2,
				values: Array.isArray(item.values) ? item.values.map((value) => Number(value || 0)) : [],
			}));
			this.xLabels = Array.isArray(xLabels) ? xLabels : [];
			this.draw();
		}

		getPlotRect() {
			const padding = this.options.padding;
			return {
				x: padding.left,
				y: padding.top,
				w: this.width - padding.left - padding.right,
				h: this.height - padding.top - padding.bottom,
			};
		}

		getMaxForAxis(axis) {
			const relevant = this.series.filter((item) => item.axis === axis);
			if (!relevant.length) return 1;
			let max = 1;
			relevant.forEach((item) => {
				const localMax = Math.max(...(item.values.length ? item.values : [0]), item.maxHint || 0, 1);
				if (localMax > max) max = localMax;
			});
			return max;
		}

		xForIndex(index, pointCount, plot) {
			if (pointCount <= 1) return plot.x;
			return plot.x + (index / (pointCount - 1)) * plot.w;
		}

		yForValue(value, axisMax, plot) {
			const clamped = Math.max(0, Number(value || 0));
			return plot.y + plot.h - ((clamped / axisMax) * plot.h);
		}

		formatValue(value, format, unit, decimals) {
			const number = Number(value || 0);
			if (globalScope.UFormat) {
				if (format === 'bytes' && globalScope.UFormat.formatBytes) return globalScope.UFormat.formatBytes(number);
				if (format === 'disk-bytes' && globalScope.UFormat.formatDiskBytes) return globalScope.UFormat.formatDiskBytes(number);
				if (format === 'count' && globalScope.UFormat.formatCount) return globalScope.UFormat.formatCount(number);
				if (format === 'duration-ms' && globalScope.UFormat.formatDurationMs) return globalScope.UFormat.formatDurationMs(number);
			}
			return `${number.toFixed(decimals)}${unit || ''}`;
		}

		drawGridAndAxes(plot, maxLeft, maxRight) {
			const ctx = this.ctx;
			ctx.strokeStyle = this.options.gridColor;
			ctx.lineWidth = 1;

			for (let index = 0; index <= 4; index += 1) {
				const y = plot.y + (plot.h / 4) * index;
				ctx.beginPath();
				ctx.moveTo(plot.x, y);
				ctx.lineTo(plot.x + plot.w, y);
				ctx.stroke();

				const leftValue = maxLeft - ((maxLeft / 4) * index);
				const leftTick = this.formatValue(leftValue, this.options.yAxisLeftFormat, '', 1);
				ctx.fillStyle = this.options.textColor;
				ctx.font = '11px Inter, sans-serif';
				const leftWidth = ctx.measureText(leftTick).width;
				ctx.fillText(leftTick, Math.max(4, plot.x - leftWidth - 8), y + 4);

				if (this.options.yAxisRightLabel) {
					const rightValue = maxRight - ((maxRight / 4) * index);
					const rightTick = this.formatValue(rightValue, this.options.yAxisRightFormat, '', 1);
					ctx.fillText(rightTick, plot.x + plot.w + 8, y + 4);
				}
			}

			ctx.strokeStyle = this.options.axisColor;
			ctx.beginPath();
			ctx.moveTo(plot.x, plot.y);
			ctx.lineTo(plot.x, plot.y + plot.h);
			ctx.lineTo(plot.x + plot.w, plot.y + plot.h);
			ctx.stroke();

			ctx.fillStyle = this.options.textColor;
			ctx.font = '12px Inter, sans-serif';
			ctx.save();
			ctx.translate(14, plot.y + (plot.h / 2));
			ctx.rotate(-Math.PI / 2);
			ctx.textAlign = 'center';
			ctx.textBaseline = 'middle';
			ctx.fillText(this.options.yAxisLeftLabel, 0, 0);
			ctx.restore();

			if (this.options.yAxisRightLabel) {
				ctx.save();
				ctx.translate(this.width - 14, plot.y + (plot.h / 2));
				ctx.rotate(Math.PI / 2);
				ctx.textAlign = 'center';
				ctx.textBaseline = 'middle';
				ctx.fillText(this.options.yAxisRightLabel, 0, 0);
				ctx.restore();
			}

			const xLabel = this.options.xAxisLabel;
			const xLabelWidth = ctx.measureText(xLabel).width;
			ctx.fillText(xLabel, plot.x + plot.w - xLabelWidth, this.height - 10);
		}

		drawSeries(plot, maxLeft, maxRight) {
			const ctx = this.ctx;
			const pointCount = Math.max(...this.series.map((item) => item.values.length), 0);
			if (pointCount <= 0) return;

			this.series.forEach((item) => {
				if (!item.values.length) return;
				const axisMax = item.axis === 'right' ? maxRight : maxLeft;
				ctx.beginPath();
				item.values.forEach((value, index) => {
					const x = this.xForIndex(index, item.values.length, plot);
					const y = this.yForValue(value, axisMax, plot);
					if (index === 0) ctx.moveTo(x, y); else ctx.lineTo(x, y);
				});
				ctx.strokeStyle = item.color;
				ctx.lineWidth = 2;
				ctx.stroke();
			});

			if (this.series.length > 1) {
				ctx.fillStyle = this.options.legendBackground;
				ctx.fillRect(plot.x + 4, plot.y + 4, Math.min(plot.w - 8, 320), 24);
				let offsetX = plot.x + 12;
				this.series.forEach((item) => {
					ctx.fillStyle = item.color;
					ctx.fillRect(offsetX, plot.y + 13, 14, 3);
					offsetX += 20;
					ctx.fillStyle = this.options.legendTextColor;
					ctx.font = '11px Inter, sans-serif';
					ctx.fillText(item.label, offsetX, plot.y + 17);
					offsetX += ctx.measureText(item.label).width + 18;
				});
			}
		}

		drawHover(plot, maxLeft, maxRight) {
			if (this.hoverIndex == null) return;
			const ctx = this.ctx;
			const pointCount = Math.max(...this.series.map((item) => item.values.length), 0);
			if (pointCount <= 0) return;

			const index = Math.max(0, Math.min(pointCount - 1, this.hoverIndex));
			const x = this.xForIndex(index, pointCount, plot);

			ctx.strokeStyle = this.options.hoverLineColor;
			ctx.lineWidth = 1;
			ctx.beginPath();
			ctx.moveTo(x, plot.y);
			ctx.lineTo(x, plot.y + plot.h);
			ctx.stroke();

			const lines = [];
			lines.push(this.xLabels[index] || `Sample ${index + 1}`);

			this.series.forEach((item) => {
				if (index >= item.values.length) return;
				const axisMax = item.axis === 'right' ? maxRight : maxLeft;
				const value = item.values[index] ?? 0;
				const y = this.yForValue(value, axisMax, plot);
				ctx.fillStyle = item.color;
				ctx.beginPath();
				ctx.arc(x, y, 3.5, 0, Math.PI * 2);
				ctx.fill();
				lines.push(`${item.label}: ${this.formatValue(value, item.format || (item.axis === 'right' ? this.options.yAxisRightFormat : this.options.yAxisLeftFormat), item.unit || '', item.decimals)}`);
			});

			ctx.font = '11px Inter, sans-serif';
			const padding = 8;
			const lineHeight = 14;
			const tooltipWidth = Math.max(...lines.map((line) => ctx.measureText(line).width)) + padding * 2;
			const tooltipHeight = lines.length * lineHeight + padding * 2 - 2;
			let tooltipX = this.hoverX + 12;
			let tooltipY = plot.y + 8;

			if (tooltipX + tooltipWidth > this.width - 4) tooltipX = this.hoverX - tooltipWidth - 12;
			if (tooltipX < 4) tooltipX = 4;
			if (tooltipY + tooltipHeight > this.height - 4) tooltipY = this.height - tooltipHeight - 4;

			ctx.fillStyle = this.options.tooltipBackground;
			ctx.fillRect(tooltipX, tooltipY, tooltipWidth, tooltipHeight);
			ctx.strokeStyle = this.options.tooltipBorder;
			ctx.strokeRect(tooltipX, tooltipY, tooltipWidth, tooltipHeight);

			lines.forEach((line, lineIndex) => {
				ctx.fillStyle = lineIndex === 0 ? this.options.tooltipTitleColor : this.options.tooltipTextColor;
				ctx.fillText(line, tooltipX + padding, tooltipY + padding + 10 + lineIndex * lineHeight);
			});
		}

		onMouseMove(event) {
			const rect = this.canvas.getBoundingClientRect();
			const x = event.clientX - rect.left;
			this.hoverX = x;

			const plot = this.getPlotRect();
			const pointCount = Math.max(...this.series.map((item) => item.values.length), 0);
			if (pointCount <= 0) {
				this.hoverIndex = null;
				this.draw();
				return;
			}

			const relative = Math.max(0, Math.min(plot.w, x - plot.x));
			this.hoverIndex = pointCount <= 1 ? 0 : Math.round((relative / plot.w) * (pointCount - 1));
			this.draw();
		}

		draw() {
			const ctx = this.ctx;
			ctx.clearRect(0, 0, this.width, this.height);

			const plot = this.getPlotRect();
			if (plot.w <= 0 || plot.h <= 0) return;
			const maxLeft = this.getMaxForAxis('left');
			const maxRight = this.getMaxForAxis('right');

			this.drawGridAndAxes(plot, maxLeft, maxRight);
			this.drawSeries(plot, maxLeft, maxRight);
			this.drawHover(plot, maxLeft, maxRight);
		}
	}

	return TimeSeriesChart;
}));