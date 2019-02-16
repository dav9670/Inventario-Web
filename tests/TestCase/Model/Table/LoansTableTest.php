<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LoansTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LoansTable Test Case
 */
class LoansTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LoansTable
     */
    public $Loans;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Loans',
        'app.Users',
        'app.Items'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Loans') ? [] : ['className' => LoansTable::class];
        $this->Loans = TableRegistry::getTableLocator()->get('Loans', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Loans);

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
