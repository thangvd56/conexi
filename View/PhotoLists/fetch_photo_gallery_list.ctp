<?php
echo $this->Form->create('PhotoList', array(
    'id' => 'photo_list',
    'action' => 'index',
    'class' => 'form-media',
    'type' => 'file'));
?>
<style>
    .btn-move{
        background: transparent;
        border: none;
        outline: 0;
    }
</style>
<div class="collection">
    <?php $id = 1; ?>
    <?php echo $this->Html->image('/uploads/photo_gallery_lists/', array('class' => 'hide sample_img', 'alt' => '')); ?>
    <?php foreach ($photo_list as $key => $value): ?>
        <?php
        $publish = $value['PhotoList']['published'];
        $publish == '1' ? $is_check = 'checked' : $is_check = '';
        $image = $value['PhotoList']['image'];
        $image == '' ? $image = 'noimage.jpg' : $image = $image;
        ?>
        <div class="item" id="<?php echo 'item' . $id; ?>">
            <div class="row">
                <input type="hidden" name="data[PhotoList][<?php echo $id; ?>][id]" value="<?php echo $value['PhotoList']['id'] ?>" />
                <input type="hidden" id="<?php echo "img_hidden_name" . $id; ?>" name="data[PhotoList][<?php echo $id; ?>][image]" value="<?php echo $value['PhotoList']['image'] ?>" />
                <div class="col-lg-7 text-center">
                    <div class="panel panel-default">
                        <div class="panel-body" style="cursor: default;">
                            <div class="col-lg-3">
                                <label for="<?php echo $id; ?>">
                                    <?php echo $this->Html->image('/uploads/photo_gallery_lists/' . $image, array('class' => 'img-responsive img', 'id' => 'img_name' . $id, 'alt' => 'Image', 'style' => 'cursor:pointer;')); ?>
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
                            </div>
                            <div class="col-lg-9 text-center col-lg-width" style="cursor: default;">
                                <div class="form-group">
                                    <input type="text" name="data[PhotoList][<?php echo $id; ?>][title]" class="form-control" value="<?php echo $value['PhotoList']['title']; ?>">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="data[PhotoList][<?php echo $id; ?>][content]" value="<?php echo $value['PhotoList']['content']; ?>">
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control" name="data[PhotoList][<?php echo $id; ?>][price]" value="<?php echo $value['PhotoList']['price']; ?>">
                                </div>
                                <div class="icon-top">
                                    <button value="up" class="btn-move glyphicon glyphicon-triangle-top"></button>
                                </div>
                                <?php echo $this->Html->image('scroll_bord.png', array('class' => 'scroll_bord', 'alt' => 'image')); ?>
                                <div class="icon_down">
                                    <button value="down" class="btn-move glyphicon glyphicon-triangle-bottom"></button>
                                </div>
                                <div class='button_toggle'>
                                    <button class="btn btn-success color back but_design2 btn_delete" id="<?php echo $value['PhotoList']['id'] ?>" data-name="<?php echo $value['PhotoList']['title']; ?>">削除</button>
                                    <input type="checkbox" class="publish toggle<?php echo $value['PhotoList']['id']; ?>" id ="<?php echo $value['PhotoList']['id']; ?>" <?php echo $is_check; ?> data-toggle="toggle" data-onstyle="warning" data-title="<?php echo $value['PhotoList']['title']; ?>" data-on="<div class='toggle_on'></div> 公開" data-off="<div class='toggle_off'></div> 非公開">
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
<?php //echo $this->Html->script('jquery-sort'); ?>
<?php echo $this->Html->script('custom'); ?>
<script type="text/javascript">
    $(function () {

        //Re-launch toggle
        function toggle_button() {
            $("[data-toggle='toggle']").bootstrapToggle('destroy');
            $("[data-toggle='toggle']").bootstrapToggle();
        }
        toggle_button();

        //Add empty field
        var id = "<?php echo $id ?>";
        $(".btn_add").click(function () {
            //Prevent user leave page without save
            //prevent_leave_page(true);
            var path = $('.sample_img').attr('src');
            var str = '<div class="item" id="' + id + '">';
            str += '<div class="row">';
            str += '<input type="hidden" name="data[PhotoList][' + id + '][id]" value="" />';
            str += '<input type="hidden" id="img_hidden_name' + id + '" name="data[PhotoList][' + id + '][image]" value="" />';
            str += '<div class="col-lg-7 text-center">';
            str += '<div class="panel panel-default">';
            str += '<div class="panel-body">';
            str += '<div class="col-lg-3">';
            str += '<label for="inputfile_' + id + '"><img src="' + path + 'noimage.jpg" class="img-responsive img" style="cursor:pointer;" id="img_name' + id + '"></label>';
            str += '<div id="loading' + id + '" class="hide loading-item text-center"><p style="font-size: 11px;">アップロード中</p></div>';
            str += '<input type="file" name="data[PhotoList][file_image]" class="upload hide" id="inputfile_' + id + '">';
            str += '<div class="error-message" id="error-upload' + id + '" style="font-size: 11px;color: #FF7C00;"></div>';
            str += '</div>';
            str += '<div class="col-lg-9 text-center col-lg-width" style="cursor: default;">';
            str += '<div class="form-group">';
            str += '<input type="text" name="data[PhotoList][' + id + '][title]" class="form-control" value="">';
            str += '</div>';
            str += '<div class="form-group">';
            str += '<input type="text" name="data[PhotoList][' + id + '][content]" class="form-control" value="">';
            str += '</div>';
            str += '<div class="form-group">';
            str += '<input type="number" name="data[PhotoList][' + id + '][price]" class="form-control" value="">';
            str += '</div>';
            str += '<div class="icon-top">';
            str += '<button value="up" class="btn-move glyphicon glyphicon-triangle-top"></button>';
            str += '</div>';
            str += '<?php echo $this->Html->image('scroll_bord.png', array('class' => 'scroll_bord', 'alt' => 'image')); ?>';
            str += '<div class="icon_down">';
            str += '<button value="down" class="btn-move glyphicon glyphicon-triangle-bottom"></button>';
            str += '</div>';
            str += '<div class="button_toggle">';
            str += '<button class="btn btn-success color back but_design2 btn_delete">削除</button>&nbsp;';
            str += '<input type="checkbox" checked data-toggle="toggle" data-onstyle="warning" data-on="<div class=' + 'toggle_on' + '></div> 公開" data-off="<div class=' + 'toggle_off' + '></div> 非公開">';
            str += '</div></div></div></div></div></div></div>';
            $(".collection").append(str);
            id++;
            toggle_button();
        });

        //Upload photo on change
        $("form#photo_list").on("change", ".upload", function (e) {
            e.preventDefault();
            var id = $(this).attr("id");
            var path = $('.sample_img').attr('src');
            if (id.split("_")[0] === "inputfile") {
                id = id.split("_")[1];
            }
            $(".form-media").ajaxForm({
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
                    if (respond.result === 'error') {
                        $("#error-upload" + id).html(respond.msg);
                        $("#loading" + id).addClass("hide");
                    } else {
                        if (respond.msg === 'Save data') {
                            $("#loading" + id).addClass("hide");
                            return false;
                        }
                        $("#error-upload" + id).html("");
                        $("#loading" + id).addClass("hide");
                        $("#img_name" + id).attr("src", path + respond.image);
                        $("#img_hidden_name" + id).val(respond.image);
                    }
                    $(".upload").val("");
                    //Prevent user leave page without save
                    //prevent_leave_page(true);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    $("#loading" + id).addClass("hide");
                    console.log("Error: " + xhr.status);
                }
            }).submit();
        });

        //Save multiple
        $("#btn_confirm_save").click(function (e) {
            e.preventDefault();
            var currentURL = document.URL;
            var photo_id = currentURL.split("photo_id=")[1];
            var data = $("#photo_list").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'photo_lists', 'action' => 'index')); ?>",
                data: data + "&action=save&photo_id=" + photo_id,
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $(".loading-item").addClass("hide");
                    $(".save_loading").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#loading_save").removeClass("hide");
                        $("#ModalSaveConfirm").modal("hide");
                        window.location.reload();
                    }
                },
                error: function (xhr, ajaxOptions, trowError) {
                    $(".save_loading").removeClass("hide");
                    console.log("Error: " + xhr.status);
                }
            });
        });

        //Alert confirm publish
        $("body").on("change", ".publish", function () {
            var id = $(this).attr("id");
            var publish = $(this).prop('checked');
            var title = $(this).attr('data-title');
            $("#label-publish").html(title + "を公開ますか？<br>この" + title + "を公開します。本当によろしいですか？");
            $("#publish_id").val(id);
            $("#publish_status").val(publish);
            $("#ModalPublishConfirm").modal("show");
        });

        //Publish photo
        $("form#published_photo_list").on("submit", function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'photo_lists', 'action' => 'index')) ?>",
                data: data + "&action=publish",
                type: "get",
                dataType: "json",
                beforeSend: function () {
                    $(".save_loading").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#ModalPublishConfirm").modal("hide");
                    } else {
                        $("#error-msg-publish").html(respond.msg);
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error: " + xhr.status);
                },
                complete: function () {
                    $(".save_loading").addClass("hide");
                }
            });
        });

        //Cancel Publish
        $("#btn_cancel_publish").click(function () {
            var id = $("#publish_id").val();
            var publish = $("#publish_status").val();
            var toggle;
            publish === 'true' ? toggle = 'off' : toggle = 'on';
            $(".toggle" + id).bootstrapToggle(toggle);
            $("#ModalPublishConfirm").modal("hide");
        });

        //Save sort order
        function save_sort() {
            var data = $("#photo_list").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'photo_lists', 'action' => 'index')); ?>",
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