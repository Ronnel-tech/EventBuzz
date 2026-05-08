<?php

use PHPUnit\Framework\TestCase;

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

require_once APP_ROOT . '/scheme/Router.php';

final class RouterTest extends TestCase
{
    private array $serverBackup = [];
    private array $postBackup = [];
    private array $sessionBackup = [];
    private string|false $sessionSavePathBackup = false;

    protected function setUp(): void
    {
        $this->serverBackup = $_SERVER;
        $this->postBackup = $_POST;
        $this->sessionBackup = $_SESSION ?? [];
        $this->sessionSavePathBackup = ini_get('session.save_path');

        $_POST = [];
        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        ini_set('session.save_path', sys_get_temp_dir());
    }

    protected function tearDown(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        $_SERVER = $this->serverBackup;
        $_POST = $this->postBackup;
        $_SESSION = $this->sessionBackup;

        if ($this->sessionSavePathBackup !== false) {
            ini_set('session.save_path', $this->sessionSavePathBackup);
        }
    }

    public function testGetRouteMatchesAndPassesPathParameter(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/events/42';
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        $matchedId = null;

        $router = new Router();
        $router->get('/events/{id}', function ($id) use (&$matchedId): void {
            $matchedId = $id;
        });

        $router->run();

        $this->assertSame('42', $matchedId);
    }

    public function testPostRouteRunsWhenCsrfTokenIsValid(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/submit';
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        $handled = false;

        $router = new Router();
        $_POST['csrf_token'] = $_SESSION['csrf_token'];

        $router->post('/submit', function () use (&$handled): void {
            $handled = true;
        });

        $router->run();

        $this->assertTrue($handled);
    }
}
