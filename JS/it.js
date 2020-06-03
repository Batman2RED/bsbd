function dishes_tbl(count, dishes_tittle, dishes_price, dishes_img)
{

	var t = document.getElementsByClassName("catalog-list")[0];
	var s = "";
	var k = Math.ceil(count/4);
	var c = 1;
	var dt_str = dishes_tittle.split(',');
	var dp_str = dishes_price.split(' ');
	var di_str = dishes_img.split(' ');

	for (var i = 0; i < k; i++)
	{
		s += "<tr>";
		for (var j = 0; j < 4; j++)
		{
			s += '<td><div class="product-item"><img src="'+ di_str[c] +'"><div class="product-list"><h3>'+ dt_str[c] +'</h3><span class="price">'+ dp_str[c] +' руб. </span><a class="button" href="cart.php?page=cart&action=add&id='+ dt_str[c] + '">В корзину</a></div></div></td>';
			c++;
		}
		s += "</tr>";

	}
	
	t.innerHTML = s;
	
}
