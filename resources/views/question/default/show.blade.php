<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
        问题详情
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
    </button>
</div>
<div class="modal-body padding-top-bottom-none padding-left-right-15">
    <form class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--group-padding-left-right-none">
        <div class="form-group m-form__group row">
            <label class="col-lg-2 col-form-label">
                标题:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{$question->title}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                所属项目:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $question->project ? $question->project->title : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                所属版块:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $question->category ? $question->category->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                上报人:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{ $question->user ? $question->user->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                上报时间:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $question->created_at}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                问题内容:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $question->content}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                相关附件:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text clearfix multi-image-upload">
                    @if ($question->file and $question->file->isNotEmpty())
                        @foreach ($question->file as $key => $value)
                            @if(is_image($value->suffix))
                                <div id="FILE_{{$value->id}}" title="{{$value->old_name}}" data-container="body"
                                     data-toggle="m-tooltip" data-placement="top"
                                     data-original-title="FILE_{{$value->old_name}}"
                                     class="file-item tooltips pull-left">
                                    <div class="file-preview">
                                        <span class="preview">
                                          <a href="{{asset($value->path)}}" data-lightbox="roadtrip">
                                            <img class="" src="{{asset($value->path)}}">
                                          </a>
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div id="FILE_{{$value->id}}" title="{{$value->old_name}}" data-container="body"
                                     data-toggle="m-tooltip" data-placement="top"
                                     data-original-title="FILE_{{$value->old_name}}"
                                     class="file-item tooltips pull-left">
                                    <div class="file-item-bg bg-grey-cararra full-height">
                                        <div class="text-right file-delete">
                                            <a href="{{ route('file.download',['file'=>$value->uniqid]) }}" target="_blank" title="下载"
                                               data-file-id="FILE_{{$value->id}}" class="m--font-primary" >
                                                <i class="fa fa-download"></i></a>
                                        </div>
                                        <div class="file-progress text-center "></div>
                                        <div class="file-state text-center">10.04K</div>
                                        <div class="file-info text-center" title="{{$value->old_name}}">{{$value->old_name}}</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-2 col-form-label">
                接收人:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    {{ $question->receiveUser ? $question->receiveUser->name : null }}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                接收时间:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $question->received_at}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                回复内容:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $question->reply_content}}
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                回复时间:
            </label>
            <div class="col-lg-9">
                <div class="form-static-text">
                    {{ $question->replied_at}}
                </div>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-2 col-form-label">
                状态:
            </label>
            <div class="col-lg-4">
                <div class="form-static-text">
                    <span class="m-badge {{config('common.question_status.'.intval($question->status).'.class')}} m-badge--wide">{{config('common.question_status.'.intval($question->status).'.title')}}</span>
                </div>
            </div>
            <label class="col-lg-2 col-form-label">
                关闭时间:
            </label>
            <div class="col-lg-3">
                <div class="form-static-text">
                    {{ $question->finished_at}}
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
        关闭
    </button>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
    });
</script>
