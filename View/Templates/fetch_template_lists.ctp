
<div class="templates">
    <?php $id = 1; ?>
    <?php foreach ($template as $key => $value): ?>
        <div class="col-xs-12 col-md-8">
            <div class="panel modify_height">
                <div class="col-xs-4 col-md-3">
                    <input type="hidden" name="id[]" value="<?php echo $value['Template']['id']; ?>">
                    <h3 class="edittitle" id="<?php echo $value['Template']['id']; ?>"><?php echo $value['Template']['title']; ?></h3>
                    <input type="text" class="form-control hide"  required="required" name="title[]" id="title<?php echo $value['Template']['id']; ?>" placeholder="定型文" value="<?php echo $value['Template']['title']; ?>">
                </div>
                <div class="col-xs-4 col-md-6 set_border">
                    <h4 class="editremark" id="<?php echo $value['Template']['id']; ?>"><?php echo $value['Template']['remarks']; ?></h4>
                    <textarea class="form-control hide" style="margin: 0px -3px 0px 0px; height: 146px; width: 321px;" required="required" name="remark[]" id="remark<?php echo $value['Template']['id']; ?>" placeholder="100字以内で紹介文を記載"><?php echo $value['Template']['remarks']; ?></textarea>
                </div>
                <div class="col-xs-4 col-md-3">
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#ModalDeleteTemplate" class="btn btn-success back butt_padding get_template_id"  id="<?php echo $value['Template']['id']; ?>">削除</a>
                </div>
            </div>
        </div>
        <?php $id++; ?>
    <?php endforeach; ?>
</div>
<script type="text/javascript">
    $(function () {
        var id = "<?php echo $id; ?>";
        $("#btn_add").unbind("click").click(function () {
            var str = '';
            str += '<div id="template' + id + '" class = "col-xs-12 col-md-8" >';
            str += '<input type="hidden" name="id[]" value="">';
            str += '<div class = "panel modify_height">';
            str += '<div class = "col-xs-4 col-md-3" >';
            str += '<input type="text" name="title[]" class="form-control" id="title" placeholder="タイトル" required="required" value="">';
            str += '</div>';
            str += '<div class = "col-xs-4 col-md-6 set_border" >';
            str += '<textarea class="form-control" style="margin: 0px -3px 0px 0px; height: 146px; width: 321px;" name="remark[]" id="remark" required="required" placeholder="100字以内で紹介文を記載"></textarea>';
            str += '</div>';
            str += '<div class = "col-xs-4 col-md-3" >';
            str += '<a href="javascript:void(0);" class="btn btn-success back butt_padding delete_template" id="' + id + '">削除</a>';
            str += '</div>';
            str += '</div>';
            str += '</div>';
            $(".templates").append(str);
            id++;
        });

        $("body").on("click", ".delete_template", function () {
            var id = $(this).attr("id");
            $("#template" + id).remove();
        });
        $(".get_template_id").click(function () {
            var id = $(this).attr("id");
            $(".template_id").val(id);
        });

        $(".edittitle").each(function () {
            //Reference the Label.
            var label = $(this);
            //Add a TextBox next to the Label.       
            //Reference the TextBox.
            var textbox = $(this).next();
            //Assign the value of Label to TextBox.
            textbox.val(label.html());
            //When Label is clicked, hide Label and show TextBox.
            label.click(function () {
                $(this).hide();
                $(this).next().show();
            });
            //When focus is lost from TextBox, hide TextBox and show Label.
            textbox.focusout(function () {
                $(this).hide();
                $(this).prev().html($(this).val());
                $(this).prev().show();
            });
        });
        $(".editremark").each(function () {
            //Reference the Label.
            var label = $(this);
            //Reference the TextBox.
            var textbox = $(this).next();
            //Assign the value of Label to TextBox.
            textbox.val(label.html());
            //When Label is clicked, hide Label and show TextBox.
            label.click(function () {
                $(this).hide();
                $(this).next().show();
            });
            //When focus is lost from TextBox, hide TextBox and show Label.
            textbox.focusout(function () {
                $(this).hide();
                $(this).prev().html($(this).val());
                $(this).prev().show();
            });
        });
        $('.edittitle').click(function () {
            var id = $(this).attr('id');
            $("#title" + id).removeClass('hide');
        });
        $('.editremark').click(function () {
            var id = $(this).attr('id');
            $("#remark" + id).removeClass('hide');
        });
    });
</script>

