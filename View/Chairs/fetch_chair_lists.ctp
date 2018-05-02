
<div class="chairs">
    <?php $id = 1; ?>
    <?php foreach ($chair as $key => $value): ?>
        <div class="col-xs-12 col-md-12 col-lg-8">
            <div class="panel modify_height">
                <div class="col-xs-4 col-md-5" style="margin-top:50px" >
                    <input type="hidden" name="id[]" value="<?php echo $value['Chair']['id']; ?>">
                    <input type="text" class="form-control"  required="required" name="chair_name[]" id="chair_name<?php echo $value['Chair']['id']; ?>" value="<?php echo $value['Chair']['chair_name']; ?>">
                </div>
                <div class="col-xs-4 col-md-4 set_border">
                    <div style="margin-top:50px;text-align:center;">
                        <script>
                            $('.value_<?php echo $value['Chair']['id']; ?>').calculator('theme_<?php echo $value['Chair']['id']; ?>');
                        </script>
<!--                        <h4 class="editcapacity" id="<?php //echo $value['Chair']['id']; ?>" title="Click to Edit"><?php //echo $value['Chair']['capacity']; ?></h4>-->
                    <input type="text" onfocus="blur();" class="form-control allow_key value_<?php echo $value['Chair']['id']; ?>" maxlength="2" required="required" name="capacity[]" id="capacity<?php echo $value['Chair']['id']; ?>" value="<?php echo $value['Chair']['capacity']; ?>">
                    </div>
                </div>
                <div class="col-xs-4 col-md-3">
                    <div style="margin-top:50px;text-align:center;">
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#ModalDeleteChair" class="btn btn-success back butt_padding2 get_chair_id"  id="<?php echo $value['Chair']['id']; ?>">削除</a>
                    </div>
                </div>
            </div>
        </div>
        <?php $id++; ?>
    <?php endforeach; ?>
</div>
<script type="text/javascript">
    $(function () {
        var id = '<?php echo $id; ?>';

        $('#btn_add').unbind('click').click(function () {
            var str = '';
            str += '<div id="chair' + id + '" class = "col-xs-12 col-md-12 col-lg-8" >';
            str += '<input type="hidden" name="id[]" value="">';
            str += '<div class = "panel modify_height">';
            str += '<div class ="col-xs-4 col-md-5" style="margin-top:50px">';
            str += '<input type="text" name="chair_name[]" class="form-control chair" id="title" placeholder="テーブル名" required="required" value="">';
            str += '</div>';
            str += '<div class = "col-xs-4 col-md-4 set_border" >';
            str += '<div style="margin-top:50px;text-align:center;">';
            str += '<input type="text" onfocus="blur();" name="capacity[]" maxlength="2" type="text" value="1" class="form-control value_'+id+' theme_'+id+' allow_key" id="capacity" placeholder="定員" required="required" value="">';
            str += '</div>';
            str += '</div>';
            str += '<div class = "col-xs-4 col-md-3" >';
            str += '<div style="margin-top:50px;text-align:center;">';
            str += '<a href="javascript:void(0);" class="btn btn-success back butt_padding2 delete_chair" id="' + id + '">削除</a>';
            str += '</div>';
            str += '</div>';
            str += '</div>';
            str += '</div>';
            $('.chairs').append(str);
            $('.value_' + id).calculator('theme_'+id);
            id++;
        });

        $('body').on('click', '.delete_chair', function () {
            var id = $(this).attr('id');
            $('#chair' + id).remove();
        });

        $('.get_chair_id').click(function () {
            var id = $(this).attr('id');
            $('.chair_id').val(id);
        });

        $('.editcapacity').click(function () {
            var id = $(this).attr('id');
            $('#capacity' + id).removeClass('hide');
        });
    });
</script>
