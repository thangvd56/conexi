<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/*
API Routes
 *  */
 Router::mapResources(array('users','photos'));
 Router::parseExtensions("json");
    Router::resourceMap(array(
        array('action' => 'index', 'method' => 'GET', 'id' => false),
        array('action' => 'is_model_exist', 'method' => 'GET', 'id' => false),
        array('action' => 'signup', 'method' => 'POST', 'id' => false),
        array('action' => 'update_notification', 'method' => 'POST', 'id' => false),
    ));
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/admin', array('controller' => 'admin_homes', 'action' => 'index', 'admin' => true));
Router::connect('/', array('controller' => 'homes', 'action' => 'index'));
Router::connect('/users/logout-confirm', array('controller' => 'logoutconfirms', 'action' => 'index'));
Router::connect('/users/view/app-info', array('controller' => 'app_informations', 'action' => 'index'));
Router::connect('/users/view/app-menu', array('controller' => 'menu_categories', 'action' => 'index'));
Router::connect('/users/view/app-menu-list', array('controller' => 'application_menu_lists', 'action' => 'index'));
Router::connect('/users/view/app-photo-gallery', array('controller' => 'photos', 'action' => 'index'));
Router::connect('/users/view/app-photo-gallery-list', array('controller' => 'photo_lists', 'action' => 'index'));
Router::connect('/users/view', array('controller' => 'users', 'action' => 'user_setting'));
Router::connect('/users/view/app-stamp', array('controller' => 'stamps', 'action' => 'index'));
Router::connect('/users/stamp_settings/create', array('controller' => 'stamp_settings', 'action' => 'create'));
Router::connect('/users/view/app-stamp-edit', array('controller' => 'stamps', 'action' => 'edit'));
Router::connect('/users/view/app-staffs', array('controller' => 'staffs', 'action' => 'index'));
Router::connect('/users/view/app-staff-create', array('controller' => 'staffs', 'action' => 'create'));
Router::connect('/users/view/app-staff-edit', array('controller' => 'staffs', 'action' => 'edit'));
Router::connect('/users/view/app-reservation-url', array('controller' => 'reservation_urls', 'action' => 'index'));
Router::connect('/users/view/app-messages', array('controller' => 'templates', 'action' => 'index'));
Router::connect('/users/view/app-sns', array('controller' => 'sns_shares', 'action' => 'index'));
Router::connect('/users/view/reservation_notification', array('controller' => 'users', 'action' => 'reservation_notification'));
Router::connect('/users/view/notification', array('controller' => 'news', 'action' => 'notification'));
Router::connect('/users/view/advance_notification', array('controller' => 'news', 'action' => 'advance_notification'));
Router::connect('/users/view/last_visit_notification', array('controller' => 'news', 'action' => 'last_visit_notification'));
Router::connect('/users/view/last_visit_notification_create', array('controller' => 'news', 'action' => 'last_visit_notification_create'));
Router::connect('/users/view/last_visit_notification_edit', array('controller' => 'news', 'action' => 'last_visit_notification_edit'));
Router::connect('/users/view/staff-info', array('controller' => 'staffs', 'action' => 'staff_info'));
Router::connect('/users/view/staff-info-edit', array('controller' => 'staffs', 'action' => 'staff_info_edit'));
Router::connect('/users/view/app-staff-create', array('controller' => 'staffs', 'action' => 'create'));
Router::connect('/users/view/first_notification', array('controller' => 'users', 'action' => 'first_notification'));
Router::connect('/news', array('controller' => 'news', 'action' => 'news_index'));
Router::connect('/customers/delete_tag', array('controller' => 'customers', 'action' => 'delete_tag'));
Router::connect('/users/view/app-supports', array('controller' => 'supports', 'action' => 'index'));
Router::connect('/users/view/app-support-create', array('controller' => 'supports', 'action' => 'create'));
Router::connect('/users/view/notices', array('controller' => 'notices', 'action' => 'index'));
Router::connect('/users/view/notices-create', array('controller' => 'notices', 'action' => 'create'));
Router::connect('/users/view/notices-edit', array('controller' => 'notices', 'action' => 'edit'));
Router::connect('/users/view/reservations', array('controller' => 'reservations', 'action' => 'index'));
Router::connect('/function-setting', array('controller' => 'settings', 'action' => 'index'));
Router::connect('/users/view/operation-chair', array('controller' => 'chairs', 'action' => 'index'));
Router::connect('/users/view/tag-registration', array('controller' => 'tags', 'action' => 'index'));

/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
