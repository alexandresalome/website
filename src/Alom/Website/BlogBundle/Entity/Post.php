<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Alom\Website\BlogBundle\Entity;

/**
 * Blog post
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * @orm:Entity(repositoryClass="Alom\Website\BlogBundle\Entity\PostRepository")
 */
class Post
{
    /**
     * @orm:Id
     * @orm:Column(type="integer")
     * @orm:GeneratedValue()
     */
    protected $id;

    /**
     * @orm:Column(type="string", length="255")
     */
    protected $title;

    /**
     * @orm:Column(type="string", length="255")
     */
    protected $slug;

    /**
     * @orm:Column(type="text")
     */
    protected $body;

    /**
     * @orm:Column(type="datetime")
     */
    protected $publishedAt;

    /**
     * Next post
     *
     * null:   unknown
     * false:  no next post
     * object: the next post
     *
     * @var Alom\Website\BlogBundle\Entity\Post
     */
    protected $nextPost;

    /**
     * Previous post
     *
     * null:   unknown
     * false:  no next post
     * object: the next post
     *
     * @var Alom\Website\BlogBundle\Entity\Post
     */
    protected $previousPost;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug) {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string $slug
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * Set body of the post
     *
     * @param string $body Body to set
     */
    public function setBody($body) {
        $this->body = $body;
    }

    /**
     * Body of the post
     *
     * @return string
     */
    public function getBody() {
        return $this->body;
    }

    public function setPublishedAt($publishedAt) {
        if (!$publishedAt instanceof \DateTime) {
            $publishedAt = new \DateTime($publishedAt);
        }

        $this->publishedAt = $publishedAt;
    }

    /**
     * @return DateTime
     */
    public function getPublishedAt() {
        return $this->publishedAt;
    }

    public function setNext($post)
    {
        $this->nextPost = $post;
    }

    public function setPrevious($post)
    {
        $this->previousPost = $post;
    }

    public function getNext()
    {
        if (null === $this->nextPost)
        {
            throw new \Exception("No next post was set !");
        }
        return $this->nextPost;
    }

    public function hasNext()
    {
        if (null === $this->nextPost)
        {
            throw new \Exception("No next post was set !");
        }
        return $this->nextPost !== false;
    }

    public function getPrevious()
    {
        if (null === $this->previousPost)
        {
            throw new \Exception("No previous post was set !");
        }
        return $this->previousPost;
    }

    public function hasPrevious()
    {
        if (null === $this->previousPost)
        {
            throw new \Exception("No previous post was set !");
        }
        return $this->previousPost !== false;
    }
}
