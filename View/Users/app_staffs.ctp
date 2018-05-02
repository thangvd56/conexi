<div class="container-fluid well">
    <div class="row">
        <h2>スタッフ情報を登録しよう！<a href="javascript:void(0);" data-toggle="modal" data-target="#ModalView">Click to view list</a></h2>
    </div>
    <div class="row">
        <table class="table table-bordered" id="tbl-add">
            <tr>
                <td class="col-md-2">
                    <image src ="sample.jpg"/>
                </td>
                <td class="col-md-9" style="border-right:0px;">
                    <p>名前</p>
                    <p>自己紹介一部</p>
                    <input type="button" class="btn btn-default" value="編集" data-toggle="modal" data-target="#ModalEdit"/>
                    <input type="button" class="btn btn-default" value="公開/非公開"/>
                    <input type="button" class="btn btn-default" value="削除"/>
                    <input type="button" class="btn btn-default" value="出勤中ON/OFF"/>
                </td>
                <td class="col-md-1" style="border-left:0px;">
                    <a href="javascript:void(0);"><i class="fa fa-lg fa-arrow-up"></i></a>
                    <br><br><br><br><br>
                    <a href="javascript:void(0);"><i class="fa fa-lg fa-arrow-down"></i></a>
                </td>
            </tr>
            <tr>
                <td class="col-md-2">
                    <image src ="sample.jpg"/>
                </td>
                <td class="col-md-9" style="border-right:0px;">
                    <p>名前</p>
                    <p>自己紹介一部</p>
                    <input type="button" class="btn btn-default" value="編集" data-toggle="modal" data-target="#ModalEdit"/>
                    <input type="button" class="btn btn-default" value="公開/非公開"/>
                    <input type="button" class="btn btn-default" value="削除"/>
                    <input type="button" class="btn btn-default" value="出勤中ON/OFF"/>
                </td>
                <td class="col-md-1" style="border-left:0px;">
                    <a href="javascript:void(0);"><i class="fa fa-lg fa-arrow-up"></i></a>
                    <br><br><br><br><br>
                    <a href="javascript:void(0);"><i class="fa fa-lg fa-arrow-down"></i></a>
                </td>
            </tr>
            <tr>
                <td class="col-md-2">
                    <image src ="sample.jpg"/>
                </td>
                <td class="col-md-9" style="border-right:0px;">
                    <p>名前</p>
                    <p>自己紹介一部</p>
                    <input type="button" class="btn btn-default" value="編集" data-toggle="modal" data-target="#ModalEdit"/>
                    <input type="button" class="btn btn-default" value="公開/非公開"/>
                    <input type="button" class="btn btn-default" value="削除"/>
                    <input type="button" class="btn btn-default" value="出勤中ON/OFF"/>
                </td>
                <td class="col-md-1" style="border-left:0px;">
                    <a href="javascript:void(0);"><i class="fa fa-lg fa-arrow-up"></i></a>
                    <br><br><br><br><br>
                    <a href="javascript:void(0);"><i class="fa fa-lg fa-arrow-down"></i></a>
                </td>
            </tr>
        </table>
    </div>
    <div class="row text-center">
        <div class="col-md-12">
            <input type="button" id="bth-add" class="btn btn-lg btn-default col-md-12" value="スタッフ新規追加"/>
        </div>
        <br><br><br>
        <div class="col-md-12">
            <input type="button" class="btn btn-default col-md-12" value="保存"/>
        </div>
    </div>
</div>
<!--Modal View-->
<div id="ModalView" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">タイトル</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="2">
                                    山田　太郎
                                    <span class="label label-primary pull-right" style="height: 25px;padding: 7px;">出勤中</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-2">
                                    <image src ="sample.jpg"/>
                                </td>
                                <td class="col-md-10">
                                    自己紹介
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="2">
                                    山田　太郎
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-2">
                                    <image src ="sample.jpg"/>
                                </td>
                                <td class="col-md-10">
                                    自己紹介
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="2">
                                    山田　太郎
                                    <span class="label label-primary pull-right" style="height: 25px;padding: 7px;">出勤中</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-2">
                                    <image src ="sample.jpg"/>
                                </td>
                                <td class="col-md-10">
                                    自己紹介
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!--Modal Edit-->
<div id="ModalEdit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">タイトル</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>スタッフ情報を登録しよう！</p>
                        <table class="table table-bordered">
                            <tr>
                                <td class="col-md-2">画像</td>
                                <td class="col-md-12">
                                    <input type="file" class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-2">名前</td>
                                <td class="col-md-12">
                                    <input type="text" class="form-control" placeholder="10字以内で名前を記載"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-2">自己紹介</td>
                                <td class="col-md-12">
                                    <textarea class="form-control" placeholder="300字以内で紹介文を記載"></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-md-12">
                        <input type="button" id="bth-add" class="btn btn-lg btn-default col-md-12" value="スタッフ新規追加"/>
                    </div>
                    <br><br><br>
                    <div class="col-md-12">
                        <input type="button" class="btn btn-default col-md-12" value="保存"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>