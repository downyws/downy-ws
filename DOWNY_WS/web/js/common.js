$(function(){
	$(".access .password a").click(function(){
		var data = {
			password: $("input[name='password']").val(),
			remember: $("input[name='remember']").val(),
			app_url: $("input[name='app_url']").val(),
			callback: $("input[name='callback']").val()
		};
		if(data.password == ""){
			$(".access .panel .msg").html("Plz input password.");
		}else{
			$.ajax({type: "POST", url: "/index.php?a=access&m=set&t=ajax", data: data, async: false, dataType: 'JSON', success: function(response){
				if(typeof(response.error) != "undefined"){
					$(".access .panel .msg").html(response.error.msg);
				}else if(typeof(response.url) != "undefined"){
					window.location.href = response.url;
				}else{
					$(".access .panel .msg").html("Ajax response data error.");
				}
			}, error: function(){
				$(".access .panel .msg").html("Ajax response error.");
			}});
		}
	});
	$(".access .remember span").click(function(){
		var checkbox = $(this).find("a");
		if(checkbox.hasClass("checked")){
			checkbox.removeClass("checked");
			$("input[name='remember']").val("0");
		}else{
			checkbox.addClass("checked");
			$("input[name='remember']").val("1");
		}
	});
});
