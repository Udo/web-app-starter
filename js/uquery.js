$ = document.querySelectorAll;

$.each = function(selector, callback) {
    if (typeof selector === 'string') {
        selector = document.querySelectorAll(selector);
    }
    for (let i = 0; i < selector.length; i++) {
        callback(selector[i], i);
    }
}

$.post = function(url, data, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
        }
    };
    xhr.send(JSON.stringify(data));
}

$.get = function(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
        }
    };
    xhr.send();
}

$.load = function(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.body.innerHTML += xhr.responseText;
            if (callback) callback();
        }
    };
    xhr.send();
}

$.ajax = function(options) {
    var xhr = new XMLHttpRequest();
    xhr.open(options.method || 'GET', options.url, true);
    if (options.headers) {
        for (var key in options.headers) {
            xhr.setRequestHeader(key, options.headers[key]);
        }
    }
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status >= 200 && xhr.status < 300) {
                options.success && options.success(JSON.parse(xhr.responseText));
            } else {
                options.error && options.error(xhr);
            }
        }
    };
    xhr.send(options.data ? JSON.stringify(options.data) : null);
}

$.ready = function(callback) {
    if (document.readyState !== 'loading') {
        callback();
    } else {
        document.addEventListener('DOMContentLoaded', callback);
    }
}

// NodeList prototypes for common jQuery-like methods, monad style

NodeList.prototype.on = function(event, handler) {
    this.forEach(function(el) {
        el.addEventListener(event, handler);
    });
    return this;
}

NodeList.prototype.off = function(event, handler) {
    this.forEach(function(el) {
        el.removeEventListener(event, handler);
    });
    return this;
}

NodeList.prototype.hide = function() {
    return this.css({ display: 'none' });
}

NodeList.prototype.show = function() {
    return this.css({ display: ''  });
}

NodeList.prototype.toggle = function() {
    this.forEach(function(el) {
        el.style.display = (el.style.display === 'none' || el.style.display === '') ? '' : 'none';
    });
    return this;
}

NodeList.prototype.addClass = function(classNameOrList) {
    var classList = Array.isArray(classNameOrList) ? classNameOrList : [classNameOrList];
    this.forEach(function(el) {
        classList.forEach(function(className) {
            el.classList.add(className);
        });
    });
    return this;
}

NodeList.prototype.removeClass = function(classNameOrList) {
    var classList = Array.isArray(classNameOrList) ? classNameOrList : [classNameOrList];
    this.forEach(function(el) {
        classList.forEach(function(className) {
            el.classList.remove(className);
        });
    });
    return this;
}

NodeList.prototype.css = function(styles, optOrValue = false) {
    this.forEach(function(el) {
        if(optOrValue !== false) {
            el.style[optOrValue] = styles;
            return;
        } else for (var key in styles) {
            el.style[key] = styles[key];
        }
    });
    return this;
}

