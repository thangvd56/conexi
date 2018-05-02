<?php
App::uses('AppHelper', 'View/Helper');

class HtmlModalHelper extends AppHelper
{
    public $helpers = array('Html', 'Form');

    public function modalHeader($id, $title = '', $size = '') {
        $help = '
            <div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog">
                <div class="modal-dialog' . $size . '" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">' . $title . '</h4>
                        </div>
                        <div class="modal-body">
        ';

        return $help;
    }

    public function modalNoHeader($id, $size = '') {
        $help = '
            <div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog">
                <div class="modal-dialog' . $size . '" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
        ';

        return $help;
    }

    public function modalFooter() {
        $help = '
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        ';
        
        return $help;
    }
}