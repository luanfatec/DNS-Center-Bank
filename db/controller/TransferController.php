<?php

namespace DB\Controller;

require_once(dirname(__FILE__) . "/../load.php");

use DB\Model\Users;
use DB\Model\Actions;

/**
 * Class TransferController
 * @package DB\Controller
 */
class TransferController {
    /**
     * @var
     */
    protected $data_transfer;
    /**
     * @var
     */
    protected $data_unblocked;

    /**
     * @var
     */
    protected $value_for_deposit;

    /**
     * @var
     */
    protected $id_user;

    /**
     * @var
     */
    protected $transfer_details;

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * @return bool
     */
    public function transfer()
    {
        $actions = new Actions();
        $actions->__set('id_user', $_SESSION["id_user"]);
        $actions->__set('user_name', $_SESSION["name"]);
        $actions->__set('transfer_data', $this->data_transfer);
        return $actions->transfer();
    }

    /**
     * @param $id_user
     * @return false|string
     */
    public function return_transactions($id_user, $account_number)
    {
        $actions = new Actions();
        $actions->__set('id_user', $id_user);
        $actions->__set('account_number', $account_number);
        $transactions = $actions->getTransactions();
        if (!$transactions) {
            return json_encode(array("status" => false));
        }
        return json_encode($transactions);
    }

    /**
     * @return false|string
     */
    public function unblockedBalance()
    {
        $actions = new Actions();
        $actions->__set('id_user', $_SESSION['id_user']);
        return $actions->unblockedBalance();
    }

    /**
     * @return false|string
     */
    public function return_transaction_details()
    {
        $actions = new Actions();
        $actions->__set("transfer_details", $this->transfer_details);
        return json_encode($actions->return_transaction_details());
    }

    public function deposit()
    {
        $actions = new Actions();
        $actions->__set('id_user', $this->id_user);
        return json_encode($actions->deposit($this->value_for_deposit));
    }
}
/**
ac_account_destiny
ac_account_origin
ac_agency_destiny
ac_agency_origin
created_at
email
name
value_transaction
 */