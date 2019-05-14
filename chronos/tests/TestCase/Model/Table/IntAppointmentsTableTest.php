<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\IntAppointmentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\IntAppointmentsTable Test Case
 */
class IntAppointmentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\IntAppointmentsTable
     */
    public $IntAppointments;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.IntAppointments',
        'app.Appointments',
        'app.Users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('IntAppointments') ? [] : ['className' => IntAppointmentsTable::class];
        $this->IntAppointments = TableRegistry::getTableLocator()->get('IntAppointments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->IntAppointments);

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
