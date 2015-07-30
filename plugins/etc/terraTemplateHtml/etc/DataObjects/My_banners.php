<?php
/**
 * Table Definition for my_banners
 */
require_once MAX_PATH.'/lib/max/Dal/DataObjects/DB_DataObjectCommon.php';

class DataObjects_My_banners extends DB_DataObjectCommon
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'my_banners';           // table name
    public $bannerid;                            // BIGINT(20) => openads_mediumint => 129  not_null primary_key
    public $terra_link_url;                      // VARCHAR(100) openads_varchar => 130
    public $terra_link_text;                     // VARCHAR(100) openads_varchar => 130
    public $terra_title;                         // VARCHAR(100) openads_varchar => 130
    public $terra_description;                   // VARCHAR(100) openads_varchar => 130
    public $terra_click_url_unesc;               // VARCHAR(100) openads_varchar => 130


    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_My_banners',$k,$v); }

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
        return $this->bannerid;
    }

    function _getContext()
    {
        return 'Banner based html terra template';
    }

    /**
     * build a my_banners specific audit array
     *
     * @param integer $actionid
     * @param array $aAuditFields
     */
    function _buildAuditArray($actionid, &$aAuditFields)
    {
        $aAuditFields['key_desc']   = $this->$bannerid;
    }
}
