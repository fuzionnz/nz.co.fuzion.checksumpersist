<?php

require_once 'checksumpersist.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function checksumpersist_civicrm_config(&$config) {
  _checksumpersist_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function checksumpersist_civicrm_xmlMenu(&$files) {
  _checksumpersist_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function checksumpersist_civicrm_install() {
  _checksumpersist_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function checksumpersist_civicrm_uninstall() {
  _checksumpersist_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function checksumpersist_civicrm_enable() {
  _checksumpersist_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function checksumpersist_civicrm_disable() {
  _checksumpersist_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function checksumpersist_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _checksumpersist_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function checksumpersist_civicrm_managed(&$entities) {
  _checksumpersist_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function checksumpersist_civicrm_caseTypes(&$caseTypes) {
  _checksumpersist_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function checksumpersist_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _checksumpersist_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook__civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function checksumpersist_civicrm_buildForm($formName, &$form) {
  // INZ-7903: Persist checksum auth beyond the contribute form.
  $persist_checksum = TRUE;
  // Don't persist if we are logged in as a Drupal user.
  global $user;
  if ($drupal_userid = $user->uid) {
    $persist_checksum = FALSE;
  }
  // Don't persist if we have an existing CiviCRM checksum.
  $session = CRM_Core_Session::singleton();
  if ($civicrm_userid = $session->get('userID')) {
    $persist_checksum = FALSE;
  }
  if ($persist_checksum) {
    switch ($formName) {
      case 'CRM_Contribute_Form_Contribution_Main':
        if ($cs = CRM_Utils_Request::retrieve('cs', 'String' , $this, false)) {
          if ($id = CRM_Utils_Request::retrieve('cid', 'Positive', $this, false, $userID)) {
            if (CRM_Contact_BAO_Contact_Utils::validChecksum($id, $cs)) {
              $session->set('userID', $id) ;
            }
          }
        }
        break;
    }
  }
}
