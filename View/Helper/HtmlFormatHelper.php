<?php
App::uses('AppHelper', 'View/Helper');

class HtmlFormatHelper extends AppHelper
{
    public $helpers = array('Html', 'Form');

    public function japanese_day ($day)
    {
        switch ($day) { //'日', '月', '火', '水', '木', '金', '土'
            case 'Monday':
                return '月';
            case 'Tuesday':
                return '火';
            case 'Wednesday':
                return '水';
            case 'Thursday':
                return '木';
            case 'Friday':
                return '金';
            case 'Saturday':
                return '土';
            default:
                return '日';
        }
    }
}