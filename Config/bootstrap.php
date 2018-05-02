<?php

/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
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
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 */
/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 */
/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); // Loads a single plugin named DebugKit
 */
/**
 * To prefer app translation over plugin translation, you can set
 *
 * Configure::write('I18n.preferApp', true);
 */
/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 * 		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 * 		'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 * 		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 * 		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 * 		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
    'engine' => 'File',
    'types' => array('notice', 'info', 'debug'),
    'file' => 'debug',
));
CakeLog::config('error', array(
    'engine' => 'File',
    'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
    'file' => 'error',
));
//Value pair of Codes
define('PAGE_LIMIT', 10);
define('DENTAL_CLINIC_ASP', 'Dental Clinic ASP ログイン');
define('USERNAME', 'ユーザID');
define('PASSWORD', 'パスワード');
define('LOGIN', 'ログイン');
define('LOGOUT', 'ログアウト');
define('COPYRIGHT_�_DENTAL_CLINIC_ASP_ALL_RIGHTS_RESERVED', 'Copyright � Dental Clinic ASP. All rights reserved.');
define('AGENT_MANAGEMENT', 'エージェント管理');
define('AGENT_MANAGEMENT_CREATE_NEW', '新規作成');
define('AGENT_MANAGEMENT_EDIT', '編集');
define('SEARCH', '検索');
define('CREATE_NEW', '新規作成');
define('AGENT_NAME', '名称');
define('CREATED', '作成日');
define('CONTACT', 'コンタクト');
define('STATUS', 'ステータス');
define('OPERATION', 'オペレーション');
define('EDIT', ' 編集');
define('DISPLAY', ' 表示');
define('DELETE', ' 削除');
define('ACTIVATE', ' アクティベート');
define('PREVIOUS', '前');
define('NEXT', '次');
define('NAME', '名称');
define('HOME', 'ホーム');
define('SELECT_STATUS', '選択してください');
define('ID', 'ID');
define('ACTIVE', 'アクティブ');
define('DEACTIVE', '非アクティブ');
define('STOP', '停止');
define('SUSPENSE', '一時停止');
define('ACTIVATED', 'アクティベート');
define('DEACTIVATED', '非アクティベート');
define('STOPPED', ' 停止');
define('SUSPENSED', '一時停止');
define('SAVE_USER', '保存');
define('SHOP_NAME', 'ショップ名称');
define('SHOP_OWNER_MANAGEMENT', 'ショップオーナー管理');
define('SHOP_OWNER_MANAGEMENT_CREATE_NEW', '新規作成');
define('SHOP_OWNER_MANAGEMENT_EDIT', '編集');
define('USER_MANAGEMENT', 'ユーザ管理');
define('USER_MANAGEMENT_CREATE_NEW', '新規作成');
define('USER_MANAGEMENT_EDIT', '編集');
define('ROLE', '種別');
define('AGENT', 'エージェント');
define('SHOP', 'ショップ');
define('CREATE_PLAN', '新規作成');
define('SELECT_SHOP','Select shop');
define('SELECT_ROLE', '選択してください');
define('NEWS_MENAGEMENT_FOR_AGENTS_AND_SHOPS', 'ニュース管理');
define('NEWS_MANEGEMENT_CREATE_NEW', '新規作成');
define('NEWS_MANAGEMENT_EDIT', '編集');
define('NEWS_MANAGEMENT', 'ニュース');
define('NEW_TITLE', 'タイトル');
define('CONTENT', '内容');
define('TARGET', '配信先');
define('SELECT_TARGET', '選択してください');
define('MEDIA', 'メディア');
define('TIME', '時間');
define('VISIBLE', '表示');
define('SAVE_NEWS', '保存');
define('IP_ADDRESS_MENAGEMENT', 'IPアドレス管理');
define('IP_ADDRESS_MENAGEMENT_CREATE_NEW', '新規作成');
define('IP_ADDRESS_MENAGEMENT_EDIT', '編集');
define('IP', 'IP');
define('SAVE_IP', '保存');
define('REMARK', '備考');
define('REMARKS', '備考');
define('GENRE_MANAGEMENT', 'ジャンル管理');
define('GENRE_MANAGEMENT_CREATE_NEW', '新規作成');
define('GENRE_MANAGEMENT_EDIT', '編集');
define('GENRE_NAME', 'ジャンル名称');
define('SAVE_GENRE', '保存');
define('SET_FUNCTION', ' 機能設定');
define('TAG_MANAGEMENT', ' タグ管理');
define('FUNCTION1', '機能1');
define('FUNCTION2', '機能2');
define('FUNCTION3', '機能3');
define('FUNCTION4', '機能4');
define('FUNCTION5', '機能5');
define('ADMINISTRATOR', '管理者');
define('AGENT_USER', 'エージェント');
define('SHOP_USER', 'ショップユーザ');
define('NORMAL_USER', '一般ユーザ');
define('SAVE_FUNCTION', '保存');
define('TAG_MANAGEMENT_CREATE_NEW', '新規作成');
define('TAG_MANAGEMENT_EDIT', '編集');
define('TAG_NAME', 'タグ名称');
define('SAVE_TAG', '保存');
define('SAVE', '保存');
define('PLAN_MANAGEMENT', 'プラン管理');
define('BACK', '戻る');
define('DUPLICATE_DATA', 'Duplicate data');
define('EMPTY_DATA', 'データが空です。');
define('UPLOAD_IMAGE', 'Please upload image!');

