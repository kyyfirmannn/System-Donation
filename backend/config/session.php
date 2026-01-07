<?php
// config/session.php

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        self::start();
        return $_SESSION[$key] ?? null;
    }

    public static function delete($key) {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        self::start();
        session_destroy();
    }

    public static function isLoggedIn() {
        return self::get('user_id') !== null;
    }

    public static function isAdmin() {
        return self::get('role') === 'admin';
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /sistem-donasi/frontend/user/donatur.php');
            exit;
        }
    }

    public static function requireAdmin() {
        self::requireLogin();
        if (!self::isAdmin()) {
            header('Location: /sistem-donasi/frontend/user/index.php');
            exit;
        }
    }
}
?>