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
					<a href="{{ menu_url_format(route('questions.create'),['mid'=>request('mid')]) }}" class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--air m-btn--pill">
						<span>
							<i class="fa fa-plus"></i>
							<span>
								新增
							</span>
						</span>
					</a>
					<a href="{{ menu_url_format(route('questions.delete'),['mid'=>request('mid')]) }}" class="batch-delete btn btn-sm btn-danger m-btn m-btn--icon m-btn--air m-btn--pill">
						<span>
							<i class="fa fa-trash"></i>
							<span>
								删除
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
              url: '{{ url("question/questions") }}',
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
          // scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
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
					width: 40,
          textAlign: 'center',
					sortable: false,
          selector: {class: 'm-checkbox--solid m-checkbox--brand'}
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
          field: "receive_user_id",
          title: "接收人",
					sortable:false,
					template: function (row) {
						return row.receive_user ? row.receive_user.name : null;
					}
        }, {
          field: "received_at",
          title: "接收时间",width:150
        }, {
          field: "replied_at",
          title: "回复时间",width:150
        }, {
          field: "user_id",
          title: "上报人",
					sortable:false,
					template: function (row) {
						return row.user ? row.user.name : null;
					}
        }, {
          field: "created_at",
          title: "创建时间",width:150
        }, {
          field: "actions",
          width: 140,
          title: "操作",
          sortable:false,
          // locked: {right: 'xl'},
          overflow: 'visible',
          template: function (row) {
						var del = '<a href="'+mAppExtend.laravelRoute('{{route_uri("questions.destroy")}}',{question:row.id})+'" class="action-delete m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="删除"><i class="la la-trash"></i></a>';
						var edit = '<a href="'+mAppExtend.laravelRoute('{{route_uri("questions.edit")}}',{question:row.id,mid:"{{request('mid')}}" })+'" class="action-edit m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="编辑">\
                <i class="la la-edit"></i></a>';
						var reply = '<a href="'+mAppExtend.laravelRoute('{{route_uri("questions.reply")}}',{question:row.id,mid:"{{request('mid')}}" })+'" class="action-reply m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="接收并回复">\
		                <i class="la la-reply"></i></a>';
						var finish = '<a href="'+mAppExtend.laravelRoute('{{route_uri("questions.finished")}}',{question:row.id,mid:"{{request('mid')}}" })+'" class="action-finished m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" title="关闭">\
		                <i class="la la-check"></i></a>';
            return edit+del+reply+finish;
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
    $('.m_datatable').on('click', 'a.action-delete', function(event) {
      event.preventDefault();
      var url = $(this).attr('href');
      mAppExtend.deleteData({
				'url':url,
				'callback':function(){
					datatable.load();
				}
			});
    });
		$('.m_datatable').on('click', 'a.action-finished', function(event) {
      event.preventDefault();
      var url = $(this).attr('href');
			mAppExtend.confirmControllData({
				'title':'你确定要关闭问题吗？',
				'url':url,
				'data':{_method:'POST'},
				'callback':function(){
					datatable.load();
				}
			});
    });
		$(".batch-delete").click(function(event) {
			event.preventDefault();
      var url = $(this).attr('href');
			var _data = datatable.setSelectedRecords().getSelectedRecords();
			if(_data.length < 1){
					mAppExtend.notification('请选择要操作的数据','error');
					return;
			}
			var id = [];
			$.each(_data,function (i,v) {
					var ids = $(v).find('input').val();
					id.push(ids);
			});
			mAppExtend.deleteData({
				'url':url,
				'data':{id:id},
				'callback':function(){
					datatable.load();
				}
			});
		});
  });
  </script>
@endsection