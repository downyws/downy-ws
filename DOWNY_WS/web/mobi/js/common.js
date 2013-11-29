$(function(){
	$("a.confirm").click(function(){
		switch(data("fun")){
			case "siteType": siteType(); break;
			case "visitPassword": visitPassword(); break;
		}
	});
});

function siteType(obj){
	var site_type = $("input[name='site_type']:checked").val();
	var site_type_default = $("input[name='site_type_default']").is(":checked");
	var href = $(obj).data("app_url") + "index.php?a=cookie&m=set&s=PC&expire=max&key=SITE_TYPE&val=" + site_type + "&callback=" + $(obj).data("callback");
	if(site_type_default){
		href = "/index.php?a=cookie&m=set&s=PC&expire=max&key=SITE_TYPE_DEFAULT&val=" + site_type + "&callback=" + encodeURIComponent(href);
	}
	$(obj).attr("href", href);
}

function visitPassword(obj){
	var visit_password = $("input[name='visitpassword']").val();
	var href = $(obj).data("app_url") + "index.php?a=cookie&m=setvp&s=PC&visit_password=" + visit_password + "&callback=" + $(obj).data("callback");
	$(obj).attr("href", href);
}
