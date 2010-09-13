<?php

namespace Bundle\Alom\ContactBundle\Entity;

/**
 * Message from contact form.
 *
 * A message has an expeditor (name, email) and is composed of a subject and a
 * body.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * @Entity
 */
class Message
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
    protected $name;

    /**
     * @Column(type="string", length="255")
     */
    protected $email;

    /**
     * @Column(type="text")
     */
    protected $body;

    /**
     * @Column(type="datetime")
     */
    protected $createdAt;

    /**
     * ID Getter
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the name of the contact
     *
     * @param string $name Name of the contact to set
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name of the contact
     *
     * @return string The name of the contact
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the email of contact
     *
     * @param string $email Email to set
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Get the email of contact
     *
     * @return string The email
     */
    public function getEmail() {
        return $this->email;
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
     * Get body of the contact message
     *
     * @return string The message
     */
    public function getBody() {
        return $this->body;
    }

    /**
     *
     * @param <type> $createdAt
     */
    public function setCreatedAt($createdAt = null)
    {
        if (null === $createdAt) {
          $createdAt = new \DateTime();
        }

        if (!$createdAt instanceof \DateTime) {
            $createdAt = new \DateTime($createdAt);
        }

        $this->createdAt = $createdAt;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }
}
