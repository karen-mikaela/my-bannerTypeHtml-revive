<?php
/**
 * Table Definition for banners_terra
 */
require_once MAX_PATH.'/lib/max/Dal/DataObjects/DB_DataObjectCommon.php';

class DataObjects_Html_template_terra extends DB_DataObjectCommon
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'html_template_terra';              // table name
    public $html_template_id;                             // BIGINT(20) => openads_mediumint => 129  not_null primary_key
    public $template_name;                                // VARCHAR(100) openads_varchar => 130
    public $template_code;                                // TEXT() => openads_text => 34
    
    
    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Html_template_terra',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    /**
     * A private method to return the account ID of the
     * account that should "own" audit trail entries for
     * this entity type; NOT related to the account ID
     * of the currently active account performing an
     * action.
     *
     * @return integer The account ID to insert into the
     *                 "account_id" column of the audit trail
     *                 database table.
     */
    function getOwningAccountIds()
    {
        $accountType = OA_Permission::getAccountType(false);
        switch ($accountType)
        {
            case OA_ACCOUNT_ADMIN:
                return parent::_getOwningAccountIdsByAccountId($accountId  = OA_Permission::getAccountId());
            case OA_ACCOUNT_ADVERTISER:
                $parentTable = 'clients';
                $parentKeyName = 'clientid';
                break;
            case OA_ACCOUNT_TRAFFICKER:
                $parentTable = 'affiliates';
                $parentKeyName = 'affiliateid';
                break;
            case OA_ACCOUNT_MANAGER:
                $parentTable = 'agency';
                $parentKeyName = 'agencyid';
                break;
        }
        return parent::getOwningAccountIds($parentTable, $parentKeyName);
    }

    function _auditEnabled()
    {
        return false;
    }

    function _getContextId()
    {
        return $this->html_template_id;
    }

    function _getContext()
    {
        return 'Html terra template';
    }

}
