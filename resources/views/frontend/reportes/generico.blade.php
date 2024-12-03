<?php

$titulos='<tr>';
$cols=0;
$color='#005dba';
 foreach($data['lista'] as $d){
    foreach($d as $key=>$value){
      $width=10;
      $width=strlen($key)*3;
      $titulos.='
      <td style="border:1px solid #ccc;background-color:'.$color.';color:#fff" align="center"><b>'.strtoupper($key).'</b></td>';
      $cols++;
    }
    break;
 }
 $titulos.='</tr>';

?>

<table width="100%" align="center" style="max-width:1200px;border:1px solid #333;">
@if($data['title']<>'')
  <tr>
    <td align="center" style="text-align:center;" colspan="{{$cols}}"><h3>{!!$data['title']!!}</h3></td>
  </tr>
  @endif
  @if($data['subtitle']<>'')
  <tr>
    <td align="center" style="text-align:center;" colspan="{{$cols}}"><h4>{!!$data['subtitle']!!}</h4></td>
  </tr>
  @endif
        {!!$titulos!!}
        @foreach($data['lista'] as $d)
            <tr>

                @foreach($d as $key=>$value)
                  <td style="border:1px solid #ccc;"    align="left">{{$value}}</td>
                @endforeach
            </tr>
        @endforeach

</table>
