<?php

/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Alom\Website\MainBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Alom\Website\MainBundle\Entity\BookRepository")
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length="256")
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length="64")
     */
    protected $slug;

    /**
     * @ORM\Column(type="date")
     */
    protected $readAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActive;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length="128", nullable=true)
     */
    protected $illustration;

    /**
     * Upload to process
     *
     * @var Symfony\Component\HttpFoundation\File\UploadedFile
     *
     * @Assert\File(maxSize = "512k", mimeTypes = {
     *     "image/png",
     *     "image/jpeg",
     *     "image/jpg",
     *     "image/gif"
     * })
     */
    protected $illustrationUpload;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getReadAt()
    {
        return $this->readAt;
    }

    public function setReadAt($readAt)
    {
        $this->readAt = $readAt;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    public function getIllustration()
    {
        return $this->illustration;
    }

    public function setIllustration($illustration)
    {
        $this->illustration = $illustration;
    }

    public function hasIllustrationUpload()
    {
        return null !== $this->illustrationUpload;
    }

    public function setIllustrationUpload(UploadedFile $illustrationUpload)
    {
        $this->illustrationUpload = $illustrationUpload;
    }

    public function getIllustrationUpload()
    {
        return $this->illustrationUpload;
    }

    public function getDescriptionAsHtml()
    {
        return str_replace("\n", "<br />", htmlentities($this->description, ENT_QUOTES, 'UTF-8'));
    }

    public function disable()
    {
        $this->setIsActive(false);
    }

    public function enable()
    {
        $this->setIsActive(true);
    }


}
