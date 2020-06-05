function dishes_tbl(count, book_tittle_list, book_price_list, book_img_list, book_id_list)
{

	var t = document.getElementsByClassName("catalog-list")[0];
	var s = "";
	var k = Math.ceil(count/4);
	var c = 1;
	var dt_str = book_tittle_list.split('~');
	var dp_str = book_price_list.split(' ');
	var di_str = book_img_list.split(' ');
	var book_id = book_id_list.split(';')

	for (var i = 0; i < k; i++)
	{
		s += "<tr>";
		for (var j = 0; j < 4; j++)
		{
			if (dt_str[c] != undefined)
			{
				s += '<td><div class="product-item"><img src="' + di_str[c] + '"><div class="product-list"><h3>' + dt_str[c] + '</h3><span class="price">' + dp_str[c] + ' руб. </span><a class="button" href="cart.php?page=cart&action=add&id=' + book_id[c] + '">В корзину</a></div></div></td>';
				c++;
            }
			
		}
		s += "</tr>";

	}
	
	t.innerHTML = s;
	
}

$(function () {
	/*удаление книги из БД*/
	$('.product-composition').on('click', '[act=del]', function (event) {
		var book_id = $(this).attr('book_id');
		//var approv = window.confirm("Удалить книгу?");
		$('body').append('<div id="overlay"></div><div id="magnify" style="text-align: center; padding: 20px 15px;"><b>Удалить книгу №' + book_id +'?</b><br><br><input type="button" name="yes" value=Да> <input type="button" name="no" value=Нет><i></i></div></div>');

		$('#magnify').css({
			left: ($(document).width() - $('#magnify').outerWidth()) / 2,
			top: ($(window).height() - $('#magnify').outerHeight()) / 4
		});
		$('#overlay, #magnify').fadeIn('fast');


		$('#magnify').on('click', '[name=yes]', function (event) {
			event.preventDefault();

			$.ajax({
				type: 'POST',
				url: "book_controller.php",
				data: {
					"del": book_id
				},
				success: function (html) {
					location.reload();
				}
			});

			$('#overlay, #magnify').fadeOut('fast', function () {
				$('#close-popup, #magnify, #overlay').remove();
			});
		});		
	});

	/*Редактирование книги*/
	$('.product-composition').on('click', '[act=edit]', function (event) {
		var book_id = $(this).attr('book_id');
		$('body').append('<div id="overlay"></div><div id="magnify"><div id="close-popup"><i></i></div></div>');

		$.ajax({
			type: 'POST',
			url: "book_controller.php",
			data: {
				"edit": book_id
			},
			success: function (html) {
				$("#magnify").append(html);
				$('#magnify').css({
					left: ($(document).width() - $('#magnify').outerWidth()) / 2,
					top: ($(window).height() - $('#magnify').outerHeight()) / 4
				});
				$('#overlay, #magnify').fadeIn('fast');
			}
		});

	});

	$('body').on('click', '#close-popup, #overlay, [name=no]', function (event) {
		event.preventDefault();

		$('#overlay, #magnify').fadeOut('fast', function () {
			$('#close-popup, #magnify, #overlay').remove();
		});
	});
});