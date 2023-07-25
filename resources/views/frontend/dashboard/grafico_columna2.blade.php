
<script>
      
         
      var options{{$id}} = {
          series: [{
            @foreach($titulos as $k=>$t)
            data: [{{implode(",",$data[$indice[$k]])}}]
            @endforeach
        }],
          chart: {
          height: 300,
          type: 'bar',
          events: {
            click: function(chart, w, e) {
              // console.log(chart, w, e)
            }
          }
        },
        colors: colors,
        plotOptions: {
          bar: {
            columnWidth: '45%',
            distributed: true,
          }
        },
        dataLabels: {
          enabled: false
        },
        legend: {
          show: false
        },
        xaxis: {
          categories: [
            @foreach($data[end($indice)] as $d)
             ['{{$d}}'],
            @endforeach 
          ],
          labels: {
            style: {
              colors: colors,
              fontSize: '12px'
            }
          }
        },title: {
          text: '{{$title}}',
          floating: true,
          offsetY: 5,
          style: {
            fontSize: '16px',
            fontWeight:'bold',
            color: '#444'
          }
        },
        };


        var chart = new ApexCharts(document.querySelector("#{{$id}}"), options{{$id}});
        chart.render();
      
      
    </script>
