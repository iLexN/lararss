<?php

declare(strict_types=1);

namespace Domain\Post\Repository;

use Domain\Post\DbModel\Post;
use Domain\Post\DTO\NewPostData;
use Domain\Post\Model\PostModel;
use Domain\Post\Model\PostModelFactory;
use Domain\Source\Model\SourceBusinessModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\LazyCollection;

final class PostRepository
{
    /**
     * @var PostModelFactory
     */
    private $postModelFactory;

    public function __construct(PostModelFactory $postModelFactory)
    {
        $this->postModelFactory = $postModelFactory;
    }

    public function findOne(int $id): PostModel
    {
        return $this->postModelFactory->create(Post::findOrFail($id));
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return PostModel[]|LazyCollection
     */
    public function findActive(int $limit = 10, int $offset = 0): LazyCollection
    {
        return $this->listActive($limit, $offset)
            ->cursor()
            ->mapInto(PostModel::class);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Builder|Post
     */
    private function listActive(int $limit, int $offset): Builder
    {
        return Post::active()
            ->sortCreatedAsc()
            ->offset($offset)
            ->limit($limit);
    }

    public function findActiveWithRelation(int $limit = 10, int $offset = 0)
    {
        return Cache::remember('post_active_relation_' . $limit . '_' . $offset, 10, function () use ($limit, $offset) {
            return $this->listActive($limit, $offset)
                ->with('source')
                ->get()
                ->mapInto(PostModel::class);
        });
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return PostModel[]|LazyCollection
     */
    public function findActivePick(int $limit = 10, int $offset = 0): LazyCollection
    {
        return $this->listActive($limit, $offset)
            ->pick()
            ->cursor()
            ->mapInto(PostModel::class);
    }

    /**
     * @param int $id
     * @param int $limit
     * @param int $offset
     * @return PostModel[]|LazyCollection
     */
    public function findBySourceId(int $id, int $limit = 10, int $offset = 0): LazyCollection
    {
        return Post::source($id)
            ->sortCreatedAsc()
            ->offset($offset)
            ->limit($limit)
            ->cursor()
            ->mapInto(PostModel::class);
    }

    public function findUrlByPostData(NewPostData $postData)
    {
        return Post::whereUrl($postData->getUrl())
            ->firstOrNew(
                ['url' => $postData->getUrl()],
                $postData->toArray(/*static function(NewPostData $data){
                    return [
                        'title' => $data->getTitle(),
                        'url' => $data->getUrl(),
                        'description' => $data->getDescription(),
                        'created' => $data->getCreated(),
                        'content' => $data->getContent(),
                        'source_id' => $data->getSource()->id,
                        'status' => $data->getStatus()->getValue(),
                        'pick' => $data->getPick()->getValue(),
                    ];
                }*/)
            );
    }

    /**
     * @param SourceBusinessModel $sourceBusinessModel
     * @param int $limit
     * @param int $offset
     * @return PostModel[]|LazyCollection
     */
    public function findBySourceModel(
        SourceBusinessModel $sourceBusinessModel,
        int $limit = 10,
        int $offset = 0
    ): LazyCollection {
        return $this->findBySourceId($sourceBusinessModel->getId(), $limit, $offset);
    }
}
