<?php

class NoticeShell extends AppShell
{

    public function main()
    {
        App::import('Model', 'News');
        $News = new News();
        //$News->sendNews(); @depricated
        
        $News->send_news_notification();

        //Update StampExpire
        App::import('Model', 'StampSetting');
        $StampSetting = new StampSetting();
        $output = array();
        $output[] = $StampSetting->stampSettingExpire();
        if (!empty($output[0])) {
            $this->out($output);
            $this->log($output);
        }
    }
}