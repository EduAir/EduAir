
$(document).ready(function() {	
	var nm = Math.floor(Math.random()*5);
	var fs = ['joints', 'spiders', 'tube-worms', 'rings', 'honeycomb'];
  	var js = document.createElement('script');
  		js.type = "text/javascript";
  		js.src = 'http://quietless.com/kitchen/wp-content/themes/quietless-v2/js/'+ fs[nm] + '.js';
  document.body.appendChild(js);
});