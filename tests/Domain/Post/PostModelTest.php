<?php
declare(strict_types=1);

namespace Tests\Domain\Post;

use Carbon\Carbon;
use Domain\Post\DbModel\Post;
use Domain\Post\DTO\NewPostDataFactory;
use Domain\Post\Enum\Pick;
use Domain\Post\Model\PostModel;
use Domain\Source\DbModel\Source;
use Domain\Source\Model\SourceBusinessModelFactory;
use Domain\Support\Enum\Status;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PostModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var NewPostDataFactory
     */
    private $newPostDataFactory;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->newPostDataFactory = $this->app->make(NewPostDataFactory::class);
    }

    public function testCreateModel(): void
    {
        $source = factory(Source::class)->create();

        /** @var Post $post */
        $post = factory(Post::class)->create([
            'source_id' => $source->id,
        ]);

        $sourceModel = new SourceBusinessModelFactory();
        $postModel = new PostModel($post);

        $this->assertEquals($post->id, $postModel->getId());
        $this->assertEquals($post->title, $postModel->getTitle());
        $this->assertEquals($post->url, $postModel->getUrl());
        $this->assertEquals($post->description, $postModel->getDescription());
        $this->assertEquals($post->created, $postModel->getCreated());
        $this->assertEquals($post->created, $postModel->getCreated());
        $this->assertEquals($post->created->toString(), $postModel->getCreated()->toString());
        $this->assertEquals($post->content, $postModel->getContent());
        $this->assertEquals(new Status($post->status), $postModel->getStatus());
        $this->assertEquals(new Pick($post->pick), $postModel->getPick());

        $this->assertEquals($sourceModel->createOne($post->source), $postModel->getSource());

        $this->assertEquals($post->source->id, $postModel->getId());
        $this->assertEquals($post->url, $postModel->getUrl());
    }

    public function testIsSame(): void
    {
        $source = factory(Source::class)->create();
        $data = [
            'title' => 'title',
            'url' => 'this is url',
            'description' => 'this is description',
            'created' => Carbon::now(),
            'content' => 'long long content',
            'source_id' => $source->id,
        ];

        $postData = $this->newPostDataFactory->createFromArray($data);
        $post = factory(Post::class)->make($postData->toArray());

        $this->assertEquals(true, $postData->isSame($post));

        $post->title = 'other title';
        $this->assertEquals(false, $postData->isSame($post));
    }
}
