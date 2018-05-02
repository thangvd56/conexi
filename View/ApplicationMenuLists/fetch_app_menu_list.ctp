<style>
    .btn-move{
        background: transparent;
        border: none;
        outline: 0;
    }
    .red_box{
        background-color: #f2dede;
    }
</style>
<?php
$class = $is_display_list ? ' form-inline' : '';
echo $this->Form->create('ApplicationMenuList', array(
    'id' => 'menu_list',
    'action' => 'index',
    'class' => 'form-media'.$class,
    'type' => 'file'));

if (!isset($is_display_list) || !$is_display_list ) :
?>
<div class="collection">
    <?php $id = 1; ?>
    <?php echo $this->Html->image('/uploads/app_menu_lists/', array('class' => 'hide sample_img', 'alt' => '')); ?>
    <?php foreach ($app_menu_list as $key => $value): ?>
        <?php
        $publish = $value['ApplicationMenuList']['published'];
        $publish == '1' ? $is_check = 'checked' : $is_check = '';
        $image = $value['ApplicationMenuList']['image'];
        $image == '' ? $image = 'noimage.jpg' : $image = $image;
        ?>
        <div class="item" id="<?php echo $id; ?>">
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <input type="hidden"
                                       name="data[ApplicationMenuList][<?php echo $id; ?>][id]"
                                       value="<?php echo $value['ApplicationMenuList']['id']; ?>">
                                <input type="hidden"
                                       id="<?php echo 'img_hidden_name'. $id; ?>"
                                       name="data[ApplicationMenuList][<?php echo $id; ?>][image]"
                                       value="<?php echo $value['ApplicationMenuList']['image']; ?>">
                                <label for="<?php echo 'inputfile_' . $id; ?>" style="cursor: pointer;">
                                    <?php
                                    echo $this->Html->image('/uploads/app_menu_lists/' . $image, array(
                                        'class' => 'img-responsive img',
                                        'id' => 'img_name' . $id));
                                    ?>
                                </label>
                                <div id="loading<?php echo $id; ?>" class="hide loading-item text-center">
                                    <p style="font-size: 11px;">アップロード中</p>
                                </div>
                                <?php
                                echo $this->Form->input('file_image', array(
                                    'class' => 'upload hide',
                                    'label' => false,
                                    'id' => 'inputfile_' . $id,
                                    'type' => 'file',
                                    'required' => false));
                                ?>
                                <div class="error-message" id="error-upload<?php echo $id; ?>" style="font-size: 11px;color: #FF7C00;"></div>
                            </div>
                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                <div class="form-group">
<!--                                <input type="text" id="old_title<?php echo $id;?>" name="data[ApplicationMenuList][<?php echo $id; ?>][title]" placeholder="文字以内で入力してください。" class="form-control" value="<?php echo $value['ApplicationMenuList']['title']; ?>" required="required">-->
                                    <textarea class="form-control counter" name="data[ApplicationMenuList][<?php echo $id; ?>][title]" id="old_title<?php echo $id;?>" placeholder="タイトル15文字以内"><?php echo $value['ApplicationMenuList']['title']; ?></textarea>
                                </div>
                                <div class="form-group">
<!--                                <input type="text" name="data[ApplicationMenuList][<?php //echo $id; ?>][content]" class="form-control" value="<?php //echo $value['ApplicationMenuList']['content']; ?>" required="required">-->
                                    <textarea class="form-control" name="data[ApplicationMenuList][<?php echo $id; ?>][content]" id="old_content<?php echo $id;?>" placeholder="説明文を入力してください。"><?php echo $value['ApplicationMenuList']['content']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="old_price<?php echo $id;?>" name="data[ApplicationMenuList][<?php echo $id; ?>][price]" class="form-control" value="<?php echo $value['ApplicationMenuList']['price']; ?>" required="required" placeholder="金額">
                                </div>
                                <div class="icon-top">
                                    <button value="up" class="btn-move glyphicon glyphicon-triangle-top"></button>
                                </div>
                                <?php echo $this->Html->image('scroll_bord.png', array('class' => 'scroll_bord')); ?>
                                <div class="icon_down">
                                    <button value="down" class="btn-move glyphicon glyphicon-triangle-bottom"></button>
                                </div>
                                <div id='button_toggle'>
                                    <button
                                        class="btn btn-success color back but_design2 btn_delete"
                                        data-title="<?php echo $value['ApplicationMenuList']['title']; ?>"
                                        data-id="<?php echo $value['ApplicationMenuList']['id']; ?>"
                                        data-toggle="modal" data-target="#ModalDeleteAppMenuList">削除</button>
                                    <input type="checkbox"
                                            <?php echo $is_check; ?>
                                           class="publish toggle<?php echo $value['ApplicationMenuList']['id']; ?>"
                                           id ="<?php echo $value['ApplicationMenuList']['id']; ?>"
                                           data-title="<?php echo $value['ApplicationMenuList']['title']; ?>"
                                           data-toggle="toggle"
                                           data-onstyle="warning"
                                           data-on="<div class='toggle_on'></div> 公開"
                                           data-off="<div class='toggle_off'></div> 非公開">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php $id++; ?>
