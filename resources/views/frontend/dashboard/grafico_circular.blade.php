
<script>
        var colors = [
            '#66A2DB',
            '#F57F32',
            '#A8A8A8',
            '#FF4560',
            '#FFC736'
            ]
var options = {
          series: [{{$data['a'][0]}},{{$data['p'][0]}}],
          chart: {
          width: 380,
          height: 320,
          type: 'pie',
        },
        colors: colors,
        labels: ['{!!implode("','",$titulos)!!}'],
        dataLabels: {
          formatter(val, opts) {
            const name = opts.w.globals.labels[opts.seriesIndex]
            return opts.w.config.series[opts.seriesIndex]
          }
        },
        
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
                position: 'bottom',
                offsetY: 0,
                height: 230,
            }
          }
        }],
        title: {
          text: '{{$data['n'][0]}}',
          floating: true,
          offsetY: 5,
          style: {
            fontSize: '16px',
            fontWeight:'bold',
            color: '#444'
          }
        },
        };

        var chart = new ApexCharts(document.querySelector("#chart0"), options);
        chart.render();
      </script>