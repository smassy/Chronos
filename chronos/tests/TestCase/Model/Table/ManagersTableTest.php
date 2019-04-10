<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ManagersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ManagersTable Test Case
 */
class ManagersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ManagersTable
     */
    public $Managers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Managers',
        'app.Departments'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Managers') ? [] : ['className' => ManagersTable::class];
        $this->Managers = TableRegistry::getTableLocator()->get('Managers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Managers);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
