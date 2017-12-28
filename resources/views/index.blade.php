@extends('layouts.app')

@section('content')

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
