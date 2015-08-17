/**
* jQuery LinkColor Plugin 1.0
*
* http://www.9lessons.info/
* 
* Copyright (c) 2012 Arun Kumar Sekar and Srinivas Tamada
*/

(function($){	
	$.fn.emotions = function(options){
		$this = $(this);
		var opts = $.extend({}, $.fn.emotions.defaults, options);
		return $this.each(function(i,obj){
			var o = $.meta ? $.extend({}, opts, $this.data()) : opts;					   	
			var x = $(obj);
			// Entites Encode 
			var encoded = [];
			for(i=0; i<o.s.length; i++){
				encoded[i] = String(o.s[i]).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
			}
			for(j=0; j<o.s.length; j++){
				var repls = x.html();
				if(repls.indexOf(o.s[j]) || repls.indexOf(encoded[j])){
					var imgr = o.a+o.b[j]+"."+o.c;			
					var rstr = "<img src='"+$('#emoticone_url').attr('rel')+imgr+"' border='0' />";	
					x.html(repls.replace(o.s[j],rstr));
					x.html(repls.replace(encoded[j],rstr));
				}
			}
		});
	}	
	// Defaults
	$.fn.emotions.defaults = {
		a : "smileys/",			// Emotions folder
		b : new Array("angel","colonthree","confused","cry","devil","frown","gasp","glasses","grin","grumpy","heart","kiki","kiss","pacman","smile","squint","sunglasses","tongue","unsure","upset","wink"),			// Emotions Type
		s : new Array("o:)",":3","o.O",":'(","3:)",":(",":O","8)",":D",">:(","<3","^_^",":*",":v",":)","-_-","8|",":p",":/",">:O",";)"),
		c : "gif"					// Emotions Image format
	};
})(jQuery);


// Notes
// a - icon folder
// b - emotions name array
// c - image format
// x - current selector
// d - type of selector
// o - options 
