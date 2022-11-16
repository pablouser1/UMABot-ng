<?php
namespace App\Controllers;

use App\Db;
use App\Helpers\Misc;
use App\Twitter;

class AdminController {
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

    static public function dashboard() {
        if (!Misc::isLoggedIn()) {
            Misc::redirect('/admin/login');
            exit;
        }

        $db = new Db;

        $contents = $db->getContents();

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
                $twitter->reply('¡Uno de tus mensajes ha sido aprobado! Has sido agregado a la cola de publicación', $content->user_id);
                Misc::redirect('/admin');
            }
        }
    }

    static public function block() {
        if (!Misc::isLoggedIn()) {
            Misc::redirect('/admin/login');
            exit;
        }

        if (isset($_GET['id'])) {
            $db = new Db;
            $twitter = new Twitter;
            $content = $db->getContent($_GET['id']);
            if ($content) {
                $db->setContentBlocked($_GET['id']);
                $twitter->reply('¡Tu mensaje ha sido denegado por la administración!', $content->user_id);
                Misc::redirect('/admin');
            }
        }
    }
}