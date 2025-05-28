<?php
require_once 'models/Grid.php';

class GridController
{
    private $model;

    public function __construct()
    {
        session_start();
        $this->model = new Grid();
    }

    // création d'une grille avec les infos générales
    public function createGrid()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $name = htmlspecialchars(trim($_POST['name']));
            $nb_row = htmlspecialchars(intval(trim($_POST['nb_row'])));
            $nb_col = htmlspecialchars(intval(trim($_POST['nb_col'])));
            $level = htmlspecialchars($_POST['level']);
            $user_id = $_SESSION['user']['id'];

            if (empty($name)) {
                $errors[] = "Un nom pour la grille est requis";
            }
            if (empty($nb_row)) {
                $errors[] = "Vous devez renseigner le nombre de ligne de la grille";
            } elseif ($nb_row <= 0) {
                $errors[] = "La grille doit avoir au moins 1 case";
            }
            if (empty($nb_col)) {
                $errors[] = "Vous devez renseigner le nombre de colonne de la grille";
            } elseif ($nb_col <= 0) {
                $errors[] = "La grille doit avoir au moins 1 case";
            }
            if (empty($level) || !in_array($level, ['easy', 'medium', 'hard'])) {
                $errors[] = "Un niveau de difficulté est nécessaire";
            }
            if (empty($user_id)) {
                $errors[] = "La grille doit avoir un auteur";
            }

