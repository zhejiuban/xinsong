<div class="col">
    <div id="m_chart_dynamic" class="m-widget14__chart" style="height: 200px">
        <div class="m-widget14__stat">
            {{ $all->count() ? round(($all->count() - $needAddDynamicTask->count())  / $all->count() * 100) : 100}}%
        </div>
    </div>
</div>
<div class="col">
    <div class="m-widget14__legends">
        <div class="m-widget14__legend">
            <span class="m-widget14__legend-bullet m--bg-accent"></span>
            <span class="m-widget14__legend-text">
                已上传 {{ $haved_count = $all->count() - $needAddDynamicTask->count()}}
            </span>
        </div>
        <div class="m-widget14__legend">
            <span class="m-widget14__legend-bullet m--bg-danger"></span>
            <span class="m-widget14__legend-text">
                未上传 {{ $unhaved_count = $needAddDynamicTask->count() }}
            </span>
        </div>
        <div class="m-widget14__legend">
            <span class="m-widget14__legend-bullet m--bg-brand"></span>
            <span class="m-widget14__legend-text">
                需上传 {{$all->count()}}
            </span>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var profitShare = function () {
            if ($('#m_chart_dynamic').length == 0) {
                return;
            }
            var chart = new Chartist.Pie('#m_chart_dynamic', {
                series: [
                    {
                        value: {{$haved_count}},
                        className: 'custom',
                        meta: {
                            color: mUtil.getColor('accent')
                        }
                    },
                    {
                        value: {{$unhaved_count}},
                        className: 'custom',
                        meta: {
                            color: mUtil.getColor('danger')
                        }
                    }
                ],
                labels: [1, 2]
            }, {
                donut: true,
                donutWidth: 17,
                showLabel: false
            });

            chart.on('draw', function (data) {
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