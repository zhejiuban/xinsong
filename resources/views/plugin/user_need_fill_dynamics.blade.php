
@foreach($needFillDynamicTask as $t)
    <!--Begin::Main Portlet-->
	<div class="m-portlet">
	    <div class="m-portlet__body  m-portlet__body--no-padding">
	        <div class="row m-row--no-padding m-row--col-separator-xl">
	            <div class="col-xl-11">
	                <div class="m-widget1">
	                    <a href="{{route('tasks.show',['id'=>$t->id])}}"
	                    class="look-task">{{$t->content}}</a>
	                    <br>
	                    所属项目：{{$t->project ? $t->project->title : null }}
	                </div>
	            </div>
	            <div class="col-xl-1 text-center">
	                <button href="{{ route('dynamics.create',['project_id'=>$t->project_id,'task_id'=>$t->id,'fill'=>1,'date'=>request('start'),'mid'=>request('mid')]) }}"
	                        class="dynamic-add btn m-btn--square btn-secondary full-width-height btn-border-none m--padding-10 m--border-radius-none">
	                    <i class="flaticon-add"></i>
	                    <p class="m--margin-0 m--font-default">补填日志</p>
	                </button>
	            </div>
	        </div>
	    </div>
	</div>
	<!--End::Main Portlet-->
@endforeach
<script type="text/javascript">
$(document).ready(function(){
 	var lookQuestion = function(url,type){
        $('#_modal').modal(type?type:'show');
        mAppExtend.ajaxGetHtml(
            '#_modal .modal-content',
            url,
            {},true);
    };
    $('.dynamic-add,.question-reply,.look-task').click(function(event){
        event.preventDefault();
        var url = $(this).attr('href');
        lookQuestion(url);
    });

});
</script>

