<?php

declare(strict_types=1);

namespace Tests\Domain\Post;

use Carbon\Carbon;
use Domain\Post\Action\CreatePostAction;
use Domain\Post\DTO\NewPostData;
use Domain\Source\DbModel\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zend\Feed\Reader\Entry\EntryInterface;

final class PostCreateTest extends TestCase
{

    use RefreshDatabase;

    public function testCreatePost(): void
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

        $postData = NewPostData::createFromArray($data);
        $createAction = new CreatePostAction();
        $createAction->execute($postData);

        $this->assertDatabaseHas('posts', $data);
    }

    public function testCreateZendFeedItem(): void
    {
        $source = factory(Source::class)->create();

        $data = [
            'title' => 'rss title',
            'url' => 'rss this is url',
            'description' => 'rss this is description',
            'created' => new \DateTime(),
            'content' => 'rss long long content',
            'source_id' => $source->id,
        ];

        $mock = $this->createMock(EntryInterface::class);
        $mock->expects($this->once())
            ->method('getTitle')
            ->willReturn($data['title']);
        $mock->expects($this->once())
            ->method('getLink')
            ->willReturn($data['url']);
        $mock->expects($this->once())
            ->method('getDescription')
            ->willReturn($data['description']);
        $mock->expects($this->once())
            ->method('getDateCreated')
            ->willReturn($data['created']);
        $mock->expects($this->once())
            ->method('getContent')
            ->willReturn($data['content']);

        $postData = NewPostData::createFromZendReader($mock, $source);
        $createAction = new CreatePostAction();
        $createAction->execute($postData);

        $this->assertDatabaseHas('posts', $data);
    }
}
