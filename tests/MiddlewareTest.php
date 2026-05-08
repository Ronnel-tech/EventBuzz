<?php

use PHPUnit\Framework\TestCase;

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

final class MiddlewareTest extends TestCase
{
    private array $sessionBackup = [];

    protected function setUp(): void
    {
        $this->sessionBackup = $_SESSION ?? [];
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        $_SESSION = $this->sessionBackup;
    }

    public function testAuthMiddlewareAllowsAuthenticatedUsers(): void
    {
        $_SESSION['user'] = ['id' => 1, 'role' => 'attendee'];

        $middleware = require APP_ROOT . '/app/middlewares/auth.php';

        $this->assertIsCallable($middleware);
        $this->assertTrue($middleware());
    }

    public function testAdminMiddlewareAllowsAdminUsers(): void
    {
        $_SESSION['user'] = ['id' => 1, 'role' => 'admin'];

        $middleware = require APP_ROOT . '/app/middlewares/admin.php';

        $this->assertIsCallable($middleware);
        $this->assertTrue($middleware());
    }

    public function testOrganizerMiddlewareAllowsOrganizerUsers(): void
    {
        $_SESSION['user'] = ['id' => 2, 'role' => 'organizer'];

        $middleware = require APP_ROOT . '/app/middlewares/organizer.php';

        $this->assertIsCallable($middleware);
        $this->assertTrue($middleware());
    }

    public function testAttendeeMiddlewareAllowsAttendeeUsers(): void
    {
        $_SESSION['user'] = ['id' => 3, 'role' => 'attendee'];

        $middleware = require APP_ROOT . '/app/middlewares/attendee.php';

        $this->assertIsCallable($middleware);
        $this->assertTrue($middleware());
    }
}
