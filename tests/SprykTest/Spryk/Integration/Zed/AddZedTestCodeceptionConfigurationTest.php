<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykTest\Spryk\Integration\Zed;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group AddZedTestCodeceptionConfigurationTest
 * Add your own group annotations below this line
 */
class AddZedTestCodeceptionConfigurationTest extends Unit
{
    /**
     * @var \SprykTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsZedTestCodeceptionConfiguration(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
        ]);

        static::assertFileExists($this->tester->getModuleDirectory() . 'tests/SprykerTest/Zed/FooBar/codeception.yml');
    }

    /**
     * @return void
     */
    public function testAddsZedTestCodeceptionConfigurationOnProjectLayer(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--mode' => 'project',
        ]);

        static::assertFileExists($this->tester->getProjectTestDirectory() . 'codeception.yml');
    }
}