<link href="{{url('/assets/css/')}}/signpad.css?t={{time()}}" rel="stylesheet" type="text/css" />
<div class="modal fade modalbox" id="signModal" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <h2 style="text-align:center;font-family:Arial">Dibuje su firma dentro del recuadro</h2>

                <div style="width:94%;max-width:800px;height:auto;margin:auto;padding: 20px 0;background-color:#eee;color:#FFF;" id="padcontainer">
                        {{ Form::hidden('firma_base','',['id'=>'firma_base']) }}
                        @if(isset($supervisores) and count($supervisores)>0)
                         
                            <div class="form-group" style="margin: 10px 20px;">
                                <div class="col-md-11" id="userListBlock" style="display: none;">
                                    <label class="label"><h3>Seleccionar el supervisor que firma</h3></label>
                                    {{ Form::select('second_sign',$supervisores,null,array('class'=>'form-control','required','autocomplete'=>'off','id'=>'firm_input','disabled')) }}
                                </div>
                            </div>
                        @endif
                        <div class="sigPad">

                            <div class="sig sigWrapper">
                                <canvas class="pad" width="300" height="200" id="signPad"></canvas>
                                <input type="hidden" name="output" class="output"  id="output_filed">
                            </div>
                            <div class="row">
                                <div class="col">
                                    <button name="clear" id="clearButton" type="button" class=" btn btn-icon btn-outline-warning clearButton signButtons">
                                        <ion-icon name="trash-outline" size="large"></ion-icon><br/>
                                        Limpiar
                                    </button>
                                </div>
                                <div class="col">
                                    <button name="type" type="button" data-dismiss="modal" id="signEnviar" class=" btn btn-icon btn-outline-success signButtons">
                                        <ion-icon name="checkmark-circle-outline" size="large"></ion-icon><br/>
                                        Enviar
                                    </button>
                                </div>
                                <div class="col">
                                    <button  type="button" id="signcerrar"  data-dismiss="modal"   class=" btn btn-icon btn-outline-danger signButtons">
                                    <ion-icon name="close-circle-outline" size="large"></ion-icon><br/>
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                            <div style="clear:both;"></div>
                        </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    var campo_firma='';

    $(document).ready(function () {
        
        
        function launchFullScreen(element) {
          if(element.requestFullScreen) {
            element.requestFullScreen();
          } else if(element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
          } else if(element.webkitRequestFullScreen) {
            element.webkitRequestFullScreen();
          }
        }
        // Lanza en pantalla completa en navegadores que lo soporten
         function cancelFullScreen() {
             if(document.cancelFullScreen) {javascript:;
                 document.cancelFullScreen();
             } else if(document.mozCancelFullScreen) {
                 document.mozCancelFullScreen();
             } else if(document.webkitCancelFullScreen) {
                 document.webkitCancelFullScreen();
             }
         }

        $('.signRequest').on('click', function(event){
            launchFullScreen(document.documentElement);
            campo_firma=$(this).attr('data-field');
            console.log('campo fien name :'+campo_firma);
            $('#userListBlock').hide();
            $('#firm_input').attr('disabled','disabled');
            if(campo_firma=='ok_supervisor') {
                $('#userListBlock').show();
                $('#firm_input').removeAttr('disabled');
            }
            limpiarCanvas();
        });
        
        
        window.closeModal = function(){
            $('.signModal').modal('hide');
            cancelFullScreen();
        };
        window.setImage = function(){

            $('#btn'+campo_firma).hide();
            $('#'+campo_firma).val($('#firma_base').val());
            $('#img_'+campo_firma).attr('src',$('#firma_base').val());
            $('#img_'+campo_firma).show();
        };

        $("#signEnviar").click(function() {
            cancelFullScreen();
                var canvas=$('#signPad');
                var dataURL = canvas[0].toDataURL();
                $('#firma_base', window.parent.document).val(dataURL);
                window.parent.setImage();
            window.parent.closeModal();
        }) ;

        $("#signcerrar").click(function(){
            window.parent.closeModal();
        });
    });
    /**CODIGO DE FIRMA**/
    var scroll = $(window).scrollTop();

    var limpiar = document.getElementById("clearButton");
    var canvas = document.getElementById("signPad");
    var ctx = canvas.getContext("2d");
    ctx.lineWidth = 10;
    var cw = canvas.width = 300,
    cx = cw / 2;
    var ch = canvas.height = 200,
    cy = ch / 2;

    var dibujar = false;
    var factorDeAlisamiento = 2;
    var Trazados = [];
    var puntos = [];
    ctx.lineJoin = "round";
    var vancho=window.innerWidth;
    var valto=window.innerHeight;
    var ancho=300;

        function setWidth(){  //configura el tamaño del canvas

        var curr_width = vancho*0.80;
        if(curr_width>720)
            curr_width=720;
            ancho=0.94* curr_width;
        cw=ancho;
        cx = cw / 2;
        $('.pad').attr('width',ancho);
        }

        setWidth();
        
        
        
        $( window ).resize(function() {//setea el tamaño del canvas
            vancho=window.innerWidth;
            valto=window.innerHeight;
           // setWidth();
        });
    
        function limpiarCanvas(){
            dibujar = false;
            ctx.clearRect(0, 0, cw, ch);
            Trazados.length = 0;
            puntos.length = 0;
        }
        limpiar.addEventListener('click', function(evt) {
            limpiarCanvas();
        }, false);
    
    
        canvas.addEventListener('mousedown', function(evt) {
        dibujar = true;
        //ctx.clearRect(0, 0, cw, ch);
        puntos.length = 0;
        ctx.beginPath();
    
        }, false);
    
        canvas.addEventListener('mouseup', function(evt) {
        redibujarTrazados();
        }, false);
    
        /*canvas.addEventListener("mouseout", function(evt) {
        redibujarTrazados();
        }, false);*/

    canvas.addEventListener("mousemove", function(evt) {
    if (dibujar) {
        var m = oMousePos(canvas, evt);
        puntos.push(m);
        ctx.lineTo(m.x, m.y);
        ctx.stroke();

    }
    }, false);

    // Eventos pantallas táctiles
    document.body.addEventListener("touchstart", function(evt){ if (evt.target.nodeName == 'CANVAS') {
    //canvas.addEventListener('touchstart', function(evt){

        dibujar = true;
        //ctx.clearRect(0, 0, cw, ch);
        puntos.length = 0;
        ctx.beginPath();
        }
    }, false);

    document.body.addEventListener("touchmove", function(evt){ if (evt.target.nodeName == 'CANVAS') {
    //canvas.addEventListener('touchmove',  function(evt){

            if (dibujar) {
                var m = oMousePos(canvas, evt);
                puntos.push(m);
                ctx.lineTo(m.x, m.y);
                ctx.stroke();
            }
        }
    }, false);


    canvas.addEventListener('touchend',  function(evt){
    redibujarTrazados();
    }, false);




    function reducirArray(n,elArray) {
    var nuevoArray = [];
    nuevoArray[0] = elArray[0];
    for (var i = 0; i < elArray.length; i++) {
        if (i % n == 0) {
        nuevoArray[nuevoArray.length] = elArray[i];
        }
    }
    nuevoArray[nuevoArray.length - 1] = elArray[elArray.length - 1];
    Trazados.push(nuevoArray);
    }

    function calcularPuntoDeControl(ry, a, b) {
    var pc = {}
    pc.x = (ry[a].x + ry[b].x) / 2;
    pc.y = (ry[a].y + ry[b].y) / 2;
    return pc;
    }

    function alisarTrazado(ry) {
    if (ry.length > 1) {
        var ultimoPunto = ry.length - 1;
        ctx.lineWidth = 10;
        ctx.beginPath();
        ctx.moveTo(ry[0].x, ry[0].y);
        for (i = 1; i < ry.length - 2; i++) {
        var pc = calcularPuntoDeControl(ry, i, i + 1);
        ctx.quadraticCurveTo(ry[i].x, ry[i].y, pc.x, pc.y);
        }
        ctx.quadraticCurveTo(ry[ultimoPunto - 1].x, ry[ultimoPunto - 1].y, ry[ultimoPunto].x, ry[ultimoPunto].y);
        ctx.stroke();
    }
    }


    function redibujarTrazados(){
    dibujar = false;
    ctx.clearRect(0, 0, cw, ch);
    reducirArray(factorDeAlisamiento,puntos);
    for(var i = 0; i < Trazados.length; i++)
    alisarTrazado(Trazados[i]);
    }

    function oMousePos(canvas, evt) {
    var ClientRect = canvas.getBoundingClientRect();
    scroll = $(window).scrollTop();
    if(evt.clientX == undefined || evt.clientY == undefined ){
        return { //objeto
            x: Math.round(evt.changedTouches[0].pageX - ClientRect.left),
            y: Math.round(evt.changedTouches[0].pageY -scroll - ClientRect.top)
        }

    }else{
        return { //objeto
            x: Math.round(evt.clientX - ClientRect.left),
            y: Math.round(evt.clientY - ClientRect.top)
        }
    }
    }

</script>
