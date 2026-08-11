jQuery(document).ready(function($) {  

	displayRangeType();
	//hide_button_ranges(5);

	$("#All").click(function (){

		var prueba = $("#select2-chosen-5").text();

		var regex = new RegExp("fees\\\[[A-Z]*\\\]\\\[[1-9]\\\]", "g");

		if ($(this).is(':checked'))
		{
			$(".input_zone").each(function(){ $(this).attr("checked",true); });
			$(".fees2").each(function(){ 

				var feedname = $(this).attr("name");

				if(feedname.match(regex)){
					$(this).removeAttr("disabled"); 
				};
			});	
		}
		else
		{
			$(".input_zone").each(function(){ $(this).removeAttr("checked"); });
			$(".fees2").each(function(){ 

				var feedname = $(this).attr("name");

				if(feedname.match(regex)){
					$(this).attr("disabled","disabled"); 
				};
			});	
		}
	});


	$(".fees1").click(function (event){

		var prueba =  event.target.name;
		var num =  event.target.id;

		var regex = new RegExp("fees\\\["+ num +"\\\]\\\[[1-9]\\\]", "g");

		if (event.target.checked)
		{
			$(".fees2").each(function(){ 

				var feedname = $(this).attr("name");

				if(feedname.match(regex)){
					$(this).removeAttr("disabled"); 
				};
			});	
		}
		else
		{
			$(".fees2").each(function(){ 

				var feedname = $(this).attr("name");

				if(feedname.match(regex)){
					$(this).attr("disabled","disabled"); 
				};
			});	
		}
	});

	$("#btn_save").click(function (){

	    $( "#div_loader" ).css("display", "block");
		var cities_available = get_cities_available();
		var taxes = get_mrw_taxes();

		//Load ranges
		var array_ranges_inf = new Array();
		var array_ranges_sup = new Array();

		$(".ranges_inf").each(function(){ 
					
			var range_name = $(this).attr('name');
			//var range_min   = $(this).attr('value');
			var range_min   = $(this).val();

			var num = range_name.match(/[0-9]+/g).map(function(n)
			{
    			return +(n);
			});

			var range_id = Number(num[0]);

			array_ranges_inf.push({
				range_id:range_id,
				min:range_min
				})
		});

		$(".ranges_sup").each(function(){ 
					
			var range_name = $(this).attr('name');
			//var range_sup   = $(this).attr('value');
			var range_sup   = $(this).val();

			var num = range_name.match(/[0-9]+/g).map(function(n)
			{
    			return +(n);
			});

			var range_id = Number(num[0]);

			array_ranges_sup.push({
				range_id:range_id,
				max:range_sup
				})
		});

		$.ajax({ 
			 data: {
			 	action: 'save_ranges',
             	array_ranges_sup:array_ranges_sup,
             	array_ranges_inf:array_ranges_inf
             },
	         type: 'post',
	         dataType: 'json',
	         url: 'admin-ajax.php',
	         success: function(data) {
	         	alert(data.message);

	         	if(data.check){
	         		
	         		//If ranges are ok, save taxes
	         		$.ajax({ 
			             data: {
			             	action: 'save_mrw_taxes',
			             	cities_available:cities_available,
			                taxes:taxes
			             },
				         type: 'post',
				         dataType: 'json',
				         url: 'admin-ajax.php',
				         success: function(data) {

				         	$( "#div_loader" ).css("display", "none");

				         	//Agregar nuevo rango oculto si no superamos el máximo.
				         	var ranges = $('.ranges_inf').length;
				         	var range_id = ranges + 1;

							 console.log(cities_available);
							 console.log(taxes);

				         	if( (($('.ranges_inf').length) < 26) && (($('.ranges_inf_new').length) == 0)){

					         	$('#range_inf').append('<td class="border_bottom new_range"><div id="range_inf[' + range_id + ']" class="input-group"><input class="new_inf ranges_inf_new" name="range_inf[' + range_id + ']" type="text" value="0" /><span class="weight_unit">Kg</span><span class="price_unit">€</span></div></td>');

					         	$('#range_sup').append('<td class="range_data new_range"><div id="range_sup[' + range_id + ']"><input class="new_sup ranges_sup_new" name="range_sup[' + range_id + ']" type="text" value="0" autocomplete="off"/><span class="weight_unit">Kg</span><span class="price_unit">€</span></div></td>');

					         	$(".fees").each(function(){ 
					
									var city_id   	 = $(this).attr('data-zoneid');
									var country_id   = $(this).attr('data-countryid');
									
									$(this).append('<td class="new_range"><div><input class="new_fees fees2_new" id=' + country_id + ' name="fees[' + city_id + '][' + range_id + ']" type="text" value="0" /><span class="weight_unit">Kg</span><span class="price_unit">€</span></div></td>');
								});	
				         	}

				         	//$(".new_range").css("display", "none");

				         	if(ranges > 1){
				         		$("#btn_delete_range").attr("name", ranges);
				         		$("#btn_delete_range").show();
				         	}

				         	if(ranges < 26){
				         		$("#btn_add_range").show();
				         	}


				         	displayRangeType();
				         	//alert(data.success);
				        },
				         error: function() {

				        	$( "#div_loader" ).css("display", "none");
				        	//alert(data.fail); 
				         }
				    });
	         	}
	         	else{

	         		$( "#div_loader" ).hide();
	         		
	         		alert(data.message);
	         	} 
	        },
	         error: function() {
	         	$( "#div_loader" ).css("display", "none");
	        	alert("Save_ranges function can't be accessed"); 
	         }
	    });
    });

	function displayRangeType()
	{
		var value = $("#woocommerce_mrw_mrwweightprice option").attr("selected");

		if ( value == "selected")
		{
			$('.weight_unit').show();
			$('.price_unit').hide();
		}
		else
		{
			$('.price_unit').show();
			$('.weight_unit').hide();
		}
	}

	function get_cities_available()
	{

		var array_cities = new Array();

		$(".input_zone").each(function(){ 
					
			var country_id = $(this).attr('value');
			var city_id   = $(this).attr('id');
			var available;

			if ($(this).is(':checked'))

				available = 1;

			else available = 0;

			array_cities.push({
				country_id:country_id,
				city_id:city_id,
				available:available
				})
		});

		return array_cities;
	}

	function get_mrw_taxes()
	{
		var array_taxes = new Array();

		$(".fees2").each(function(){ 
					
			var city_range_id = $(this).attr('name');
			var country_id = $(this).attr('id');
			//var price   = $(this).attr('value');
			var price   = $(this).val();
			
			//Split to get city_id from fees name
			var split = city_range_id.split("[");
			city_id = split[1].split("]");
			city_id = city_id[0];

			//Get range_id
			var num = city_range_id.match(/[0-9]+/g).map(function(n)
			{
    			return +(n);
			});

			var range_id = Number(num[0]);

			array_taxes.push({
				country_id:country_id,
				city_id:city_id,
				range_id:range_id,
				price:price
				})
		});

		array_taxes = JSON.stringify(array_taxes);
		//console.log( array_taxes );

		return array_taxes;
	}

	$("#btn_add_range").click(function (){

		$("#btn_delete_range").hide();
		$("#btn_add_range").hide();

		$(".new_range").show();

		//Delete clases from new elements to save them in db
		$('.new_inf').removeClass('ranges_inf_new');
		$('.new_inf').addClass('ranges_inf');

		$('.new_sup').removeClass('ranges_sup_new');
		$('.new_sup').addClass('ranges_sup');

		$('.new_fees').removeClass('fees2_new');
		$('.new_fees').addClass('fees2');

    });

    $("#btn_delete_range").click(function (){

    	$("#btn_delete_range").hide();
    	$("#btn_add_range").hide();

    	delete_new_range();

    	$( "#div_loader" ).css("display", "block");

		// Gets range id to delete
		var del_range = $('#btn_delete_range').attr("name");

        $.ajax({ 
             data: {
             	action: 'delete_ranges',
             	del_range:del_range
             },
	         type: 'post',
	         dataType: 'json',
	         url: 'admin-ajax.php',
	         success: function(data) {

	         	$( "#div_loader" ).css("display", "none");
	         	var range_del = data.new_range;
	         	//Eliminar de la página
	         	//
	         	// Delete inf range
				var range_inf = document.getElementById("range_inf[" + range_del + "]");
				range_inf.remove();

				// Delete sup range
				var range_sup = document.getElementById("range_sup[" + range_del + "]");
				range_sup.remove();

				//$(".new_range").css("display", "none");

				//Hide button if we have only one range
				//hide_button_delete(range_del - 1);
				//
				$("#range_sup td:last").remove();
				$("#range_inf td:last").remove();
				//$("#range_inf tr:last").remove();

				$(".fees2").each(function(){ 

					var feedname = $(this).attr("name");

					var num = feedname.match(/[0-9]+/g).map(function(n)
					{
		    			return +(n);
					});

					if (num == range_del){

						$(this).parents('td').remove();					

					}	
				});

	         	displayRangeType();
	        },
	         error: function() {

	        	$( "#div_loader" ).css("display", "none");
	        	//alert(data.fail); 
	         }
	    });

    });

	function hide_button_ranges($n){

		var numItems = $('.ranges_inf').length;

		if( numItems >= $n ){
			$("#btn_add_range").hide();
		}
	}

	function hide_button_delete($n){

		var numItems = $('.ranges_inf').length;

		if( numItems < 2 ){
			$("#btn_delete_range").hide();
		}
	}

	function delete_new_range(){

     	$("#range_inf th:last-child, #range_inf td:last-child").remove();
     	$("#range_sup th:last-child, #range_sup td:last-child").remove();
     	$("#fees_id th:last-child, #fees_id td:last-child").remove();
	}
});