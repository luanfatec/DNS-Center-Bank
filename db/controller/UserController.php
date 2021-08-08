<?php

namespace DB\Controller;

require_once(dirname(__FILE__) . "/../load.php");

use db\Messages\Messages;
use DB\Model\Users;
use db\Support\Support;

/**
 *
 */
class UserController extends Messages {

    /**
     * @var
     */
    protected $_files;
    /**
     * @var
     */
    protected $_post;

    /**
     * @var
     */
    protected $id_user;

    /**
     * @var
     */
    protected $email;

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
     * @return array|bool|string
     */
    public function logindo($email, $password)
    {
        $users = new Users();
        $support = new Support();

        $data = $users->sigin($email);

        if (empty($data) or !$data) {
            return false;
        } else {
            session_start();
            if (isset($_SESSION["PHPSESSID"]))
            {
                session_destroy();
            }

            if (!$support->bcrypt_hash_verify($password, $data["password"])) {
                return false;
            }

            $_SESSION["hash_pay"] = session_id($data["email"]);
            $_SESSION["email"] = $data["email"];
            $_SESSION["name"] = $data["name"];
            $_SESSION["created_at"] = $data["created_at"];
            $_SESSION["id_user"] = $data["id"];
            $_SESSION["url_profile"] = $data["url_profile"];
            return true;
        }
    }

    /**
     *
     */
    public function logout()
    {
        session_start();
        if (isset($_SESSION) or isset($_SESSION["PHPSESSID"]))
        {
            session_destroy();
        }
    }

    /**
     * @param $user
     * @return mixed
     */
    public function return_account($user)
    {
        $account = new Users();
        return json_encode($account->return_account($user));
    }

    /**
     * @param $nascimento
     * @return false|float
     */
    protected function calc_idade($nascimento)
    {
        list($y, $m, $d) = explode('-', $nascimento);
        $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $birth = mktime( 0, 0, 0, $d, $m, $y);

        return floor((((($today - $birth) / 60) / 60) / 24) / 365.25);
    }

    /**
     * @param $dataNascimento
     * @return false|int|mixed|string
     */
    public function checkAge($dataNascimento){
        // formato da data de nascimento
        // yyyy-mm-dd
        $data       = explode("-",$dataNascimento);
        $anoNasc    = $data[0];
        $mesNasc    = $data[1];
        $diaNasc    = $data[2];

        $anoAtual   = date("Y");
        $mesAtual   = date("m");
        $diaAtual   = date("d");

        $idade      = $anoAtual - $anoNasc;

        if ($mesAtual < $mesNasc){
            $idade -= 1;
            return $idade;
        } elseif ( ($mesAtual == $mesNasc) && ($diaAtual <= $diaNasc) ){
            $idade -= 1;
            return $idade;
        }else
            return $idade;
    }

