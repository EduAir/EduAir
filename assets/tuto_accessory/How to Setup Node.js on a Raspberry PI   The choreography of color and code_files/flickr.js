$(document).ready(init);

var id = '19382137@N06';
var key = '01f8c98a98c4a69a68a7563efe7536bb';
var num = 8; // number of pictures to request //
var req = 'http://api.flickr.com/services/rest/?&method=flickr.people.getPublicPhotos&api_key='+key+'&user_id='+id+'&per_page='+num+'&format=json&jsoncallback=?';
function init()
{	
	$.getJSON(req, function(result){onFlickrDataReceived(result);});	
}

function onFlickrDataReceived(data)
{
	if (data.stat=='ok'){
	    $.each(data.photos.photo, function(i, obj) {
			var tmb = 'http://farm' + obj.farm + '.static.flickr.com/' + obj.server + '/' + obj.id + '_' + obj.secret + '_s.jpg';
			var url = 'http://www.flickr.com/photos/braitsch/'+obj.id;
			var img = '<div class="flickr-image"><a href='+url+' target=_blank><img src='+tmb+'></a></div>';
	      	$('#flickr').append(img);
	    });		
	}	else{
		$('#flickr').append('Sorry, Flickr Feed Currently Unavailable');
	}
}

