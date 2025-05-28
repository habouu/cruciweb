<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    // insertion dans la base de données d'un utilisateur de type 'registered'
    public function addNewUser($username, $email, $password)
    {
        if ($this->isFieldUnique('username', $username)) {
            throw new Exception("Identifiant déjà utilisé");
        }
        if ($this->isFieldUnique('email', $email)) {
            throw new Exception("Adresse e-mail déjà utilisée");
        }
        $hashPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = $this->db->prepare(
            "insert into user (username, email, password)
            values (?, ?, ?)"
        );
        return $sql->execute([$username, $email, $hashPassword]);
    }

    // vérification si l'identifiant ou l'adresse e-mail + password match
    public function getCredentialsUser($identifiant, $password)
    {
        $sql = $this->db->prepare(
            "select *
            from user
            where username = ? or email = ?"
        );
        $sql->execute([$identifiant, $identifiant]);
        $user = $sql->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    // récupération de tous les utilisateurs sauf l'admin
    public function getAllUser()
    {
        $sql = $this->db->query(
            "select *
            from user
            where role != 'admin'"
        );
        $sql->execute();
        $users = $sql->fetchAll(PDO::FETCH_ASSOC);
        if ($users) {
            return $users;
        }
        return null;
    }

    // suppression d'un utilisateur particulier
    public function deleteUser($user_id)
    {
        $sql = $this->db->prepare(
            "delete from user
            where id = ?"
        );
        return $sql->execute([$user_id]);
    }

    // vérifie l'unicité d'un champ dans une table
    private function isFieldUnique($field, $value)
    {
        $sql = $this->db->prepare(
            "select count(*)
            from user
            where $field = ?"
        );
        $sql->execute([$value]);
        return $sql->fetchColumn() != 0;
    }
}