var enable_debug = false;

K = {
  
  update : function(eid, params) {
    var element = $('#'+eid);
    if(!element) {
      console.error('element not found', eid, params);
    } else {
	  if(!params) params = {};
      var attr = {};
      $(element.get(0).attributes).each(function(k, v) {
        attr[v.name] = v.value;
      });
      var component = attr['data-com'];
      delete attr['data-com'];
      params.attr = attr;
      $.post('/:'+component, params, function(data) {
        element.replaceWith(data);
        if(enable_debug) console.log(component, params);
      });
    }
  },
  
}

