@if($rating != null)
    <div style="display:inline-block;">
    @for($i=0; $i<5; $i++)

            <i class="glyphicon glyphicon-star"
        @if($rating > $i)
             style="color:gold;"
        @endif
            ></i>

        @endfor
    </div>

@endif