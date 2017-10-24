@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="m-portlet">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							Basic Tables
						</h3>
					</div>
				</div>
			</div>
			<div class="m-portlet__body">
				address
			</div>
			<!--end::Section-->
		</div>
    </div>
    <div class="col-md-4">
    	b
    </div>
</div>
@endsection
@section('js')

	<script>
		$(document).ready(function(){
			alert(1);
		});
	</script>
@endsection
