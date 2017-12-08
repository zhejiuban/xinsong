<script type="text/javascript">
var profitShare = function() {
    if ($('#m_chart_profit_share').length == 0) {
        return;
    }

    var chart = new Chartist.Pie('#m_chart_profit_share', {
        series: [{
                value: {{100 - $project->finishPrecent()}},
                className: 'custom',
                meta: {
                    color: mUtil.getColor('danger')
                }
            },
            {
                value: {{$project->finishPrecent()}},
                className: 'custom',
                meta: {
                    color: mUtil.getColor('success')
                }
            }
        ],
        labels: ['未完成', '已完成']
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
                    dur: 1000,
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

            data.element.animate(animationDefinition, false);
        }
    });
}
$(document).ready(function(){
    profitShare();
    $('#project-start,#project-finish').click(function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        mAppExtend.ajaxPostSubmit({
            'url':url
        });
    });
    $("#project-delete").click(function(event){
        event.preventDefault();
        var url = $(this).attr('href');
        mAppExtend.deleteData({
            'url':url
        });
    });
});
</script>