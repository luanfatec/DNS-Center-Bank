<?php

namespace DB\model;

require_once(dirname(__FILE__) . "/../load.php");

use DB\DBConnection;
use db\Messages\Messages;
use DB\Support\Support;

/**
 *
 */
class Users extends Messages {

    /**
     * @var
     */
    protected $_files;
    /**
     * @var
     */
    protected $_post;

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * @param $email
     * @param $password
     * @return array|string|bool
     */
    public function sigin($email)
    {
        try {
            $connection = new DBConnection();
            $connection->connect();
            $stmt = $connection->prepare("SELECT * FROM dns_users WHERE email = :email");
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $err) {
            return false;
        }
    }

    /**
     * @param $user
     * @return mixed
     *
     */
    public function return_account($user)
    {
        $connection = new DBConnection();
        $connection->connect();
        $stmt = $connection->prepare("SELECT format(ac_balance,2,'pt_BR') as ac_balance, format(ac_blocked_balance,2,'pt_BR') as ac_blocked_balance, ac_account, ac_agency FROM dns_account WHERE fk_id_user = :id");
        $stmt->bindValue(":id", $user);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $email
     * @return false|mixed
     */
    public function checkuserexists($email)
    {
        try {
            $connection = new DBConnection();
            $connection->connect();
            $stmt = $connection->prepare("SELECT * FROM dns_users WHERE email = :email");
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $err) {
            return false;
        }
    }

    /**
     * @param $data
     * @return bool
     */
    public function update_dns_users($data)
    {
        if ($data['update_data_access']) {
            try {
                $connection = new DBConnection();
                $connection->connect();
                $stmt = $connection->prepare("UPDATE dns_users SET email=:email, password=:password, name=:name, url_profile=:url_profile, updated_at=now() where id=:id");
                $stmt->bindValue(":id", $data['id']);
                $stmt->bindValue(":email", $data['email']);
                $stmt->bindValue(":password", $data['password']);
                $stmt->bindValue(":name", $data['name']);
                $stmt->bindValue(":url_profile", $data['url_path']);
                $stmt->execute();
                if ($stmt->rowCount()) {
                    return true;
                }
                return false;
            } catch (\Exception $err) {
                return false;
            }
        } else {
            try {
                $connection = new DBConnection();
                $connection->connect();
                $stmt = $connection->prepare("UPDATE dns_users SET name=:name, url_profile=:url_profile, updated_at=now() where id=:id");
                $stmt->bindValue(":id", $data['id']);
                $stmt->bindValue(":name", $data['name']);
                $stmt->bindValue(":url_profile", $data['url_path']);
                $stmt->execute();
                if ($stmt->rowCount()) {
                    return true;
                }
                return false;
            } catch (\Exception $err) {
                return false;
            }
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function checkExistsUserPersonal($id)
    {
        try {
            $connection = new DBConnection();
            $connection->connect();
            $stmt = $connection->prepare("SELECT * FROM dns_personal_data WHERE pd_id_user_fk = :pd_id_user_fk");
            $stmt->bindValue(":pd_id_user_fk", $id);
            $stmt->execute();
            if ($stmt->rowCount()) {
                return true;
            } else {
                return false;
            }

        } catch (\Exception $err) {
            return false;
        }
    }

    /**
     * @param $data
     * @return bool
     */
    public function personal_insert($data)
    {
        try {
            $connection = new DBConnection();
            $connection->connect();
            $stmt = $connection->prepare("
            INSERT INTO dns_personal_data (
                      pd_id_user_fk, pd_age, pd_data_birth, pd_document, pd_ident, pd_sex, pd_zip_code, pd_public_place, pd_number, pd_district, pd_city, pd_uf, created_at
                    ) VALUES (
                      :pd_id_user_fk, :pd_age, :pd_data_birth, :pd_document, :pd_ident, :pd_sex, :pd_zip_code, :pd_public_place, :pd_number, :pd_district, :pd_city, :pd_uf, now()
                );
            ");
            $stmt->bindValue(':pd_id_user_fk', $data['pd_id_user_fk']);
            $stmt->bindValue(':pd_age', $data["pd_age"]);
            $stmt->bindValue(':pd_data_birth', $data['pd_data_birth']);
            $stmt->bindValue(':pd_document', $data["pd_document"]);
            $stmt->bindValue(':pd_ident', $data["pd_ident"]);
            $stmt->bindValue(':pd_sex', $data["pd_sex"]);
            $stmt->bindValue(':pd_zip_code', $data["pd_zip_code"]);
            $stmt->bindValue(':pd_public_place', $data["pd_public_place"]);
            $stmt->bindValue(':pd_number', $data["pd_number"]);
            $stmt->bindValue(':pd_district', $data["pd_district"]);
            $stmt->bindValue(':pd_city', $data["pd_city"]);
            $stmt->bindValue(':pd_uf', $data["pd_uf"]);
            $stmt->execute();

            if ($stmt->rowCount()) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $err) {
            return false;
        }
    }

    /**
     * @param $data
     * @return bool
     */
    public function personal_update($data)
    {
        try {
            $connection = new DBConnection();
            $connection->connect();
            $stmt = $connection->prepare("
                UPDATE dns_personal_data SET 
                    pd_age=:pd_age, pd_data_birth=:pd_data_birth, pd_document=:pd_document, pd_ident=:pd_ident, 
                    pd_sex=:pd_sex, pd_zip_code=:pd_zip_code, pd_public_place=:pd_public_place, 
                    pd_number=:pd_number, pd_district=:pd_district, pd_city=:pd_city, pd_uf=:pd_uf, updated_at=now()
                WHERE pd_id_user_fk = :pd_id_user_fk
            ");
            $stmt->bindValue(':pd_age', $data['pd_age']);
            $stmt->bindValue(':pd_data_birth', $data['pd_data_birth']);
            $stmt->bindValue(':pd_document', $data['pd_document']);
            $stmt->bindValue(':pd_ident', $data['pd_ident']);
            $stmt->bindValue(':pd_sex', $data['pd_sex']);
            $stmt->bindValue(':pd_zip_code', $data['pd_zip_code']);
            $stmt->bindValue(':pd_public_place', $data['pd_public_place']);
            $stmt->bindValue(':pd_number', $data['pd_number']);
            $stmt->bindValue(':pd_district', $data['pd_district']);
            $stmt->bindValue(':pd_city', $data['pd_city']);
            $stmt->bindValue(':pd_uf', $data['pd_uf']);
            $stmt->bindValue(':pd_id_user_fk', $data['pd_id_user_fk']);
            $stmt->execute();
            if ($stmt->rowCount()) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $err) {
            return false;
        }
    }

    /**
     * @param $id_user
     * @return array
     */
    public function getUsersDataPersonal($id_user)
    {
        try {
            $connection = new DBConnection();
            $connection->connect();
            $stmt = $connection->prepare("
        SELECT 
            dns_users.name, dns_users.url_profile, dns_users.email, dns_personal_data.pd_age, dns_personal_data.pd_data_birth, dns_personal_data.pd_document, dns_personal_data.pd_ident, dns_personal_data.pd_sex,
            dns_personal_data.pd_zip_code, dns_personal_data.pd_public_place, dns_personal_data.pd_number, dns_personal_data.pd_district, dns_personal_data.pd_city, dns_personal_data.pd_uf
        FROM dns_users INNER JOIN dns_personal_data ON dns_personal_data.pd_id_user_fk = dns_users.id WHERE pd_id_user_fk=:id;
        ");
            $stmt->bindValue(":id", $id_user);
            $stmt->execute();
            if ($stmt->rowCount()) {
                return array("data" => $stmt->fetch(\PDO::FETCH_ASSOC));
            } else {
                return array("status" => true, "message" => $this->getMessages("UpdateErrorNotExistisDataPersonal"));
            }
        } catch (\PDOException $err) {
            return array("status" => false, "message" => $this->getMessages("UpdateErrorExceptionProgram"));
        }
    }

    protected function createNewAccount() {
        //
    }

    public function createNewUserAccess()
    {
        if (!$this->checkuserexists($this->_post["email"]))
        {
            $support = new Support();
            $password = $support->bcrypt_hash_encode($this->_post["password"]);
            try {
                $connection = new DBConnection();
                $connection->connect();
                $stmt = $connection->prepare("INSERT INTO dns_users (name, email, password) VALUES (:name, :email, :password)");
                $stmt->bindValue(":name", $this->_post["name"]);
                $stmt->bindValue(":email", $this->_post["email"]);
                $stmt->bindValue(":password", $password);
                $stmt->execute();
                if ($stmt->rowCount())
                {
                    return array("message" => $this->getMessages("CreateNewUserSuccess"), "status" => true);
                } else
                {
                    return array("message" => $this->getMessages("CreateNewUserError"), "status" => false);
                }
            } catch (\PDOException $err) {
                return array("message" => $this->getMessages("CreateNewUserError"), "status" => false);
            }
        }
        else {
            return array("message" => $this->getMessages("CreateNewUserErrorExists"), "status" => false);
        }

    }
}