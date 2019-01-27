<div class="table_table_wrapper col-xs-12 col-sm-12 col-md-6">
    <table>
        <tr>
            <td class="table_subheader" colspan="5">
                <a href="#" class="stats_info"><i class="glyphicon glyphicon-check"></i>
                    Cols climbed (most recent) </span>
                </a>
            </td>
        </tr>

        @foreach($cols as $col)
            <tr class="table_row" id="done_{{$col->ColID}}">
                <td class="table_col" onclick="goToColl('{{$col->ColIDString}}')">
                    {{$col->Col}}
                </td>
                <td class="table_country" onclick="goToColl('{{$col->ColIDString}}')">
                    <img src="/images/flags/{{$col->Country1}}.gif" title="{{$col->Country1}}"/>
                    @if ($col->Country2)
                        <img src="/images/flags/{{$col->Country2}}.gif" title="{{$col->Country2}}"/>
                    @endif
                </td>

                <td class="table_value" onclick="goToColl('{{$col->ColIDString}}')">
                    {{$col->Height}}m
                </td>
                <td onclick="goToColl('{{$col->ColIDString}}')">
                        <span style="float:right" title="Date Added">
                        {{Carbon\Carbon::parse($col->pivot->CreatedAT)->format('d M Y')}}
                        </span>
                </td>
                <td>
                    <span style="float:right; color:red" class="glyphicon glyphicon-remove-circle" onclick="removeColl({{$col->ColID}}, '{{$col->Col}}')">
                    </span>
                </td>
            </tr>
        @endforeach
    </table>
</div>

<script type="text/javascript">

    function removeColl(ColID, name){


        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure you want to remove  <strong>'+name+'</strong> from you achievements?',
            buttons: {
                confirm: function () {
                    $.post('/ajax/col/' + ColID, {'done': false})
                        .done(function (data) {

                            $('#done_' + ColID).hide();

                        });
                },
                cancel: function () {
                }
            }
        });


    }
</script>