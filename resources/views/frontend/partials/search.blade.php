<!-- Search Component -->
<div class="wide-block pt-2 pb-2">
    <form class="search-form" action="">
        <div class="form-group searchbox">
            <input name="q" type="text" class="form-control" value="" placeholder="{{ $title }}" id="searchButton">
            <i class="input-icon">
                <ion-icon name="search-outline" role="img" class="md hydrated" aria-label="search outline"></ion-icon>
            </i>
        </div>
    </form>
</div>
<!-- * Search Component -->
<script>
    let page_search =2
    var search_button = document.getElementById("searchButton");
    search_button.onkeyup = () =>{
        cargando.removeAttribute('hidden')
        fetch('{{ $search_url }}?q='+search_button.value,{
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
</script>
