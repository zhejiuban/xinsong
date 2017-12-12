<script type="text/javascript">
$(document).ready(function(){
    $("#project-delete").click(function(event){
        event.preventDefault();
        var url = $(this).attr('href');
        mAppExtend.deleteData({
            'url':url
        });
    });
});
</script>