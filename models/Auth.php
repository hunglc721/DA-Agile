<?php
class Auth
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user']);
    }

    public static function user()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user'] ?? null;
    }

    public static function isAdmin()
    {
        $user = self::user();
        return isset($user['is_admin']) && (int)$user['is_admin'] === 1;
    }

    public static function requireLogin()
    {
        if (!self::check()) {
            redirect('/');
        }
    }

    public static function requireAdmin()
    {
        if (!self::isAdmin()) {
            redirect('/');
        }
    }
}
?>
