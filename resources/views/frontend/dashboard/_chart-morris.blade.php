<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="panel panel-default card-view">
          <div class="panel-heading">
              <div class="pull-left">
                  <h6 class="panel-title txt-dark">Daftar Transaksi</h6>
              </div>
              <div class="pull-right">
                  <select name="" id="" class="form-control">
                      @for ($i = date('Y'); $i >= 2015; $i--)
                          <option value="">{{$i}}</option>
                      @endfor
                  </select>
              </div>
              <div class="clearfix"></div>
          </div>
          <div class="panel-wrapper collapse in">
              <div class="panel-body">
                  <div id="morris_extra_line_chart" class="morris-chart" style="height: 293px; position: relative; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                  </div>
                  <ul class="flex-stat mt-40">
                      <li>
                          <span style="color:#dc0030" class="block">Transaksi Sukses</span>
                          <span style="color:#dc0030" class="block weight-500 font-18"><span class="counter-anim">{{format_quantity($graph_transaction['total_transaction_success'])}}</span></span>
                      </li>
                      <li>
                          <span style="color:#f2b701" class="block">Transaksi Gagal</span>
                          <span style="color:#f2b701" class="block weight-500 font-18"><span class="counter-anim">{{format_quantity($graph_transaction['total_transaction_failed'])}}</span></span>
                      </li>
                      <li>
                          <span style="color:#09a275" class="block">Transaksi Total</span>
                          <span style="color:#09a275" class="block weight-500 font-18">
                              <span class="counter-anim">{{format_quantity($graph_transaction['total_transaction'])}}</span>
                          </span>
                      </li>
                  </ul>
              </div>
          </div>
      </div>
  </div>
</div>
@push('scripts')
    
<script>
    /**
    var data = [
        {
            period: 'Jan',
            nasiGoreng: 10,
            sate: 60,
            bebekSinjai: 20
        }, 
        {
            period: 'Feb',
            nasiGoreng: 110,
            sate: 100,
            bebekSinjai: 80
        },
        {
            period: 'March',
            nasiGoreng: 120,
            sate: 100,
            bebekSinjai: 80
        },
        {
            period: 'April',
            nasiGoreng: 110,
            sate: 100,
            bebekSinjai: 80
        },
        {
            period: 'May',
            nasiGoreng: 170,
            sate: 100,
            bebekSinjai: 80
        },
        {
            period: 'June',
            nasiGoreng: 120,
            sate: 150,
            bebekSinjai: 80
        },
        {
            period: 'July',
            nasiGoreng: 120,
            sate: 150,
            bebekSinjai: 80
        },
        {
            period: 'Aug',
            nasiGoreng: 190,
            sate: 120,
            bebekSinjai: 80
        },
        {
            period: 'Sep',
            nasiGoreng: 110,
            sate: 120,
            bebekSinjai: 80
        },
        {
            period: 'Oct',
            nasiGoreng: 10,
            sate: 170,
            bebekSinjai: 10
        },
        {
            period: 'Nov',
            nasiGoreng: 10,
            sate: 470,
            bebekSinjai: 10
        },
        {
            period: 'Dec',
            nasiGoreng: 30,
            sate: 170,
            bebekSinjai: 10
        }
    ];
    **/
    var data = <?php echo $graph_transaction['grafik']; ?>;
    data = JSON.stringify(data);
    data = JSON.parse(data)

    var key = JSON.stringify({!! $graph_transaction['keys'] !!});
    key = JSON.parse(key)

    var label = JSON.stringify({!! $graph_transaction['label'] !!});
    label = JSON.parse(label)

    var lineChart = Morris.Line({
        element: 'morris_extra_line_chart',
        data: data ,
        xkey: 'period',
        ykeys: key,
        labels: label,
        pointSize: 2,
        fillOpacity: 0,
        lineWidth:2,
        pointStrokeColors:['#dc0030', '#f2b701', '#09a275'],
        behaveLikeLine: true,
        gridLineColor: '#878787',
        hideHover: 'auto',
        lineColors: ['#dc0030', '#f2b701', '#09a275'],
        resize: true,
        redraw: true,
        gridTextColor:'#878787',
        gridTextFamily:"Roboto",
        parseTime: false
    });
</script>

@endpush