@extends('layouts.app')

@section('content')

<!--Begin::Main Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__body  m-portlet__body--no-padding">

            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-xl-12">
                    <!--begin:: Widgets/Profit Share-->
                    <div class="m-widget14">
                        <span class="m-subheader__daterange m--bg-brand " style="padding: 0;" id="m_dashboard_daterangepicker">
                            <span class="m-subheader__daterange-label">
                                <span class="m-subheader__daterange-title m--font-light">昨日</span>
                                <span class="m-subheader__daterange-date m--font-light">{{yesterday_date()}}</span>
                            </span>
                            <a href="javascript:;" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                                <i class="la la-angle-down"></i>
                            </a>
                        </span>

                        <a href="javascript:;" class=" btn btn-secondary  m-btn  m-btn--icon m-btn--icon-only m-btn--pill"
                           data-toggle="modal" data-target="#_needmodal" title="高级查询">
                            <i class="la la-ellipsis-h"></i> 
                        </a>

                        <a href="{{url('project/dynamics?mid=a70ebe696b17f962d6271b83eee22c7c')}}" class=" btn btn-secondary  m-btn  m-btn--icon m-btn--icon-only m-btn--pill"
                           data-toggle="m-tooltip" data-skin="dark"  title="日志统计">
                            <i class="la la-list"></i>
                        </a>
                       
                    </div>
                    <!--end:: Widgets/Profit Share-->
                </div>
                
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->
	<div class=" min-height-300" id="dynamic-need-user">

	</div>
<!--begin::Modal-->
    <div class="modal fade" id="_needmodal" tabindex="-1" role="dialog" aria-labelledby="_NeedModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="_NeedModalLabel">
                        日志统计查询
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="m-form" action="{{route('dynamic.need.add.user')}}" method="get" id="need-dynamic-form">
                        <div class="form-group">
                            <label for="name" class="form-control-label">
                                查询日期:
                            </label>
                            <input type="text" class="form-control m-input m-dates"
                                   placeholder="开始日期" name="start" id="start" readonly value="{{yesterday_date()}}"/>
                        </div>
                        <div class="form-group">
                            <label>
                                所属项目:
                            </label>
                            <div class="">
                                <select class="form-control m-input select2" id="project_id" name="project_id">
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        关闭
                    </button>
                    <button type="button" class="btn btn-primary" id="dynamic-search-button">
                        查询
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
	<!--begin::Modal-->
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="_ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection

@section('js')
<script type="text/javascript">
        function formatProjectRepo(repo) {
            if (repo.loading) return repo.text;
            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" + repo.title + "</div>";
            markup += "<div class='select2-result-repository__description'>项目编号：" + repo.no + "</div>";
            markup += "</div></div>";
            return markup;
        }

        function formatProjectRepoSelection(repo) {
            return repo.title || repo.text;
        }

        $(document).ready(function(){
            mAppExtend.datePickerInstance('.m-dates',{
                endDate : new Date(),
                clearBtn : false
            });
            var $projectSelector = $("#project_id").select2({
                language: 'zh-CN',
                placeholder: "输入项目编号、名称等关键字搜索，选择项目",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{route('projects.selector.data')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            all:'company',
                            per_page: {{config('common.page.per_page')}}
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * data.per_page) < data.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 0,
                templateResult: formatProjectRepo, // omitted for brevity, see the source of this page
                templateSelection: formatProjectRepoSelection // omitted for brevity, see the source of this page
            });

            var daterangepickerInit = function() {
                if ($('#m_dashboard_daterangepicker').length == 0) {
                    return;
                }

                var picker = $('#m_dashboard_daterangepicker');
                var start = moment().subtract(1, 'days');
                var end = moment().subtract(1, 'days');

                function cb(start, end, label) {
                    var title = '';
                    range = start.format('YYYY-MM-DD');
                    if (range == '{{current_date()}}') {
                        title = '今日';
                    }
                    picker.find('.m-subheader__daterange-date').html(range);
                    picker.find('.m-subheader__daterange-title').html(title);

                    
                    mAppExtend.ajaxGetHtml(
                        '#dynamic-need-user',
                        "{{route('user.need.fill.dynamics')}}",
                        {
                            'start':start.format('YYYY-MM-DD'),
                            'end':end.format('YYYY-MM-DD')
                        },
                        "#dynamic-need-user",null,null,
                        true
                    );
                }

                picker.daterangepicker({
                    startDate: start,
                    endDate: end,
                    maxDate: end,
                    opens: 'right',
                    singleDatePicker:true,
                    locale:{
                        "format": 'YYYY-MM-DD',
                        "separator": "-",
                        "applyLabel": "确定",
                        "cancelLabel": "取消",
                        "fromLabel": "起始时间",
                        "toLabel": "结束时间'",
                        "customRangeLabel": "自定义",
                        "weekLabel": "W",
                        "daysOfWeek": ["日", "一", "二", "三", "四", "五", "六"],
                        "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                        "firstDay": 1
                    },
                    ranges: {
                        // '今日': [moment(), moment()],
                        // '昨日': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        // '最近7日': [moment().subtract(6, 'days'), moment()],
                        // '最近30日': [moment().subtract(29, 'days'), moment()],
                        // '本月': [moment().startOf('month'), moment().endOf('month')],
                        // '上月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, cb);

                cb(start, end, '');
            };
            daterangepickerInit();
            $("#dynamic-search-button").click(function () {
                var start = $('#start').val();
                var project_id = $('#project_id').val();
               
                mAppExtend.ajaxGetHtml(
                    '#dynamic-need-user',
                    "{{route('user.need.fill.dynamics')}}",
                    {
                        'start':start,
                        'project_id':project_id
                    },
                    "#dynamic-need-user",null,null,
                    true
                );
                $("#_needmodal").modal('hide');
                $('#m_dashboard_daterangepicker')
                    .find('.m-subheader__daterange-date')
                    .html(start);
            });

            var lookQuestion = function(url,type){
                $('#_modal').modal(type?type:'show');
                mAppExtend.ajaxGetHtml(
                    '#_modal .modal-content',
                    url,
                    {},true);
            };
            $('.dynamic-add,.question-reply,.look-task').click(function(event){
                event.preventDefault();
                var url = $(this).attr('href');
                lookQuestion(url);
            });

        });
    </script>
@endsection