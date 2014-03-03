
function get_modulename()
{
	var position = $('#module_position').val();
	$.ajax({
		type: "GET",
		url: BASE_URL + "admin/eazycrudpyrocms/sub_module/get_modules_name",
		data: "position="+position, // post value for get value
		success: function(msg){
		$("#boxModulename").html(msg);
		}
	});
}