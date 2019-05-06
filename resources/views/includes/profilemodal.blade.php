<div class="modal fade" id="modalProfile" tabindex="-1" role="dialog" aria-labelledby="modalProfileLabel" aria-hidden="true">
	<div class="d-flex align-items-center justify-content-around h-100" style="pointer-events: none">
		<div class="modal-dialog modal-lg" role="document" style="cursor: move";>
			<div class="modal-content">
				<div class="modal-header">
					<div class="d-flex align-items-baseline">
						<span class="category"></span>
						<h6 class="modal-title font-weight-light mx-1" id="modalProfileLabel"></h6>
						<span class="modal-title-secondary text-small-75"></span>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="modal-remarks text-small-75 text-muted"></div>
					<img class="profile-img" src="" data-path="{{env('PROFILES_PATH','/profiles')}}"></img>
				</div>
				<div class="modal-footer p-0 text-small-75 d-flex justify-content-start align-items-start">
					<div class="stat1 px-2 py-1 border m-2" title="Distance" data-toggle="tooltip">
						<i class="fas fas-grey fa-arrows-alt-h no-pointer pr-1"></i>
						<span></span>
					</div>
					<div class="stat2 px-2 py-1 border m-2" title="Altitude Gain" data-toggle="tooltip">
						<i class="fas fas-grey fa-arrows-alt-v no-pointer pr-1"></i>
						<span></span>
					</div>
					<div class="stat3 px-2 py-1 border m-2" title="Average Slope" data-toggle="tooltip">
						<i class="fas fas-grey fa-location-arrow no-pointer pr-1"></i>
						<span></span>
					</div>
					<div class="stat4 px-2 py-1 border m-2" title="Maximum Slope" data-toggle="tooltip">
						<i class="fas fas-grey fa-bomb no-pointer pr-1"></i>
						<span></span>
					</div>
					<div class="stat5 px-2 py-1 border m-2" title="Profile Index" data-toggle="tooltip">
						<i class="fas fas-grey fa-signal no-pointer pr-1"></i>
						<span></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
