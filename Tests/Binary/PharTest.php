<?php
namespace Tests\Binary;

/**
 * @group binary
 */
class PharTest extends \PHPUnit_Framework_TestCase
{
    private $phar;

    public function __construct()
    {
        $this->phar = __DIR__ . '/../../releases/sfynx-ddd-generator.phar';
    }

    public function testICanRunPhar()
    {
        $command = sprintf('%s --version', $this->phar);
        $r = shell_exec($command);
        $this->assertContains('Sfynx DDD Generator', $r);
    }
}
