$(function(){
	$("a.confirm").click(function(){
		var site_type = $("input[name='site_type']:checked").val();
		var site_type_default = $("input[name='site_type_default']").is(":checked");
		var href = $(this).data("app_url") + "index.php?a=cookie&m=set&s=PC&expire=max&key=SITE_TYPE&val=" + site_type + "&callback=" + $(this).data("callback");
		if(site_type_default){
			href = "/index.php?a=cookie&m=set&s=PC&expire=max&key=SITE_TYPE_DEFAULT&val=" + site_type + "&callback=" + encodeURIComponent(href);
		}
		$(this).attr("href", href);
	});
});
