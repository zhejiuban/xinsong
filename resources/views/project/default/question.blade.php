<div class="m-actions">

    <a href="{{ route('questions.create',['project_id'=>$project->id,'mid'=>request('mid'),'board'=>1]) }}" id="question-add"
       class="m-nav__link  btn btn-sm btn-primary  m--margin-bottom-20 m-btn--pill">
        <i class="m-nav__link-icon flaticon-add"></i>
        <span class="m-nav__link-text">
            发布问题
        </span>
    </a>

    <a href="{{ route('project.questions',['project_id'=>$project->id,'mid'=>request('mid')]) }}"
       class="m-nav__link  btn btn-sm btn-secondary  m--margin-bottom-20 m-btn--pill"
       data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
        <i class="m-nav__link-icon flaticon-list"></i>
        <span class="m-nav__link-text">
            所有的问题
        </span>
    </a>

    <a href="{{ route('project.questions',['project_id'=>$project->id,'only'=>1,'mid'=>request('mid')]) }}"
       class="m-nav__link  btn btn-sm btn-secondary  m--margin-bottom-20 m-btn--pill"
       data-toggle="relaodHtml" data-target="#project-body" data-loading="#project-body">
        <i class="m-nav__link-icon flaticon-user"></i>
        <span class="m-nav__link-text">
            只看我的问题
        </span>
    </a>

</div>
<div class="m-widget5">
    @foreach($questions as $question)
    <div id="question-{{$question->id}}" class="m-widget5__item">
        <div class="m-widget5__content m--padding-left-none" style="padding-left: 0;">
            <h4 class="m-widget5__title">
                <a href="{{ route('questions.show',['question'=>$question->id,'mid'=>request('mid')]) }}" class="m--font-default question-look">{{$question->title}}</a>
            </h4>
            {{--<span class="m-widget5__desc">--}}
                {{--{{str_limit($question->content,150,'...')}}--}}
            {{--</span>--}}
            <div class="m-widget5__info">
                <span class="m-widget5__author">
                    上报人:
                </span>
                <span class="m-widget5__info-author-name">
                    {{$question->user ? $question->user->name : null}}
                </span>
                <br>
                <span class="m-widget5__info-label">
                    时间:
                </span>
                <span class="m-widget5__info-date m--font-info">
                    {{$question->created_at->diffForHumans()}}
                </span>
                <span class="m-widget5__info-action ">
                    @if(check_project_owner($project,'company') || (!$question->status && $question->user_id == get_current_login_user_info()))
                     @if(!$question->status)
                        <a href="{{ route('questions.edit',['question'=>$question->id,'mid'=>request('mid'),'board'=>1])}}"
                        class="question-edit  btn btn-outline-primary m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i>
                        </a>
                    @endif
                    <a href="{{ route('questions.destroy',['question'=>$question->id,'id'=>$question->id]) }}"
                       class="btn btn-outline-danger m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill question-del">
                        <i class="la la-trash"></i></a>
                    @endif
                    @if(($question->status < 3) && $question->receive_user_id == get_current_login_user_info())
                    <a href="{{ route('questions.reply',['question'=>$question->id,'board'=>1,'mid'=>request('mid')]) }}"  title="回复"
                       class="question-reply btn btn-outline-primary m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                        <i class="la la-reply"></i></a>
                    @else
                    <a href="{{ route('questions.show',['question'=>$question->id]) }}"
                           class="question-look btn btn-outline-info m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                    <i class="la la-eye"></i></a>
                    @endif
                    @if((check_project_owner($project,'company') || $question->user_id == get_current_login_user_info()) && $question->status == 2)
                        <a href="{{ route('questions.finished',['question'=>$question->id,'board'=>1,'mid'=>request('mid')]) }}" title="关闭"
                           class="question-close btn btn-outline-danger m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                        <i class="la la-power-off"></i></a>
                    @endif

                </span>
            </div>
        </div>
        <div class="m-widget5__stats1">
            <span class="m-widget5__number">
                <span class="m-badge {{question_status($question->status,'class')}} m-badge--wide">{{question_status($question->status)}}</span>
            </span>
        </div>
        <div class="m-widget5__stats2">
            <span class="m-widget5__number">
                {{intval($question->click)}}
            </span>
            <br>
            <span class="m-widget5__votes">
                热度
            </span>
        </div>
    </div>
    @endforeach
</div>
{{ $questions->appends([
'mid' => request('mid'),
'only' => request('only')
])->links('vendor.pagination.project-board-ajax') }}
<script type="text/javascript">
    var lookQuestion = function(url,type){
        $('#_modal').modal(type?type:'show');
        mAppExtend.ajaxGetHtml(
            '#_modal .modal-content',
            url,
            {},true);
    }
    $(document).ready(function(){
        $('.question-look,.question-reply,#question-add,.question-edit').click(function(event){
            event.preventDefault();
            var url = $(this).attr('href');
            lookQuestion(url);
        });
        $('.question-del').click(function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            mAppExtend.deleteData({
                'url':url,
                callback:function(response, status, xhr){
                    if(response.data[0]){
                        $("#question-"+response.data[0]).fadeOut('slow');
                    }
                }
            });
        });
        $('.question-close').click(function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            mAppExtend.confirmControllData({
                'title':'你确定要关闭问题吗？',
                'url':url,
                'data':{_method:'POST'},
                callback:function(response, status, xhr){
                    mAppExtend.ajaxGetHtml(
                        "#project-body","{!! get_redirect_url('board_ajax_url') !!}"
                        , {}, "#project-body");
                }
            });
        });
    });
</script>
