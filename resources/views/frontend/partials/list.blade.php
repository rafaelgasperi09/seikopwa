<div class="section full mt mb">
    <ul class="listview image-listview media mb-2" id="list-block">
        @include('frontend.'.$page_view,array('data',$data))
    </ul>
    <div id="cargando" hidden align="center"><img src="{{ url('/assets/img/Spinner-3.gif') }}"></div>
</div>
@if(isset($page_url) and $page_url<>'')
    @php
        $dom='';
        if(isset($dominio))
            $dom=$dominio;
    @endphp
<script>
    let page =2
    const cargando    =     document.getElementById("cargando")
    window.onscroll = () =>{

        if((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight-15){

            if(!tope)
                cargando.removeAttribute('hidden')
            fetch('{{ $page_url }}?q='+search_button.value+'&page='+page+'&dominio={{$dom}}',{
                method:'get'
            })
            .then(response => response.text())
            .then( html => {
                if(html.length==0){
                    cargando.setAttribute('hidden','');
                    tope=true;
                }
                else{
                cargando.setAttribute('hidden','')
                document.getElementById("list-block").innerHTML += html;
                page++;
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
                }

            })
            .catch(error => console.log(error))
        }
    }
</script>
@endif
