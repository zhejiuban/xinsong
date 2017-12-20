@extends('layouts.app')

@section('content')
    @if($needAddDynamicTask->isNotEmpty())
    <div class="m-alert m-alert--icon m-alert--air m-alert--square alert alert-dismissible fade show alert-warning" role="alert">
        <div class="m-alert__icon">
            <i class="la la-warning"></i>
        </div>
        <div class="m-alert__text">
            <strong>
                警告：
            </strong>
            您今日有日志未上传，请尽快上传
        </div>
        <div class="m-alert__close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif
    @foreach($needAddDynamicTask as $task)
    <!--Begin::Main Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__body  m-portlet__body--no-padding">
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-xl-11">
                    <div class="m-widget1">
                        {{$task->content}}
                    </div>
                </div>
                <div class="col-xl-1 text-center">
                    <button href="{{ route('dynamics.create',['project_id'=>$task->project_id,'task_id'=>$task->id,'mid'=>request('mid')]) }}"
                            class="dynamic-add btn m-btn--square btn-secondary full-width-height btn-border-none m--padding-10 m--border-radius-none">
                        <i class="flaticon-add"></i>
                        <p class="m--margin-0 m--font-default">添加日志</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->
    @endforeach
    <!--Begin::Main Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__body  m-portlet__body--no-padding">
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-xl-4">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="m-widget1">
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        任务
                                    </h3>
                                    <span class="m-widget1__desc">
                                        进行中：
                                        <span class="m--font-danger">
                                            {{$user->leaderTasks()->where('status',0)->count()}}
                                        </span>
                                    </span>
                                </div>
                                <div class="col m--align-right">
                                    <span class="m-widget1__number m--font-brand">
                                        {{$user->leaderTasks()->count()}}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        日志
                                    </h3>
                                    <span class="m-widget1__desc">
                                        今日日志：
                                        +{{$user->dynamics()->whereBetween('created_at', [
                                            date_start_end(), date_start_end(null, 'end')
                                        ])->count()}}
                                    </span>
                                </div>
                                <div class="col m--align-right">
                                    <span class="m-widget1__number m--font-brand">
                                        {{$user->dynamics()->count()}}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        文档
                                    </h3>
                                    <span class="m-widget1__desc">
                                        今日文档：
                                            +{{$user->files()->whereBetween('created_at', [
                                            date_start_end(), date_start_end(null, 'end')
                                        ])->count()}}
                                    </span>
                                </div>
                                <div class="col m--align-right">
                                    <span class="m-widget1__number m--font-brand">
                                        {{$user->files()->count()}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-xl-4">
                    <!--begin:: Widgets/Stats2-1 -->
                    {{--<div class="m-widget1">
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        问题
                                    </h3>
                                    <span class="m-widget1__desc">
                                        待办问题：
                                        <span class="m--font-danger">
                                            {{$user->receiveQuestions()->count()}}
                                        </span>
                                    </span>
                                </div>
                                <div class="col m--align-right">
                                    <span class="m-widget1__number m--font-brand">
                                        {{$user->questions()->count()}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                   <!--end:: Widgets/Stats2-1 -->
                    <!--begin:: Widgets/Profit Share-->
                    <div class="m-widget14">
                        <div class="m-widget14__header">
                            <h3 class="m-widget14__title">
                                我发布的问题
                            </h3>
                        </div>
                        <div class="row  align-items-center">
                            <div class="col">
                                <div id="m_chart_question" class="m-widget14__chart" style="height: 160px">
                                    <div class="m-widget14__stat">
                                        {{$user->questions()->count()}}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="m-widget14__legends">
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-danger"></span>
                                        <span class="m-widget14__legend-text">
                                            {{$receive = $user->questions()->where('status',0)->count()}} 待接收
                                        </span>
                                    </div>
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-brand"></span>
                                        <span class="m-widget14__legend-text">
                                            {{$reply = $user->questions()->where('status',1)->count()}} 待回复
                                        </span>
                                    </div>
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-info"></span>
                                        <span class="m-widget14__legend-text">
                                            {{$replyed = $user->questions()->where('status',2)->count()}} 已回复
                                        </span>
                                    </div>
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-success"></span>
                                        <span class="m-widget14__legend-text">
                                            {{$close = $user->questions()->where('status',3)->count()}} 已关闭
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
                                我参与的项目
                            </h3>
                        </div>
                        <div class="row  align-items-center">
                            <div class="col">
                                <div id="m_chart_profit_share" class="m-widget14__chart" style="height: 160px">
                                    <div class="m-widget14__stat">
                                        {{$user->projects()->count()}}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="m-widget14__legends">
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-accent"></span>
                                        <span class="m-widget14__legend-text">
                                            {{$finish = $user->projects()->where('status',2)->count()}} 已完成
                                        </span>
                                    </div>
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-danger"></span>
                                        <span class="m-widget14__legend-text">
                                            {{$unstart = $user->projects()->where('status',0)->count()}} 未开始
                                        </span>
                                    </div>
                                    <div class="m-widget14__legend">
                                        <span class="m-widget14__legend-bullet m--bg-brand"></span>
                                        <span class="m-widget14__legend-text">
                                            {{$processing = $user->projects()->where('status',1)->count()}} 进行中
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Profit Share-->
                </div>
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->

    <!--begin::Modal-->
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="_ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->

    {{--<div class="row">
        <div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                任务
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                </div>
                <!--end::Section-->
            </div>
        </div>
        <div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                项目
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                </div>
                <!--end::Section-->
            </div>
        </div>

        <div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                日志
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                </div>
                <!--end::Section-->
            </div>
        </div>

        <div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                问题
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                </div>
                <!--end::Section-->
            </div>
        </div>

    </div>--}}
@endsection
@section('js')

    <script type="text/javascript">
        var ActionModal = function(url,type){
            $('#_modal').modal(type?type:'show');
            mAppExtend.ajaxGetHtml(
                '#_modal .modal-content',
                url,
                {},true);
        }
        $(document).ready(function(){
            $('.dynamic-add').click(function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                ActionModal(url);
            });
            //== Profit Share Chart.
            //** Based on Chartist plugin - https://gionkunz.github.io/chartist-js/index.html
            var question = function() {
                if ($('#m_chart_question').length == 0) {
                    return;
                }
                var chart = new Chartist.Pie('#m_chart_question', {
                    series: [{
                        value: {{$reply}},
                        className: 'custom',
                        meta: {
                            color: mUtil.getColor('brand')
                        }
                    },
                        {
                            value: {{$close}},
                            className: 'custom',
                            meta: {
                                color: mUtil.getColor('success')
                            }
                        },
                        {
                            value: {{$receive}},
                            className: 'custom',
                            meta: {
                                color: mUtil.getColor('danger')
                            }
                        },
                        {
                            value: {{$replyed}},
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
            var profitShare = function() {
                if ($('#m_chart_profit_share').length == 0) {
                    return;
                }
                var chart = new Chartist.Pie('#m_chart_profit_share', {
                    series: [{
                        value: {{$processing}},
                        className: 'custom',
                        meta: {
                            color: mUtil.getColor('brand')
                        }
                    },
                        {
                            value: {{$finish}},
                            className: 'custom',
                            meta: {
                                color: mUtil.getColor('accent')
                            }
                        },
                        {
                            value: {{$unstart}},
                            className: 'custom',
                            meta: {
                                color: mUtil.getColor('danger')
                            }
                        }
                    ],
                    labels: [1, 2, 3]
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
            profitShare();

        });
    </script>
@endsection
