<?php
echo $this->Html->css('news');
echo $this->Html->css('custom');
?>

<style>
    .btn_back_color{
        background: #5E5E5E !important;
        border: none;
        width: 100px;
        margin: 5px;
        box-shadow: 0 4px #494949;
    }
</style>
<?php $items   = count($news_search); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-10">
            <h1 class="page-header">
                NEWS 店舗権限
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h3>通知履歴</h3>
        </div>
        <div class="col-md-6">
            <div class="col-md-4">
                <div class="form-group">
                    <?php
                    $display = array(
                        '5' => '選択しま',
                        '20' => '20件表示',
                        '50' => '50件表示',
                        '100' => '100件表示'
                    );

                    echo $this->Form->input('display',
                        array(
                        'id' => 'display',
                        'type' => 'select',
                        'class' => 'form-control',
                        'options' => $display,
                        'label' => false
                    ));
                    ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?php
                    $transmission = array(
                        'select' => '選択しま',
                        'function_addition' => '機能追加',
                        'change' => '変更',
                        'bug' => '不具合',
                        'deals' => 'お得情報',
                        'notice' => 'お知らせ'
                    );
                    echo $this->Form->input('transmission ',
                        array(
                        'id' => 'transmission',
                        'type' => 'select',
                        'class' => 'form-control',
                        'options' => $transmission,
                        'label' => false
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div id="loading_news_list" class="text-center hide">
<?php echo $this->Html->image('/uploads/loading.gif',
    array()).' ローディング'; ?>
                </div>
            </div>
        </div>
        <div id="news_list"></div>
        <div id="news_list_hide" class="hide"></div>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <label style="width: 10%;margin-top: 5px;" class="pull-left"><?php echo '全 '.$items.' 件'; ?></label>
                <div id="paginate" style="width:90%" style="pull-right"></div>
            </div>
        </div>
        <div class="clearfix">&nbsp;</div>
    </div>
</div>
<?php
echo $this->Html->css('simplePagination');
echo $this->Html->script('jquery.simplePagination');
?>
<script>

    $(function () {

        //List of display
        var display = $("#display").val();
        $("#display, #transmission").on("change", function () {
            display = $("#display").val();
            load_change();
        });
        function load_change() {
            $("#paginate").pagination({
                items: "<?php echo $items; ?>",
                //itemsOnPage: "<?php //echo ITEMS_ON_PAGE;        ?>",
                itemsOnPage: display,
                cssStyle: 'light-theme',
                prevText: '前',
                nextText: '次',
                onPageClick: pageClick
            });
            news_list();
        }
        $("#paginate").pagination({
            items: "<?php echo $items; ?>",
            //itemsOnPage: "<?php //echo ITEMS_ON_PAGE;        ?>",
            itemsOnPage: display,
            cssStyle: 'light-theme',
            prevText: '前',
            nextText: '次',
            onPageClick: pageClick
        });
        //Pagination click
        function pageClick() {
            var currentPage = $("#paginate").pagination('getCurrentPage');
            //var itemOnPage = "<?php //echo ITEMS_ON_PAGE;        ?>";
            var itemOnPage = display;
            var start = ((currentPage - 1) * itemOnPage) + 1;
            var start_int = parseInt(start);
            var end = (currentPage) * itemOnPage;
            var end_int = parseInt(end);
            var new_respond = "";
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'news', 'action' => 'fetch_news_list')) ?>",
                dataType: "html",
                beforeSend: function () {
                    $("#loading_news_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#news_list_hide").html("");
                    $("#news_list_hide").html(respond);
                    $(".news_list").each(function () {
                        var id = $(this).attr("id");
                        id = id.split("news_")[1];
                        var id_int = parseInt(id);
                        if (id_int >= start_int && id_int <= end_int) {
                            new_respond += $("#news_" + id)[0].outerHTML;
                        }
                    });
                    $("#news_list").html("");
                    $("#news_list").html("<table class='table table-bordered'>" + new_respond + "</table>");
                },
                error: function (xhr, ajaxOption, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading_news_list").addClass("hide");
                }
            });
        }
        //Loading news list
        function news_list() {
            var new_respond = "";
            var itemOnPage = display;
            $("#news_list").html("");
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'news', 'action' => 'fetch_news_list')) ?>",
                dataType: "html",
                beforeSend: function () {
                    $("#loading_news_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#news_list_hide").html("");
                    $("#news_list_hide").html(respond);
                    $(".news_list").each(function () {
                        var id = $(this).attr("id");
                        id = id.split("news_")[1];
                        var id_int = parseInt(id);
                        if (id_int <= itemOnPage) {
                            new_respond += $("#news_" + id)[0].outerHTML;
                        }
                    });
                    //notification_read();
                    $("#news_list").html("");
                    $("#news_list").html("<table class='table table-bordered'>" + new_respond + "</table>");
                },
                error: function (xhr, ajaxOption, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading_news_list").addClass("hide");
                }
            });
        }
        news_list();
//        //Function hide notification after read
//        function notification_read() {
//            $(".is_read").each(function () {
//             var id = $(this).attr("id");
//             $.ajax({
//                url: "<?php //echo Router::url(array('controller' => 'news', 'action' => 'news_index'))  ?>",
//                data:"&action=read&id=" + id,
//                error: function (xhr) {
//                    console.log("Error:" + xhr.status);
//                }
//            });
//            });
//        }
    });

</script>