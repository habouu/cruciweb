<?php
require_once 'config/DB.php';

// routeur de l'application
$query = isset($_GET['q']) ? $_GET['q'] : 'grids';

switch ($query) {
    case 'register':
        require_once 'controllers/UserController.php';
        $userController = new UserController();
        $userController->createNewUser();
        break;

    case 'login':
        require_once 'controllers/UserController.php';
        $userController = new UserController();
        $userController->getCredentialsUser();
        break;

    case 'logout':
        require_once 'controllers/UserController.php';
        $userController = new UserController();
        $userController->logoutUser();
        break;

    case 'users':
        require_once 'controllers/UserController.php';
        $userController = new UserController();
        $userController->getListUser();
        break;

    case 'user-create':
        require_once 'controllers/UserController.php';
        $userController = new UserController();
        $userController->adminCreateNewUser();
        break;

    case 'user-delete':
        require_once 'controllers/UserController.php';
        $userController = new UserController();
        $userController->deleteUser();
        break;
    
    case 'grids':
        require_once 'controllers/GridController.php';
        $gridController = new GridController();
        $gridController->getListGrid();
        break;

    case 'grid-create':
        require_once 'controllers/GridController.php';
        $gridController = new GridController();
        $gridController->createGrid();
        break;

    case 'grid-create-cell':
        require_once 'controllers/GridController.php';
        $gridController = new GridController();
        $gridController->createCellGrid();
        break;

    case 'grid-create-def':
        require_once 'controllers/GridController.php';
        $gridController = new GridController();
        $gridController->createDefGrid();
        break;

    case 'grid-show':
        require_once 'controllers/GridController.php';
        $gridController = new GridController();
        $gridController->showGrid();
        break;

    case 'grid-save':
        require_once 'controllers/GridController.php';
        $gridController = new GridController();
        $gridController->saveGrid();
        break;

    case 'grid-msg':
        require_once 'controllers/GridController.php';
        $gridController = new GridController();
        $gridController->saveMessageGrid();
        break;

    case 'grid-save-user':
        require_once 'controllers/GridController.php';
        $gridController = new GridController();
        $gridController->getListSaveGrid();
        break;
    
    case 'grid-delete':
        require_once 'controllers/GridController.php';
        $gridController = new GridController();
        $gridController->deleteGrid();
        break;

    case 'grid-save-del':
        require_once 'controllers/GridController.php';
        $gridController = new GridController();
        $gridController->deleteSaveGrid();
        break;

    case 'grid-sort-level':
        require_once 'controllers/GridController.php';
        $gridController = new GridController();
        $gridController->sortByLevelGrid();
        break;

    case 'grid-sort-date':
        require_once 'controllers/GridController.php';
        $gridController = new GridController();
        $gridController->sortByDateGrid();
        break;
        
    default:
        include 'views/partials/_error_404.php';
        http_response_code(404);
        break;
}