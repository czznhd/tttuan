$(document).ready(function() {
	KE.init({
		id : 'content',
		afterCreate : function(id) {
			KE.util.focus(id);
		}
	});
	$('#contentType').bind('change', function() {
		if ($(this).val() == 'html')
		{
			KE.create('content');
		}
		else
		{
			KE.remove('content');
		}
	});
	$('#content').css('width', '500px').css('height', '120px');
});
