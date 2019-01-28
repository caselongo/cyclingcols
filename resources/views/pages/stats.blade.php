@extends('layouts.master')

@section('title')
CyclingCols - Stats
@stop

@include('includes.functions')

@section('content')
<script type="text/javascript">	
	$(document).ready(function() {
		$(".stat_icon_header").removeClass("stat_icon_selected");
		$("#stat{{$statid}}").addClass("stat_icon_selected");
		
		$(".flag_header").removeClass("flag_selected");
		$("#flag{{$geoid}}").addClass("flag_selected");
	});

</script>

<?php
	
?>
<main role="main" class="bd-content p-3">
    <div class="header">
        <h1>CyclingCols Stats</h1>
	</div>
	
	<div class="content">
		<!--<div class="table_header">Stat:</div>-->
		<div class="table_header clearfix">
			<div>
			Select Stat
			<a href="/stats/0/{{$geoid}}"><img id="stat0" class="stat_icon_header" src="/images/stat_all.png" title="Summary of all stats" /></a>
			<a href="/stats/1/{{$geoid}}"><img id="stat1" class="stat_icon_header" src="/images/{{statNameShort(1)}}.png" title="{{statName(1)}}" /></a>
			<a href="/stats/2/{{$geoid}}"><img id="stat2" class="stat_icon_header" src="/images/{{statNameShort(2)}}.png" title="{{statName(2)}}" /></a>
			<a href="/stats/3/{{$geoid}}"><img id="stat3" class="stat_icon_header" src="/images/{{statNameShort(3)}}.png" title="{{statName(3)}}" /></a>
			<a href="/stats/4/{{$geoid}}"><img id="stat4" class="stat_icon_header" src="/images/{{statNameShort(4)}}.png" title="{{statName(4)}}" /></a>
			<a href="/stats/5/{{$geoid}}"><img id="stat5" class="stat_icon_header" src="/images/{{statNameShort(5)}}.png" title="{{statName(5)}}" /></a>
			</div>
			<div>
			Select Country
			<a href="/stats/{{$statid}}/0"><img id="flag0" class="flag_header" src="/images/flags/Europe.gif" title="Europe" /></a>
			<a href="/stats/{{$statid}}/2"><img id="flag2" class="flag_header" src="/images/flags/Andorra.gif" title="Andorra" /></a>
			<a href="/stats/{{$statid}}/3"><img id="flag3" class="flag_header" src="/images/flags/Austria.gif" title="Austria" /></a>
			<a href="/stats/{{$statid}}/4"><img id="flag4" class="flag_header" src="/images/flags/France.gif" title="France" /></a>
			<a href="/stats/{{$statid}}/5833"><img id="flag5833" class="flag_header" src="/images/flags/Great-Britain.gif" title="Great-Britain" /></a>
			<a href="/stats/{{$statid}}/5"><img id="flag5" class="flag_header" src="/images/flags/Italy.gif" title="Italy" /></a>
			<a href="/stats/{{$statid}}/6383"><img id="flag6383" class="flag_header" src="/images/flags/Norway.gif" title="Norway" /></a>
			<a href="/stats/{{$statid}}/6"><img id="flag6" class="flag_header" src="/images/flags/Slovenia.gif" title="Slovenia" /></a>
			<a href="/stats/{{$statid}}/7"><img id="flag7" class="flag_header" src="/images/flags/Spain.gif" title="Spain" /></a>
			<a href="/stats/{{$statid}}/8"><img id="flag8" class="flag_header" src="/images/flags/Switzerland.gif" title="Switzerland" /></a>
			</div>
		</div>
		<div class="table_table clearfix">
<?php		
$statid_ = 0;
$statcount = 0;
$rowcount = $stats->count();

foreach($stats as $stat) {
	if ($statid > 0) {
		if ($statcount == 0) {
			?>			
				<div class="table_table_wrapper col-xs-12 col-sm-12 col-md-6">
					<table>
						<tbody>		
						<tr><td class="table_subheader" colspan="5">
							<a href="/stats/{{$stat->StatID}}/{{$geoid}}">
							<img class="stat_icon" src="/images/{{statNameShort($stat->StatID)}}.png" />
							Largest {{statName($stat->StatID)}}
							</a>
						</td></tr>	
			<?php	
		}
		
		if ($statcount >= $rowcount / 2) {
			$statcount = 0;
			?>	
					</tbody>
				</table>
				</div>
				<div class="table_table_wrapper col-xs-12 col-sm-12 col-md-6">
				<table>
					<tbody>		
					<tr><td class="table_subheader hidden-xs hidden-sm" colspan="5">&nbsp;</td></tr>	
		<?php
		}
			
		$statcount++;
	}
	else if ($stat->StatID != $statid_) {
		if ($statcount > 2 || $statid_ == 0) {
			$statcount = 0;
			if ($statid_ != 0) {
			?>	
					</tbody>
				</table>
				</div>
			<?php	
			}
			?>			
				<div class="table_table_wrapper col-xs-12 col-sm-12 col-md-6">
				<table>
					<tbody>		
		<?php
		}
		?>
					<tr><td class="table_subheader" colspan="5">
						<a href="/stats/{{$stat->StatID}}/{{$geoid}}">
						<img class="stat_icon" src="/images/{{statNameShort($stat->StatID)}}.png" />
						Largest {{statName($stat->StatID)}}
						</a>
					</td></tr>		
		<?php	
		$statid_ = $stat->StatID;
		$statcount++;
	}
?>
					<tr id="{{$stat->ColIDString}}/{{$stat->ProfileID}}-{{$stat->FileName}}" class="table_row">
						<td class="table_rank">{{$stat->Rank}}</td>
						<td class="table_col">{{$stat->Col}}</td>
						<td class="table_country">
							<img src="/images/flags/{{$stat->Country1}}.gif" title="{{$stat->Country1}}" />
@if ($stat->Country2)
							<img src="/images/flags/{{$stat->Country2}}.gif" title="{{$stat->Country2}}" />
@endif
						</td>
						<td class="table_value">{{formatStat($stat->StatID,$stat->Value)}}</td>
						
@if ($stat->SideID > 0)
						<td class="table_side">
							<img src="/images/{{$stat->Side}}.png" title="{{$stat->Side}}"/>
							<span>{{$stat->Side}}</span>
						</td>
@else
						<td>&nbsp;</td>	
@endif				
					</tr>
<?php		
		}		
?>
				</tbody>
			</table>
			</div>
		</div>
    </div>
</main>
@stop
