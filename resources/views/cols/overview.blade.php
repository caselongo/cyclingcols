@extends('layouts.app')

@section('title')

@stop

@include('includes.functions')

@section('content')
    <div id="stats" class="canvas col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="header">
            <h1>My Achievements</h1>
        </div>
        <div style="heigth:10px;">
            &ensp;
        </div>

        <div class="content">
            <div class="table_table clearfix">
          @include('cols.rating_overview',['cols'=>$ratings])

            @include('cols.done_overview', ['cols'=>$done])
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function goToColl(colID)
        {

            return window.location.href = '/col/'+colID;
        }


    </script>
@stop