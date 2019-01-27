<div class="col-xs-4 kpi_div">

    <span class="kpi">{{$kpi}}</span>
    @if(isset($imgUrl))
        <img class="stat_icon_header" style="width:40px; padding-bottom:10px;" src="{{$imgUrl}}" />
    @endif
    <br />
    <span class="kpi_text">{{$text}}</span>
</div>