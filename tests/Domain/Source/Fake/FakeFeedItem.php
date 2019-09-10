<?php
declare(strict_types=1);


namespace Tests\Domain\Source\Fake;


use Zend\Feed\Reader\Collection\Category;
use Zend\Feed\Reader\Entry\EntryInterface;

final class FakeFeedItem implements EntryInterface
{

    /**
     * Get the specified author
     *
     * @param int $index
     * @return array<string, string>|null
     */
    public function getAuthor($index = 0): ?array
    {
        return null;
    }

    /**
     * Get an array with feed authors
     *
     * @return array
     */
    public function getAuthors(): array
    {
        return [];
    }

    /**
     * Get the entry content
     *
     * @return string
     */
    public function getContent(): string
    {
        return 'this is fake ' . __METHOD__;
    }

    /**
     * Get the entry creation date
     *
     * @return \DateTime
     * @throws \Exception
     */
    public function getDateCreated(): \DateTime
    {
        return new \DateTime();
    }

    /**
     * Get the entry modification date
     *
     * @return \DateTime
     * @throws \Exception
     */
    public function getDateModified(): \DateTime
    {
        return new \DateTime();
    }

    /**
     * Get the entry description
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return 'this is fake ' . __METHOD__;
    }

    /**
     * Get the entry enclosure
     *
     * @return \stdClass
     */
    public function getEnclosure(): ?\stdClass
    {
        return new \stdClass();
    }

    /**
     * Get the entry ID
     *
     * @return string
     */
    public function getId(): string
    {
        return 'this is fake ' . __METHOD__;
    }

    /**
     * Get a specific link
     *
     * @param int $index
     * @return string
     */
    public function getLink($index = 0): ?string
    {
        return 'this is fake ' . __METHOD__;
    }

    /**
     * Get all links
     *
     * @return array
     */
    public function getLinks(): ?array
    {
        return [];
    }

    /**
     * Get a permalink to the entry
     *
     * @return string
     */
    public function getPermalink(): ?string
    {
        return 'this is fake ' . __METHOD__;
    }

    /**
     * Get the entry title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return 'this is fake ' . __METHOD__;
    }

    /**
     * Get the number of comments/replies for current entry
     *
     * @return int
     */
    public function getCommentCount(): int
    {
        return 0;
    }

    /**
     * Returns a URI pointing to the HTML page where comments can be made on this entry
     *
     * @return string
     */
    public function getCommentLink(): ?string
    {
        return 'this is fake ' . __METHOD__;
    }

    /**
     * Returns a URI pointing to a feed of all comments for this entry
     *
     * @return string
     */
    public function getCommentFeedLink(): string
    {
        return 'this is fake ' . __METHOD__;
    }

    /**
     * Get all categories
     *
     * @return Category
     */
    public function getCategories(): Category
    {
        return new Category();
    }
}
