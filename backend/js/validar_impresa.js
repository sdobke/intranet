/*
 * MoonCake v1.3.1 - Form Validation Demo JS
 *
 * This file is part of MoonCake, an Admin template build for sale at ThemeForest.
 * For questions, suggestions or support request, please mail me at maimairel@yahoo.com
 *
 * Development Started:
 * July 28, 2012
 * Last Update:
 * December 07, 2012
 *
 */
;(function( $, window, document, undefined ) {
			
	var demos = {
	};
	$(document).ready(function() {
							   
		if( $.fn.validate ) {
			
			$("#detalles").validate({
				rules: {
					pdf: {
						required: true,
						extension: "pdf"
					},
					imagen: {
						required: true,
						extension: "jpg"
					} 
				}, 
				invalidHandler: function(form, validator) {
					var errors = validator.numberOfInvalids();
					if (errors) {
						var message = errors == 1
						? 'Falta un campo. El mismo se encuentra resaltado.'
						: 'Faltan ' + errors + ' campos. Los mismos han sido resaltados.';
						$("#da-ex-val1-error").html(message).show();
					} else {
						$("#da-ex-val1-error").hide();
					}
				}
			});
		}
		
	});
	
	$(window).load(function() {
		
		// When all page resources has finished loading
	});
	
}) (jQuery, window, document);