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

        $this->activeAction->executeByModel($source);
        $this->assertEquals($status->opposite(),$source->status);
        $this->assertDatabaseHas('sources', $source->toArray());
    }

    /**
     * @throws \Exception
     */
    public function testSetActiveBySourceWithQueue(): void
    {
        $status = Status::inActive();

        $source = factory(Source::class)->make([
            'url' => 'this is url' . \random_int(1,9),
            'status' => $status->getValue(),
        ]);

        $this->activeAction->onQueueByModel($source);
        //cos using queue, so here source status have no change
        $this->assertEquals($status->getValue(),$source->status);
        $this->assertDatabaseHas('sources', [
            'url' => $source->url,
            'status' => !$source->status
        ]);
    }

    public function testSetActiveByDomainModel(): void
    {
        $status = Status::inActive();
        $source = factory(Source::class)->make([
            'status' => $status->getValue(),
        ]);
        $domainModel = $this->factory->createOne($source);
        $this->activeAction->execute($domainModel);
        $this->assertEquals($status->opposite(),$source->status);
        $this->assertDatabaseHas('sources', $source->toArray());
    }

    /**
     * @throws \Exception
     */
    public function testSetActiveByDomainModelWithQueue(): void
    {
        $status = Status::inActive();
        $source = factory(Source::class)->make([
            'url' => 'this is url' . \random_int(1,9),
            'status' => $status->getValue(),
        ]);
        $domainModel = $this->factory->createOne($source);
        $this->activeAction->onQueue()->execute($domainModel);
        //cos using queue, so here source status have no change
        $this->assertEquals($status->getValue(),$source->status);
        $this->assertDatabaseHas('sources', [
            'url' => $source->url,
            'status' => !$source->status
        ]);
    }

    public function testSetInActiveBySource(): void
    {
        $status = Status::active();
        $source = factory(Source::class)->make([
            'status' => $status->getValue(),
        ]);

        $this->inActiveAction->executeByModel($source);
        $this->assertEquals($status->opposite(),$source->status);
        $this->assertDatabaseHas('sources', $source->toArray());
    }

    /**
     * @throws \Exception
     */
    public function testSetInActiveBySourceWithQueue(): void
    {
        $status = Status::active();
        $source = factory(Source::class)->make([
            'url' => 'this is url' . \random_int(1,9),
            'status' => $status->getValue(),
        ]);

        $this->inActiveAction->onQueueByModel($source);
        $this->assertEquals($status->getValue(),$source->status);
        $this->assertDatabaseHas('sources', [
            'url' => $source->url,
            'status' => !$source->status
        ]);
    }

    public function testSetInActiveByDomainModel(): void
    {
        $status = Status::active();
        $source = factory(Source::class)->make([
            'status' => $status->getValue(),
        ]);
        $domainModel = $this->factory->createOne($source);
        $this->inActiveAction->execute($domainModel);
        $this->assertEquals($status->opposite(),$source->status);
        $this->assertDatabaseHas('sources', $source->toArray());
    }

    public function testSetInActiveByDomainModelOnQueue(): void
    {
        $status = Status::active();
        $source = factory(Source::class)->make([
            'url' => 'this is url',
            'status' => $status->getValue(),
        ]);
        $domainModel = $this->factory->createOne($source);

        $this->inActiveAction->onQueue()->execute($domainModel);
        //cos using queue, so here source status have no change
        $this->assertEquals($status->getValue(),$source->status);
        $this->assertDatabaseHas('sources', [
            'url' => $source->url,
            'status' => !$source->status
        ]);
    }
}
