var str_table = "<table class='pure-table'>\
				<thead>\
					<tr>\
						<th>ID</th>\
						<th>Name</th>\
						<th>Price</th>\
						<th>Date Created</th>\
						<th>Actions</th>\
					</tr>\
				</thead>\
				<tbody>\
					%rows%\
				</tbody>\
			</table>";


jQuery(function(){

	/*	To a product single	*/
	jQuery('.load-all').click(function() {
	
		jQuery('#result').html("Cargando...");
		
		jQuery.post(
		ajaxurl,
		{
			ajax : 1,
			action: "load_all"
			//wpnonce: varjs.wpnonce
		},
		function(response) {

			var data = jQuery.parseJSON(response);

			if( typeof data.ok !== 'undefined' ) {

				jQuery('#result').html("Tabla de Productos cargada");

			}
		});


	});


	/*	To a product single	*/
	jQuery('.consult').click(function() {
		
		var productID = jQuery('#productId').val();

		if( jQuery.isNumeric( productID ) && productID >0 ) {

			jQuery('#result').html("Cargando...");
			
			jQuery.post(
			ajaxurl,
			{
				ajax : 1,
				action: "get_product",
				id: productID
				//wpnonce: varjs.wpnonce
			},
			function(response) {

				var data = jQuery.parseJSON(response);

				if( typeof data.error === 'undefined' ) {

					var str_row = "<tr id='row_" + data.id + "'><td>" + data.id + "</td><td>" + data.name + "</td><td>" + data.price + "</td><td>" + data.date_created + "</td><td><a href='#' class='remove' data-id='" + data.id + "'><span class='icono-trash'></span></a></td></tr>";

					var output = str_table.replace("%rows%",str_row);

					jQuery('#result').html(output);

				} else {
					jQuery('#result').html("No hay información para ese ID");
				}
			});

			
		} else {
			jQuery('#result').html("Ingresar un ID del producto válido");
		}
	});



	/*	To all products	*/
	jQuery('.see-all').click(function() {

		jQuery('#result').html("Cargando...");
		
		jQuery.post(
		ajaxurl,
		{
			ajax : 1,
			action: "get_products"
			//wpnonce: varjs.wpnonce
		},
		function(response) {

			var data = jQuery.parseJSON(response);

			if( typeof data.error === 'undefined' ) {

				var str_row = '';
				
				jQuery.each( data, function( key, obj ) {
					str_row = str_row + "<tr id='row_" + obj.id + "'><td>" + obj.id + "</td><td>" + obj.name + "</td><td>" + obj.price + "</td><td>" + obj.date_created + "</td><td><a href='#' class='remove' data-id='" + obj.id + "'><span class='icono-trash'></span></a></td></tr>";
				});

				var output = str_table.replace("%rows%",str_row);

				jQuery('#result').html(output);

			} else {
				jQuery('#result').html("No hay información para ese ID");
			}
		});
	});


	/*	Delete Product	*/
	jQuery("#result").on("click","a.remove",function(event) {

		event.preventDefault();
		
		var productID = jQuery(this).attr("data-id");

		if( jQuery.isNumeric( productID ) && productID >0 ) {
			
			jQuery.post(
			ajaxurl,
			{
				ajax : 1,
				action: "delete_product",
				id: productID
				//wpnonce: varjs.wpnonce
			},
			function(response) {

				var data = jQuery.parseJSON(response);

				if( typeof data.ok !== 'undefined' ) {

					jQuery('tr#row_' + productID).remove();

					//jQuery('#result').html(output);

				} else {
					jQuery('#result').html("No hay información para ese ID");
				}
			});

			
		} else {
			jQuery('#result').html("Ingresar un ID del producto válido");
		}
	});
});