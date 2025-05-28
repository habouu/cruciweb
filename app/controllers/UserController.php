<?php
require_once 'models/User.php';

class UserController
{
    private $model;

    public function __construct()
    {
        session_start();
        $this->model = new User();
    }

    // création d'un nouvel utilisateur
    public function createNewUser()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars(trim($_POST['username']));
            $email = htmlspecialchars(trim($_POST['email']));
            $password = htmlspecialchars(trim($_POST['password']));

            if (empty($username)) {
                $errors[] = "Un nom d'utilisateur est requis";
            }
            if (empty($email)) {
                $errors[] = "Vous devez donner une adresse e-mail";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Le format de l'adresse e-mail est invalide";
            }
            if (empty($password)) {
                $errors[] = "Un mot de passe est fortement requis";
            } elseif (mb_strlen($password) < 6) {
                $errors[] = "Le mot de passe fournit est trop court. Au moins 6 caractères svp";
            }

            if (count($errors) == 0) {
                try {
                    $this->model->addNewUser($username, $email, $password);
                    header("Location: index.php?q=login");
                    exit;
                } catch (Exception $e) {
                    $errors[] = "KO: " . $e->getMessage();
                }
            } else {
                $errors[] = "Un problème lors de l'inscription est apparue";
            }
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
            }
        }
        include 'views/user/register.php';
    }

    // récupération d'un utilisateur
    public function getCredentialsUser()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifiant = htmlspecialchars($_POST['identifiant']);
            $password = htmlspecialchars($_POST['password']);

            if (empty($identifiant)) {
                $errors[] = "Vous devez rentrer votre adresse e-mail ou username";
            }
            if (empty($password)) {
                $errors[] = "Vous devez renseigner votre mot de passe";
            }

            if (count($errors) == 0) {
                try {
                    $user = $this->model->getCredentialsUser($identifiant, $password);
                    if ($user) {
                        $_SESSION['user'] = $user;

                        header("Location: index.php?q=grids");
                        exit;
                    } else {
                        $errors[] = "Identifiant et/ou mot de passe incorrect";
                    }
                } catch (Exception $e) {
                    $errors[] = "KO: " . $e->getMessage();
                }
            } else {
                $errors[] = "Imossible de vous connecter";
            }
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
            }
        }
        include 'views/user/login.php';
    }

    // affichage de la liste des utilisateurs
    public function getListUser()
    {
        $users = $this->model->getAllUser();
        include 'views/user/admin/users.php';
    }

    // création d'un nouvel utilisateur par l'admin
    public function adminCreateNewUser()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);

            if (empty($username)) {
                $errors[] = "Un nom d'utilisateur est requis";
            }
            if (empty($email)) {
                $errors[] = "Vous devez donner une adresse e-mail";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Le format de l'adresse e-mail est invalide";
            }
            if (empty($password)) {
                $errors[] = "Un mot de passe est fortement requis";
            } elseif (mb_strlen($password) < 6) {
                $errors[] = "Le mot de passe doit faire au moins 6 caractères";
            }

            if (count($errors) == 0) {
                try {
                    $this->model->addNewUser($username, $email, $password);
                    
                    header("Location: index.php?q=users");
                    exit;
                } catch (Exception $e) {
                    $errors[] = "KO: " . $e->getMessage();
                }
            } else {
                $errors[] = "Impossible d'ajouter cet utilisateur";
            }
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
            }
        }
        include 'views/user/admin/user_create.php';
    }

    // suppression d'un utilisateur par son id
    public function deleteUser()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['user_id'])) {
                $user_id = intval($_GET['user_id']);

                try {
                    $this->model->deleteUser($user_id);
                    header("Location: index.php?q=users");
                    exit;
                } catch (Exception $e) {
                    $errors[] = "KO: " . $e->getMessage();
                }
            } else {
                $errors[] = "Utilisateur inconnu";
            }
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
            }
        }
        include 'views/user/admin/users.php';
    }

    // déconnexion
    public function logoutUser()
    {
        session_destroy();
        session_unset();
        header("Location: index.php?q=login");
        exit;
    }
}