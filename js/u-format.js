(function (root, factory) {
	if (typeof define === 'function' && define.amd) {
		define([], factory);
	} else if (typeof module === 'object' && module.exports) {
		module.exports = factory();
	} else {
		root.UFormat = factory();
	}
}(typeof self !== 'undefined' ? self : this, function () {
	'use strict';

	const BYTE_UNITS = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

	function scaleBytes(bytes) {
		let value = Number(bytes || 0);
		let unitIndex = 0;
		while (Math.abs(value) >= 1024 && unitIndex < BYTE_UNITS.length - 1) {
			value /= 1024;
			unitIndex += 1;
		}
		return { value, unitIndex };
	}

	function formatBytes(bytes) {
		if (bytes == null || bytes === '') return '--';
		const scaled = scaleBytes(bytes);
		const decimals = scaled.unitIndex === 0 ? 0 : 1;
		return `${scaled.value.toFixed(decimals)} ${BYTE_UNITS[scaled.unitIndex]}`;
	}

	function formatDiskBytes(bytes) {
		if (bytes == null || bytes === '') return '--';
		const scaled = scaleBytes(bytes);
		const decimals = scaled.unitIndex >= 4 ? 2 : scaled.unitIndex >= 1 ? 1 : 0;
		return `${scaled.value.toFixed(decimals)} ${BYTE_UNITS[scaled.unitIndex]}`;
	}

	function formatCount(value) {
		const number = Number(value);
		if (!Number.isFinite(number)) return '--';
		return number.toLocaleString();
	}

	function formatDurationMs(value) {
		const number = Number(value);
		if (!Number.isFinite(number)) return '--';
		if (Math.abs(number) >= 1000) {
			return `${(number / 1000).toFixed(number >= 10000 ? 0 : 1)} s`;
		}
		return `${number.toFixed(number >= 100 ? 0 : 1)} ms`;
	}

	function parseUnitNumber(text) {
		const normalized = String(text || '').trim().toLowerCase().replace(/,/g, '');
		if (!normalized) return null;

		const pure = normalized.match(/^([-+]?\d*\.?\d+)$/);
		if (pure) {
			return Number(pure[1]);
		}

		const withUnit = normalized.match(/^([-+]?\d*\.?\d+)\s*([a-z%][a-z0-9\/_-]*)$/);
		if (!withUnit) {
			return null;
		}

		const value = Number(withUnit[1]);
		let unit = withUnit[2];
		if (!Number.isFinite(value)) {
			return null;
		}

		if (unit.endsWith('/s')) {
			unit = unit.slice(0, -2);
		}

		const bytes = {
			b: 1,
			kb: 1024,
			kib: 1024,
			mb: 1024 ** 2,
			mib: 1024 ** 2,
			gb: 1024 ** 3,
			gib: 1024 ** 3,
			tb: 1024 ** 4,
			tib: 1024 ** 4,
			pb: 1024 ** 5,
			pib: 1024 ** 5,
		};

		if (Object.prototype.hasOwnProperty.call(bytes, unit)) {
			return value * bytes[unit];
		}

		const durations = {
			ms: 0.001,
			s: 1,
			sec: 1,
			secs: 1,
			second: 1,
			seconds: 1,
			m: 60,
			min: 60,
			mins: 60,
			minute: 60,
			minutes: 60,
			h: 3600,
			hr: 3600,
			hrs: 3600,
			hour: 3600,
			hours: 3600,
			d: 86400,
			day: 86400,
			days: 86400,
		};

		if (Object.prototype.hasOwnProperty.call(durations, unit)) {
			return value * durations[unit];
		}

		if (unit === '%') {
			return value;
		}

		return null;
	}

	return {
		formatBytes,
		formatCount,
		formatDiskBytes,
		formatDurationMs,
		parseUnitNumber,
		scaleBytes,
	};
}));