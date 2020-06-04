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

function book_delete(id) {
	$.ajax({
		type: 'POST',
		url: "book_controller.php",
		data: {
			"del": id
		},
		success: function (html) {
			location.reload();
			//$("#123").append(html);
		}
	});
}