
app.service('dictionaryService', function() {
  var dictionary;

  addProduct = function(newObj) {
      productList.push(newObj);
  };
  getProducts = function(){
      return productList;
  };
});