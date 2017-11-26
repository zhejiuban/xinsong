@extends('layouts.app')

@section('content')
<div class="m-portlet m-portlet--mobile m--margin-bottom-0">
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
					<a href="{{ route('question.personal.create',['back'=>'personal','mid'=>'33b831058807f08d98879f28b9847613']) }}" class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--air m-btn--pill">
						<span>
							<i class="fa fa-plus"></i>
							<span>
								新增
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
		<!--begin::Modal-->
		<div class="modal fade" id="question_modal" tabindex="-1" role="dialog" aria-labelledby="QuestionModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				</div>
			</div>
		</div>
		<!--end::Modal-->
	</div>
</div>
@endsection

@section('js')
  <script type="text/javascript">
    var datatable;
    var DatatableAjax = function () {
    //== Private functions
    // basic demo
    var DataList = function () {

      datatable = $('.m_datatable').mDatatable({
        // datasource definition
        data: {
          type: 'remote',
          source: {
            type:'get',
            read: {
              url: '{{ url("question/pending") }}',
              param:{}
            }
          },
          pageSize: {{config('common.page.per_page',10)}},
          saveState: {
            cookie: true,
            webstorage: true
          },
          serverPaging: true,
          serverFiltering: true,
          serverSorting: true
        },

        // layout definition
        layout: {
          theme: 'default', // datatable theme
          class: '', // custom wrapper class
          scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
          // height:600,
          footer: false // display/hide footer
        },

        // column sorting
        sortable: true,

        // column based filtering
        filterable: false,

        pagination: true,

        // columns definition
        columns: [{
          field: "id",
          title: "ID",
          width: 60,
          textAlign: 'center'
        },{
            field: "status",
            title: "状态",width: 60,
            template:function (row) {
                var status = @json(config('common.question_status'));
								var rowStatus = Number(row.status);
                return '<span class="m-badge ' + status[rowStatus].class + ' m-badge--wide">' + status[rowStatus].title + '</span>';
            }
        }, {
          field: "title",
          title: "标题",
          filterable: false,
					width:200,
					template: function (row) {
						return '<a href="'+mAppExtend.laravelRoute('{{route_uri("questions.show")}}',{question:row.id})+'" data-toggle="modal" data-target="#question_modal" class="action-show">'+row.title+'</a>';
					}
        },{
          field: "question_category_id",
          title: "所属版块",
					sortable:false,
					template: function (row) {
						return row.category ? row.category.name : null;
					}
        }, {
          field: "user_id",
          title: "上报人",
					sortable:false,
					template: function (row) {
						return row.user ? row.user.name : null;
					}
        }, {
          field: "received_at",
          title: "接收时间",width:150
        }, {
          field: "created_at",
          title: "创建时间",width:150
        }, {
          field: "actions",
          width: 110,
          title: "操作",
          sortable:false,
          // locked: {right: 'xl'},
          overflow: 'visible',
          template: function (row) {
						var edit = '<a href="'+mAppExtend.laravelRoute('{{route_uri("questions.reply")}}',{question:row.id,mid:"{{request('mid')}}" })+'" class="action-reply m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="接收并回复">\
                <i class="la la-reply"></i></a>';
						if(row.status == 3){
							edit = '';
						}
            return edit;
          }
        }]
      });

      var query = datatable.getDataSourceQuery();

      $('#m_form_search').on('keyup', function (e) {
        // shortcode to datatable.getDataSourceParam('query');
        var query = datatable.getDataSourceQuery();
        query.search = $(this).val();
        // shortcode to datatable.setDataSourceParam('query', query);
        datatable.setDataSourceQuery(query);
        datatable.load();
      }).val(query.search);
    };

    return {
      // public functions
      init: function () {
        DataList();
      }
    };
  }();

  jQuery(document).ready(function () {
    DatatableAjax.init(); //加载数据列表
		$('.m_datatable').on('click', 'a.action-show,a.action-reply', function(event) {
			event.preventDefault();
			var url = $(this).attr('href');
			$('#question_modal').modal('show');
			mAppExtend.ajaxGetHtml(
				'#question_modal .modal-content',
				url,
				{},true);
		});
  });
  </script>
@endsection
