<script>
          var colors = [
            '#66A2DB',
            '#F57F32',
            '#A8A8A8',
            '#FFC736',
            '#FF4560',
            ]
        var options{{$id}} = {
          series: [
            @foreach($titulos as $k=>$t)
            {
            name: '{{$titulos[$k]}}',
            data: [{{implode(",",$data[$indice[$k]])}}]
            }, 
            @endforeach
          ],
          chart: {
          type: 'bar',
          height: 300
        },
        colors: colors,
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '60%',
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['{!!implode("','",$data[end($indice)])!!}'],
        },
        yaxis: {
          title: {
            text: '{{$ejey}}'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return  val + " equipo(s)"
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
        animations: {
          enabled: true,
          easing: 'easeinout',
          speed: 800,
          animateGradually: {
              enabled: true,
              delay: 150
          },
          dynamicAnimation: {
              enabled: true,
              speed: 350
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#{{$id}}"), options{{$id}});
        chart.render();
      
      
    </script>