<?php

namespace Bundle\Alom\BlogBundle\Entity;

/**
 * Comment on a blog post
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * @Entity
 */
class PostComment
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
    protected $email;

    /**
     * @Column(type="string", length="255")
     */
    protected $fullname;

    /**
     * @Column(type="string", length="255")
     */
    protected $website;

    /**
     * @Column(type="text")
     */
    protected $body;

    /**
     * @Column(type="datetime")
     */
    protected $createdAt;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     */
    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    /**
     * Get Website
     *
     * @return string $website
     */
    public function getWebsite() {
        return $this->website;
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

    /**
     * Set the creation date
     *
     * @param <type> $createdAt
     */
    public function setCreatedAt($createdAt) {
        if (!$createdAt instanceof \DateTime) {
            $createdAt = new \DateTime($createdAt);
        }

        $this->createdAt = $createdAt;
    }

    /**
     * Get the creation date
     *
     * @return DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }
}