define('MESSAGE_CREATE', '新規作成しました。');
define('MESSAGE_UPDATE', '更新しました。');
define('MESSAGE_DELETE', '削除しました。');
define('MESSAGE_FAIL', '処理に失敗しました。再度お試し下さい。');

define('RESERVATION_MANAGEMENT', '新規予約');
define('PLAN_MANAGEMENT_CREATE_NEW', '新規作成');
define('PLAN_MANAGEMENT_EDIT', '編集');
define('PLAN_NAME', 'プラン名称');
define('GENRE', 'ジャンル');
define('SELECT_GENRE', '選択してください');
define('SAVE_PLAN', '保存');
define('FUNCTION_MANAGEMENT_BY_GENRE', 'ジャンル毎の機能管理');
define('FUNCTION_MANAGEMENT_BY_PLAN', 'プラン毎の機能管理');
define('URL', 'URL');
define('PLAN_TYPE','Plan type');
define('SELECT_PLAN_TYPE','Select plan type');
define('SERVICE_NAME','Service name');
define('GOTO_LOGIN','Go to login page');
define('SUBMIT_EMAIL','Submit Email');// user when for submit email when user reset password
define('EMAIL_NOT_EXIST','Email address does not exist');
define('CLICK_LINK_TO_RESET_PASSWORD','Please click this link to reset password');
define('PLEASE_CHECK_EMAIL_TO_RESET_PASSWORD','Please check you email to reset password');
define('EMAIL_FROM','support@dental-clinc.com');
define('RESET_PASSWORD','Reset password');
define('FORGOT_PASSWORD','パスワードをお忘れですか');
define('EMAIL_SUBJECT_RESET_PASSWORD','Reset password');
define('EMAIL_HAS_BEEN_SENT','Email has been sent');
define('RESET','Reset');
define('PASSWORD_AT_LEAST_6CHARACTORS','Password at lease 6 charactors');
define('PASSWORD_MISMATCH','Password mismatch');
define('PASSWORD_HAS_BEEN_RESETED','Password has been reseted');
define('PASSWORD_COULD_NOT_RESET','Password could not reseted');
define('GENRE_COULD_NOT_BE_SAVE', '処理に失敗しました。再度お試しください。');
define('IP_COULD_NOT_BE_SAVE', '処理に失敗しました。再度お試しください。');
define('PLAN_COULD_NOT_BE_SAVE', '処理に失敗しました。再度お試しください。');
define('TAG_COULD_NOT_BE_SAVE', '処理に失敗しました。再度お試しください。');
define('NEWS_COULD_NOT_BE_SAVE', '処理に失敗しました。再度お試しください。');
define('USER_COULD_NOT_BE_SAVE', '処理に失敗しました。再度お試しください。');
define('INVALID_USERNAME_PASSWORD', 'ユーザIDかパスワードが不正です。再度お試しください。');
define('INVALID_USER', '値が不正です。');
define('INVALID_NEWS', '値が不正です。');
define('INVALID_GENRE', '値が不正です。');
define('INVALID_PLAN', '値が不正です。');
define('INVALID_TAG', '値が不正です。');
define('INVALID_IP', '値が不正です。');
define('IMAGE_CANNOT_SAVE', 'アップロードできませんでした。再度お試しください。');
define('TYPE_NOT_ALLOW', 'この拡張子はアップロードできません。再度お試しください。');
define('ARE_YOU_SURE_WANT_TO_DELETE_THIS_PHOTO', 'Are you sure want to delete this photo?');
define('MALE','Male');
define('ENABLE','Enable');
define('DISABLE','Disable');
define('FEMALE','Female');
define('ITEMS_ON_PAGE',5);
define('SELECT_GENDER','性別');
define('SELECT_AREA','地域');
define('HTTP','http://');

