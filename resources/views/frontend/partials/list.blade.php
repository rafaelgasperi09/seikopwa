<div class="section full mt mb">
    <ul class="listview image-listview media mb-2" id="list-block">
        @include('frontend.'.$page_view,array('data',$data))
    </ul>
    <div id="cargando" hidden align="center"><img src="{{ url('/assets/img/Spinner-3.gif') }}"></div>
</div>
<script>
    let page =2
    const cargando    =     document.getElementById("cargando")
    window.onscroll = () =>{
        cargando.removeAttribute('hidden')
        if((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight-50){
            fetch('{{ $page_url }}?page='+page,{
                method:'get'
            })
                .then(response => response.text())
                .then( html => {
                    cargando.setAttribute('hidden','')
                    document.getElementById("list-block").innerHTML += html;
                    page++;
                })
                .catch(error => console.log(error))
        }
    }
</script>
