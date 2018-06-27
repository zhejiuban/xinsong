@extends('layouts.app')

@section('content')
<div class="m-portlet">
    <div class="m-portlet__body  m-portlet__body--no-padding">
        <div class="row m-row--no-padding m-row--col-separator-xl">
            <div class="col-md-12 col-lg-6 col-xl-4">
                <!--begin::Total Profit-->
                <div class="m-widget24">
                    <div class="m-widget24__item">
                        <h4 class="m-widget24__title">
                            <a href="javascript:;">{{ $user->name }}</a>
                        </h4>
                        <br>
                        <span class="m-widget24__desc">
                            {{ $user->department_info }}
                        </span>
                        <span class="m-widget24__stats m--font-brand">
                        </span>
                        <div class="m--space-10"></div>
                        <div class="progress m-progress--sm">
                        </div>
                    </div>
                </div>
                <!--end::Total Profit-->
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <!--begin::New Feedbacks-->
                <div class="m-widget24">
                    <div class="m-widget24__item">
                        <h4 class="m-widget24__title">
                            <a href="javascript:;">本月考核</a>
                        </h4>
                        <br>
                        <span class="m-widget24__desc">
                            {{ date('Y-m') }}
                        </span>
                        <span class="m-widget24__stats m--font-info">
                            {{ $monthScore }}
                        </span>
                        <div class="m--space-10"></div>
                        <div class="progress m-progress--sm">
                        </div>
                    </div>
                </div>
                <!--end::New Feedbacks-->
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <!--begin::New Orders-->
                <div class="m-widget24">
                    <div class="m-widget24__item">
                        <h4 class="m-widget24__title">
                            <a href="javascript:;">总考核</a>
                        </h4>
                        <br>
                        <span class="m-widget24__desc" id="date-range">
                            {{ arr2str($allMonth,'~') }}
                        </span>
                        <span class="m-widget24__stats m--font-success" id="all-score">
                            {{ $allScore }}
                        </span>
                        <div class="m--space-10"></div>
                        <div class="progress m-progress--sm">
                        </div>
                    </div>
                </div>
                <!--end::New Orders-->
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="m-portlet m-portlet--mobile m--margin-bottom-0">
          <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-statistics"></i>
                </span>
                    <h3 class="m-portlet__head-text m--font-primary">
                        考核记录
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                  <li class="m-portlet__nav-item">
                    @if(check_permission('assessment/assessments/create'))
                      <a href="{{ menu_url_format(route('assessments.create'),['mid'=>request('mid')]) }}" data-toggle="modal" data-target="#m_role_modal" class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--air m-btn--pill">
                          <span>
                              <i class="fa fa-plus"></i>
                              <span>
                                  新增
                              </span>
                          </span>
                      </a>
                      @endif
                    </li>
                    <li class="m-portlet__nav-item">
                      <a href="{{ get_redirect_url() }}" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--air m-btn--pill">
                          <span>
                              <i class="fa fa-reply"></i>
                              <span>
                                  返回
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
                        <div class="col-xl-12 order-2 order-xl-1">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-md-6">
                                    <input type="text" placeholder="开始时间" readonly="true" name="start" id="start" class="form-control m-input month">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" placeholder="结束时间" readonly="true" name="end" id="end" class="form-control m-input month">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-separator m-separator--dashed"></div>
                <!--end: Search Form -->
                <!--begin: Datatable -->
                <div class="m_datatable" id="ajax_data"></div>
                <!--end: Datatable -->
                <!--begin::Modal-->
                <div class="modal fade" id="m_role_modal" tabindex="-1" role="dialog" aria-labelledby="RoleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            </div>
                        </div>
                    </div>
                <div class="modal fade" id="m_role_modal_edit" tabindex="-1" role="dialog" aria-labelledby="RoleModalEditLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>
                <!--end::Modal-->
            </div>
        </div>
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
              url: '{{ route("user.assessments",["user"=>$user->id]) }}',
              param:{}
            }
          },
          pageSize:  {{config('common.page.per_page',10)}},
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
          field: "user_id",
          title: "姓名",
          sortable:false,
          filterable: false,
          template: function (row) {
            return row.user ? row.user.name : '';
          }
        },{
          field: "created_at",
          title: "考核时间",
          filterable: false
        },{
          field: "assessment_rule",
          title: "考核原因",
          sortable:false,
          filterable: false,
          width:200,
          template: function (row) {
            return row.assessment_rule ? row.assessment_rule.name +'('+ row.assessment_rule.content + ',' + row.assessment_rule.remark+')' : ''
          }
        },{
          field: "score",
          title: "考核分值",
          filterable: false
        },{
          field: "asssessment_user",
          title: "考核人",
          sortable:false,
          filterable: false,
          template: function (row) {
            return row.assessment_user ? row.assessment_user.name : '系统自动';
          }
        },{
          field: "updated_at",
          title: "更新时间",
          filterable: false
        },{
          field: "actions",
          width: 110,
          title: "操作",
          sortable:false,
          // locked: {right: 'xl'},
          overflow: 'visible',
          template: function (row) {
            var checkEdit = "{{check_permission('assessment/assessments/edit')}}";
            var checkDel = "{{check_permission('assessment/assessments/destroy')}}"
            var del = '<a href="'+mAppExtend.laravelRoute('{{route_uri("assessments.destroy")}}',{assessment:row.id })+'" class="action-delete m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="删除"><i class="la la-trash"></i></a>';
            var edit = '<a href="'+mAppExtend.laravelRoute('{{route_uri("assessments.edit")}}',{assessment:row.id})+'" class="action-edit m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="编辑">\
                <i class="la la-edit"></i></a>';
            if(checkEdit != 1){
              edit = '';
            }
            if(checkDel != 1){
              del = '';
            }
            return edit + del;
          }
        }]
      });

      var query = datatable.getDataSourceQuery();

        $('#start').on('change', function () {
          // shortcode to datatable.getDataSourceParam('query');
          var query = datatable.getDataSourceQuery();
          query.start = $(this).val();
          // shortcode to datatable.setDataSourceParam('query', query);
          datatable.setDataSourceQuery(query);
          datatable.load();
          setTimeout(function(){
            $('#date-range').html($('#start').val() + '~' + $('#end').val());
            $('#all-score').html(datatable.API.params.pagination.score);
          },1000)
        }).val(typeof query.start !== 'undefined' ? query.start : '');
        $('#end').on('change', function () {
          // shortcode to datatable.getDataSourceParam('query');
          var query = datatable.getDataSourceQuery();
          query.end = $(this).val();
          // shortcode to datatable.setDataSourceParam('query', query);
          datatable.setDataSourceQuery(query);
          datatable.load();
          setTimeout(function(){
            $('#date-range').html($('#start').val() + '~' + $('#end').val());
            $('#all-score').html(datatable.API.params.pagination.score);
          },1000)
        }).val(typeof query.end !== 'undefined' ? query.end : '');

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
        mAppExtend.datePickerInstance('.month',{
          language: "zh-CN",
          todayBtn: false,
          clearBtn: true,
          // orientation: "bottom left",
          zIndexOffset: 200,
          todayHighlight: true,
          startView: 1,
          maxViewMode:2,
          minViewMode:1,
          format: "yyyy-mm",
          autoclose: true,
          templates: {
              leftArrow: '<i class="la la-angle-left"></i>',
              rightArrow: '<i class="la la-angle-right"></i>'
          }
        });
        $('.m_datatable').on('click', 'a.action-delete', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                mAppExtend.deleteData({
                    'url':url,
                    'data':{},
                    'callback':function(){
                        datatable.load();
                    }
                });
            });
        //添加角色弹窗打开时，加载添加页面
        $('#m_role_modal').on('shown.bs.modal', function (e) {
            mAppExtend.ajaxGetHtml(
                '#m_role_modal .modal-content',
                "{{ route('assessments.create') }}",
                {}, true);
        })
        $('.m_datatable').on('click', 'a.action-edit', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                $('#m_role_modal_edit').modal('show');
                mAppExtend.ajaxGetHtml(
                    '#m_role_modal_edit .modal-content',
                    url,
                    {}, true);
            });
    });
    </script>
@endsection