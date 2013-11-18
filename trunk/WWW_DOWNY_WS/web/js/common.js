$(function() {
	// ·ÖÏí
	$(".links ul li a.share").pageShare();
});

var DOWNY_SHARE_URL_PREFIX = "http://share.downy.ws/index.php?a=share&m=index"
	+ "&url=" + encodeURIComponent(window.location.href)
	+ "&desc=" + encodeURIComponent(document.title);
function DOWNY_SHARE_URL(site){
	return DOWNY_SHARE_URL_PREFIX + "&tosite=" + site;
}

$.fn.extend({
	pageShare: function(){
		$(this).each(function(){
			var href = DOWNY_SHARE_URL($(this).data("site"));
			$(this).attr("href", href);
		});
	}
});
