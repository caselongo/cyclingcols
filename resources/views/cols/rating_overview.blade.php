<div class="table_table_wrapper col-xs-12 col-sm-12 col-md-6">
    <table>
        <tr>
            <td class="table_subheader" colspan="5">
                <a href="#" class="stats_info"><i class="glyphicon glyphicon-star" style="color:red"></i>
                    Beauty Rating </span>
                </a>
            </td>
        </tr>

        @foreach($cols as $col)
            <tr class="table_row" onclick="goToColl('{{$col->ColIDString}}')">
                <td class="table_col">
                    {{$col->Col}}
                </td>
                <td class="table_country">
                    <img src="/images/flags/{{$col->Country1}}.gif" title="{{$col->Country1}}"/>
                    @if ($col->Country2)
                        <img src="/images/flags/{{$col->Country2}}.gif" title="{{$col->Country2}}"/>
                    @endif
                </td>

                <td class="table_value">
                    {{$col->Height}}m
                </td>

                <td>
                      <span style="float:right">
                    @include('cols.rating',['rating'=>$col->pivot->Rating])
                    </span>
                </td>
            </tr>

        @endforeach
    </table>
</div>