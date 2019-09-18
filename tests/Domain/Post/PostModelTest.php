<?php
declare(strict_types=1);

namespace Tests\Domain\Post;

use Domain\Post\DbModel\Post;
use Domain\Post\Enum\Pick;
use Domain\Post\Model\PostModel;
use Domain\Source\DbModel\Source;
use Domain\Source\Model\SourceBusinessModelFactory;
use Domain\Support\Enum\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PostModelTest extends TestCase
{
    use RefreshDatabase;

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
}