define('MESSAGE_SUCCESS','Success.');
define('MESSAGE_ERROR','Request is not success.');

define('COUPON_NOTIFICATION','coupon_notification');
define('BIRTHDAY_NOTIFICATION','birthday_notification');
define('LAST_VISIT_NOTIFICATION','last_visit_notification');
define('MAX_NOTIFICATION', 3);
define('MAX_DAY', 90);
define('MIN_TIME', 7);
define('MAX_TIME', 21);

define('GENDER', serialize(array(
    '男性' => '男性',
    '女性' => '女性'
)));

define('NOTICE_TYPE_SETTING', 'notice_settings');
define('NOTICE_TYPE_RESERVATION', 'reservation_notice');

define('ROLE_SHOP', 'shop');
define('ROLE_HEADQUARTER', 'headquarter');
define('ROLE_USER', 'user');

define('USER_STATUS_ACTIVE', 1);
define('USER_STATUS_DISABLED', 0);
define('ANDROID_PLATFORM', 'android');
define('IOS_PLATFORM', 'ios');

define('USER_ROLE', serialize(array(
    ROLE_SHOP => '店舗管理アカウント',
    ROLE_HEADQUARTER => '本社管理アカウント'
)));

define('FUNCTION_NAME', serialize(array(
    'Myカルテ' => 'Myカルテ',
    '利用履歴' => '利用履歴'
)));

define('TAG_REMOVE', serialize(array(
    'sns_share',
    'coupon',
    'web_reservations',
    'photo_gallery'
)));

define('MONDAY','月');
define('TUESDAY','火');
define('WEDNESDAY','水');
define('THURSDAY','木');
define('FRIDAY','金');
define('SATURDAY','土');
define('SUNDAY','日');

define('JANUARY','January');
define('FEBRUARY','February');
define('MARCH','March');
define('APRIL','April');
define('MAY','May');
define('JUNE','June');
define('JULY','July');
define('AUGUST','August');
define('SEPTEMBER','September');
define('OCTOBER','October');
define('NOVEMBER','November');
define('DECEMBER','December');

define('NOTICE_TYPE','通知種類');

define('IOS_URL', 'ssl://gateway.push.apple.com:2195');
define('IOS_PATH', WWW_ROOT.'ios_push'.DS.'production/');
define('IOS_APP_NAME', 'Conexi');

define('TIME_NOTICES', serialize(array(
    '' => '--',
    '10:00:00' => '10:00',
    '10:30:00' => '10:30',
    '11:00:00' => '11:00',
    '11:30:00' => '11:30',
    '12:00:00' => '12:00',
    '12:30:00' => '12:30',
    '13:00:00' => '13:00',
    '13:30:00' => '13:30',
    '14:00:00' => '14:00',
    '14:30:00' => '14:30',
    '15:00:00' => '15:00',
    '15:30:00' => '15:30',
    '16:00:00' => '16:00',
    '16:30:00' => '16:30',
    '17:00:00' => '17:00',
    '17:30:00' => '17:30',
    '18:00:00' => '18:00',
    '18:30:00' => '18:30',
    '19:00:00' => '19:00',
    '19:30:00' => '19:30',
    '20:00:00' => '20:00',
    '20:30:00' => '20:30',
    '21:00:00' => '21:00'
)));

define('NEWS_FILTER', serialize(array(
    'all' => '全体',
    'filter' => '絞り込み'
)));

define('NEWS_TARGET_AGE', serialize(array(
    '1-19' => '～19歳',
    '20-29' => '20～29歳',
    '30-39' => '30～39歳',
    '40-49' => '40～49歳',
    '50-59' => '50～59歳',
    '60-60' => '60歳～',
)));
//Upload resize
define('THUMBNAIL_SIZE_1', serialize(array('width' => 300, 'height' => 300, 'ext' => '-fit-300x300.')));

define('NOTIFICATION_MSG_BIRTHDAY_OR_NEWS', 'お知らせが届いています。');
define('NOTIFICATION_MSG_SEND_PHOTO', '写真が届いています。');
define('NOTIFICATION_MSG_COUPON', 'クーポンが届いています。');