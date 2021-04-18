<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GMP Check - Firma</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link href="{{url('/assets/css/')}}/signpad.css?t={{time()}}" rel="stylesheet" type="text/css" />
</head>
<body>
	
	<h1 style="text-align:center;font-family:Arial">Dibuje su firma dentro del recuadro</h1>
	
	<div style="width:80%;max-width:800px;height:auto;margin:50px auto;padding: 20px 0;background-color:#eee;color:#FFF;" id="padcontainer">
		<form method="post" action="" class="">
			<div class="sigPad">

			<div class="sig sigWrapper">
				<canvas class="pad" width="300" height="200" id="signPad"></canvas>
				<input type="hidden" name="output" class="output"  id="output_filed">
			</div>
			<div class="row">
				<div class="col">
				<button name="clear" type="clear" class=" btn btn-icon btn-outline-warning clearButton signButtons">
					<ion-icon name="trash-outline" size="large"></ion-icon><br/>
					Limpiar
				</button>
				</div>
				<div class="col">
				<button name="type" type="button" id="signEnviar" class=" btn btn-icon btn-outline-success signButtons">
					<ion-icon name="checkmark-circle-outline" size="large"></ion-icon><br/>
					Enviar
				</button>
				</div>
				<div class="col">
				<button  type="button" id="signcerrar"   class=" btn btn-icon btn-outline-danger signButtons">
				<ion-icon name="close-circle-outline" size="large"></ion-icon><br/>
					Cerrar
				</button>
				</div>	
			</div>
			<div style="clear:both;"></div>
			</div>
		</form>
		
	</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<!--[if lt IE 9]><script src="flashcanvas.js"></script> <![endif]-->
<script src="{{url('/assets/js/')}}/jquery.signaturepad.min.js?t={{time()}}"></script>
<script src="{{url('/assets/js/')}}/json2.min.js?t={{time()}}"></script>
<script type="text/javascript" src="{{url('/assets/js/')}}/functions.js?t={{time()}}"></script>
<script async   type="module" src="https://unpkg.com/ionicons@5.2.3/dist/ionicons/ionicons.js"></script>
<script>
function setWidth(){  //configura el tamaño del canvas
    var curr_width = $('#padcontainer').css('width'); 
	curr_width= curr_width.replace('px','');
	var ancho=0.9* curr_width;
	$('.pad').attr('width',ancho);
}
	setWidth();

	$( window ).resize(function() {//setea el tamaño del canvas
		setWidth();
	});
	$("#signEnviar").click(function() {
		if( $('#output_filed').val().length>2 ){
			var canvas=$('#signPad');
			var dataURL = canvas[0].toDataURL(); 
			$('#firma_base', window.parent.document).val(dataURL);
			window.parent.setImage();
		}
		window.parent.closeModal();
	}) ;	

	$("#signcerrar").click(function(){
		window.parent.closeModal();
	});	
</script>
</body>
</html>
