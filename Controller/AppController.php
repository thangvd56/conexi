<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    /*
     * $components
     * Created 10/ November/2015
     * Channeth
     */

    public $components = array(
        'Session',
        'Auth' => array(
            //'loginRedirect' => '/admin',
//            'loginRedirect' => '/users/view',
//            'logoutRedirect' => '/users/view',
            'loginRedirect' => '/records',
            'logoutRedirect' => '/records',
            'loginAction' => '/users/login',
            'authorize' => 'controller',
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish',
                    'fields' => array('username' => 'username')
                )
            )
        ),
        'Cookie'
    );
    /*
     * Function beforeFilter
     * Modified 02/ December/2015
     * Channeth
    */
    public function beforeFilter() {
        /*$this->Cookie->key = 'qSI232qs*&sXOw!adre@34SAv!@*(XSL#$%)asGb$@11~_+!@#HKis~#^';
        $this->Cookie->httpOnly = true;
        if (!$this->Auth->loggedIn() && $this->Cookie->read('remember_me_cookie')) {
            $cookie = $this->Cookie->read('remember_me_cookie');
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.username' => $cookie['username'],
                    'User.password' => $cookie['password']
                )
            ));
            if ($user && !$this->Auth->login($user['User'])) {
                $this->redirect('/users/logout'); // destroy session & cookie
            }
        }*/
        if (isset($this->request->params['admin'])) {
            $this->theme = 'Admin';
        }
        //Function display amount of notification
        $dt   = new DateTime();
        $date = $dt->format('Y-m-d');
        $this->loadModel('News');
        $this->loadModel('NewsDelivery');
        $news_display = $this->News->find("all", array(
            "joins" => array(
                array(
                    "table" => "news_deliveries",
                    "alias" => "NewsDelivery",
                    "type" => "INNER",
                    "conditions" => array(
                        "News.id = NewsDelivery.news_id",
                    )
                ),
            ),
            'conditions' => array(
                'NewsDelivery.user_id' => $this->Auth->user('id'),
                'NewsDelivery.is_published' => 1,
                'NewsDelivery.is_read' => 0,
                'News.delivery_date_value <=' => $date,
                'NewsDelivery.is_deleted <>' => 1
            ),
        ));
          if($news_display){
                $this->set('news_display',$news_display);
          }else{
                $this->set('news_display','');
          }
    }

    /*
     * Function isAuthorized
     * Modified 11/ November/2015
     * Channeth
     */

    public function isAuthorized($user) {
        if (empty($this->request->params['admin'])) {
            return true;
        }

        if (isset($this->request->params['admin'])) {
            return (bool) ($user['role'] === 'admin');
        }

        return false;
    }

}
