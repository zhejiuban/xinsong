<div class="m-actions">
    @if($task = $project->checkUserTaskDayDynamic())
        <div class="alert alert-warning alert-dismissible fade show   m-alert m-alert--square m-alert--air"
             role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            <strong>温馨提醒：</strong>
            今日您未上传日志，请尽快上传
        </div>
    @endif
    <span class="m-subheader__daterange m--bg-brand " style="padding: 0;margin-bottom:20px;" id="m_daterangepicker">
        <span class="m-subheader__daterange-label">
            <span class="m-subheader__daterange-title m--font-light"></span>
            <span class="m-subheader__daterange-date m--font-light">{{request('date',current_date())}}</span>
        </span>
        <a href="javascript:;"
           class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
            <i class="la la-angle-down"></i>
        </a>
    </span>
    @if($task)
        <a href="{{ route('dynamics.create',['project_id'=>$project->id,'task_id'=>$task->id,'mid'=>request('mid'),'board'=>1]) }}"
           class="m-nav__link dynamic-add  btn btn-sm btn-primary  m-btn--pill">
            <i class="m-nav__link-icon flaticon-add"></i>
            <span class="m-nav__link-text">
            上传日志
        </span>
        </a>
    @endif
    <a href="{{ route('project.dynamics',['project_id'=>$project->id,'all'=>1,'mid'=>request('mid')]) }}"
       class="m-nav__link  btn btn-sm btn-secondary  m-btn--pill "
       data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
        <i class="m-nav__link-icon flaticon-list"></i>
        <span class="m-nav__link-text">
            所有日志
        </span>
    </a>
    <a href="{{ route('project.dynamics',['project_id'=>$project->id,'only'=>1,'mid'=>request('mid')]) }}"
       class="m-nav__link  btn btn-sm btn-secondary  m-btn--pill "
       data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
        <i class="m-nav__link-icon flaticon-user"></i>
        <span class="m-nav__link-text">
            只看自己的
        </span>
    </a>
</div>
@php $dy_users = $project->getUnAddUserTaskDynamic(request('date'));@endphp
@if(check_project_owner($project,'edit') && $dy_users->isNotEmpty())
    <div class="m-section m--margin-top-15">
        <div class="m-section__content">
            <div class="m-demo">
                <div class="m-demo__preview m--padding-15">
                    <h6 class="m--font-danger">未上传日志人员</h6>
                    @foreach($dy_users as $user)
                        <div class="m-widget-user m--padding-10">
                            <div class="m-widget-img">
                                <img src="{{avatar($user->leaderUser->avatar)}}" alt="">
                            </div>
                            <div class="m-widget-text">
                                {{$user->leaderUser->name}}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
<div class="m-widget3">
    @foreach($dynamics as $dynamic)
        <div class="m-widget3__item" id="dynamic-{{$dynamic->id}}">
            <div class="m-widget3__header">
                <div class="m-widget3__user-img">
                    <img class="m-widget3__img" src="{{avatar($dynamic->user?$dynamic->user->avatar:null)}}" alt="">
                </div>
                <div class="m-widget3__info">
                <span class="m-widget3__username">
                    {{$dynamic->user?$dynamic->user->name:null}}
                </span>
                    <br>
                    <span class="m-widget3__time">
                    {{$dynamic->created_at->diffForHumans()}}
                </span>
                </div>
                {{--<span class="m-widget3__status" style="float: none;">
                    @if(check_project_owner($dynamic->project,'company') || $dynamic->user->id == get_current_login_user_info())
                    <a href="{{ route('dynamics.edit',['dynamic'=>$dynamic->id,'mid'=>request('mid')]) }}"
                       class="dynamic-edit btn btn-outline-brand m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill"><i class="la la-edit"></i></a>
                    @endif
                </span>--}}
                <span class="m-widget3__status" style="float: none;padding-left:10px;">
                @if(check_project_owner($dynamic->project,'company'))
                    <a href="{{ route('dynamics.destroy',['dynamic'=>$dynamic->id,'mid'=>request('mid')]) }}"
                       class="dynamic-del btn btn-outline-danger m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                        <i class="la la-trash"></i></a>
                @endif
            </span>
            </div>
            <div class="m-widget3__body">
                <p class="m-widget3__text">
                    {{$dynamic->content}}
                </p>
            </div>
        </div>
    @endforeach
</div>
{{ $dynamics->appends([
    'mid' => request('mid'),
    'only'=>request('only'),
    'date'=>request('date'),
    'all'=>request('all'),
    ])->links('vendor.pagination.project-board-ajax') }}

<script type="text/javascript">
    var ActionModal = function (url, type) {
        $('#_modal').modal(type ? type : 'show');
        mAppExtend.ajaxGetHtml(
            '#_modal .modal-content',
            url,
            {}, true);
    }
    $(document).ready(function () {
        $('.dynamic-add,.dynamic-edit').click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            ActionModal(url);
        });
        $('.dynamic-del').click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            mAppExtend.deleteData({
                'url': url,
                callback: function (response, status, xhr) {
                    if (response.data.id) {
                        $("#dynamic-" + response.data.id).fadeOut('slow');
                    }
                }
            });
        });
        var daterangepickerInit = function() {
            if ($('#m_daterangepicker').length == 0) {
                return;
            }
            var picker = $('#m_daterangepicker');
            var start = "{{request('date',current_date())}}";
            var end = moment();
            function cb(start, end, label) {
                var title = '';
                range = start.format('YYYY-MM-DD');
                picker.find('.m-subheader__daterange-date').html(range);
                picker.find('.m-subheader__daterange-title').html(title);
                mAppExtend.ajaxGetHtml(
                    '#project-body',
                    "{{ route('project.dynamics',['project'=>$project->id,'mid'=>request('mid')]) }}",
                    {
                        'date':range
                    },
                    "#project-body"
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
                    // '昨日': [moment().subtract(1, 'days'), moment().subtract(1, 'days')]
                }
            }, cb);
        };
        daterangepickerInit();
    });
</script>
