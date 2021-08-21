<?php


namespace DB\model;

require_once(dirname(__FILE__) . "/../load.php");

use DB\DBConnection;
use db\Messages\Messages;

/**
 * Class Actions
 * @package DB\model
 */
class Actions extends Messages
{
    /**
     * @var
     */
    protected $transfer_data;

    /**
     * @var
     */
    protected $id_user;
    /**
     * @var
     */
    protected $user_name;

    /**
     * @var
     */
    protected $transfer_details;

    /**
     * @var
     */
    protected $account_number;

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }


    /**
     * @return bool|mixed
     */
    public function transfer() // checkAgencyTarget
    {
        try {
            if (!isset($this->transfer_data)) {
                return json_encode(array("message" => $this->getMessages('ErrorActionsEmptyTransferData'), "status" => false));

            } elseif (!$this->checkAgencyTarget($this->transfer_data["agency_target"])) {
                return json_encode(array("teste" => $this->checkAgencyTarget($this->transfer_data["agency_target"]), "message" => $this->getMessages('ErrorActionsEmptyTransferAgencyTargetNotFound'), "status" => false));

            } elseif (empty($this->transfer_data["value_target"])) {
                return json_encode(array("message" => $this->getMessages('ErrorActionsEmptyTransferDataAccountTarget'), "status" => false));

            } elseif (empty($this->transfer_data["account_target"])) {
                return json_encode(array("message" => $this->getMessages('ErrorActionsEmptyTransferDataAccountTarget'), "status" => false));

            } elseif (empty($this->transfer_data["agency_target"])) {
                return json_encode(array("message" => $this->getMessages('ErrorActionsEmptyTransferDataAgencyTarget'), "status" => false));

            } elseif ($this->transfer_data["account_number"] == $this->transfer_data["account_target"]) {
                return json_encode(array("message" => $this->getMessages('ErrorActionsTransferAccountEqual'), "status" => false));

            }  else
            {
                $data_actual = $this->getAccount();
                if (floatval($data_actual["ac_balance"]) < floatval($this->transfer_data["value_target"]))
                {
                    return json_encode(array("message" => $this->getMessages('ErrorActionsTransferBigger'), "status" => false));

                } elseif (floatval($this->transfer_data["value_target"]) < 1)
                {
                    return json_encode(array("message" => $this->getMessages('ErrorActionsTransferSmaller'), "status" => false));
                }

                $upd_balance = (floatval ($data_actual["ac_balance"]) - floatval($this->transfer_data["value_target"])); // Value origin
                //
                $data_actual_target = $this->getAccountTarget($this->transfer_data["account_target"]);
                if (empty($data_actual_target) || !$data_actual_target)
                {
                    return json_encode(array("message" => $this->getMessages('ErrorActionsTransferAccountTargetNotFound'), "status" => false));
                }
                $upd_balance_target = floatval($this->transfer_data["value_target"]) + floatval ($data_actual_target["ac_blocked_balance"]); // Value origin
                //
                $this->updateAccountValue($upd_balance_target, $this->transfer_data["account_target"], $this->transfer_data["agency_target"]);
                $this->updateAccount($upd_balance, $this->id_user);
                //
                $this->saveTransactions(Array(
                    "fk_id_user_tr" => $this->id_user, "ac_account_origin" => $this->transfer_data['account_number'], "ac_agency_origin" => $this->transfer_data['agency_account'], "ac_name" => $this->user_name,
                    "ac_account_destiny" => $this->transfer_data['account_target'], "ac_agency_destiny" => $this->transfer_data['agency_target'], "ac_value_transaction" => floatval($this->transfer_data["value_target"])
                ));
                return json_encode(array("message" => $this->getMessages('SuccessActionTransfer'), "status" => true));
            }
        } catch (\Exception $err) {
            return $this->getMessages('ErrorActionsTransferException');
        }
    }

    /**
     * @return mixed|string
     */
    private function getAccount()
    {
        try {
            $connection = new DBConnection();
            $stmt = $connection->prepare("SELECT format(ac_balance,2,'pt_BR') as ac_balance FROM dns_account WHERE fk_id_user = :id_user");
            $stmt->bindValue(":id_user", $this->id_user);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $err) {
            return $err->getMessage();
        }
    }

    /**
     * @param $account_target
     * @return mixed|string
     */
    private function getAccountTarget($account_target)
    {
        try {
            $connection = new DBConnection();
            $stmt = $connection->prepare("SELECT format(ac_blocked_balance,2,'pt_BR') as ac_blocked_balance FROM dns_account WHERE ac_account = :account_target");
            $stmt->bindValue(":account_target", $account_target);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $err) {
            return false;
        }
    }

    /**
     * @param $ac_agency
     * @return bool
     */
    private function checkAgencyTarget($ac_agency)
    {
        try {
            $connection = new DBConnection();
            $stmt = $connection->prepare("SELECT * FROM dns_account WHERE ac_agency = :ac_agency");
            $stmt->bindValue(":ac_agency", $ac_agency);
            $stmt->execute();
            if ($stmt->fetch(\PDO::FETCH_ASSOC))
            {
                return true;
            }
            return false;
        } catch (\PDOException $err) {
            return false;
        }
    }


    /**
     * @param $upd_balance
     * @param $fk_id_user
     * @return bool
     */
    private function updateAccount($upd_balance, $fk_id_user)
    {
        try {
            $connection = new DBConnection();
            $stmt = $connection->prepare("UPDATE dns_account SET ac_balance = :upd_balance WHERE fk_id_user = :fk_id_user");
            $stmt->bindValue(":fk_id_user", $fk_id_user);
            $stmt->bindValue(":upd_balance", $upd_balance);
            $stmt->execute();
            return true;
        } catch (\PDOException $err) {
            return false;
        }
    }

    /**
     * @param $ac_blocked_balance
     * @param $ac_account
     * @return bool
     */
    private function updateAccountValue($ac_blocked_balance, $ac_account, $agency_target)
    {
       try {
           $connection = new DBConnection();
           $stmt = $connection->prepare("UPDATE dns_account SET ac_blocked_balance = :ac_blocked_balance WHERE ac_account = :ac_account and ac_agency = :agency_target;");
           $stmt->bindValue(":ac_blocked_balance", $ac_blocked_balance);
           $stmt->bindValue(":ac_account", $ac_account);
           $stmt->bindValue(":agency_target", $agency_target);
           $stmt->execute();
           return true;
       } catch (\PDOException $err) {
           return false;
       }
    }

    /**
     * @param $data
     * @return bool
     */
    private function saveTransactions($data)
    {
        try {
            $connection = new DBConnection();
            $stmt = $connection->prepare("insert into dns_transactions (fk_id_user_tr, ac_account_origin, ac_agency_origin, ac_account_destiny, ac_agency_destiny, ac_name, ac_value_transaction) values (:fk_id_user_tr, :ac_account_origin, :ac_agency_origin, :ac_account_destiny, :ac_agency_destiny, :ac_name, :ac_value_transaction)");
            $stmt->bindValue(":fk_id_user_tr", $data['fk_id_user_tr']);
            $stmt->bindValue(":ac_account_origin", $data['ac_account_origin']);
            $stmt->bindValue(":ac_agency_origin", $data['ac_agency_origin']);
            $stmt->bindValue(":ac_account_destiny", $data['ac_account_destiny']);
            $stmt->bindValue(":ac_agency_destiny", $data['ac_agency_destiny']);
            $stmt->bindValue(":ac_name", $data['ac_name']);
            $stmt->bindValue(":ac_value_transaction", $data['ac_value_transaction']);

            $stmt->execute();
            return true;
        } catch (\PDOException $err) {
            return false;
        }
    }

    /**
     * @return array|false
     */
    public function getTransactions()
    {
        try {
            $connection = new DBConnection();
            // $stmt = $connection->prepare("SELECT *, format(ac_value_transaction,2,'pt_BR') as ac_value_trans FROM dns_transactions WHERE fk_id_user_tr = :fk_id_user_tr ORDER BY created_at DESC");
            $stmt = $connection->prepare("SELECT *, format(ac_value_transaction,2,'pt_BR') as ac_value_trans FROM dns_transactions WHERE fk_id_user_tr = :fk_id_user_tr OR ac_account_destiny = :account_number ORDER BY created_at DESC");
            $stmt->bindValue(':fk_id_user_tr', $this->id_user);
            $stmt->bindValue(':account_number', $this->account_number);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $err) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function updatedBalanceBlocked()
    {
        try {
            $connection = new DBConnection();
            $stmt = $connection->prepare("UPDATE dns_account SET ac_balance = format((ac_blocked_balance + ac_balance),2,'pt-BR'), ac_blocked_balance = 0.0 WHERE fk_id_user = :fk_id_user");
            $stmt->bindValue(':fk_id_user', $this->id_user);
            if ($stmt->execute())
            {
                return true;
            }
            return false;
        } catch (\PDOException $err)
        {
            return false;
        }
    }

    /**
     * @return false|string
     */
    public function unblockedBalance()
    {
        if ($this->updatedBalanceBlocked()) {
            return json_encode(array("message" => $this->getMessages('SuccessActionUblockedBalance'), "status" => true));
        } else
        {
            return json_encode(array("message" => $this->getMessages('ErrorActionUnblockedBalanceNotFound'), "status" => false));
        }
    }

    /**
     * @return array|mixed
     */
    public function return_transaction_details()
    {
        try {
            $connection = new DBConnection();
            $stmt = $connection->prepare("SELECT 
                dns_transactions.ac_account_destiny, dns_transactions.ac_agency_destiny, dns_transactions.ac_account_origin, dns_transactions.ac_agency_origin, format(dns_transactions.ac_value_transaction,2,'pt_BR') as value_transaction, dns_transactions.created_at,
                dns_users.name, dns_users.email
                FROM dns_transactions INNER JOIN dns_users ON dns_users.id = dns_transactions.fk_id_user_tr WHERE id_tr = :transfer_id");
            $stmt->bindValue(":transfer_id", $this->transfer_details["transfer_id"]);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $err)
        {
            return array("status" => false);
        }
    }

    public function deposit($value_for_deposit)
    {
        return $this->getAccount();

//        try {
//            $connection = new DBConnection();
//            $stmt = $connection->prepare("UPDATE dns_account SET ac_balance=:ac_balance WHERE fk_id_user=:fk_id_user;");
//            $stmt->bindValue(":ac_balance", $value_for_deposit);
//            $stmt->bindValue(":fk_id_user", $this->id_user);
//            $stmt->execute();
//            if ($stmt->rowCount())
//            {
//                return true;
//            }
//            else
//            {
//                return false;
//            }
//        } catch (\PDOException $err)
//        {
//            return array("status" => false);
//        }
    }

}