
<?php $id = 1; ?>
<?php foreach ($question_answer as $key => $value): ?>
    <?php echo $this->Form->create('Support',
        array('id' => 'form_question_answer'.$id.'')); ?>
    <div id="question_answer<?php echo $id; ?>" class="panel-body change_style_div display">
        <input type="hidden" name="question_answer_edit_id" id="question_answer_edit_id" value="<?php echo $value['QuestionAnswer']['id']; ?>">
        <div class="col-xs-9 col-md-12">
            <input type = "text" name ="question" readonly class ="form-control" id ="question<?php echo $value['QuestionAnswer']['id'] ?>" required ="required" value="<?php echo $value['QuestionAnswer']['question']; ?>">
        </div>
        <div class="col-xs-3 col-md-3"><span class="glyphicon right" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapseExample"></span></div>
    </div>
    <div class="color display">
        <input type="button" class="btn btn-success back_color_edit but edit_question_answer" form_id="<?php echo $id; ?>"  id="<?php echo $value['QuestionAnswer']['id']; ?>" value="編集">
        <a href = "javascript:void(0);" class = "btn btn-success back_color delete_question_answer" data-id="<?php echo $value['QuestionAnswer']['id']; ?>" data-toggle="modal" data-target="#ModalDeleteQA"> 削除 </a>
    </div>
    <div class="collapse in" id="collapse1" aria-expanded="true">
        <div id="action<?php echo $id; ?>" class="card card-block">
            <input type = "text" name ="answer" readonly class ="form-control" id="answer<?php echo $value['QuestionAnswer']['id'] ?>" required="required" value="<?php echo $value['QuestionAnswer']['answer']; ?>" >
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
    <?php $id++; ?>
<?php endforeach; ?>
<script type="text/javascript">

    $(function () {

        var $elem =$("#fetch_question_answer_list");
        var id = "<?php echo $id; ?>";
        $("#add_question_answer").unbind('click').click(function () {
            var str = '';
            str += '<form action="'+URL+'supports/create" id="form_question_answer' + id + '" method="post" accept-charset="utf-8">';
            str += '<div id="question_answer' + id + '" class="panel-body change_style_div display">';
            str += '<div class="col-xs-9 col-md-12">';
            str += '<input type = "text" name ="question" class ="form-control" id ="question" placeholder ="１００文字以内で質問を記載" required ="required" value="">';
            str += '</div>';
            str += '<div class="col-xs-3 col-md-3"><span class="glyphicon right" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapseExample"></span></div>';
            str += '</div>';
            str += '<div class="color display">';
            str += '<a href = "javascript:void(0);" class="btn btn-success back_color_edit butt save_question_answer" form_id="' + id + '" id="' + id + '" data-toggle="modal" data-target="#ModalSaveQAConfirm"> 保存 </a>';
            str += '<a href = "javascript:void(0);" class = "btn btn-success back_color delete_question_answer" id="' + id + '"> 削除 </a>';
            str += '</div>';
            str += '<div class="collapse in" id="collapse1" aria-expanded="true">';
            str += '<div id="action' + id + '" class="card card-block">';
            str += '<input type = "text" name ="answer" class ="form-control" id ="answer" placeholder ="１００文字以内で質問を記載。" required ="required" value="">';
            str += '</div>';
            str += '</div>';
            str += '</form>';
            $("#fetch_question_answer_list").append(str);
            id++;
             setTimeout(function(){$('#content_q').animate({scrollTop: $elem.height()});},100);
        });
        //Function get form id question and answer 
        $("body").on("click", ".save_question_answer", function () {
            var form_id = $(this).attr("form_id");
            $("#hdf_question_answer_form_id").val(form_id);
        });
        //Function update question and answer
        $("body").on("click", ".save_edit_question_answer", function () {
            var form_id = $(this).attr("form_id");
            $("#hdf_question_answer_form_id").val(form_id);
        });
        //Function delete question and answer
        $("body").on("click", ".delete_question_answer", function () {
            var id = $(this).attr("id");
            $("#form_question_answer" + id).remove();
            $("#action" + id).remove();
        });
        $(".get_question_answer_id").click(function () {
            var id = $(this).attr("id");
            $(".question_answer_id").val(id);
        });
    });

</script>
