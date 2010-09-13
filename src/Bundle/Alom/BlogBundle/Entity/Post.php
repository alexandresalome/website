<?php

namespace Bundle\Alom\BlogBundle\Entity;

/**
 * Blog post
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * @Entity
 */
class Post
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @Column(type="string", length="255")
     */
    protected $title;

    /**
     * @Column(type="string", length="255")
     */
    protected $slug;

    /**
     * @Column(type="text")
     */
    protected $body;

    /**
     * @Column(type="datetime")
     */
    protected $publishedAt;

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

    public function getPublishedAt() {
        return $this->publishedAt;
    }
}
