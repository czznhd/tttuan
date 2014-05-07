/** * @copyright (C)2011 Cenwor Inc. * @author Moyo <dev@uuland.org> * @package js * @name product.detail.js * @date 2013-11-07 19:52:54 */ $(document).ready(function(){
	resizeProductAreaImage();
});

function resizeProductAreaImage()
{
	var obj = $('#product_detail_area');
	var maxWidth = obj.width() - 18;
	$.each($(obj).find('img'), function(i, n){
		var opImg = this;
		var nwImg = new Image();
		nwImg.src = opImg.src;
		$(nwImg).bind('load', function(){
			var iWidth = this.width;
			var iHeight = this.height;
			if (iWidth > maxWidth)
			{
				opImg.width = maxWidth;
				opImg.height = (maxWidth*iHeight)/iWidth;

				$(opImg).wrap('<a href="'+opImg.src+'" target="_blank" title="点击查看大图片"></a>');
			}
		});
	});
}
