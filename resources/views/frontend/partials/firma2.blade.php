<html>
<head>
<script
  src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>
  @yield('css')
    <link rel="stylesheet" href="{{ url('assets/css/style.css?time='.time()) }}">
	<script async   type="module" src="https://unpkg.com/ionicons@5.2.3/dist/ionicons/ionicons.js"></script>
<style>
.drop-shadow {
	margin:2em 20% 4em;
}
canvas {
	border-style:solid;
	border-color:#000000;
	border-radius:5px;
	border-width:1px;
}
</style>
<title>Firma</title>
</head>
 
<body>
	<div class="container-fluid">
	<div class="row">
		<div class="col-12"><br/></div>
		<div class="col-12">
			<canvas id="signPad_{{request()->get('campo')}}" ></canvas>
			<div class="signText">Firmar aqui</div>
		</div>
		<div class="col-12">
			<div class="row">
				<div class="col-4 text-center">
					<button type="button" id="signcerrar"  class="signClose signButtons  btn btn-icon btn-outline-danger"><ion-icon name="close-circle-outline" size="large"></ion-icon><br/>Cerrar</button>
				</div>
				<div class="col-4 text-center">
					<button type="button" id="borrador" class="signButtons btn btn-icon btn-outline-warning"><ion-icon name="trash-outline" size="large"></ion-icon><br/>Borrar Firma</button>
				</div>
				<div class="col-4 text-center">
					<button type="button" id="signEnviar" class="signButtons btn btn-icon btn-outline-success"><ion-icon name="checkmark-circle-outline" size="large"></ion-icon><br/>Listo</button>
				</div>
			</div>
		</div>        
	</div>
	</div>
	{{ Form::hidden('firma','',['id'=>'firma']) }}

	<script>
    //======================================================================
    // VARIABLES
    //======================================================================
    let miCanvas = document.querySelector('#signPad_{{request()->get('campo')}}');
    let lineas = [];
    let correccionX = 0;
    let correccionY = 0;
    let pintarLinea = false;
    // Marca el nuevo punto
    let nuevaPosicionX = 0;
    let nuevaPosicionY = 0;

    let posicion = miCanvas.getBoundingClientRect()
    var alto= 350;
	@if(request()->get('alto'))
		alto={{request()->get('alto')	}}; 	
	@endif
	var ancho=0.97* (window.innerWidth/12*10);
	@if(request()->get('ancho'))
		ancho={{request()->get('ancho')	}}; 	
	@endif
		
    correccionX = posicion.x;
    correccionY = posicion.y;

    miCanvas.width =ancho;
    miCanvas.height =alto;
    //======================================================================
    // FUNCIONES
    //======================================================================

    /**
     * Funcion que empieza a dibujar la linea
     */
    function empezarDibujo () {
        pintarLinea = true;
        lineas.push([]);
    };
    
    /**
     * Funcion que guarda la posicion de la nueva línea
     */
    function guardarLinea() {
        lineas[lineas.length - 1].push({
            x: nuevaPosicionX,
            y: nuevaPosicionY
        });
    }

    /**
     * Funcion dibuja la linea
     */
    function dibujarLinea (event) {
        event.preventDefault();
        if (pintarLinea) {
            let ctx = miCanvas.getContext('2d')
            // Estilos de linea
            ctx.lineJoin = ctx.lineCap = 'round';
            ctx.lineWidth = 2;
            // Color de la linea
            ctx.strokeStyle = '#000';
            miCanvas.style.cursor="crosshair";
            // Marca el nuevo punto
            if (event.changedTouches == undefined) {
                // Versión ratón
                nuevaPosicionX = event.layerX;
                nuevaPosicionY = event.layerY;
            } else {
                // Versión touch, pantalla tactil
                nuevaPosicionX = event.changedTouches[0].pageX - correccionX;
                nuevaPosicionY = event.changedTouches[0].pageY - correccionY;
            }
            // Guarda la linea
            guardarLinea();
            // Redibuja todas las lineas guardadas
            ctx.beginPath();
            lineas.forEach(function (segmento) {
                ctx.moveTo(segmento[0].x, segmento[0].y);
                segmento.forEach(function (punto, index) {
                    ctx.lineTo(punto.x, punto.y);
                });
            });
            ctx.stroke();
        }
    }

    /**
     * Funcion que deja de dibujar la linea
     */
    function pararDibujar () {
        pintarLinea = false;
        guardarLinea();
    }

    //======================================================================
    // EVENTOS
    //======================================================================

    // Eventos raton
    miCanvas.addEventListener('mousedown', empezarDibujo, false);
    miCanvas.addEventListener('mousemove', dibujarLinea, false);
    miCanvas.addEventListener('mouseup', pararDibujar, false);

    // Eventos pantallas táctiles
    miCanvas.addEventListener('touchstart', empezarDibujo, false);
    miCanvas.addEventListener('touchmove', dibujarLinea, false);


   
    $(document).ready(function () {

        function erase(){
        let ctx = miCanvas.getContext('2d')
		ctx.clearRect(0, 0, miCanvas.width, miCanvas.height);
        lineas= [];
	    }
        
        borrador=document.getElementById("borrador");
		borrador.addEventListener('click',erase,false);
		
		$("#signPad_{{request()->get('campo')}}").mouseout(function(){
			pararDibujar();
		});

		$("#signcerrar").click(function(){
			window.parent.closeModal();
		});


        $("#signEnviar").on('click',function() {
            var canvas=$('#signPad_{{request()->get('campo')}}');
            var dataURL = canvas[0].toDataURL(); 
			 $('#{{request()->get('campo')}}', window.parent.document).val(dataURL);
			 window.parent.setImage_{{request()->get('campo')}}();
			 window.parent.closeModal();
        }) ;
    });
    
</script>

</body>
</html>