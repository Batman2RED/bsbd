function book_tbl(count, book_tittle, book_price, book_img)
{

	var t = document.getElementsByClassName("catalog-list")[0];
	var s = "";
	var k = Math.ceil(count/4);
	var c = 1;
	var dt_str = book_tittle.split(',');
	var dp_str = book_price.split(' ');
	var di_str = book_img.split(' ');

	for (var i = 0; i < k; i++)
	{
		s += "<tr>";
		for (var j = 0; j < 4; j++)
		{
			s += '<td><div class="product-item"><img src="'+ di_str[c] '"><div class="product-list"><h3>'+ dt_str[c] +'</h3><span class="price">'+ dp_str[c] +' руб. </span><a class="button" href="cart.php?page=cart&action=add&id='+ dt_str[c] + '">В корзину</a></div></div></td>';
			c++;
		}
		s += "</tr>";

	}
	
	t.innerHTML = s;
	
}
