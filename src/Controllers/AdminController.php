<?php
namespace App\Controllers;

use App\Db;
use App\Helpers\Misc;
use App\Twitter;

class AdminController {
    const VALID_TYPES = [
        'waiting', 'approved', 'blocked', 'all'
    ];

    static public function loginGet() {
        if (Misc::isLoggedIn()) {
            Misc::redirect('/admin');
            exit;
        }
        Misc::plates('login');
    }

    static public function loginPost() {
        if (Misc::isLoggedIn()) {
            Misc::redirect('/admin');
            exit;
        }
        if (isset($_POST['username'], $_POST['password'])) {
            $db = new Db;
            $username = $_POST['username'];
            $input_password = $_POST['password'];

            $admin = $db->getAdmin($username);
            if ($admin) {
                $valid = password_verify($input_password, $admin->password);
                if ($valid) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = $admin->id;
                    $_SESSION['username'] = $admin->username;
                    Misc::redirect('/admin');
                }
            }
        }
    }

    static public function logout() {
        session_destroy();
        Misc::redirect('/');
    }

    static public function dashboard() {
        if (!Misc::isLoggedIn()) {
            Misc::redirect('/admin/login');
            exit;
        }

        $db = new Db;
        $filter = 'waiting';

        if (isset($_GET['type']) && in_array($_GET['type'], self::VALID_TYPES)) {
            $filter = $_GET['type'];
        }

        $contents = $db->getContents($filter);

        Misc::plates('dashboard', ['contents' => $contents]);
    }

    static public function approve() {
        if (!Misc::isLoggedIn()) {
            Misc::redirect('/admin/login');
            exit;
        }

        if (isset($_GET['id'])) {
            $db = new Db;
            $twitter = new Twitter;
            $content = $db->getContent($_GET['id']);
            if ($content) {
                $db->setContentApproved($_GET['id']);
                $position = $db->getContentQueue();
                $twitter->reply('¡Uno de tus mensajes ha sido aprobado! Has sido agregado a la cola de publicación, posición: ' . $position, $content->user_id);
                Misc::redirect('/admin');
            }
        }
    }

    static public function blockGet() {
        if (!Misc::isLoggedIn()) {
            Misc::redirect('/admin/login');
            exit;
        }

        if (isset($_GET['id'])) {
            Misc::plates('block', ['id' => $_GET['id']]);
        }
    }

    static public function blockPost() {
        if (!Misc::isLoggedIn()) {
            Misc::redirect('/admin/login');
            exit;
        }

        $reason = isset($_POST['reason']) && !empty($_POST['reason']) ? htmlspecialchars($_POST['reason']) : null;

        if (isset($_GET['id'])) {
            $db = new Db;
            $twitter = new Twitter;
            $content = $db->getContent($_GET['id']);
            if ($content) {
                $db->setContentBlocked($_GET['id']);
                $res = 'Uno de tus mensajes ha sido denegado por la administración!';
                if ($reason) {
                    $res .= ' Motivo: ' . $reason;
                }
                $twitter->reply($res, $content->user_id);
                Misc::redirect('/admin');
            }
        }
    }

    static public function delete() {
        if (!Misc::isLoggedIn()) {
            Misc::redirect('/admin/login');
            exit;
        }

        if (isset($_GET['id'])) {
            $db = new Db;
            $db->deleteContent($_GET['id']);
        }
    }
}
