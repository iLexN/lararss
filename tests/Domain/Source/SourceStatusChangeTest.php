<?php
declare(strict_types=1);

namespace Tests\Domain\Source;

use Domain\Source\Action\StatusActiveSourceAction;
use Domain\Source\Action\StatusInActiveSourceAction;
use Domain\Source\Domain\SourceOperationModelFactory;
use Domain\Source\Enum\Status;
use Domain\Source\Model\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SourceStatusChangeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var StatusActiveSourceAction
     */
    private $activeAction;
    /**
     * @var StatusInActiveSourceAction
     */
    private $inActiveAction;
    /**
     * @var SourceOperationModelFactory
     */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new SourceOperationModelFactory();
        $this->activeAction = new StatusActiveSourceAction($this->factory);
        $this->inActiveAction = new StatusInActiveSourceAction($this->factory);
    }

    public function testSetActiveBySource(): void
    {
        $status = Status::inActive();

        $source = factory(Source::class)->make([
            'status' => $status->getValue(),
        ]);

        $this->activeAction->byModel($source);
        $this->assertEquals($status->opeosite(),$source->status);
        $this->assertDatabaseHas('sources', $source->toArray());
    }

    public function testSetActiveByDomainModel(): void
    {
        $status = Status::inActive();
        $source = factory(Source::class)->make([
            'status' => $status->getValue(),
        ]);
        $domainModel = $this->factory->createOne($source);
        $this->activeAction->byDomain($domainModel);
        $this->assertEquals($status->opeosite(),$source->status);
        $this->assertDatabaseHas('sources', $source->toArray());
    }

    public function testSetInActiveBySource(): void
    {
        $status = Status::active();
        $source = factory(Source::class)->make([
            'status' => $status->getValue(),
        ]);

        $this->inActiveAction->byModel($source);
        $this->assertEquals($status->opeosite(),$source->status);
        $this->assertDatabaseHas('sources', $source->toArray());
    }

    public function testSetInActiveByDomainModel(): void
    {
        $status = Status::active();
        $source = factory(Source::class)->make([
            'status' => $status->getValue(),
        ]);
        $domainModel = $this->factory->createOne($source);
        $this->inActiveAction->byDomain($domainModel);
        $this->assertEquals($status->opeosite(),$source->status);
        $this->assertDatabaseHas('sources', $source->toArray());
    }
}
