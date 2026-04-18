// common code for gauges components

window.GaugeComponents = window.GaugeComponents || {};

Object.assign(window.GaugeComponents, { // as a namespace

    // utility functions
    clampValue: function(value, min, max) {
        return Math.min(max, Math.max(min, value));
    },

    pickEntryFromRange: function(ranges, value) {
        if (!Array.isArray(ranges)) return null;
        for (const entry of ranges) {
            if (value >= entry.from && value <= entry.to) return entry;
        }
        return null;
    },
    
    /**
     * Creates an SVG element with namespace
     */
    createSVGElement: function(tagName, attributes = {}) {
        const element = document.createElementNS('http://www.w3.org/2000/svg', tagName);
        Object.entries(attributes).forEach(([key, value]) => {
            element.setAttribute(key, value);
        });
        return element;
    },

    /**
     * Gets CSS custom property value from computed styles
     */
    getCSSVar: function(varName) {
        return getComputedStyle(document.documentElement).getPropertyValue(varName).trim();
    },

    resolveColor: function(colorSpec, value, pct) {
        if (Array.isArray(colorSpec)) {
            const match = this.pickEntryFromRange(colorSpec, value);
            if (match && match.color) return match.color;
        }
        if (typeof colorSpec === 'string' && colorSpec !== '') return colorSpec;
        if (pct < 60) return this.getCSSVar('--success') || '#10b981';
        if (pct < 85) return this.getCSSVar('--warning') || '#f59e0b';
        return this.getCSSVar('--error') || '#ef4444';
    },

    formatValue: function(value, precision, suffix) {
        const numericValue = Number(value);
        const normalizedPrecision = Number.isFinite(precision) ? precision : 1;
        if (!Number.isFinite(numericValue)) return '--';
        return numericValue.toFixed(normalizedPrecision) + (suffix || '');
    },

    gaugeArcPoint: function(pct, radius = 50) {
        const angle = Math.PI - (pct / 100) * Math.PI;
        return {
            x: 60 + radius * Math.cos(angle),
            y: 60 - radius * Math.sin(angle),
        };
    },

    updateWatermark: function(prefix, pct) {
        const now = Date.now();
        this._watermarks = this._watermarks || {};
        let watermark = this._watermarks[prefix];
        const resetWindow = 10 * 60 * 1000;
        if (!watermark || (now - watermark.resetTs) > resetWindow) {
            watermark = { lo: pct, hi: pct, resetTs: now };
            this._watermarks[prefix] = watermark;
        } else {
            if (pct < watermark.lo) watermark.lo = pct;
            if (pct > watermark.hi) watermark.hi = pct;
        }
        return watermark;
    },

    renderWatermarkTick: function(lineId, pct) {
        const line = document.getElementById(lineId);
        if (!line) return;
        if (pct == null) {
            line.setAttribute('opacity', '0');
            return;
        }
        const outer = this.gaugeArcPoint(pct, 53);
        const inner = this.gaugeArcPoint(pct, 43);
        line.setAttribute('x1', outer.x.toFixed(1));
        line.setAttribute('y1', outer.y.toFixed(1));
        line.setAttribute('x2', inner.x.toFixed(1));
        line.setAttribute('y2', inner.y.toFixed(1));
        line.setAttribute('opacity', '0.7');
    },

    updateArcGauge: function(options) {
        const value = Number(options.value);
        const max = Number(options.max || 100);
        const normalizedValue = Number.isFinite(value) ? value : 0;
        const pct = this.clampValue(max === 0 ? 0 : (normalizedValue / max) * 100, 0, 100);
        const arcLength = (pct / 100) * 157.08;
        const arc = document.getElementById(options.arcId);
        const text = document.getElementById(options.textId);
        const meta = options.metaId ? document.getElementById(options.metaId) : null;
        if (arc) {
            arc.setAttribute('stroke-dasharray', arcLength.toFixed(1) + ' 157.08');
            arc.setAttribute('stroke', this.resolveColor(options.color, normalizedValue, pct));
        }
        if (text) {
            text.textContent = this.formatValue(normalizedValue, options.precision, options.suffix);
        }
        if (meta && options.meta != null) {
            meta.textContent = options.meta;
        }
        if (options.watermarkPrefix) {
            const watermark = this.updateWatermark(options.watermarkPrefix, pct);
            this.renderWatermarkTick(options.watermarkPrefix + 'WmLo', watermark.lo);
            this.renderWatermarkTick(options.watermarkPrefix + 'WmHi', watermark.hi);
        }
    }

});