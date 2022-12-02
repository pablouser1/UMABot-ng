<?php
namespace App\Controllers;

use App\Constants\FilterTypes;
use App\Constants\Messages;
use App\Constants\StatusTypes;
use App\Helpers\Misc;
use App\Helpers\Wrappers;
use App\Items\Admin;
use App\Items\Content;
use App\Twitter;

class AdminController {
    const VALID_TYPES = [
        'waiting', 'approved', 'blocked', 'published', 'all'
    ];

    static public function loginGet() {
        if (Misc::isLoggedIn()) {
            Misc::redirect('/admin');
            exit;
        }
        Wrappers::plates('login');
    }

    static public function loginPost() {
        if (Misc::isLoggedIn()) {
            Misc::redirect('/admin');
            exit;
        }
        if (isset($_POST['username'], $_POST['password'])) {
            $admin = new Admin();
            $username = $_POST['username'];
            $input_password = $_POST['password'];

            $admin = $admin->get($username);
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

        $contentDb = new Content();
        $filter = FilterTypes::WAITING;

        if (isset($_GET['type']) && in_array($_GET['type'], self::VALID_TYPES)) {
            $filter = $_GET['type'];
        }

        $contents = $contentDb->getAll($filter);

        Wrappers::plates('dashboard', ['contents' => $contents]);
    }

    static public function approveGet() {
        if (!Misc::isLoggedIn()) {
            Misc::redirect('/admin/login');
            exit;
        }

        if (isset($_GET['id'])) {
            Wrappers::plates('approve', ['id' => $_GET['id']]);
        }
    }

    static public function approvePost() {
        if (!Misc::isLoggedIn()) {
            Misc::redirect('/admin/login');
            exit;
        }

        $note = isset($_POST['note']) && !empty($_POST['note']) ? htmlspecialchars($_POST['note']) : null;

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $contentDb = new Content();
            $twitter = new Twitter;
            $content = $contentDb->get($_GET['id']);
            if ($content) {
                $contentDb->updateState($_GET['id'], StatusTypes::APPROVED);
                $res = Messages::MESSAGE_APPROVED;
                if ($note) {
                    $res .= ' Comentario de la administración: ' . $note;
                }
                $position = $contentDb->queue(StatusTypes::APPROVED);
                $twitter->reply(sprintf($res, $position), $content->user_id);
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
            Wrappers::plates('block', ['id' => $_GET['id']]);
        }
    }

    static public function blockPost() {
        if (!Misc::isLoggedIn()) {
            Misc::redirect('/admin/login');
            exit;
        }

        $reason = isset($_POST['reason']) && !empty($_POST['reason']) ? htmlspecialchars($_POST['reason']) : null;

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $contentDb = new Content();
            $twitter = new Twitter;
            $content = $contentDb->get($_GET['id']);
            if ($content) {
                $contentDb->updateState($_GET['id'], StatusTypes::BLOCKED);
                $res = Messages::MESSAGE_BLOCKED;
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

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $contentDb = new Content();
            $contentDb->delete($_GET['id']);
            Misc::redirect('/admin');
        }
    }
}
