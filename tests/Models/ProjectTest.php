<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\ProjectManagement\tests\Models;

use Modules\Admin\Models\NullAccount;
use Modules\ProjectManagement\Models\ProgressType;
use Modules\ProjectManagement\Models\Project;
use Modules\Tasks\Models\Task;
use phpOMS\Localization\Money;

/**
 * @internal
 */
class ProjectTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\ProjectManagement\Models\Project
     * @group module
     */
    public function testDefault() : void
    {
        $project = new Project();

        self::assertEquals(0, $project->getId());
        self::assertInstanceOf('\Modules\Calendar\Models\Calendar', $project->calendar);
        self::assertEquals((new \DateTime('now'))->format('Y-m-d'), $project->createdAt->format('Y-m-d'));
        self::assertEquals((new \DateTime('now'))->format('Y-m-d'), $project->getStart()->format('Y-m-d'));
        self::assertEquals((new \DateTime('now'))->modify('+1 month')->format('Y-m-d'), $project->getEnd()->format('Y-m-d'));
        self::assertEquals(0, $project->createdBy->getId());
        self::assertEquals('', $project->getName());
        self::assertEquals('', $project->description);
        self::assertEquals(0, $project->getCosts()->getInt());
        self::assertEquals(0, $project->getBudgetCosts()->getInt());
        self::assertEquals(0, $project->getBudgetEarnings()->getInt());
        self::assertEquals(0, $project->getEarnings()->getInt());
        self::assertEquals(0, $project->getProgress());
        self::assertEquals(ProgressType::MANUAL, $project->getProgressType());
        self::assertEmpty($project->getTasks());
        self::assertFalse($project->removeTask(2));
        self::assertInstanceOf('\Modules\Tasks\Models\Task', $project->getTask(0));
    }

    /**
     * @covers Modules\ProjectManagement\Models\Project
     * @group module
     */
    public function testSetGet() : void
    {
        $project = new Project();

        $project->setName('Project');
        self::assertEquals('Project', $project->getName());

        $project->description = 'Description';
        self::assertEquals('Description', $project->description);

        $project->setStart($date = new \DateTime('2000-05-05'));
        self::assertEquals($date->format('Y-m-d'), $project->getStart()->format('Y-m-d'));

        $project->setEnd($date = new \DateTime('2000-05-05'));
        self::assertEquals($date->format('Y-m-d'), $project->getEnd()->format('Y-m-d'));

        $money = new Money();
        $money->setString('1.23');

        $project->setCosts($money);
        self::assertEquals($money->getAmount(), $project->getCosts()->getAmount());

        $project->setBudgetCosts($money);
        self::assertEquals($money->getAmount(), $project->getBudgetCosts()->getAmount());

        $project->setBudgetEarnings($money);
        self::assertEquals($money->getAmount(), $project->getBudgetEarnings()->getAmount());

        $project->setEarnings($money);
        self::assertEquals($money->getAmount(), $project->getEarnings()->getAmount());

        $task        = new Task();
        $task->title = 'A';
        $task->setCreatedBy(new NullAccount(1));

        $project->addTask($task);

        self::assertEquals('A', $project->getTask(0)->title);
        self::assertCount(1, $project->getTasks());
        self::assertTrue($project->removeTask(0));
        self::assertEquals(0, $project->countTasks());

        $project->setProgress(10);
        self::assertEquals(10, $project->getProgress());

        $project->setProgressType(ProgressType::TASKS);
        self::assertEquals(ProgressType::TASKS, $project->getProgressType());
    }
}
