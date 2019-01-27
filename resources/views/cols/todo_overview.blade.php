<div class="table_table_wrapper col-xs-12 col-sm-12 col-md-6">
    <table>
        <tr>
            <td class="table_subheader" colspan="5">
                <a href="#" class="stats_info"><i style='color:red' class="glyphicon glyphicon-heart"></i>
                    My ToDo list </span>
                </a>
            </td>
        </tr>

        @foreach($cols as $col)
            <tr class="table_row" id="todo_{{$col->ColID}}">
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
                    <span id="check_{{$col->ColID}}" style="float:right; color:red" class="glyphicon glyphicon-check"
                          onclick="addColl({{$col->ColID}}, '{{$col->Col}}')">
                    </span>
                </td>
            </tr>
        @endforeach
    </table>
</div>

<script type="text/javascript">

    function addColl(ColID, name) {
        $.confirm({
            title: 'Confirm!',
            content: 'Congretzzzz with your performance on the  <strong>' + name + '</strong>. Do you want to add this to your achievement list?',
            buttons: {
                confirm: function () {
                    $.post('/ajax/col/' + ColID, {'done': true})
                        .done(function (data) {

                            $('#check_' + ColID).remove();
                            let x = $('#todo_' + ColID);
                            let y = x.clone().attr("id", "done_" + ColID);
                            y.appendTo('#done_table');

                            x.remove();
                        });
                },
                cancel: function () {
                }
            }
        });
    }
</script>