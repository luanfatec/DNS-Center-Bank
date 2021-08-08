<?php

require_once(dirname(__FILE__) . "/db/load.php");

use DB\Controller\UserController;
use DB\Controller\TransferController;
use db\Messages\Messages;

/**
 * Class Route
 */
class Route extends Messages {
    /**
     * @var
     */
    protected $email;
    /**
     * @var
     */
    protected $password;
    /**
     * @var
     */
    protected $id_user;

    /**
     * @var
     */
    protected $unblocked_data;

    /**
     * @var
     */
    protected $transference;

    /**
     * @var
     */
    protected $transfer_details;

    /**
     * @var
     */
    protected $account_number;

    /**
     * @param $attr
     * @param $value
     */
    public function __set($attr, $value)
    {
        $this->$attr = $value;
    }

    /**
     * @return bool|array|string
     */
    public function login()
    {
        $userController = new UserController();
        return $userController->logindo($this->email, $this->password);
    }

    /**
     * Action Logout
     * No return value (Void)
     */
    public function logout()
    {
        $userController = new UserController();
        $userController->logout();
    }

    /**
     * @return mixed
     */
    public function return_account()
    {
        $userController = new UserController();
        return $userController->return_account($this->id_user);
    }

    /**
     * @return false|string
     */
    public function return_transactions()
    {
        $transferController = new TransferController();
        return $transferController->return_transactions($this->id_user, $this->account_number);
    }

    /**
     * @return string|array
     */
    public function transfer()
    {
        $transferController = new TransferController();
        $transferController->__set("data_transfer", $this->transference);
        return $transferController->transfer();
    }

    /**
     * @return false|string
     */
    public function unblockedBalance()
    {
        if (!isset ($this->unblocked_data["value_blocked"]) || $this->unblocked_data["value_blocked"] == 0 || $this->unblocked_data["value_blocked"] == "0")
        {
            return json_encode(array("status" => false, "message" => $this->getMessages("ErrorActionUnblockedBalanceWhiteOrZero")));
        }

        $transferController = new TransferController();
        return $transferController->unblockedBalance();
    }

    /**
     * @return false|string
     */
    public function return_transaction_details()
    {
        $transferController = new TransferController();
        $transferController->__set("transfer_details", $this->transfer_details);
        return $transferController->return_transaction_details();
    }

    public function update_personal()
    {
        $userController = new UserController();
        $userController->__set('_files', $_FILES);
        $userController->__set('_post', $_POST);
        $userController->__set('id_user', $this->id_user);
        $userController->__set('email', $this->email);
        return $userController->update_personal();
    }
    
}
