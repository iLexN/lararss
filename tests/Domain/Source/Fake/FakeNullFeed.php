<?php
declare(strict_types=1);

namespace Tests\Domain\Source\Fake;

use Zend\Feed\Reader\Collection\Category;
use Zend\Feed\Reader\Feed\FeedInterface;

final class FakeNullFeed implements FeedInterface
{
    private $position = 0;

    public $array ;

    public function __construct($array) {
        $this->position = 0;
        $this->array = $array;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current() {
        return $this->array[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->array[$this->position]);
    }

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return \count($this->array);
    }

    /**
     * Get a single author
     *
     * @param int $index
     * @return string|null
     */
    public function getAuthor($index = 0): ?string
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
     * Get the copyright entry
     *
     * @return string|null
     */
    public function getCopyright(): ?string
    {
        return null;
    }

    /**
     * Get the feed creation date
     *
     * @return \DateTime|null
     */
    public function getDateCreated(): ?\DateTime
    {
        return null;
    }

    /**
     * Get the feed modification date
     *
     * @return \DateTime|null
     */
    public function getDateModified(): ?\DateTime
    {
        return new \DateTime();
    }

    /**
     * Get the feed description
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return null;
    }

    /**
     * Get the feed generator entry
     *
     * @return string|null
     */
    public function getGenerator(): ?string
    {
        return null;
    }

    /**
     * Get the feed ID
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return null;
    }

    /**
     * Get the feed language
     *
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return null;
    }

    /**
     * Get a link to the HTML source
     *
     * @return string|null
     */
    public function getLink(): ?string
    {
        return null;
    }

    /**
     * Get a link to the XML feed
     *
     * @return string|null
     */
    public function getFeedLink(): ?string
    {
        return null;
    }

    /**
     * Get the feed title
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return null;
    }

    /**
     * Get all categories
     *
     * @return \Zend\Feed\Reader\Collection\Category
     */
    public function getCategories(): Category
    {
        return new Category();
    }
}
