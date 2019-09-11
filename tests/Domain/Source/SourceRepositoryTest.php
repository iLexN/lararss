<?php
declare(strict_types=1);


namespace Tests\Domain\Source;

use Domain\Support\Enum\Status;
use Domain\Source\Model\Source;
use Domain\Source\Repository\SourceRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var SourceRepository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new SourceRepository(new Source());

        //add data
        factory(Source::class, 5)->create([
            'status' => Status::active()->getValue(),
        ]);
        factory(Source::class, 5)->create([
            'status' => Status::inActive()->getValue(),
        ]);
    }

    public function testGetAll(): void
    {
        $sources = $this->repository->getAll();
        $this->assertTrue(true);
        $this->assertEquals(10, $sources->count());
    }

    public function testGetActive(): void
    {
        $sources = $this->repository->getActive();
        $this->assertEquals(5, $sources->count());
        foreach ($sources as $source) {
            $this->assertEquals(Status::active()->getValue(), $source->status);
        }
    }
}