    /**
     * Credit Code = https://gist.github.com/rafael-neri/ab3e58803a08cb4def059fce4e3c0e40
     * @param $cpf
     * @return bool
     */
    protected function validate_cpf($cpf) {

        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function upload_img() {
        $ext = strtolower(substr($this->_files['img_profile']['name'],-4));
        $new_name = strtolower(str_replace(' ', '', $this->_post['name'])) . $ext;
        $dir = './inc/profile/';
        if (file_exists($dir.$new_name.$ext)) {
            unlink($dir.$new_name.$ext);
        }
        if (move_uploaded_file($this->_files['img_profile']['tmp_name'], $dir.$new_name)) {
            return true;
        }
        return false;
    }

    /**
     * @return false|int|mixed|string|void
     */
    public function update_personal()
    {
        $users = new Users();

        /// Checa se há um valor em age ou se ele foi definido corretamente.
        if (!isset($this->_post['age']) || empty($this->_post['age'])) {
            header("location:edit-profile.php?status=false&message=".$this->getMessages('ErrorUpdatePersonalNotInformateAge'));
        }

        /// Checa de há um valor em name ou se foi declarada em post corretamente..
        if (!isset($this->_post['name']) || empty($this->_post['name'])) {
            header("location:edit-profile.php?status=false&message=".$this->getMessages('ErrorUpdatePersonalNotInformateName'));
        }

        /// Checa de há um valor em sex ou se foi declarada em post corretamente..
        if (!isset($this->_post['sex-select']) || empty($this->_post['sex-select'])) {
            header("location:edit-profile.php?status=false&message=".$this->getMessages('ErrorUpdatePersonalNotInformateSex'));
        }

        /// Checa se o valor da data de nascimento foi informada corretamente ou se está definida...
        if (!isset($this->_post['data_birth']) || empty($this->_post['data_birth'])) {
            header("location:edit-profile.php?status=false&message=".$this->getMessages('ErrorUpdatePersonalNotInformateDataBirth'));
        }

        /// Checa se o valor da data de ident foi informada corretamente ou se está definida...
        if (!isset($this->_post['ident']) || empty($this->_post['ident'])) {
            header("location:edit-profile.php?status=false&message=".$this->getMessages('ErrorUpdatePersonalNotInformateIdent'));
        }

        /// Checa se o valor da data de document foi informada corretamente ou se está definida...
        if (!isset($this->_post['document']) || empty($this->_post['document'])) {
            header("location:edit-profile.php?status=false&message=".$this->getMessages('ErrorUpdatePersonalNotInformateDocument'));
        }

        /// Checa se o valor da data de number foi informada corretamente ou se está definida...
        if (!isset($this->_post['number']) || empty($this->_post['number'])) {
            header("location:edit-profile.php?status=false&message=".$this->getMessages('ErrorUpdatePersonalNotInformateNumber'));
        }

        /// Checa se o valor da data de zip code foi informada corretamente ou se está definida...
        if (!isset($this->_post['zip_code']) || empty($this->_post['zip_code'])) {
            header("location:edit-profile.php?status=false&message=".$this->getMessages('ErrorUpdatePersonalNotInformateZipCode'));
        }

        /// Checa se a data de aniversário bate com a idade informada...
        if (intval($this->checkAge($this->_post['data_birth'])) != intval($this->_post['age'])) {
            return $this->checkAge($this->_post['data_birth']);
//            header("location:edit-profile.php?status=false&message=".$this->getMessages('ErrorUpdatePersonalAgeDoesNotMatchDateOfBirth'));
        }

        /// Valida se o CPF informado pelo usuário é válido...
        if (!$this->validate_cpf($this->_post["document"])) {
            header("location:edit-profile.php?status=false&message=".$this->getMessages('ErrorUpdatePersonalDocumentInvalid'));
            return $this->_post['document'];
        }


        /// Valida se uma imagem foi selecionada.
        if (isset($this->_files[''])) {
            header("location:edit-profile.php?status=false&message=".$this->getMessages('ErrorUpdatePersonalSelectImage'));
        }

        /// Verifica se o usuário quer alterar os dados de acesso...
        if (isset($this->_post['update_data_access'])) {

            /// Verifica se o email já existe na base de dados...
            if ($users->checkuserexists($this->_post['email']) and $users->checkuserexists($this->_post['email'])["email"] != $this->email) {
                header("location:edit-profile.php?status=false&message=".$this->getMessages('ErrorUpdatePersonalUsernameExisis'));
            }

            if ($this->upload_img()) {

                /// Realiza a alteração dos dados de acesso...
                $support = new Support();
                $ext = strtolower(substr($this->_files['img_profile']['name'],-3));
                $new_name = strtolower(str_replace(' ', '', $this->_post['name']));

                /// Verifica se o usuário já possui endereço cadastrado...
                if (!$users->checkExistsUserPersonal($this->id_user)) {

                    /// Atualizando tabela de usuários...
                    $users->update_dns_users(array(
                        'email' => $this->email, 'password' => $support->bcrypt_hash_encode($this->_post["password"]), 'id' => $this->id_user,
                        'url_path' => $new_name."-".$ext, 'name' => $this->_post['name'], 'update_data_access' => true
                    ));

                    // Cria um novo endereço associado ao usuário que está atualizando os dados ou inserindo o endereço pela primeira vez...
                    $users->personal_insert(array(
                        "pd_id_user_fk" => $this->id_user, "pd_age" => $this->_post["age"],"pd_data_birth" => $this->_post["data_birth"],
                        "pd_document" => $this->_post["document"],"pd_ident" => $this->_post["ident"], "pd_sex" => $this->_post["sex-select"],
                        "pd_zip_code" => $this->_post["zip_code"], "pd_public_place" => $this->_post["public_place"], "pd_number" => $this->_post["number"],
                        "pd_district" => $this->_post["district"], "pd_city" => $this->_post["city"], "pd_uf" => $this->_post["state-select"]
                    ));

                    header("location:edit-profile.php?status=false&message=".$this->getMessages('UpdatePersonalSuccess'));

                } elseif ($users->checkExistsUserPersonal($this->id_user)) {

                    /// Atualizando tabela de usuários...
                    $users->update_dns_users(array(
                        'email' => $this->email, 'password' => $support->bcrypt_hash_encode($this->_post["password"]), 'id' => $this->id_user,
                        'url_path' => $new_name."-".$ext, 'name' => $this->_post['name'], 'update_data_access' => true
                    ));

                    // Cria um novo endereço associado ao usuário que está atualizando os dados ou inserindo o endereço pela primeira vez...
                    $users->personal_update(array(
                        "pd_id_user_fk" => $this->id_user, "pd_age" => $this->_post["age"],"pd_data_birth" => $this->_post["data_birth"],
                        "pd_document" => $this->_post["document"],"pd_ident" => $this->_post["ident"], "pd_sex" => $this->_post["sex-select"],
                        "pd_zip_code" => $this->_post["zip_code"], "pd_public_place" => $this->_post["public_place"], "pd_number" => $this->_post["number"],
                        "pd_district" => $this->_post["district"], "pd_city" => $this->_post["city"], "pd_uf" => $this->_post["state-select"]
                    ));

                    header("location:edit-profile.php?status=false&message=".$this->getMessages('UpdatePersonalSuccess'));
                }
            }

        } elseif (!isset($this->_post['update_data_access'])) {

            if ($this->upload_img()) {

                /// Realiza a alteração dos dados de acesso...
                $support = new Support();
                $ext = strtolower(substr($this->_files['img_profile']['name'],-3));
                $new_name = strtolower(str_replace(' ', '', $this->_post['name']));

                /// Verifica se o usuário já possui endereço cadastrado...
                if (!$users->checkExistsUserPersonal($this->id_user)) {

                    /// Atualizando tabela de usuários...
                    $users->update_dns_users(array(
                        'id' => $this->id_user, 'url_path' => $new_name."-".$ext, 'name' => $this->_post['name'], 'update_data_access' => false
                    ));

                    // Cria um novo endereço associado ao usuário que está atualizando os dados ou inserindo o endereço pela primeira vez...
                    $users->personal_insert(array(
                        "pd_id_user_fk" => $this->id_user, "pd_age" => $this->_post["age"],"pd_data_birth" => $this->_post["data_birth"],
                        "pd_document" => $this->_post["document"],"pd_ident" => $this->_post["ident"], "pd_sex" => $this->_post["sex-select"],
                        "pd_zip_code" => $this->_post["zip_code"], "pd_public_place" => $this->_post["public_place"], "pd_number" => $this->_post["number"],
                        "pd_district" => $this->_post["district"], "pd_city" => $this->_post["city"], "pd_uf" => $this->_post["state-select"]
                    ));

                    header("location:edit-profile.php?status=false&message=".$this->getMessages('UpdatePersonalSuccess'));

                } elseif ($users->checkExistsUserPersonal($this->id_user)) {

                    /// Atualizando tabela de usuários...
                    $users->update_dns_users(array(
                        'id' => $this->id_user, 'url_path' => $new_name."-".$ext, 'name' => $this->_post['name'], 'update_data_access' => false
                    ));

                    // Cria um novo endereço associado ao usuário que está atualizando os dados ou inserindo o endereço pela primeira vez...
                    $users->personal_update(array(
                        "pd_id_user_fk" => $this->id_user, "pd_age" => $this->_post["age"],"pd_data_birth" => $this->_post["data_birth"],
                        "pd_document" => $this->_post["document"],"pd_ident" => $this->_post["ident"], "pd_sex" => $this->_post["sex-select"],
                        "pd_zip_code" => $this->_post["zip_code"], "pd_public_place" => $this->_post["public_place"], "pd_number" => $this->_post["number"],
                        "pd_district" => $this->_post["district"], "pd_city" => $this->_post["city"], "pd_uf" => $this->_post["state-select"]
                    ));

                    header("location:edit-profile.php?status=false&message=".$this->getMessages('UpdatePersonalSuccess'));
                }
            }

        }

    }
}