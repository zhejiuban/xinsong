@extends('layouts.app')

@section('content')

    <!--Begin::Main Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__body  m-portlet__body--no-padding">

            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-xl-5">
                    <!--begin:: Widgets/Profit Share-->
                    <div class="m-widget14">
                        <span class="m-subheader__daterange m--bg-brand " style="padding: 0;" id="m_dashboard_daterangepicker">
                            <span class="m-subheader__daterange-label">
                                <span class="m-subheader__daterange-title m--font-light">今日</span>
                                <span class="m-subheader__daterange-date m--font-light">{{current_date()}}</span>
                            </span>
                            <a href="javascript:;" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                                <i class="la la-angle-down"></i>
                            </a>
                        </span>

                        <a href="javascript:;" class=" btn btn-secondary  m-btn  m-btn--icon m-btn--icon-only m-btn--pill"
                           data-toggle="modal" data-target="#_needmodal" title="更多查询">
                            <i class="la la-ellipsis-h"></i>
                        </a>

                        <a href="{{url('project/dynamics?mid=a70ebe696b17f962d6271b83eee22c7c')}}" class=" btn btn-secondary  m-btn  m-btn--icon m-btn--icon-only m-btn--pill"
                           data-toggle="m-tooltip" data-skin="dark"  title="日志统计">
                            <i class="la la-list"></i>
                        </a>

                        <div class="row  align-items-center min-height-200" id="dynamic-need-count">
                        </div>
                    </div>
                    <!--end:: Widgets/Profit Share-->
                </div>
                <div class="col-xl-7 min-height-300" id="dynamic-need-user">
                </div>
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->

    <!--Begin::Main Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__body  m-portlet__body--no-padding">
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-xl-4">
                    <!--begin:: Widgets/Profit Share-->
                    <div class="m-widget14">
                        <div class="m-widget14__header">
                            <h3 class="m-widget14__title">
                                <a href="{{url('project/projects?mid=bd128edbfd250c9c5eff5396329011cd')}}">项目统计</a>
                            </h3>
                            <span class="m-widget14__desc">
                                今日项目：+{{$project['day_add']}}
                            </span>
                        </div>
                        <div class="row  align-items-center">
                            <div class="col">
                                <div id="m_chart_project" class="m-widget14__chart" style="height: 160px">
                                    <div class="m-widget14__stat">
                                        {{ $project['all'] }}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="m-widget14__legends">
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-accent"></span>
                                        <span class="m-widget14__legend-text">
                                            已完成 {{ $project['finished'] }}
                                        </span>
                                    </div>
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-danger"></span>
                                        <span class="m-widget14__legend-text">
                                            未开始 {{ $project['unstart'] }}
                                        </span>
                                    </div>
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-brand"></span>
                                        <span class="m-widget14__legend-text">
                                            进行中 {{ $project['processing'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Profit Share-->
                </div>
                <div class="col-xl-4">
                    <!--begin:: Widgets/Profit Share-->
                    <div class="m-widget14">
                        <div class="m-widget14__header">
                            <h3 class="m-widget14__title">
                                <a href="{{url('project/tasks?mid=da448943e7f05e99593248d4c86b7565')}}">任务统计</a>
                            </h3>
                            <span class="m-widget14__desc">
                                今日进行中任务：{{$task['day_processing']}}
                            </span>
                        </div>
                        <div class="row  align-items-center">
                            <div class="col">
                                <div id="m_chart_task" class="m-widget14__chart" style="height: 160px">
                                    <div class="m-widget14__stat">
                                        {{ $task['all'] }}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="m-widget14__legends">
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-accent"></span>
                                        <span class="m-widget14__legend-text">
                                            已完成 {{ $task['finished'] }}
                                        </span>
                                    </div>
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-brand"></span>
                                        <span class="m-widget14__legend-text">
                                            进行中 {{ $task['processing'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Profit Share-->
                </div>
                <div class="col-xl-4">
                    <div class="m-widget14">
                        <div class="m-widget14__header">
                            <h3 class="m-widget14__title">
                                <a href="{{url('question/questions?mid=3affc334d19fc914c4d667a01848f55d')}}">问题统计</a>
                            </h3>
                            <span class="m-widget14__desc">
                                今日问题：+{{$question['day_add']}}
                            </span>
                        </div>
                        <div class="row  align-items-center">
                            <div class="col">
                                <div id="m_chart_question" class="m-widget14__chart" style="height: 160px">
                                    <div class="m-widget14__stat">
                                        {{$question['all']}}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="m-widget14__legends">
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-danger"></span>
                                        <span class="m-widget14__legend-text">
                                            待接收 {{$question['receive']}}
                                        </span>
                                    </div>
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-brand"></span>
                                        <span class="m-widget14__legend-text">
                                            待回复 {{$question['reply']}}
                                        </span>
                                    </div>
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-info"></span>
                                        <span class="m-widget14__legend-text">
                                           已回复 {{$question['replyed']}}
                                        </span>
                                    </div>
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-success"></span>
                                        <span class="m-widget14__legend-text">
                                            已关闭 {{$question['close']}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->

    <div class="m-portlet">
        <div class="m-portlet__body  m-portlet__body--no-padding">
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-md-12 col-lg-6 col-xl-4">
                    <!--begin::Total Profit-->
                    <div class="m-widget24">
                        <div class="m-widget24__item">
                            <h4 class="m-widget24__title">
                                <a href="{{url('malfunction/malfunctions?mid=0bd559bbe15fe3f5b7a2ed3efdd3e901')}}">故障记录</a>
                            </h4>
                            <br>
                            <span class="m-widget24__desc">
                                分部记录
                            </span>
                            <span class="m-widget24__stats m--font-brand">
                                @inject('malfunction', 'App\Malfunction')
                                {{$malfunction->companySearch()->count()}}
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
                                <a href="{{url('project/dynamics?mid=a70ebe696b17f962d6271b83eee22c7c')}}">日志统计</a>
                            </h4>
                            <br>
                            <span class="m-widget24__desc">
                                分部日志
                            </span>
                            <span class="m-widget24__stats m--font-info">
                                @inject('dynamic', 'App\Dynamic')
                                {{$dynamic->companySearch()->count()}}
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
                                <a href="{{url('user/users?mid=833c2d485b891dcc28c43c3d5ea061e4')}}">用户统计</a>
                            </h4>
                            <br>
                            <span class="m-widget24__desc">
                                分部用户
                            </span>
                            <span class="m-widget24__stats m--font-danger">
                                {{get_company_user()->count()}}
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
            <div class="m-portlet m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-info"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-primary">
                                待我回复问题
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <a href="{{url('question/create?direct=1&mid=5e5fa7160f2d8bf507f11ac18455f61e')}}" class="m-portlet__nav-link btn btn-secondary m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill">
                                    <i class="la la-plus"></i>
                                </a>
                            </li>
                            <li class="m-portlet__nav-item">
                                <a href="{{url('question/pending?mid=8053529e293f7baef9b15cad1fa80eb6')}}" class="m-portlet__nav-link btn btn-secondary m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill">
                                    <i class="la la-ellipsis-v"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-scrollable" data-scrollable="true" data-max-height="250" data-mobile-max-height="200" data-scrollbar-shown="true">
                        <div class="m-widget4">
                            @if($question['need_reply']->isNotEmpty())
                                @foreach($question['need_reply'] as $q)
                                    <div class="m-widget4__item" style="padding: 5px 0;">
                                        <div class="m-widget4__info m--padding-left-0">
                                    <span class="m-widget4__text">
                                        <a href="{{ route('questions.reply',['question'=>$q->id,'mid'=>request('mid')]) }}" title="查看并回复"
                                           class="m--font-default question-reply">
                                            {{$q->title}}
                                        </a>
                                    </span>
                                        </div>
                                        <div class="m-widget4__ext">
                                            <a href="{{ route('questions.reply',['question'=>$q->id,'mid'=>request('mid')]) }}" title="查看并回复"
                                               class="m-widget4__icon question-reply">
                                                <i class="la la-reply"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="success-info text-center">
                                    暂无要回复的问题
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="col-xl-6">
            <div class="m-portlet m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">

                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                </div>
            </div>
        </div>--}}
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
                                   placeholder="开始日期" name="start" id="start" readonly value="{{current_date()}}"/>
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
                var start = moment();
                var end = moment();

                function cb(start, end, label) {
                    var title = '';
                    range = start.format('YYYY-MM-DD');
                    if (range == '{{current_date()}}') {
                        title = '今日';
                    }
                    picker.find('.m-subheader__daterange-date').html(range);
                    picker.find('.m-subheader__daterange-title').html(title);

                    mAppExtend.ajaxGetHtml(
                        '#dynamic-need-count',
                        "{{route('dynamic.need.add.count')}}",
                        {
                            'start':start.format('YYYY-MM-DD'),
                            'end':end.format('YYYY-MM-DD')
                        },
                        "#dynamic-need-count",null,null,
                        false
                    );
                    mAppExtend.ajaxGetHtml(
                        '#dynamic-need-user',
                        "{{route('dynamic.need.add.user')}}",
                        {
                            'start':start.format('YYYY-MM-DD'),
                            'end':end.format('YYYY-MM-DD')
                        },
                        "#dynamic-need-user",null,null,
                        false
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
                    '#dynamic-need-count',
                    "{{route('dynamic.need.add.count')}}",
                    {
                        'start':start,
                        'project_id':project_id
                    },
                    "#dynamic-need-count",null,null,
                    false
                );
                mAppExtend.ajaxGetHtml(
                    '#dynamic-need-user',
                    "{{route('dynamic.need.add.user')}}",
                    {
                        'start':start,
                        'project_id':project_id
                    },
                    "#dynamic-need-user",null,null,
                    false
                );
                $("#_needmodal").modal('hide');
                $('#m_dashboard_daterangepicker')
                    .find('.m-subheader__daterange-date')
                    .html(start);
            });

            var project = function() {
                if ($('#m_chart_project').length == 0) {
                    return;
                }
                var chart = new Chartist.Pie('#m_chart_project', {
                    series: [{
                            value: {{ $project['processing'] }},
                            className: 'custom',
                            meta: {
                                color: mUtil.getColor('brand')
                            }
                        },
                        {
                            value: {{ $project['finished'] }},
                            className: 'custom',
                            meta: {
                                color: mUtil.getColor('accent')
                            }
                        },
                        {
                            value: {{ $project['unstart'] }},
                            className: 'custom',
                            meta: {
                                color: mUtil.getColor('danger')
                            }
                        }
                    ],
                    labels: [1, 2,3]
                }, {
                    donut: true,
                    donutWidth: 17,
                    showLabel: false
                });

                chart.on('draw', function(data) {
                    if (data.type === 'slice') {
                        // Get the total path length in order to use for dash array animation
                        var pathLength = data.element._node.getTotalLength();

                        // Set a dasharray that matches the path length as prerequisite to animate dashoffset
                        data.element.attr({
                            'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
                        });

                        // Create animation definition while also assigning an ID to the animation for later sync usage
                        var animationDefinition = {
                            'stroke-dashoffset': {
                                id: 'anim' + data.index,
                                dur: 500,
                                from: -pathLength + 'px',
                                to: '0px',
                                easing: Chartist.Svg.Easing.easeOutQuint,
                                // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
                                fill: 'freeze',
                                'stroke': data.meta.color
                            }
                        };

                        // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
                        if (data.index !== 0) {
                            animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
                        }

                        // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us

                        data.element.attr({
                            'stroke-dashoffset': -pathLength + 'px',
                            'stroke': data.meta.color
                        });

                        // We can't use guided mode as the animations need to rely on setting begin manually
                        // See http://gionkunz.github.io/chartist-js/api-documentation.html#chartistsvg-function-animate
                        data.element.animate(animationDefinition, false);
                    }
                });
            };
            project();
            var task = function() {
                if ($('#m_chart_task').length == 0) {
                    return;
                }
                var chart = new Chartist.Pie('#m_chart_task', {
                    series: [{
                        value: {{ $task['processing'] }},
                        className: 'custom',
                        meta: {
                            color: mUtil.getColor('brand')
                        }
                    },
                        {
                            value: {{ $task['finished'] }},
                            className: 'custom',
                            meta: {
                                color: mUtil.getColor('accent')
                            }
                        }
                    ],
                    labels: [1, 2 ]
                }, {
                    donut: true,
                    donutWidth: 17,
                    showLabel: false
                });

                chart.on('draw', function(data) {
                    if (data.type === 'slice') {
                        // Get the total path length in order to use for dash array animation
                        var pathLength = data.element._node.getTotalLength();

                        // Set a dasharray that matches the path length as prerequisite to animate dashoffset
                        data.element.attr({
                            'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
                        });

                        // Create animation definition while also assigning an ID to the animation for later sync usage
                        var animationDefinition = {
                            'stroke-dashoffset': {
                                id: 'anim' + data.index,
                                dur: 500,
                                from: -pathLength + 'px',
                                to: '0px',
                                easing: Chartist.Svg.Easing.easeOutQuint,
                                // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
                                fill: 'freeze',
                                'stroke': data.meta.color
                            }
                        };

                        // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
                        if (data.index !== 0) {
                            animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
                        }

                        // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us

                        data.element.attr({
                            'stroke-dashoffset': -pathLength + 'px',
                            'stroke': data.meta.color
                        });

                        // We can't use guided mode as the animations need to rely on setting begin manually
                        // See http://gionkunz.github.io/chartist-js/api-documentation.html#chartistsvg-function-animate
                        data.element.animate(animationDefinition, false);
                    }
                });
            };
            task();
            var question = function() {
                if ($('#m_chart_question').length == 0) {
                    return;
                }
                var chart = new Chartist.Pie('#m_chart_question', {
                    series: [{
                        value: {{$question['reply']}},
                        className: 'custom',
                        meta: {
                            color: mUtil.getColor('brand')
                        }
                    },
                        {
                            value: {{$question['close']}},
                            className: 'custom',
                            meta: {
                                color: mUtil.getColor('success')
                            }
                        },
                        {
                            value: {{$question['receive']}},
                            className: 'custom',
                            meta: {
                                color: mUtil.getColor('danger')
                            }
                        },
                        {
                            value: {{$question['replyed']}},
                            className: 'custom',
                            meta: {
                                color: mUtil.getColor('info')
                            }
                        }
                    ],
                    labels: [1, 2, 3,4]
                }, {
                    donut: true,
                    donutWidth: 17,
                    showLabel: false
                });

                chart.on('draw', function(data) {
                    if (data.type === 'slice') {
                        // Get the total path length in order to use for dash array animation
                        var pathLength = data.element._node.getTotalLength();

                        // Set a dasharray that matches the path length as prerequisite to animate dashoffset
                        data.element.attr({
                            'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
                        });

                        // Create animation definition while also assigning an ID to the animation for later sync usage
                        var animationDefinition = {
                            'stroke-dashoffset': {
                                id: 'anim' + data.index,
                                dur: 500,
                                from: -pathLength + 'px',
                                to: '0px',
                                easing: Chartist.Svg.Easing.easeOutQuint,
                                // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
                                fill: 'freeze',
                                'stroke': data.meta.color
                            }
                        };

                        // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
                        if (data.index !== 0) {
                            animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
                        }

                        // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us

                        data.element.attr({
                            'stroke-dashoffset': -pathLength + 'px',
                            'stroke': data.meta.color
                        });

                        // We can't use guided mode as the animations need to rely on setting begin manually
                        // See http://gionkunz.github.io/chartist-js/api-documentation.html#chartistsvg-function-animate
                        data.element.animate(animationDefinition, false);
                    }
                });
            };
            question();

            var lookQuestion = function(url,type){
                $('#_modal').modal(type?type:'show');
                mAppExtend.ajaxGetHtml(
                    '#_modal .modal-content',
                    url,
                    {},true);
            }
            $('.question-reply').click(function(event){
                event.preventDefault();
                var url = $(this).attr('href');
                lookQuestion(url);
            });
        });
    </script>
@endsection