            // si tout est OK on essaye d'insérer
            if (count($errors) == 0) {
                try {
                    $grid_id = $this->model->addInfoGrid($name, $nb_row, $nb_col, $level, $user_id);
                    
                    // envoie du numéro de la grille dans les paramètres de l'url
                    header("Location: index.php?q=grid-create-cell&grid_id=$grid_id");
                    exit;
                } catch (Exception $e) {
                    $errors[] = "createInfoGeneralGrid KO: " . $e->getMessage();
                }
            } else {
                $errors[] = "Un problème lors de l'ajout est apparu";
            }
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
            }
        }
        include 'views/grid/grid_create.php';
    }

    // ajout des cases noires dans une grille spécifique
    public function createCellGrid()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //var_dump($_POST);
            //var_dump($_GET);
            $grid_id = isset($_POST['grid_id']) ? intval($_POST['grid_id']) : null;
            $blackcells = htmlspecialchars(trim($_POST['blackcells']));
            $blackcell = explode(';', $blackcells);

            if (empty($blackcell)) {
                $errors[] = "La grille doit avoir des cases noires";
            }
            if (empty($grid_id)) {
                $errors[] = "Il manque un identifiant de grille";
            }

            if (count($errors) == 0) {
                try {
                    foreach ($blackcell as $cell) {
                        if (preg_match('/^([0-9]+)([A-Z])/', $cell, $matches)) {
                            $num_row = intval($matches[1]);
                            $num_col = strtoupper($matches[2]);
                            $this->model->addBlackcell($num_row, $num_col, $grid_id);
                        } else {
                            $errors[] = "Coordonnées invalides";
                        }
                    }
                    header("Location: index.php?q=grid-create-def&grid_id=$grid_id");
                    exit;
                } catch (Exception $e) {
                    $errors[] = "createCellGrid KO: " . $e->getMessage();
                }
            } else {
                $errors[] = "Un problème lors de la création des cases noires";
            }
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
            }
        }
        include 'views/grid/grid_create_cell.php';
    }

    // ajout des définitions pour une grille donnée
    public function createDefGrid()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //var_dump($_POST);
            //var_dump($_GET);
            /*echo '<pre>';
            var_dump($_POST['definitions']);
            echo '</pre>';*/
            $grid_id = isset($_POST['grid_id']) ? intval($_POST['grid_id']) : null;
            $definitions = isset($_POST['definitions']) ? $_POST['definitions'] : [];

            if (empty($grid_id)) {
                $errors[] = "Il manque un identifiant de grille";
            }

            foreach ($definitions as $definition) {
                $definitions_errors = [];
                $start_num_row = intval(trim($definition['start_num_row']));
                $start_num_col = intval(trim($definition['start_num_col']));
                $end_num_row = intval(trim($definition['end_num_row']));
                $end_num_col = intval(trim($definition['end_num_col']));
                $direction = htmlspecialchars($definition['direction']);
                $content = htmlspecialchars(trim($definition['content']));

                try {
                    $start_col = $this->convertNumToLetter($start_num_col);
                    $end_col = $this->convertNumToLetter($end_num_col);
                    if (empty($start_col)) {
                        throw new InvalidArgumentException("Colonne de départ invalide");
                    }
                    if (empty($end_col)) {
                        throw new InvalidArgumentException("Colonne de terminaison invalide");
                    }
                } catch (InvalidArgumentException $e) {
                    $definitions_errors[] = "createDefGrid -> Colonnes invalide KO: " . $e->getMessage();
                }

                if (empty($direction) || !in_array($direction, ['horizontal', 'vertical'])) {
                    $definitions_errors[] = "Il faut une direction";
                }

                if ($start_num_row < 1 || $start_num_row > $this->model->getNbRow($grid_id)) {
                    $errors[] = "Ligne de départ invalide";
                }
                if ($start_num_col < 1 || $start_num_col > $this->model->getNbCol($grid_id)) {
                    $errors[] = "La colonne de départ invalide";
                }
                if ($end_num_row < 1 || $end_num_row > $this->model->getNbRow($grid_id)) {
                    $errors[] = "La ligne dépasse";
                }
                if ($end_num_col < 1 || $end_num_col > $this->model->getNbCol($grid_id)) {
                    $errors[] = "La colonne dépasse";
                }
                if (empty($content)) {
                    $definitions_errors[] = "La définition ne peut pas être vide";
                }
                if (empty($direction)) {
                    $definitions_errors[] = "Il faut faire un choix entre horizontal e vertical";
                }

                if (count($definitions_errors) == 0) {
                    try {
                        $this->model->addDefinition(
                            $start_num_row, $start_col, $end_col,
                            $end_num_row, $direction, $content, $grid_id);
                    } catch (Exception $e) {
                        $definitions_errors[] = "createDefGrid KO: " . $e->getMessage();
                    }
                }
                $errors = array_merge($errors, $definitions_errors);
            }

            if (count($errors) == 0) {
                header("Location: index.php?q=grids");
                exit;
            } else {
                $_SESSION['errors'] = $errors;
            }
        }
        include 'views/grid/grid_create_def.php';
    }

    // affichage de la liste des grilles
    public function getListGrid()
    {
        $grids = $this->model->getAllGrids();
        include 'views/grid/grids.php';
    }

    // affiche le détail d'une grille
    public function showGrid()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['grid_id'])) {
                $grid_id = intval($_GET['grid_id']);
                try {
                    $grid = $this->model->getOneGrid($grid_id);
                    if (!$grid) {
                        $errors[] = "Aucune ne correspond";
                    } else {
                        include 'views/grid/grid_show.php';
                        return;
                    }
                } catch (Exception $e) {
                    $errors[] = "showGrid KO: " . $e->getMessage();
                }
            } else {
                $errors[] = "Votre requête n'a pas pu aboutir";
            }
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
            }
        }
        include 'views/grid/grids.php';
    }

    // effectue la sauvegarde d'une grille et de son contenu
    public function saveGrid()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $grid_id = isset($_POST['grid_id']) ? intval($_POST['grid_id']) : null;
            $user_id = $_SESSION['user']['id'];
            $inputCell = isset($_POST['cells']) ? $_POST['cells'] : null;

            //var_dump($inputCell);

            if (empty($grid_id)) {
                $errors[] = "Il manque un identifiant de grille";
            }
            if (is_null($inputCell)) {
                $errors[] = "La sauvegarde d'une grille vide est impossible";
            }
            if (count($errors) == 0) {
                try {
                    foreach ($inputCell as $row => $cols) {
                        foreach ($cols as $col => $letter) {
                            $row = intval($row);
                            $col = strtoupper($col);
                            $char = htmlspecialchars($letter);
     
                            if (!empty($char)) {
                                $this->model->addSave($row, $col, $char, $user_id, $grid_id);
                                header("Location: index.php?q=grid-msg");
                                exit;
                            }
                        }
                    }
                } catch (Exception $e) {
                    $errors[] = "saveGrid KO: " . $e->getMessage();
                }
            }
        }
        header("Location: index.php?q=grid-msg");
        exit;
    }

    // affiche un message de confirmation
    public function saveMessageGrid()
    {
        include 'views/grid/grid_create_save_msg.php';
    }

    // affiche la liste des grilles sauvegradéés par l'utilisateur actuelement connecté
    public function getListSaveGrid()
    {
        $user_id = $_SESSION['user']['id'];
        $saves = $this->model->getSaveUserGrid($user_id);
        include 'views/grid/grid_saves.php';
    }

    // suppression d'une grille
    public function deleteGrid()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['grid_id'])) {
                $grid_id = intval($_GET['grid_id']);

                try {
                    $this->model->deleteGrid($grid_id);
                    header("Location: index.php?q=grids");
                    exit;
                } catch (Exception $e) {
                    $errors[] = "deleteGrid KO: " . $e->getMessage();
                }
            } else {
                $errors[] = "Grille introuvable";
            }
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
            }
        }
        include 'views/grid/grids.php';
    }

    // suppression d'une grille sauvegardée
    public function deleteSaveGrid()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['grid_id'])) {
                $grid_id = intval($_GET['grid_id']);

                try {
                    $this->model->deleteSaveGrid($grid_id);
                    header("Location: index.php?q=grid-save-user");
                    exit;
                } catch (Exception $e) {
                    $errors[] = "deleteSaveGrid KO: " . $e->getMessage();
                }
            } else {
                $errors[] = "Grille introuvable";
            }
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
            }
        }
        include 'views/grid/grid_saves.php';
    }

    // trie par niveau
    public function sortByLevelGrid()
    {
        $grids = $this->model->sortByLevelGrid();
        include 'views/grid/grids.php';
    }

    // trie par date
    public function sortByDateGrid()
    {
        $grids = $this->model->sortByDateGrid();
        include 'views/grid/grids.php';
    }

    // transforme un chiffre en lettre
    private function convertNumToLetter($num)
    {
        if ($num <= 0) {
            throw new InvalidArgumentException("num doit être > 0");
        }
        $letter = '';
        while ($num > 0) {
            $reste = ($num - 1) % 26;

            // le code ascii
            $letter = chr($reste + ord('A')) . $letter;
            $num = intval(($num - 1) / 26);
        }
        return $letter;
    }
}