<div class="m-widget1">
    <div class="m-widget1__headers">
        <h3 class="m-widget1__title">
            未上传日志人员
        </h3>
    </div>
    <div class="m-scrollable" data-scrollable="true" data-max-height="210" style="height: 210px; overflow: hidden;">
        @foreach($needAddDynamicTask as $user)
        @if($user->leaderUser)
        <div class="m-widget-user m--padding-10">
            <div class="m-widget-img">
                <img src="{{avatar($user->leaderUser->avatar)}}" alt="">
            </div>
            <div class="m-widget-text">
                {{$user->leaderUser->name}}
            </div>
        </div>
        @endif
        @endforeach
        @if($needAddDynamicTask->isEmpty())
        <div class="m--margin-top-20 m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-success alert-dismissible fade show" role="alert">
            <div class="m-alert__icon">
                <i class="flaticon-list-2"></i>
                <span></span>
            </div>
            <div class="m-alert__text">
                <strong>
                    恭喜！
                </strong>
                今日员工日志已全部上传
            </div>
            <div class="m-alert__close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        mApp.initScroller($('.m-scrollable'), {});
    });
</script>