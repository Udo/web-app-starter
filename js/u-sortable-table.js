(function (root, factory) {
	if (typeof define === 'function' && define.amd) {
		define([], factory);
	} else if (typeof module === 'object' && module.exports) {
		module.exports = factory();
	} else {
		root.USortableTable = factory();
	}
}(typeof self !== 'undefined' ? self : this, function () {
	'use strict';
	const globalScope = typeof globalThis !== 'undefined' ? globalThis : (typeof window !== 'undefined' ? window : this);

	function getTable(target) {
		if (!target) return null;
		if (typeof target === 'string') return document.getElementById(target);
		return target.nodeType === 1 ? target : null;
	}

	function getStorageKey(table, options) {
		if (options && options.storageKey) return options.storageKey;
		if (table && table.id) return `u-sortable-table.${table.id}`;
		return null;
	}

	function loadSort(table, options) {
		const storageKey = getStorageKey(table, options);
		if (!storageKey || !globalScope.localStorage) return null;
		try {
			const raw = globalScope.localStorage.getItem(storageKey);
			if (!raw) return null;
			const parsed = JSON.parse(raw);
			if (!parsed || !Number.isInteger(parsed.column) || (parsed.direction !== 'asc' && parsed.direction !== 'desc')) {
				return null;
			}
			return parsed;
		} catch (error) {
			return null;
		}
	}

	function saveSort(table, column, direction, options) {
		const storageKey = getStorageKey(table, options);
		if (!storageKey || !globalScope.localStorage) return;
		try {
			globalScope.localStorage.setItem(storageKey, JSON.stringify({ column, direction }));
		} catch (error) {
			// Ignore storage errors.
		}
	}

	function parseSortValue(text) {
		const normalized = String(text || '').trim();
		if (!normalized) return { type: 'text', value: '' };

		if (globalScope.UFormat && typeof globalScope.UFormat.parseUnitNumber === 'function') {
			const unitValue = globalScope.UFormat.parseUnitNumber(normalized);
			if (unitValue != null) {
				return { type: 'number', value: unitValue };
			}
		}

		const timestamp = Date.parse(normalized);
		if (!Number.isNaN(timestamp)) {
			return { type: 'number', value: timestamp };
		}

		return { type: 'text', value: normalized.toLowerCase() };
	}

	function setHeaderState(table, columnIndex, direction) {
		Array.from(table.querySelectorAll('thead th')).forEach((header, index) => {
			header.classList.remove('sorted-asc', 'sorted-desc');
			header.setAttribute('aria-sort', 'none');
			if (index === columnIndex) {
				header.classList.add(direction === 'asc' ? 'sorted-asc' : 'sorted-desc');
				header.setAttribute('aria-sort', direction === 'asc' ? 'ascending' : 'descending');
			}
		});
	}

	function sortTable(table, columnIndex, direction, options, persist) {
		const tbody = table.querySelector('tbody');
		if (!tbody) return;

		const rows = Array.from(tbody.querySelectorAll('tr')).filter((row) => row.children.length > 0 && !row.hasAttribute('data-empty-row'));
		if (!rows.length) return;

		rows.sort((rowA, rowB) => {
			const cellA = rowA.children[columnIndex];
			const cellB = rowB.children[columnIndex];
			const rawA = cellA ? (cellA.getAttribute('data-sort-value') || cellA.textContent) : '';
			const rawB = cellB ? (cellB.getAttribute('data-sort-value') || cellB.textContent) : '';
			const valueA = parseSortValue(rawA);
			const valueB = parseSortValue(rawB);

			let comparison = 0;
			if (valueA.type === 'number' && valueB.type === 'number') {
				comparison = valueA.value - valueB.value;
			} else {
				comparison = String(valueA.value).localeCompare(String(valueB.value), undefined, {
					numeric: true,
					sensitivity: 'base',
				});
			}
			return direction === 'asc' ? comparison : -comparison;
		});

		rows.forEach((row) => tbody.appendChild(row));
		setHeaderState(table, columnIndex, direction);
		if (persist !== false) {
			saveSort(table, columnIndex, direction, options);
		}
	}

	function getInitialSort(table, options) {
		const saved = loadSort(table, options);
		if (saved) return saved;
		if (options && options.initialSort) {
			return {
				column: Number(options.initialSort.column || 0),
				direction: options.initialSort.direction === 'desc' ? 'desc' : 'asc',
			};
		}
		return null;
	}

	function attachHeader(header, table, columnIndex, options) {
		if (header.getAttribute('data-sortable') === 'false') return;
		header.classList.add('sortable');
		header.tabIndex = 0;
		header.addEventListener('click', function () {
			const current = loadSort(table, options);
			const nextDirection = current && current.column === columnIndex && current.direction === 'asc' ? 'desc' : 'asc';
			sortTable(table, columnIndex, nextDirection, options, true);
		});
		header.addEventListener('keydown', function (event) {
			if (event.key !== 'Enter' && event.key !== ' ') return;
			event.preventDefault();
			header.click();
		});
	}

	function init(target, options = {}) {
		const table = getTable(target);
		if (!table || table.dataset.sortableTableInit === '1') return table;

		Array.from(table.querySelectorAll('thead th')).forEach((header, index) => attachHeader(header, table, index, options));
		table.dataset.sortableTableInit = '1';

		const initialSort = getInitialSort(table, options);
		if (initialSort) {
			sortTable(table, initialSort.column, initialSort.direction, options, false);
		}

		return table;
	}

	function initAll(selector = '.u-sortable-table') {
		Array.from(document.querySelectorAll(selector)).forEach((table) => init(table));
	}

	return {
		init,
		initAll,
		parseSortValue,
		sortTable,
	};
}));