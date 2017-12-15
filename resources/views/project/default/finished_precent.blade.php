<script type="text/javascript">
$(document).ready(function(){
    $("#project-delete").click(function(event){
        event.preventDefault();
        var url = $(this).attr('href');
        mAppExtend.deleteData({
            'url':url
        });
    });
    $('.phase_status,#agent-leader-change').click(function (event) {
        event.preventDefault();
        var url = $(this).attr('href');
        $('#_phaseModal').modal('show');
        mAppExtend.ajaxGetHtml(
            '#_phaseModal .modal-content',
            url,
            {}, true);
    });
    $(".look-project").click(function (event) {
        event.preventDefault();
        var url = $(this).attr('href');
        $('#_lookProjectModal').modal('show');
        mAppExtend.ajaxGetHtml(
            '#_lookProjectModal .modal-content',
            url,
            {}, true);
    });
});
</script>