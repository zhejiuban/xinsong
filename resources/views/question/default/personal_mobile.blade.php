@extends('layouts.app')

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <form method="get" action="{{route('question.personal',['mid'=>request('mid')])}}"
              class="m-form m-form--fit m-form--group-seperator-dashed m-form-group-padding-bottom-10">
            <div class="m-portlet__body ">
                <div class=" m-form__group row align-items-center">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <select name="project_id" class="form-control" id="project_id">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" class="form-control m-input m-date"
                                   placeholder="发布日期" name="date" id="date"
                                   readonly value=""/>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" class="form-control m-input" name="search" placeholder="关键字..."
                                   id="m_form_search">
                        </div>
                    </div>
                </div>
                <div class=" m-form__group row align-items-center">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <select name="status" class="form-control" id="status">
                                {!! question_status_select() !!}
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="hidden" name="mid" value="{{request('mid')}}">
                            <button type="submit" class="btn btn-brand  m-btn m-btn--pill m-btn--icon">
                                <span>
                                    <i class="la la-search"></i>
                                    <span>查询</span>
                                </span>
                            </button>
                            <a href="{{ route('question.personal.create',['back'=>'personal','mid'=>'5e5fa7160f2d8bf507f11ac18455f61e']) }}"
                               class="btn btn-primary m-btn m-btn--icon m-btn--pill">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span>
                                        新增
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @foreach($list as $question)
    <div class="m-portlet m-portlet--mobile " id="question-{{$question->id}}">
        <div class="m-portlet__body">
            <div class="m-widget">
                <div class="m-widget-body">
                    <div class="m-section m-section-none">
                        <h3 class="m-section__heading m-line-height-25">
                            <a href="{{route('questions.show',[
                            'question'=>$question->id,'mid'=>request('mid')
                            ])}}" class="action-show">{{$question->title}}</a>
                        </h3>
                        <span class="m-section__sub m-section__sub-margin-bottom-none">
                            所属项目：{{$question->project ? $question->project->title : null}} <br>
                            发布日期：{{$question->created_at}} <br>
                            问题状态：<span class="m-badge {{question_status($question->status,'class')}} m-badge--wide">{{question_status($question->status,'title')}}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet-action row">
            <div class="btn-group m-btn-group col" role="group" aria-label="">
                <a href="{{route('questions.edit',['question'=>$question->id,'mid'=>request('mid')])}}"
                    class="m-btn-left-bottom-border-none m-btn
                    m-btn--square btn btn-secondary col m-btn--icon
                    m-btn--icon-center @if($question->status != 1) disabled @endif">
                    <span>
                        <i class="la la-edit"></i>
                        <span>编辑</span>
                    </span>
                </a>
                <a class="m-btn-bottom-border-none m-btn
                m-btn--square m-btn--icon btn btn-secondary
                col m-btn--icon-center action-delete @if($question->status != 1) disabled @endif" data-question-id="{{$question->id}}"
                   href="{{route('questions.destroy',['question'=>$question->id,'mid'=>request('mid')])}}">
                    <span>
                        <i class="la la-trash"></i>
                        <span>删除</span>
                    </span>
                </a>
                <a class="m-btn-right-bottom-border-none m-btn
                m-btn--square btn btn-secondary col m-btn--icon
                m-btn--icon-center action-finished @if($question->status > 2 ) disabled @endif" href="{{route('questions.finished',[
                            'question'=>$question->id,'mid'=>request('mid')
                            ])}}">
                    <span>
                        <i class="la la-power-off"></i>
                        <span>关闭</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
    @endforeach

    {{ $list->appends([
        'mid' => request('mid'),
        'project_id' => request('project_id'),
        'date' => request('date'),
        'search' => request('search'),
        'status' => request('status'),
    ])->links('vendor.pagination.bootstrap-4') }}
    <!--begin::Modal-->
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
        $(document).ready(function () {
            $('#status').selectpicker();
            var $projectSelector = $("#project_id").select2({
                language:'zh-CN',
                placeholder: "输入项目编号、名称等关键字搜索，选择项目",
                allowClear: true,
                width:'100%',
                ajax: {
                    url: "{{route('projects.selector.data')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            per_page: {{config('common.page.per_page')}}
                        };
                    },
                    processResults: function(data, params) {
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
                escapeMarkup: function(markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 0,
                templateResult: formatProjectRepo, // omitted for brevity, see the source of this page
                templateSelection: formatProjectRepoSelection // omitted for brevity, see the source of this page
            });
            $('a.action-show').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                $('#_modal').modal('show');
                mAppExtend.ajaxGetHtml(
                    '#_modal .modal-content',
                    url,
                    {}, true);
            });
            $('a.action-delete').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                var id = $(this).data('question-id');
                mAppExtend.deleteData({
                    'url': url,
                    'callback': function () {
                        $("#question-"+id).fadeOut('slow');
                    }
                });
            });
            $('a.action-finished').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                mAppExtend.confirmControllData({
                    'title': '你确定要关闭问题吗？',
                    'url': url,
                    'data': {_method: 'POST'}
                });
            });
        })
    </script>
@endsection
