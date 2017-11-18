@extends('layouts.app')

@section('content')
  <div class="m-portlet m-portlet--mobile m--margin-bottom-0">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-interface-7"></i>
                </span>
                <h3 class="m-portlet__head-text m--font-primary">
                    项目列表
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ menu_url_format(route('projects.create'),['mid'=>request('mid')])  }}" class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                        <span>
                            <i class="fa fa-plus"></i>
                            <span>
                                新增
                            </span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
  		<!--begin: Search Form -->
  		<div class="m-form m-form--label-align-right  m--margin-bottom-20">
  			<div class="row align-items-center">
  				<div class="col-xl-8 order-2 order-xl-1">
  					<div class="form-group m-form__group row align-items-center">
  						<div class="col-md-4">
  							<div class="m-input-icon m-input-icon--left">
  								<input type="text" class="form-control m-input" placeholder="关键字..." id="m_form_search">
  								<span class="m-input-icon__icon m-input-icon__icon--left">
  									<span>
  										<i class="fa fa-search"></i>
  									</span>
  								</span>
  							</div>
  						</div>
  					</div>
  				</div>
  				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
  					<a href="#" data-toggle="modal" data-target="#m_role_modal" class="btn btn-default m-btn m-btn--icon m-btn--pill">
  						<span>
  							<i class="fa fa-search"></i>
  							<span>
  								查询
  							</span>
  						</span>
  					</a>
  					<div class="m-separator m-separator--dashed d-xl-none"></div>
  				</div>
  			</div>
  		</div>
      <div class="m-separator m-separator--dashed"></div>
  		<!--end: Search Form -->
  		<!--begin: Datatable -->
  		<div class="m_datatable" id="ajax_data"></div>
  		<!--end: Datatable -->
  	</div>
  </div>
@endsection

@section('js')
  <script type="text/javascript">
  jQuery(document).ready(function () {

  });
  </script>
@endsection
