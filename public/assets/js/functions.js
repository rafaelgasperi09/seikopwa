/**
* Capture, save and regenerate a user's signature using
* the SignaturePad jQuery plugin.
*
* Note: Only this script (functions.js) was written by Aaron Tennyson.
* SignaturePad and it's dependencies are the work of their respective
* authors.	
*
* @author Aaron Tennyson <aaron@aarontennyson.com>
* @link http://www.codecompendium.com/tutorials/demo/signature-pad/
* @copyright Copyright 2011, Aaron Tennyson
*
* Dependencies: 
* SignaturePad 2.0.2 http://thomasjbradley.ca/lab/signature-pad
*
*/ 

$(document).ready(function() {
	
	/********************** Capture and save a signature **********************/
	
	/*** Configure options for SignaturePad ***/
	var options = {
			defaultAction: 'drawIt',
			drawOnly: true,
			penColour: '#000'
			
	}
	
	/*** Initialize the plugin with configured options to accept a signature ***/
	$('.sigPad').signaturePad(options);	
	
	/********************** Regenerate the stored signature **********************/
	
	var signature = $('.signed').signaturePad({displayOnly:true}); // stores a reference to the DOM element where 
																   // we want to regenerate the saved signature	
	
	/*** Fetch stored signature data and regenerate on button click ***/
	$('#get').click(function() {
		$.ajax({
			url: 'return_signature.php',
			type: 'POST',
			success: function(data) { 
				signature.regenerate(data); // regenerates the signature in the stored DOM element
			} 
		});
	});	
});