<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2012 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * システム情報 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_System_System.php 22496 2013-02-04 09:12:18Z m_uehara $
 */
class LC_Page_Admin_System_System extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'system/system.tpl';
        $this->tpl_subno    = 'system';
        $this->tpl_mainno   = 'system';
        $this->tpl_maintitle = t('c_System_01');
        $this->tpl_subtitle = t('c_System information_01');
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {

        $objFormParam = new SC_FormParam_Ex();

        $this->initForm($objFormParam, $_GET);
        switch ($this->getMode()) {

            // PHP INFOを表示
            case 'info':
                phpinfo();
                SC_Response_Ex::actionExit();
                break;

            default:
                break;
        }

        $this->arrSystemInfo = $this->getSystemInfo();

    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
     * フォームパラメーター初期化.
     *
     * @param object $objFormParam
     * @param array $arrParams $_GET値
     * @return void
     */
    function initForm(&$objFormParam, &$arrParams) {
        $objFormParam->addParam(t('c_mode_01'), 'mode', INT_LEN, '', array('ALPHA_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->setParam($arrParams);
    }

    /**
     * システム情報を取得する.
     *
     * @return array システム情報
     */
    function getSystemInfo() {
        $objDB = SC_DB_DBFactory_Ex::getInstance();

        $arrSystemInfo = array(
            array('title' => t('c_EC-CUBE_01'),     'value' => ECCUBE_VERSION),
            array('title' => t('c_Server OS_01'),    'value' => php_uname()),
            array('title' => t('c_DB server_01'),    'value' => $objDB->sfGetDBVersion()),
            array('title' => t('c_WEB server_01'),   'value' => $_SERVER['SERVER_SOFTWARE']),
        );

        $value = phpversion() . ' (' . implode(', ', get_loaded_extensions()) . ')';
        $arrSystemInfo[] = array('title' => 'PHP', 'value' => $value);

        if (extension_loaded('GD') || extension_loaded('gd')) {
            $arrValue = array();
            foreach (gd_info() as $key => $val) {
                $arrValue[] = "$key => $val";
            }
            $value = t('c_Enabled_01') . ' (' . implode(', ', $arrValue) . ')';
        } else {
            $value = t('c_Inactive_01');
        }
        $arrSystemInfo[] = array('title' => t('c_GD_01'), 'value' => $value);
        $arrSystemInfo[] = array('title' => t('c_HTTP user agent_01'), 'value' => $_SERVER['HTTP_USER_AGENT']);

        return $arrSystemInfo;
    }
}
