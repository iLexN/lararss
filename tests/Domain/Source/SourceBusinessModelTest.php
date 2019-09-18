<?php
declare(strict_types=1);

namespace Tests\Domain\Source;

use Carbon\Carbon;
use Domain\Source\Model\SourceBusinessModelFactory;
use Domain\Source\Model\Sub\SourceIsWithInUpdateRange;
use Domain\Support\Enum\Status;
use Domain\Source\DbModel\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SourceBusinessModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var SourceBusinessModelFactory
     */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new SourceBusinessModelFactory();
    }

    public function testGetMethod(): void
    {
        $source = factory(Source::class)->create();
        $model = $this->factory->createOne($source);

        $this->assertEquals($source->status, $model->isActive());
        $this->assertEquals($source->id, $model->getId());
        $this->assertEquals($source->url, $model->getUrl());
        $this->assertEquals(new Status($source->status), $model->getStatus());
        $this->assertEquals($source->status, $model->getStatusValue());
        $this->assertEquals($source->created_at->toString(), $model->getCreateAt()->toString());
    }

    public function testIsActiveTrue(): void
    {
        $status = Status::active();
        $source = factory(Source::class)->make([
            'status' => $status->getValue(),
        ]);
        $model = $this->factory->createOne($source);
        $this->assertEquals(true, $model->isActive());
    }

    public function testIsActiveFalse(): void
    {
        $status = Status::inActive();
        $source = factory(Source::class)->make([
            'status' => $status->getValue(),
        ]);
        $model = $this->factory->createOne($source);
        $this->assertEquals(false, $model->isActive());
    }

    /**
     * @dataProvider getUpdatedOutRange
     * @param int $hour
     */
    public function testShouldSyncTrue(int $hour): void
    {
        $status = Status::active();
        $source = factory(Source::class)->make([
            'status' => $status->getValue(),
            'updated_at' => Carbon::now()->subHours($hour),
        ]);
        $model = $this->factory->createOne($source);
        $this->assertEquals(true, $model->shouldSync());
    }

    public function testShouldSyncFalseStatusIsInactive(): void
    {
        $status = Status::inActive();
        $source = factory(Source::class)->make([
            'status' => $status->getValue(),
        ]);
        $model = $this->factory->createOne($source);
        $this->assertEquals(false, $model->shouldSync());
    }

    /**
     * @dataProvider getUpdatedInRange
     * @param int $hour
     */
    public function testShouldSyncFalseTimeNoArrive(int $hour): void
    {
        $status = Status::active();
        $source = factory(Source::class)->make([
            'status' => $status->getValue(),
            'updated_at' => Carbon::now()->subHours($hour),
        ]);
        $model = $this->factory->createOne($source);
        $this->assertEquals(false, $model->shouldSync());
    }

    public function getUpdatedInRange(): \Generator
    {
        $range = range(0,SourceIsWithInUpdateRange::UPDATED_RANGE_HOUR);
        foreach ($range as $value){
            yield 'hour within '.$value => [
                $value,
            ];
        }
    }

    public function getUpdatedOutRange(): ?\Generator
    {
        $range = range(SourceIsWithInUpdateRange::UPDATED_RANGE_HOUR + 1,10);
        foreach ($range as $value){
            yield 'hour within '.$value => [
                $value,
            ];
        }
    }
}