<?php endforeach; ?>
</div>
<?php else : ?>

<?php foreach ($app_menu_list as $key => $value): ?>
    <div class="row add-margin-bottom">
        <div class="col-lg-8">
            <?php echo $this->Form->input($key.'.id', array(
                    'type' => 'hidden',
                    'label' => false,
                    'class' => 'form-control',
                    'value' => $value['ApplicationMenuList']['id']
                )); ?>
            <?php echo $this->Form->input($key.'.image', array(
                    'type' => 'hidden',
                    'label' => false,
                    'class' => 'form-control',
                    'value' => $value['ApplicationMenuList']['image']
                )); ?>
            <?php echo $this->Form->input($key.'.content', array(
                    'type' => 'hidden',
                    'label' => false,
                    'class' => 'form-control',
                    'value' => $value['ApplicationMenuList']['content']
                )); ?>
            <div class="form-group">
                <label class="col-xs-12">タイトル</label>
                <?php echo $this->Form->input($key.'.title', array(
                    'label' => false,
                    'class' => 'form-control',
                    'value' => $value['ApplicationMenuList']['title']
                )); ?>
            </div>
            <div class="form-group">
                <label class="col-xs-12">金額</label>
                <?php echo $this->Form->input($key.'.price', array(
                    'type' => 'text',
                    'label' => false,
                    'class' => 'form-control',
                    'value' => $value['ApplicationMenuList']['price']
                )); ?>
            </div>
            <div class="form-group">
                <label class="col-xs-12 hide-text">削除</label>
                <input type="button" class="btn btn-default btn_delete" value="削除"
                    data-title="<?php echo $value['ApplicationMenuList']['title']; ?>"
                    data-id="<?php echo $value['ApplicationMenuList']['id']; ?>"
                    data-toggle="modal" data-target="#ModalDeleteAppMenuList"
                >
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php endif; ?>
<?php echo $this->Form->end(); ?>
 <input type="hidden" name="new_sub" id="new_sub"/>
