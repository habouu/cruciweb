<?php

class Grid
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    // insert dans la base les infos générale d'une grille et retourne
    // son ID
    public function addInfoGrid($name, $nb_row, $nb_col, $level, $user_id)
    {
        if ($this->isFieldUnique('name', $name)) {
            throw new Exception("Il existe déjà une grille avec ce nom");
        }
        $sql = $this->db->prepare(
            "insert into grid (name, nb_row, nb_col, level, user_id)
            values (?, ?, ?, ?, ?)"
        );
        $sql->execute([$name, $nb_row, $nb_col, $level, $user_id]);
        return $this->db->lastInsertID();
    }

    // ajouter les cases noires pour une grille données
    public function addBlackcell($num_row, $num_col, $grid_id)
    {
        $sql = $this->db->prepare(
            "insert into cell (num_row, num_col, letter, type, grid_id)
            values (?, ?, NULL, 'black', ?)"
        );
        return $sql->execute([$num_row, $num_col, $grid_id]);
    }

    // ajoute les cases noires d'une grille particulière
    public function addDefinition(
        $start_num_row, $start_num_col, $end_num_col, $end_num_row,
        $direction, $content, $grid_id)
    {
        $sql = $this->db->prepare(
            "insert into definition (start_num_row, start_num_col, end_num_col,
            end_num_row, direction, content, grid_id)
            values (?, ?, ?, ?, ?, ?, ?)"
        );
        return $sql->execute([
            $start_num_row, $start_num_col, $end_num_col, $end_num_row,
            $direction, $content, $grid_id
        ]);
    }

    // ajoute les solution à une grille particulère
    public function addSolution($num_row, $num_col, $letter, $grid_id)
    {
        $sql = $this->db->prepare(
            "insert into solution (num_row, num_col, letter, grid_id)
            values (?, ?, ?, ?)"
        );
        return $sql->execute([$num_row, $num_col, $letter, $grid_id]);
    }

    // ajout les solutions d'un utilisateur en particulier pour une grille particulière
    public function addSolutionUser($num_row, $num_col, $letter, $user_id, $grid_id)
    {
        $sql = $this->db->prepare(
            "insert into solution_user (num_row, num_col, letter, user_id, grid_id)
            values (?, ?, ?, ?, ?)"
        );
        return $sql->execute([$num_row, $num_col, $letter, $user_id, $grid_id]);
    }

    // récupéèrer toutes les grilles
    public function getAllGrids()
    {
        $sql = $this->db->query(
            "select g.id, g.name, g.nb_row, g.nb_col, g.level, g.created_at, u.username
            from grid g
            join user u on u.id = g.user_id"
        );
        $sql->execute();
        $grids = $sql->fetchAll(PDO::FETCH_ASSOC);
        if ($grids) {
            return $grids;
        }
        return null;
    }

    // récupérer une grille en particulier
    public function getOneGrid($grid_id)
    {
        $sql = $this->db->prepare(
            "select *
            from grid
            where id = ?"
        );
        $sql->execute([$grid_id]);
        $grid = $sql->fetch(PDO::FETCH_ASSOC);
        if ($grid) {
            $sql = $this->db->prepare(
                "select concat(num_row, num_col) as cells
                from cell
                where grid_id = ?"
            );
            $sql->execute([$grid_id]);
            $cells = $sql->fetchAll(PDO::FETCH_ASSOC);

            $sql = $this->db->prepare(
                "select start_num_row, start_num_col, end_num_row,
                end_num_col, content, direction, grid_id
                from definition
                where grid_id = ?
                order by direction"
            );
            $sql->execute([$grid_id]);
            $definitions = $sql->fetchAll(PDO::FETCH_ASSOC);

            $grid['blackcells'] = $cells;
            $grid['definitions'] = $definitions;
            return $grid;
        }
        return null;
    }

    // récupérer le nombre de ligne d'une grille
    public function getNbRow($grid_id)
    {
        $sql = $this->db->prepare(
            "select nb_row
            from grid
            where id = ?"
        );
        $sql->execute([$grid_id]);
        $nb_row = $sql->fetch(PDO::FETCH_ASSOC);
        return $nb_row;
    }

    // récupérer le nombre de colonne d'une grille
    public function getNbCol($grid_id)
    {
        $sql = $this->db->prepare(
            "select nb_col
            from grid
            where id = ?"
        );
        $sql->execute([$grid_id]);
        $nb_col = $sql->fetch(PDO::FETCH_ASSOC);
        return $nb_col;
    }

    // ajoute une sauvegarde pour un utilisateur et une grille en particulier
    public function addSave($row, $col, $letter, $user_id, $grid_id)
    {
        $sql = $this->db->prepare(
            "insert into save (row, col, letter, user_id, grid_id)
            values (?, ?, ?, ?, ?)"
        );
        return $sql->execute([$row, $col, $letter, $user_id, $grid_id]);
    }

    // récupère les sauvegardes d'un utilisateur
    public function getSaveUserGrid($user_id) {
        $sql = $this->db->prepare(
            "select g.id as grid_id, g.name, g.level, s.created_at, s.id as id
            from save s
            join grid g on g.id = s.grid_id
            where s.user_id = ?"
        );
        $sql->execute([$user_id]);
        $saves = $sql->fetchAll(PDO::FETCH_ASSOC);
        if ($saves) {
            return $saves;
        }
        return null;
    }

    // suppression d'une grille
    public function deleteGrid($grid_id)
    {
        $sql = $this->db->prepare(
            "delete from grid
            where id = ?"
        );
        return $sql->execute([$grid_id]);
    }

    // suppression d'une sauvegarde
    public function deleteSaveGrid($grid_id)
    {
        $sql = $this->db->prepare(
            "delete from save
            where grid_id = ?"
        );
        return $sql->execute([$grid_id]);
    }

    // trie d'une grille par niveau
    public function sortByLevelGrid()
    {
        $sql = $this->db->query(
            "select g.id, g.name, g.nb_row, g.nb_col, g.level, g.created_at, u.username
            from grid g
            join user u on u.id = g.user_id
            order by g.level desc"
        );
        $sql->execute();
        $grids = $sql->fetchAll(PDO::FETCH_ASSOC);
        if ($grids) {
            return $grids;
        }
        return null;
    }

    // trie d'une grille par date de création
    public function sortByDateGrid()
    {
        $sql = $this->db->query(
            "select g.id, g.name, g.nb_row, g.nb_col, g.level, g.created_at, u.username
            from grid g
            join user u on u.id = g.user_id
            order by g.created_at desc"
        );
        $sql->execute();
        $grids = $sql->fetchAll(PDO::FETCH_ASSOC);
        if ($grids) {
            return $grids;
        }
        return null;
    }

    // vérifie l'unicité d'un champ dans une table
    private function isFieldUnique($field, $value)
    {
        $sql = $this->db->prepare(
            "select count(*)
            from grid
            where $field = ?"
        );
        $sql->execute([$value]);
        return $sql->fetchColumn() != 0;
    }
}