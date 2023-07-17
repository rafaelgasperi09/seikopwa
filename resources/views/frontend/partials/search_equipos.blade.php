@php 
$gmpfilter=false;
if(\Sentinel::hasAccess('equipos.dominio'))
    $gmpfilter=true;

 $dominio=request()->dominio;   
@endphp
<!-- Search Component -->
<div class="extraHeader">
    <div class='container-fluid'>
        <div class='row'>
            
            <div class='@if($gmpfilter and $dominio!="gmp") col-md-6 @else col-md-9 @endif'>
                <form class="search-form" action="">
                    <div class="form-group searchbox">
                        <input name="q" type="text" class="form-control" value="" placeholder="{{ $title }}" id="searchButton" autocomplete="off">
                        <i class="input-icon">
                            <ion-icon name="search-outline" role="img" class="md hydrated" aria-label="search outline"></ion-icon>
                        </i>
                    </div>
                </form>
            </div>
            @if($gmpfilter) 
            <div class='col-md-3'>
            {{ Form::select('dominio',[''=>'Todos','gmp'=>'GMP','cliente'=>'Cliente'],request('dominio'),array('class'=>'form-control','autocomplete'=>'off','id'=>'dominio')) }} 
            </div>
            @endif
            @if($dominio!="gmp")
            <div class='col-md-3'>
            {{ Form::select('cliente_id',$clientes,request('cliente_id'),array('class'=>'form-control','autocomplete'=>'off','id'=>'cliente_id')) }} 
            </div>
            @endif
        </div>
    </div>
</div>
<!-- * Search Component -->
<script>
    let page_search =2
    var search_button = document.getElementById("searchButton");
    var tope=false;
    search_button.onkeyup = () =>{
        tope=false;
        cargando.removeAttribute('hidden')
        fetch('{{ $search_url }}?q='+search_button.value+'&dominio={{request('dominio')}}',{
            method:'get'
        })
        .then(response => response.text())
        .then( html => {
            cargando.setAttribute('hidden','')
            document.getElementById("list-block").innerHTML = html;
            page_search++;
            if(search_button.value == '') page = 2;
            $(".multi-level > a.item").click(function () {
                if ($(this).parent(".multi-level").hasClass("active")) {
                    $(this).next("ul").slideToggle(250);
                    $(this).parent(".multi-level").removeClass("active");
                }
                else {
                    // $(".multi-level ul").slideUp(250);
                    $(this).parent(".multi-level").parent("ul").children("li").children("ul").slideUp(250)
                    $(this).next("ul").slideToggle(250);
                    $(this).parent(".multi-level").parent("ul").children(".multi-level").removeClass("active")
                    // $(".multi-level").removeClass("active");
                    $(this).parent(".multi-level").addClass("active");
                }
            });
        })
        .catch(error => console.log(error))

    }

    $('#dominio').on('change',function(){
        //redirect when dominio is diferent to todos
        if(this.value != 'todos') {
            window.location.href = "{{ url(route('equipos.lista')) }}?dominio="+this.value;
        }
    });
    $('#cliente_id').on('change',function(){
        //redirect when dominio is diferent to todos
            window.location.href = "{{ url(route('equipos.lista')) }}?dominio=cliente&cliente_id="+this.value;
    });
</script>
