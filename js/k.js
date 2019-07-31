K = {
  
  update : function(eid, params) {
    var element = $('#'+eid);
    if(!element) {
      console.error('element not found', eid, params);
    } else {
      var p = {
        call : JSON.stringify(params || {}),
      };
      $(element.get(0).attributes).each(function(k, v) {
        p[v.name] = v.value;
      });
      //console.log(p);
      $.post('index.php', p, function(data) {
        element.replaceWith(data);
      });
    }
  },
  
}