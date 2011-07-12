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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Blog post
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * @ORM\Entity(repositoryClass="Alom\Website\BlogBundle\Entity\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length="255")
     * @Assert\MinLength(limit=3)
     * @Assert\NotBlank
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length="255")
     * @Assert\NotBlank
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    protected $body;

    /**
     * @ORM\Column(type="text")
     */
    protected $bodyHtml;

    /**
     * @ORM\Column(type="text")
     */
    protected $metaDescription;

    /**
     * @ORM\Column(type="date")
     */
    protected $publishedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActive;

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
     * @ORM\OneToMany(targetEntity="PostComment", mappedBy="post", cascade={"remove"})
     */
    protected $comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set body of the post
     *
     * @param string $body Body to set
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Body of the post
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the publication date
     *
     * @param DateTime|string $publishedAt The date to set
     */
    public function setPublishedAt($publishedAt)
    {
        if (!$publishedAt instanceof \DateTime) {
            $publishedAt = new \DateTime($publishedAt);
        }

        $this->publishedAt = $publishedAt;
    }

    /**
     * Get declared publication date
     *
     * @return DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Get the body HTML
     *
     * @return string The body as HTML
     */
    public function getBodyHtml()
    {
        return $this->bodyHtml;
    }

    /**
     * Set the body HTML
     *
     * @param string $bodyHtml Value to set
     */
    public function setBodyHtml($bodyHtml)
    {
        $this->bodyHtml = $bodyHtml;
    }

    /**
     * Set the next post
     *
     * @param Alom\Website\BlogBundle\Entity\Post $post The next post
     */
    public function setNext($post)
    {
        $this->nextPost = $post;
    }

    /**
     * Set the previous post
     *
     * @param Alom\Website\BlogBundle\Entity\Post $post The previous post
     */
    public function setPrevious($post)
    {
        $this->previousPost = $post;
    }

    /**
     * Get the next post
     *
     * @return Alom\Website\BlogBundle\Entity\Post The next post, or false if
     *                                             there is no previous post
     *
     * @throws Exception Throws an exception if the previous post was not set
     */
    public function getNext()
    {
        if (null === $this->nextPost) {
            throw new \Exception("No next post was set !");
        }

        return $this->nextPost;
    }

    /**
     * Test if the post has a next post configured
     *
     * @return boolean A boolean indicating if there is a next post
     *
     * @throws Exception Throws an exception if the next post was not set
     */
    public function hasNext()
    {
        if (null === $this->nextPost) {
            throw new \Exception("No next post was set !");
        }
        return $this->nextPost !== false;
    }

    /**
     * Get the previous post
     *
     * @return Alom\Website\BlogBundle\Entity\Post The previous post, or false
     *                                             if there is no previous post
     *
     * @throws Exception Throws an exception if the previous post was not set
     */
    public function getPrevious()
    {
        if (null === $this->previousPost) {
            throw new \Exception("No previous post was set !");
        }

        return $this->previousPost;
    }

    /**
     * Test if the post has a previous post configured
     *
     * @return boolean A boolean indicating if there is a previous post
     *
     * @throws Exception Throws an exception if the previous post was not set
     */
    public function hasPrevious()
    {
        if (null === $this->previousPost) {
            throw new \Exception("No previous post was set !");
        }
        return $this->previousPost !== false;
    }

    /**
     * Get comments of the post
     *
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Get the isActive value (true = activated, false = disactivated)
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set the isActive value (true = activate, false, = disactivate)
     *
     * @param boolean $isActive The value to set
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Get the meta description
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set the meta description
     *
     * @param string $metaDescription Meta description to set
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * Enable the post
     */
    public function enable()
    {
        $this->isActive = true;
    }

    /**
     * Disable the post
     */
    public function disable()
    {
        $this->isActive = false;
    }
}
