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
<?php
echo $this->Form->create('MenuCategory', array(
    'id' => 'menu_category',
    'action' => 'index',
    'class' => 'form-media',
    'type' => 'file'));
?>
<div class="collection">
    <?php $id = 1; ?>
    <?php echo $this->Html->image('/uploads/app_menus/', array('class' => 'hide sample_img', 'alt' => '')); ?>
    <?php foreach ($menu_categories as $key => $value): ?>
        <?php
        $publish = $value['MenuCategory']['published'];
        $publish == '1' ? $is_check = 'checked' : $is_check = '';
        $image = $value['MenuCategory']['image'];
        $image == '' ? $image = 'noimage.jpg' : $image = $image;
        ?>
        <div class="item" id="<?php echo 'item' . $id; ?>">
            <div class="row row-item">
                <input type="hidden"
                       name="data[MenuCategory][<?php echo $id; ?>][id]"
                       value="<?php echo $value['MenuCategory']['id'] ?>" />
                <input type="hidden"
                       id="<?php echo "img_hidden_name" . $id; ?>"
                       name="data[MenuCategory][<?php echo $id; ?>][image]"
                       value="<?php echo $value['MenuCategory']['image'] ?>" />
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-md-4 col-sm-4 col-xs-4">
                                <label for="<?php echo $id; ?>">
                                    <?php
                                    echo $this->Html->image('/uploads/app_menus/' . $image, array(
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
                            <div class="col-md-8 col-sm-8 col-xs-8">
                                <div role="form">
                                    <div class="form-group">
                                        <label>大カテゴリータイトル</label>
                                        <input type="text" class="form-control" name="data[MenuCategory][<?php echo $id; ?>][title]" value="<?php echo $value['MenuCategory']['title']; ?>">
                                    </div>
                                </div>
                                <div id='button_toggle'>
                                    <?php
                                    echo $this->Html->link('メニュー追加', array(
                                        'controller' => 'application_menu_lists',
                                        'action' => 'index',
                                        '?' => array(
                                            'menu_id' => $value['MenuCategory']['id'])), array('class' => 'btn btn-success color back_color width but_design'));
                                    ?>
                                    <div class="icon-top">
                                        <button value='up' class="btn-move glyphicon glyphicon-triangle-top"></button>
                                    </div>
                                    <?php echo $this->Html->image('scroll_bord.png', array('class' => 'scroll_bord scroller')); ?>
                                    <div class="icon_down">
                                        <button value='down' class="btn-move glyphicon glyphicon-triangle-bottom"></button>
                                    </div>
                                    <button class="btn btn-success color back but_design2 btn_delete get_menu_cate_id"
                                            id ="<?php echo $value['MenuCategory']['id']; ?>"
                                            data-name ="<?php echo $value['MenuCategory']['title']; ?>"
                                            data-toggle="modal"
                                            data-target="#ModalDeleteMenuCate">削除</button>
                                    <input type="checkbox"
                                           class="publish toggle<?php echo $value['MenuCategory']['id']; ?>"
                                           id ="<?php echo $value['MenuCategory']['id']; ?>"
                                           <?php echo $is_check; ?>
                                           data-toggle="toggle"
                                           data-onstyle="warning"
                                           data-title="<?php echo $value['MenuCategory']['title']; ?>"
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
<?php echo $this->Form->end(); ?>
 <input type="hidden" name="new_menu" id="new_menu"/>
<!--Prevent user leave page-->
<?php echo $this->Html->script('custom'); ?>
<script type="text/javascript">
    $(function () {
        function bootstrapToggle() {
            $("[data-toggle='toggle']").bootstrapToggle('destroy');
            $("[data-toggle='toggle']").bootstrapToggle();
        }
        bootstrapToggle();
        var unsaved = false;
        $(':input').change(function () {
            unsaved = true;
        });
        var id   = "<?php echo $id; ?>";
        var main_id =[];
        $('#bth_add').off('click').on('click', function() {
            //Prevent user leave page without save 
            var str = '';
            var path = $('.sample_img').attr('src');
            str = '<div class="item​ new_add" id="item' + id + '">';
            str += '<div class="row">';
            str += '<input type="hidden" name="data[MenuCategory][' + id + '][id]" value="" />';
            str += '<input type="hidden" id="img_hidden_name' + id + '" name="data[MenuCategory][' + id + '][image]" value="" />';
            str += '<div class="col-lg-8">';
            str += '<div class="panel panel-default">';
            str += '<div class="panel-body">';
            str += '<div class="col-md-4 col-sm-4 col-xs-4">';
            str += '<label for="inputfile_' + id + '"><img src="' + path + 'noimage.jpg" class="img-responsive img-center cursor-pointer" id="img_name' + id + '"></label>';
            str += '<div id="loading' + id + '" class="hide loading-item text-center"><p style="font-size: 11px;">アップロード中</p></div>';
            str += '<input type="file" name="data[MenuCategory][file_image]" class="upload hide" id="inputfile_' + id + '">';
            str += '<div class="error-message" id="error-upload' + id + '" style="font-size: 11px;color: #FF7C00;"></div>';
            str += '</div>';
            str += '<div class="col-md-8 col-sm-8 col-xs-8">';
            str += '<div role="form">';
            str += '<div class="form-group"><label id="lb'+ id +'">大カテゴリータイトル</label><input type="text" id="title'+ id +'" class="form-control" name="data[MenuCategory][' + id + '][title]" value=""></div>';
            str += '</div>';
            str += '<div id="button_toggle">';
            //str += '<a href="/app_menu?menu_id=' + id + '#" data-main="'+ id +'" class="btn btn-success color back_color width but_design">メニュー追加</a>&nbsp;';
            //str += '<div class="icon-top"><span value="up" class="btn-move glyphicon glyphicon-triangle-top"></span></div>';
            //str += '<?php //echo $this->Html->image('scroll_bord.png', array('class' => 'scroll_bord')); ?>';
            //str += '<div class="icon_down"><span value="down" class="btn-move glyphicon glyphicon-triangle-bottom"></span></div>';
            str += '<button class="btn btn-success color back but_design2 btn_delete remove_empty_field" id="' + id + '">削除</button>&nbsp;';
            str += '<input type="checkbox" class="publish toggle1" id ="' + id + '" checked data-toggle="toggle" data-onstyle="warning" data-on="<div class=' + 'toggle_on' + '></div>公開" data-off="<div class=' + 'toggle_off' + '></div> 非公開">';
            str += '</div></div></div></div></div></div>';
            $(".collection").append(str);
            str = '';
            bootstrapToggle();
            //Add to array mai_id
            main_id.push(id);
            $("#new_menu").val(main_id);
            id++;
            unsaved = true;
        });
        //Function check new menu
        $('body').on('click','.add_menu',function() {
            var val = $('#new_menu').val();
            var arr_val = val.split(",");
            for (var j = 0; j < arr_val.length; j++) {
                var id = arr_val[j];
                var title =$("#title" + id).val();
                if (title =="" || title ==" "){
                  $("#lb" + id ).css({"color": "red"});
                  $("#lb" + id ).fadeOut(100);
                  $("#lb" + id ).fadeIn(100);
                    return false;
                }else{
                  $("#lb" + id ).css({"color": "#5e5e5e"});
                }
            }
        });

        $('body').on('click', '.remove_empty_field', function () {
            var id = $(this).attr("id");
            $("#item" + id).remove();
        });

        $('form#menu_category').on('change', '.upload', function (e) {
            e.preventDefault();
            var id = $(this).attr("id");
            upload_media(id);
        });

        function upload_media(id) {
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
                    if (respond.result === 'error') {
                        $("#error-upload" + id).html(respond.msg);
                    } else {
                        $("#error-upload" + id).html("");
                        $("#img_name" + id).attr("src", URL + 'uploads/app_menus/' + respond.image);
                        $("#img_hidden_name" + id).val(respond.image);
                    }
                    $(".upload").val("");
                    //prevent_leave_page(true);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading" + id).addClass("hide");
                }
            }).submit();
        }

        $('.get_menu_cate_id').click(function () {
            var id = $(this).attr("id");
            var title = $(this).attr("data-name");
            $(".menu_cate_id").val(id);
            $(".error-message").html("");
            //$("#label-delete").html(title + "を削除します。<br>この" + title + "を非表示BOX箱に移 <br>動します。<br>よろしいですか？");
            $("#label-delete-confirm").html("本当によろしいですか？");
        });

        function save_sort() {
            var data = $('#menu_category').serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'menu_categories', 'action' => 'index')); ?>",
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
            if (prev.length == 0)
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
            if (next.length == 0)
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

        $('body').on('click', '.btn-move', function (e) {
            e.preventDefault();
            var btn = $(this);
            var val = btn.val();
            if (val == 'up')
                moveUp(btn.parents('.item'));
            else
                moveDown(btn.parents('.item'));
        });

        $('body').on('click', '.btn_delete', function (e) {
            e.preventDefault();
        });
    });
</script>