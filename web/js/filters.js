app.filter('brDef', function() {
  return function(input) {
    input = input || '';
    return input.replace(/,/g, '<br/>');
  };
}).filter('capitalize', function() {
  return function(input, scope) {
    return input ? input.substring(0, 1).toUpperCase() + input.substring(1) : '';
  }
}).filter('split', function() {
  return function(input, delimiter) {
    var delimiter = delimiter || ',';
    return input.split(delimiter);
  }
}).filter('mySort', function() {
  return function(input) {
    return input.sort(function sortMultiDimensional(a, b) {
      return((a.length < b.length) ? -1 : ((a.length > b.length) ? 1 : 0));
    });
  }
});