<!--Prevent user leave page-->
<?php
echo $this->Html->script('textcounter.min');
echo $this->Html->script('custom');
?>
<script type="text/javascript">

    function toggle_button() {
        $("[data-toggle='toggle']").bootstrapToggle('destroy');
        $("[data-toggle='toggle']").bootstrapToggle();
    }
    toggle_button();
    $(function () {

        var unsaved = false;
        $(':input').change(function () {
            unsaved = true;
        });
        <?php if(!isset($is_display_list) || !$is_display_list) : ?>
        var id = "<?php echo $id; ?>";
        var sub_id = [];
        $('.btn_add').off('click').on('click', function() {
            //Prevent user leave page without save
            var path = $('.sample_img').attr('src');
            var str = '<div class="item" id="item' + id + '">';
            str += '<div class="row">';
            str += '<div class="col-lg-8">';
            str += '<div class="panel panel-default">';
            str += '<div class="panel-body">';
            str += '<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">';
            str += '<input type="hidden" name="data[ApplicationMenuList][' + id + '][id]" value="">';
            str += '<input type="hidden" id="img_hidden_name' + id + '" name="data[ApplicationMenuList][' + id + '][image]" value="">';
            str += '<label for="inputfile_' + id + '" style="cursor:pointer;">';
            str += '<img id="img_name'+ id +'" src="' + path + 'noimage.jpg" class="img-responsive img">';
            str += '<div id="loading' + id + '" class="hide loading-item text-center"><p style="font-size: 11px;">アップロード中</p></div>';
            str += '</label>';
            str += '<input type="file" id="inputfile_' + id + '" class="upload hide" name="data[ApplicationMenuList][file_image]">';
            str += '<div class="error-message" id="error-upload'+ id +'" style="font-size: 11px;color: #FF7C00;"></div>';
            str += '</div>';
            str += '<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">';
            str += '<div class="form-group">';
            //str += '<input type="text" id="title' + id + '" placeholder="文字以内で入力してください。", class="form-control" name="data[ApplicationMenuList][' + id + '][title] value="">';
            str += '<textarea class="form-control counter" name="data[ApplicationMenuList][' + id + '][title]" id="title'+ id +'" placeholder="タイトル15文字以内"></textarea>';
            str += '</div>';
            str += '<div class="form-group">';
            //str += '<input type="text" class="form-control" name="data[ApplicationMenuList][' + id + '][content] value="">';
            str += '<textarea class="form-control" name="data[ApplicationMenuList][' + id + '][content]" id="content'+ id +'" placeholder="説明文を入力してください"></textarea>';
            str += '</div>';
            str += '<div class="form-group">';
            str += '<input type="text" id="price'+ id +'" class="form-control" name="data[ApplicationMenuList][' + id + '][price] value="" placeholder="金額">';
            str += '</div>';
//            str += '<div class="icon-top">';
//            str += '<span class="glyphicon glyphicon-triangle-top"></span>';
//            str += '</div>';
            //str += '<?php //echo $this->Html->image('scroll_bord.png', array('class' => 'scroll_bord')); ?>';
//            str += '<div class="icon_down">';
//            str += '<span class="glyphicon glyphicon-triangle-bottom"></span>';
//            str += '</div>';
            str += '<div id="button_toggle">';
            str += '<button class="btn btn-success color back but_design2 remove_empty_field" id="' + id + '">削除</button>&nbsp;';
            str += '<input type="checkbox" checked data-toggle="toggle" data-onstyle="warning" data-on="<div class=' + 'toggle_on' + '></div> 公開" data-off="<div class=' + 'toggle_off' + '></div> 非公開">';
            str += '</div></div></div></div></div></div>';
            $(".collection").append(str);
            sub_id.push(id);
            $("#new_sub").val(sub_id);
            id++;
            toggle_button();
            unsaved = true;
            textCounter();
        });
        <?php endif; ?>
        //Function check new sub
        $('body').on('click','.add_sub',function() {
            var val = $('#new_sub').val();
            var arr_val = val.split(",");
            for (var j = 0; j < arr_val.length; j++) {
                var id = arr_val[j];
                var title =$("#title" + id).val();
                var content =$("#content" + id).val();
                var price =$("#price" + id).val();
                if (title =="" || content =="" || price ==""){

                  $("#title" + id ).addClass('red_box');
                  $("#title" + id ).fadeOut(100);
                  $("#title" + id ).fadeIn(100);

                  $("#content" + id ).addClass('red_box');
                  $("#content" + id ).fadeOut(100);
                  $("#content" + id ).fadeIn(100);

                  $("#price" + id ).addClass('red_box');
                  $("#price" + id ).fadeOut(100);
                  $("#price" + id ).fadeIn(100);

                    return false;
                }else{
                  $("#title" + id ).removeClass('red_box');
                  $("#content" + id ).removeClass('red_box');
                  $("#price" + id ).removeClass('red_box');
                   prevent_leave_page(false);
                }
            }
        });
        //Function remove empty field
        $('body').on('click', '.remove_empty_field', function () {
            prevent_leave_page(false);
            var id = $(this).attr("id");
            $("#item" + id).remove();
        });

        $('body').on('click', '.delNewData', function (e) {
            e.preventDefault();
            var btn_id = $(this).attr("id");
            var id = btn_id.split("btn_del")[1];
            //var title = $("#title" + id).val();
            $('#app_menu_list_id').val("");
            //$("#label-delete").html(title + "を削除します。<br>この" + title + "を非表示BOX箱に移 <br>動します。<br>よろしいですか？");
            $('#label-delete-confirm').html("本当によろしいですか？");
        });

        $("form#menu_list").on("change", ".upload", function () {
            var id = $(this).attr("id");
            upload_media(id);
            return false;
        });

        function upload_media(id) {
            var path = $(".sample_img").attr("src");
            if (id.split("_")[0] === "inputfile") {
                id = id.split("_")[1];
            }
            $("#menu_list").ajaxForm({
                dataType: "json",
                type: "post",
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $("#loading" + id).removeClass("hide");
                },
                success: function (respond) {
                    if (respond.msg === "Save data") {
                        return false;
                    }
                    if (respond.result === "error") {
                        $("#error-upload" + id).html(respond.msg);
                    } else {
                        $("#error-upload" + id).html("");
                        $("#img_name" + id).attr("src", path + respond.image);
                        $("#img_hidden_name" + id).val(respond.image);
                    }
                    $(".upload").val("");
                    //Prevent user leave page without save
                    prevent_leave_page(true);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log("Error:" + xhr.status);
                },complete:function(){
                    $("#loading" + id).addClass("hide");
                }
            }).submit();
        }

        function save(data, menu_id) {
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'application_menu_lists', 'action' => 'index')) ?>",
                data: data + "&action=save&menu_id=" + menu_id,
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $(".loading-item").addClass("hide");
                    $("#loading_save").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#loading_save").addClass("hide");
                        prevent_leave_page(false);
                        window.location.reload();
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                }
            });
        }

