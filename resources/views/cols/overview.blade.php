@extends('layouts.master')

@section('title')

@stop

@include('includes.functions')

@section('content')
    <div id="stats" class="canvas col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="header">
            <h1>My Cyclingcols.com</h1>
        </div>
        <div class="content">
            <!--<div class="table_header">Stat:</div>-->
            <div class="table_header clearfix">

                    <a href="/user/cols"><img id="flag0" class="flag_header" src="/images/flags/Europe.gif" title="Europe" /></a>
                    <a href="/user/cols?country=2"><img id="flag2" class="flag_header" src="/images/flags/Andorra.gif" title="Andorra" /></a>
                    <a href="/user/cols?country=3"><img id="flag3" class="flag_header" src="/images/flags/Austria.gif" title="Austria" /></a>
                    <a href="/user/cols?country=4"><img id="flag4" class="flag_header" src="/images/flags/France.gif" title="France" /></a>
                    <a href="/user/cols?country=5833"><img id="flag5833" class="flag_header" src="/images/flags/Great-Britain.gif" title="Great-Britain" /></a>
                    <a href="/user/cols?country=5"><img id="flag5" class="flag_header" src="/images/flags/Italy.gif" title="Italy" /></a>
                    <a href="/user/cols?country=6383"><img id="flag6383" class="flag_header" src="/images/flags/Norway.gif" title="Norway" /></a>
                    <a href="/user/cols?country=6"><img id="flag6" class="flag_header" src="/images/flags/Slovenia.gif" title="Slovenia" /></a>
                    <a href="/user/cols?country=7"><img id="flag7" class="flag_header" src="/images/flags/Spain.gif" title="Spain" /></a>
                    <a href="/user/cols?country=8"><img id="flag8" class="flag_header" src="/images/flags/Switzerland.gif" title="Switzerland" /></a>
                </div>
        </div>

        <div class="content">
            <div class="table_table clearfix">
            @include('cols.rating_overview',['cols'=>$ratings])
            @include('cols.done_overview', ['cols'=>$done])
            </div>

            <div class="table_table clearfix">

                @include('cols.todo_overview', ['cols'=>$ratings])
            </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function goToColl(colID)
        {

            return window.location.href = '/col/'+colID;
        }

        $(document).ready(function(){

           $('#flag'+'{{$countryID ?? 0}}').addClass('flag_selected');

        });


    </script>
@stop