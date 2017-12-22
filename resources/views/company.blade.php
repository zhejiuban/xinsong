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
                                <span class="m-subheader__daterange-title m--font-light"></span>
                                <span class="m-subheader__daterange-date m--font-light">{{current_date()}}</span>
                            </span>
                            <a href="javascript:;" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                                <i class="la la-angle-down"></i>
                            </a>
                        </span>

                        <a href="javascript:;" class=" btn btn-secondary  m-btn  m-btn--icon m-btn--icon-only m-btn--pill"
                           data-toggle="m-tooltip" title="更多查询" data-html="true" data-skin="dark">
                            <i class="la la-ellipsis-h"></i>
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
                                项目统计
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
                                任务统计
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
                                问题统计
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

    <!--begin:: Widgets/Audit Log-->
    <div class="m-portlet m-portlet--full-height">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        日志统计
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link active" >
                            日
                        </a>
                    </li>
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link">
                            周
                        </a>
                    </li>
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" >
                            月
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="dynamics" style="width: 100%;height:400px;">

            </div>
        </div>
    </div>
    <!--end:: Widgets/Audit Log-->

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

        $(document).ready(function(){
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
        });
    </script>
@endsection
