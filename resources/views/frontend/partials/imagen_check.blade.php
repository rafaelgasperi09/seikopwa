<div class="section full mt-2 mb-2" id="">
                {{ Form::hidden('check_image','',['id'=>'check_image']) }}
                <div class="section-title"></div>
                <div class="wide-block pb-1 pt-2">
                    <div class="row">
                    
                        <div class="form-group boxed col-12">
                            <div class="input-wrapper text-center mx-auto">
                                <label class="label" for="comentarios">Marque con un gancho</label> 
                                <canvas height="800px" width="800px" id="imgmc"></canvas>
                            </div>
                        </div>
                        
                        <div class="form-group boxed col col-1 mx-auto">
                            <button name="clear" id="clearButton2" type="button" class=" btn btn-icon btn-outline-warning clearButton2 signButtons">
                                <ion-icon name="trash-outline" size="large"></ion-icon><br/>
                                Limpiar
                            </button>
                        </div>
                       
                        
                        
                    </div>
                </div>
                <script>
                    var canvasEquipo = document.getElementById("imgmc");
                    var ctx3 = canvasEquipo.getContext("2d");
                    function cargaImagen(){
                        var img = new Image();
                        img.src = "{{url('images/montacargas/800/'.$data->tipo->image)}}";

                        // Importante el onload
                        img.onload = function(){
                            ctx3.drawImage(img, 0, 0);
                        }
                    }

                    cargaImagen();
                    
                    $('#clearButton2').on('click',function(){
                        cargaImagen();
                    });

                    function setearImagen(){
                        var canvas2=$('#imgmc');
                        var dataURL2 = canvas2[0].toDataURL(); 
                        $('#check_image').val(dataURL2);
                    }

                    $("#signEnviar2").click(function() {
                        setearImagen();
                    }) ;	
                    function checkImage(canvasEquipo, event) {
                        const rect = canvasEquipo.getBoundingClientRect()
                        const x = event.clientX - rect.left-16
                        const y = event.clientY - rect.top-16
                        var imgChk = new Image();
                        imgChk.src = "{{url('images/check.png')}}";

                        // Importante el onload
                        imgChk.onload = function(){
                            ctx3.drawImage(imgChk, x, y);
                        }
                        setearImagen();
                    }


                    $(document).on('click','#imgmc',function(event){
                        var coordenadas = $("#imgmc").offset();
                        checkImage(canvasEquipo,event);
                    });

                    $(document).on('submit','#form',function(e){
                        setearImagen();
                    });
                </script>
            </div>     