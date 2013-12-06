$(function(){
	$(".visitpassword .password a").click(function(){
		var data = {
			accesspassword: $("input[name='accesspassword']").val(),
			remember: $("input[name='remember']").val(),
			app_url: $("input[name='app_url']").val(),
			callback: $("input[name='callback']").val()
		};
		if(data.accesspassword == ""){
			$(".visitpassword .panel .msg").html("Plz input access password.");
		}else{
			$.ajax({type: "POST", url: "/index.php?a=set&m=accesspassword&t=ajax", data: data, async: false, dataType: 'JSON', success: function(response){
				if(typeof(response.error) != "undefined"){
					$(".visitpassword .panel .msg").html(response.error.msg);
				}else if(typeof(response.url) != "undefined"){
					window.location.href = response.url;
				}else{
					$(".visitpassword .panel .msg").html("Ajax response data error.");
				}
			}, error: function(){
				$(".visitpassword .panel .msg").html("Ajax response error.");
			}});
		}
	});
	$(".visitpassword .remember span").click(function(){
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
