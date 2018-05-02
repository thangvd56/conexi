<style>
    .cursor-pointer
    {
        cursor: pointer;
    }
    .img-loading{
        width: 31px;
        margin-top: -12px;
    }
    .btn-move{
        background: transparent;
        border: none;
        outline: 0;
    }
</style>
<?php echo $this->Form->create('Photo', array('id' => 'photo', 'type' => 'file', 'action' => 'index')); ?>
<div class="collection">
    <?php $id = 1; ?>
    <?php echo $this->Html->image('/uploads/photo_gallerise/', array('class' => 'hide sample_img', 'alt' => '')); ?>
    <?php foreach ($photo as $key => $value): ?>
        <?php
        $publish = $value['Photo']['published'];
        $publish == '1' ? $is_check = 'checked' : $is_check = '';
        $image = $value['Photo']['image'];
        $image == '' ? $image = 'noimage.jpg' : $image = $image;
        ?>
        <div class="item" id="<?php echo 'item' . $id; ?>">
            <div class="row">
                <input type="hidden" name="data[Photo][<?php echo $id; ?>][id]" value="<?php echo $value['Photo']['id'] ?>" />
                <input type="hidden" id="<?php echo "img_hidden_name" . $id; ?>" name="data[Photo][<?php echo $id; ?>][image]" value="<?php echo $value['Photo']['image'] ?>" />
                <div class="col-xs-12 col-md-7">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-lg-3">
                                <label for="<?php echo $id; ?>">
                                    <?php
                                    echo $this->Html->image('/uploads/photo_gallerise/' . $image, array(
                                        'class' => 'img-responsive img-center cursor-pointer',
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
                                    'id' => $id,
                                    'type' => 'file',
                                    'required' => false));
                                ?>
                                <div class="error-message" id="error-upload<?php echo $id; ?>" style="font-size: 11px;color: #FF7C00;"></div>
                            </div>
                            <div class="col-lg-9">
                                <div role="form">
                                    <div class="form-group">
                                        <label>大カテゴリータイトル</label>
                                        <input type="text" class="form-control" name="data[Photo][<?php echo $id; ?>][title]" value="<?php echo $value['Photo']['title']; ?>">
                                    </div>
                                </div>
                                <div id='button_toggle'>
                                    <?php
                                    echo $this->Html->link('メニュー追加', array(
                                        'controller' => 'photo_lists',
                                        'action' => 'index',
                                        '?' => array(
                                            'photo_id' => $value['Photo']['id'])), array('class' => 'btn btn-success color back_color width but_design'));
                                    ?>
                                    <div id="icon-top">
                                        <button value='up' class="btn-move glyphicon glyphicon-triangle-top"></button>
                                    </div>
                                    <?php echo $this->Html->image('scroll_bord.png', array('class' => 'scroll_bord')); ?>
                                    <div id="icon_down">
                                        <button value='down' class="btn-move glyphicon glyphicon-triangle-bottom"></button>
                                    </div>
                                    <button class="btn btn-success color back but_design2 btn_delete" id ="<?php echo $value['Photo']['id']; ?>" data-name ="<?php echo $value['Photo']['title']; ?>">削除</button>
                                    <input type="checkbox" class="publish toggle<?php echo $value['Photo']['id']; ?>" id ="<?php echo $value['Photo']['id']; ?>" <?php echo $is_check; ?> data-toggle="toggle" data-onstyle="warning" data-title="<?php echo $value['Photo']['title']; ?>" data-on="<div class='toggle_on'></div> 公開" data-off="<div class='toggle_off'></div> 非公開">
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
<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('custom'); ?>
<script type="text/javascript">
    $(function () {

        //Click delete altert cinfirm
        $("body").on("click", ".btn_delete", function (e) {
            e.preventDefault();
            $("#ModalDeleteConfirm").modal("show");
            var title = $(this).attr("data-name");
            var id = $(this).attr("id");
            $("#photo_id").val(id);
            $("#label-delete").html(title + "を削除します。<br>この" + title + "を非表示BOX箱に移 <br>動します。<br>よろしいですか？");
            $("#label-delete-confirm").html(title + "を削除します本当によろしいですか？");
        });
        $("#btn_confirm_delete").click(function () {
            $("#ModalDeleteConfirm").modal("hide");
            $("#ModalDeletePhoto").modal("show");
        });

        //Re-launch toggle button because in layout ajax is not work
        function toggle_button() {
            $("[data-toggle='toggle']").bootstrapToggle('destroy');
            $("[data-toggle='toggle']").bootstrapToggle();
        }
        toggle_button();

        //Add empty field
        var id = "<?php echo $id; ?>";
        $(".btn-add").click(function () {
            //Prevent user leave page without save
            //prevent_leave_page(true);
            var path = $('.sample_img').attr('src');
            var str = '<div class="item" id="' + id + '">';
            str += '<div class="row">';
            str += '<input type="hidden" name="data[Photo][' + id + '][id]" value="" />';
            str += '<input type="hidden" id="img_hidden_name' + id + '" name="data[Photo][' + id + '][image]" value="" />';
            str += '<div class="col-xs-12 col-md-7">';
            str += '<div class="panel panel-default">';
            str += '<div class="panel-body">';
            str += '<div class="col-lg-3">';
            str += '<label for="inputfile_' + id + '"><img src="' + path + 'noimage.jpg" class="img-responsive img-center cursor-pointer" id="img_name' + id + '"></label>';
            str += '<div id="loading' + id + '" class="hide loading-item text-center"><p style="font-size: 11px;">アップロード中</p></div>';
            str += '<input type="file" name="data[Photo][file_image]" class="upload hide" id="inputfile_' + id + '">';
            str += '<div class="error-message" id="error-upload' + id + '" style="font-size: 11px;color: #FF7C00;"></div>';
            str += '</div>';
            str += '<div class="col-lg-9">';
            str += '<div role="form">';
            str += '<div class="form-group"><label>大カテゴリータイトル</label><input type="text" class="form-control" name="data[Photo][' + id + '][title]" value=""></div>';
            str += '</div>';
            str += '<div id="button_toggle">';
            //str += '<a href="/app_menu?menu_id=' + id + '#" class="btn btn-success color back_color width but_design">メニュー追加</a>&nbsp;';
            str += '<div id="icon-top"><button value="up" class="btn-move glyphicon glyphicon-triangle-top"></button></div>';
            str += '<?php echo $this->Html->image('scroll_bord.png', array('class' => 'scroll_bord')); ?>';
            str += '<div id="icon_down"><button value="down" class="btn-move glyphicon glyphicon-triangle-bottom"></button></div>';
            str += '<button class="btn btn-success color back but_design2 btn_delete" id="' + id + '" data-name="">削除</button>&nbsp;';
            str += '<input type="checkbox" class="publish toggle1" id ="' + id + '" checked data-toggle="toggle" data-onstyle="warning" data-on="<div class=' + 'toggle_on' + '></div>公開" data-off="<div class=' + 'toggle_off' + '></div> 非公開">';
            str += '</div></div></div></div></div></div>';
            $(".collection").append(str);
            id++;
            toggle_button();
        });

        //Upload photo on change
        $("form#photo").on("change", ".upload", function (e) {
            e.preventDefault();
            var id = $(this).attr("id");
            var path = $('.sample_img').attr('src');
            if (id.split("_")[0] === "inputfile") {
                id = id.split("_")[1];
            }
            $("#photo").ajaxForm({
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
                        $("#loading" + id).addClass("hide");
                        return false;
                    }
                    if (respond.result === 'error') {
                        $("#error-upload" + id).html(respond.msg);
                        $("#loading" + id).addClass("hide");
                    } else {
                        $("#error-upload" + id).html("");
                        $("#img_name" + id).attr("src", path + respond.image);
                        $("#img_hidden_name" + id).val(respond.image);
                    }
                    $(".upload").val("");
                    $("#loading" + id).addClass("hide");

                    //Prevent user leave page without save
                    //prevent_leave_page(true);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error" + xhr.status);
                }
            }).submit();
        });

        //Save multiple
        $("#btn_confirm_save").click(function (e) {
            e.preventDefault();
            var data = $("#photo").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'photos', 'action' => 'index')); ?>",
                data: data + "&action=save",
                type: "get",
                beforeSend: function () {
                    $(".loading-item").addClass("hide");
                    $("#save_loading").removeClass("hide");
                },
                success: function () {
                    $("#loading_save").addClass("hide");
                    $("#ModalSaveConfirm").modal("hide");
                    window.location.reload();
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#save_loading").addClass("hide");
                }
            });
        });

        //Click to confirm publish
        $("form#photo").on("change", ".publish", function () {
            var id = $(this).attr("id");
            var publish = $(this).prop('checked');
            var title = $(this).attr('data-title');
            $("#label-publish").html(title + "を公開ますか？<br>この" + title + "を公開します。本当によろしいですか？");
            $("#publish_id").val(id);
            $("#publish_status").val(publish);
            $("#ModalPublishConfirm").modal("show");
        });

        //Save sort order
        function save_sort() {
            var data = $("#photo").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'photos', 'action' => 'index')); ?>",
                data: data + "&action=save_sort",
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
        }
        function sendOrderToServer() {
//            var items = $(".collection").sortable('toArray');
//            var itemList = jQuery.grep(items, function (n, i) {
//                return (n !== "" && n !== null);
//            });
//            $("#items").html(itemList);
            save_sort();
        }
//        $(".collection").sortable({
//            items: ".item",
//            update: function () {
//                save_sort();
//            }
//        });
        $("body").on("click", ".btn-move", function (e) {
            e.preventDefault();
            var btn = $(this);
            var val = btn.val();
            if (val === 'up')
                moveUp(btn.parents('.item'));
            else
                moveDown(btn.parents('.item'));
        });
//        var orderList = jQuery.grep($(".collection").sortable('toArray'), function (n, i) {
//            return (n !== "" && n !== null);
//        });
//        $("#items").html(orderList);
        //End sort

    });
</script>