/*
* Created By : Yoga Kurniawan
* Date       : 17/01/2012
* Email      : yogaygk@gmail.com
*/

function get_html_data(data_url, data_get, div_loading, div_result){
	loading(div_loading,true);
	setTimeout(function(){
			$.ajax({
			type: "GET",
			url: data_url,
			data: data_get,
			cache: false,
			dataType: 'html',
			success: function(html){
							$("#"+div_result).fadeOut('slow',function(){
									$("#"+div_result).html(html);
									$("#"+div_result).fadeIn();
								});
							loading(div_loading,false);
						}
		});
	},1100);	
}

function post_html_data(data_url,data_post,div_loading,div_result, append){
	loading(div_loading, true);
	setTimeout(function(){
		$.ajax({
			type: "POST",
			url: data_url,
			cache: false,
			data: data_post,
			success: function(html){
							$("#"+div_result).fadeOut('slow',function(){
								if(undefined == append){
									$("#"+div_result).html(html);
								}else{
									$("#"+div_result).append(html);
								}
								$("#"+div_result).fadeIn();
							});
							loading(div_loading, false);
						}
		})
	},1000);
}

function loading(div_container, is_show){
	if(is_show == true){
		$("#"+div_container).html('<img src="'+base_url+'/images/icon/loading.gif" />').fadeIn();
	}else{
		$("#"+div_container).html('<img src="'+base_url+'/images/icon/loading.gif" />').fadeOut();
	}
}
