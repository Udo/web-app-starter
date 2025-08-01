// common code for gauges components

GaugeComponents = { // as a namespace

    // utility functions
    
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

    

}