//        $("form#menu_list").on("change", ".publish", function () {
//            var publish = $(this).prop('checked');
//            var id = $(this).attr("id");
//            var title = $(this).attr('data-title');
//            $("#label-publish").html(title + "を公開ますか？<br>この" + title + "を公開します。本当によろしいですか？");
//            $("#publish_id").val(id);
//            $("#publish_status").val(publish);
//            $("#ModalPublishConfirm").modal("show");
//        });

        function save_sort() {
            var data = $("#menu_list").serialize();
            var currentURL = document.URL;
            var menu_id = currentURL.split("menu_id=")[1];
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'application_menu_lists', 'action' => 'index')); ?>",
                data: data + "&action=save_sort&menu_id=" + menu_id,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                },
                success: function () {
                    console.log("success");
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error" + xhr.status);
                },
                complete: function () {
                }
            });
        }
        //Start sort
        function moveUp(item) {
            var prev = item.prev();
            if (prev.length === 0)
                return;
            prev.css('z-index', 999).css('position', 'relative').animate({
                top: item.height()
            }, 250);
            item.css('z-index', 1000).css('position', 'relative').animate({
                top: '-' + prev.height()
            }, 300, function () {
                prev.css('z-index', '').css('top', '').css('position', '');
                item.css('z-index', '').css('top', '').css('position', '');
                item.insertBefore(prev);
                sendOrderToServer();
            });
            unsaved = true;
        }
        function moveDown(item) {
            var next = item.next();
            if (next.length === 0)
                return;
            next.css('z-index', 999).css('position', 'relative').animate({
                top: '-' + item.height()
            }, 250);
            item.css('z-index', 1000).css('position', 'relative').animate({
                top: next.height()
            }, 300, function () {
                next.css('z-index', '').css('top', '').css('position', '');
                item.css('z-index', '').css('top', '').css('position', '');
                item.insertAfter(next);
                sendOrderToServer();
            });
            unsaved = true;
        }
        function sendOrderToServer() {
            save_sort();
        }
        $("body").on("click", ".btn-move", function (e) {
            e.preventDefault();
            var btn = $(this);
            var val = btn.val();
            if (val == 'up')
                moveUp(btn.parents('.item'));
            else
                moveDown(btn.parents('.item'));
        });
        
        $(".btn_delete").click(function (e) {
            e.preventDefault();
            //var title = $(this).attr("data-title");
            var id = $(this).attr("data-id");
            $("#app_menu_list_id").val(id);
            //$("#label-delete").html(title + "を削除します。<br>この" + title + "を非表示BOX箱に移 <br>動します。<br>よろしいですか？");
            $("#label-delete-confirm").html("本当によろしいですか？");
        });

        $('form').submit(function () {
            //window.onbeforeunload = null;
        });

        $('#btn_back').click(function () {
            window.location.replace(URL +'users/view/app-menu');
        });

        textCounter();
    });
    function textCounter() {
        $('.counter').textcounter({
            type : 'character', // "character" or "word"
            min : 0,
            max : 15,
            countContainerClass : 'message-counter',
            maximumErrorText : 'タイトル15文字以内',
            displayErrorText : true,
            countExtendedCharacters : false,
            maxcount : function(el){
                //alert('タイトル15文字以内');
            },
            init : function(el){
                $('.message-counter').html('');
            }
        });
    }
</script>