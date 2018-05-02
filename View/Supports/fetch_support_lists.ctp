
<?php $id = 1; ?>
<?php foreach ($supports as $key => $value): ?>
    <?php echo $this->Form->create('Support', array('id' => 'form_support' . $id . '')); ?>

    <div id="support<?php echo $id; ?>" class="panel-body change_style_div display">
         <input type="hidden" name="edit_id" id="edit_id" value=" <?php echo $value['Support']['id']; ?>">
        <div class="col-xs-4 col-md-4 add_border_right">
           <input type = "text" readonly name ="title" class ="form-control" id ="support_plan<?php echo $value['Support']['id'] ?>" required ="required" value="<?php echo $value['Support']['title']; ?>">
        </div>
        <div class="col-xs-4 col-md-4 add_border_right">
            <input type="text" readonly name="title" class="form-control" id="title<?php echo $value['Support']['id'] ?>" required="required" value="<?php echo $value['Support']['title']; ?>" >
        </div>
<!--        <div class="col-xs-4 col-md-4">
            <textarea class="form-control" readonly name="detail" style="width:235px;" id="detail<?php echo $value['Support']['id'] ?>" required="required" > <?php echo $value['Support']['detail']; ?> </textarea>
        </div>-->
    </div>
    <div id="action<?php echo $id; ?>" class="color display">
          <input type="button" class="btn btn-success back_color_edit but edit_support" form_id="<?php echo $id; ?>"  id="<?php echo $value['Support']['id']; ?>" value="編集">
         <a href = "javascript:void(0);" class = "btn btn-success back_color delete_support" data-id="<?php echo $value['Support']['id']; ?>" data-toggle="modal" data-target="#ModalDeleteSupport"> 削除 </a>
    </div>
    <?php echo $this->Form->end(); ?>
    <?php $id++; ?>
<?php endforeach; ?>
<script type="text/javascript">
    
    $(function () {
        var $elem =$("#fetch_support_list");
        $('#btn_add_support').fadeIn('slow');
        var id = "<?php echo $id; ?>";
        $("#btn_add_support").unbind('click').click(function () {
            var str = '';
            str += '<form action="'+URL+'supports/create" id="form_support' + id + '" method="post" accept-charset="utf-8">';
            str += '<div id="support' + id + '" class="panel-body change_style_div display">';
            str += '<div class="col-xs-4 col-md-4 add_border_right">';
            str += '<input type = "text" name ="support_plan" class ="form-control" id ="support_plan" placeholder ="特別サポー" required ="required" value="">';
            str += '</div>';
            str += '<div class="col-xs-4 col-md-4 add_border_right">';
            str += '<input type="text" name="title" class="form-control" id="title" placeholder="タイトル" required="required" value="" >';
            str += '</div>';
            str += '<div class="col-xs-4 col-md-4">';
            str += '<textarea class="form-control" name="detail" style="width:235px;" id="introduct" required="required" placeholder="100字以内で紹介文を記載"> </textarea>';
            str += '</div>';
            str += '</div>';
            str += '<div id="action' + id + '" class="color display">';
            str += '<a href = "javascript:void(0);" class="btn btn-success back_color_edit butt save_support" form_id="' + id + '" id="' + id + '" data-toggle="modal" data-target="#ModalSaveConfirm"> 保存 </a>';
            str += '<a href = "javascript:void(0);" class = "btn btn-success back_color delete_support" id="' + id + '"> 削除 </a>';
            str += '</div>';
            str += '</form>';
            $("#fetch_support_list").append(str);
            id++;
            setTimeout(function(){$('#content_plan').animate({scrollTop: $elem.height()});},100);
            
        });
        $("body").on("click", ".save_support", function () {
            var form_id = $(this).attr("form_id");
            $("#hdf_form_id").val(form_id);
        });

        $("body").on("click", ".delete_support", function () {
            var id = $(this).attr("id");
            $("#support" + id).remove();
            $("#action" + id).remove();
        });
        $(".get_support_id").click(function () {
            var id = $(this).attr("id");
            $(".support_id").val(id);
        });
    });

</script>
