$(function(){
	$("a.confirm").click(function(){
		switch($(this).data("fun")){
			case "siteType": siteType(this); break;
			case "accessPassword": accessPassword(this); break;
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

function accessPassword(obj){
	var data = {
		password: $("input[name='password']").val(),
		remember: $("input[name='remember']").is(":checked") ? 1 : 0,
		app_url: $("input[name='app_url']").val(),
		callback: $("input[name='callback']").val()
	};
	if(data.password == ""){
		$(".content .tips .msg").html("Plz input password.");
	}else{
		$.ajax({type: "POST", url: "/index.php?a=access&m=set&t=ajax", data: data, async: false, dataType: 'JSON', success: function(response){
			if(typeof(response.error) != "undefined"){
				$(".content .tips .msg").html(response.error.msg);
			}else if(typeof(response.url) != "undefined"){
				window.location.href = response.url;
			}else{
				$(".content .tips .msg").html("Ajax response data error.");
			}
		}, error: function(){
			$(".content .tips .msg").html("Ajax response error.");
		}});
	}
}
