<div class="collection">
    <?php foreach ($shops_data as $key => $value) : ?>
        <div class="item" id="<?php echo $value['Shop']['id']; ?>">
            <p>店舗名</p>
            <div class="row">
                <div class="col-md-11">
                    <input type="text" class="form-control" value="<?php echo $value['Shop']['name']; ?>"/>
                    <input type="hidden" class="form-control" value="<?php echo $value['Shop']['id']; ?>"/>
                </div>
                <div>
                    <button value='up' class="btn btn-default btn-sm"><i class="fa fa-lg fa-arrow-up"></i></button><br>
                    <button value='down' class="btn btn-default btn-sm"><i class="fa fa-lg fa-arrow-down"></i></button>
                </div>
            </div>
            <br>
            <div class="row pull-right">
                <div class="col-md-12 ">
                    <a href="javascript:void(0);" id="<?php echo $value['Shop']['id']; ?>" class="btn btn-default get_shop_id btn_view_shop" data-toggle="modal" data-target="#ModalView">View</a>
                    <a href="#" id="<?php echo $value['Shop']['id']; ?>" class="btn btn-default get_shop_id btn_edit_shop" data-toggle="modal" data-target="#ModalEdit" >Edit</a>
                    <a href="javascript:void(0);" id="<?php echo $value['Shop']['id']; ?>" class="btn btn-default get_shop_id" data-toggle="modal" data-target="#ModalDeleteShop">Delete</a>
                    <a href="javascript:void(0);" id="<?php echo $value['Shop']['id']; ?>" class="btn btn-default get_shop_id" data-toggle="modal" data-target="#ModalPublishedShop">Public/Private</a>
                </div>
            </div>
            <br><br>
        </div>
    <?php endforeach; ?>
</div>
<script type="text/javascript">
    $(function () {
        $(".get_shop_id").on("click", function () {
            $(".error-message").html("");
            $(".success-message").html("");
            $("#image").val("");
            var shop_id = $(this).attr("id");
            $(".shop_id").val(shop_id);
        });
        $(".btn_view_shop").click(function () {
            $.ajax({
                url: "<?php $this->Html->url(array('controlller' => 'shops', 'action' => 'index')); ?>",
                data: "action=detail&shop_id=" + $(this).attr('id'),
                dataType: "json",
                success: function (respond) {
                    if (respond.result === "success") {
                        var time = (respond.data.AppInformation[0].business_hours_start).substring(0, 5);
                        time += respond.data.AppInformation[0].business_hours_start_type;
                        time += " - " + (respond.data.AppInformation[0].business_hours_end).substring(0, 5);
                        time += respond.data.AppInformation[0].business_hours_end_type;
                        $("#txt_shop_name").val(respond.data.Shop.name);
                        $("#txt_address").val(respond.data.AppInformation[0].address);
                        $("#txt_time").val(time);
                        $("#txt_holiday").val(respond.data.AppInformation[0].holidays);
                        $("#txt_phone").val(respond.data.AppInformation[0].phone);
                        $("#txt_fax").val(respond.data.AppInformation[0].fax);
                        $("#txt_url").val(respond.data.AppInformation[0].url);
                        $("#txt_email").val(respond.data.AppInformation[0].email);
                        $("#txt_facebook").val(respond.data.AppInformation[0].facebook);
                        $("#txt_twitter").val(respond.data.AppInformation[0].twitter);

                        var path = document.getElementById("sample_img").src;
                        document.getElementById("img_name").src = path + "/" + respond.data.AppInformation[0].image;
                    }
                },
                error: function () {
                    console.log("Error view shop");
                }
            });
        });
        $(".btn_edit_shop").click(function () {
            $.ajax({
                url: "<?php $this->Html->url(array('controlller' => 'shops', 'action' => 'index')); ?>",
                data: "action=detail&shop_id=" + $(this).attr('id'),
                dataType: "json",
                success: function (respond) {
                    if (respond.result === "success") {
                        var start = respond.data.AppInformation[0].business_hours_start;
                        start += respond.data.AppInformation[0].business_hours_start_type;
                        var end = respond.data.AppInformation[0].business_hours_end;
                        end += respond.data.AppInformation[0].business_hours_end_type;
                        $("#introduction_edit").val(respond.data.AppInformation[0].introduction);
                        $("#shop_name_edit").val(respond.data.AppInformation[0].shop_name);
                        $("#shop_kana_edit").val(respond.data.AppInformation[0].shop_kana);
                        $("#address_edit").val(respond.data.AppInformation[0].address);
                        $("#business_hours_start_edit").val(start);
                        $("#business_hours_end_edit").val(end);
                        $("#holidays_edit").val(respond.data.AppInformation[0].address);
                        $("#phone_edit").val(respond.data.AppInformation[0].address);
                        $("#fax_edit").val(respond.data.AppInformation[0].fax);
                        $("#url_edit").val(respond.data.AppInformation[0].url);
                        $("#email_edit").val(respond.data.AppInformation[0].email);
                        $("#facebook_edit").val(respond.data.AppInformation[0].facebook);
                        $("#twitter_edit").val(respond.data.AppInformation[0].twitter);
                        $("#ios_download_link_edit").val(respond.data.AppInformation[0].ios_download_link);
                        $("#android_download_link_edit").val(respond.data.AppInformation[0].android_download_link);

                        $('#start_time').timepicker();
                        $('#end_time').timepicker();
                    }
                },
                error: function () {
                    console.log("Error edit shop");
                }
            });
        });
    });
    $(document).ready(function () {
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
        }
        function sendOrderToServer() {
            var items = $(".collection").sortable('toArray');
            var itemList = jQuery.grep(items, function (n, i) {
                return (n !== "" && n != null);
            });
            $("#items").html(itemList);
        }
        $(".collection").sortable({
            items: ".item"
        });
        $('button').click(function () {
            var btn = $(this);
            var val = btn.val();
            if (val == 'up')
                moveUp(btn.parents('.item'));
            else
                moveDown(btn.parents('.item'));
        });
        var orderList = jQuery.grep($(".collection").sortable('toArray'), function (n, i) {
            return (n !== "" && n != null);
        });
        $("#items").html(orderList);
    });
</script>