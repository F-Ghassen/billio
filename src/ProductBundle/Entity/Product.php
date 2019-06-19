<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ProductRepository")
 * @Vich\Uploadable
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;


    /**
     * @ORM\Column(type="boolean", length=255, nullable=true)
     * @var bool
     */
    private $enabled;

    /**
     * @ORM\Column(type="boolean", length=255, nullable=true)
     * @var bool
     */
    private $highlight;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image1;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image1")
     * @var File
     */
    private $imageFile1;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image2;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image2")
     * @var File
     */
    private $imageFile2;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image3;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image3")
     * @var File
     */
    private $imageFile3;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return bool
     */
    public function isHighlight()
    {
        return $this->highlight;
    }

    /**
     * @param bool $highlight
     */
    public function setHighlight($highlight)
    {
        $this->highlight = $highlight;
    }

    /**
     * @return string
     */
    public function getImage1()
    {
        return $this->image1;
    }

    /**
     * @param string $image1
     */
    public function setImage1($image1)
    {
        $this->image1 = $image1;
    }

    /**
     * @return File
     */
    public function getImageFile1()
    {
        return $this->imageFile1;
    }

    /**
     * @param File $imageFile1
     */
    public function setImageFile1($imageFile1)
    {
        $this->imageFile1 = $imageFile1;
    }

    /**
     * @return string
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * @param string $image2
     */
    public function setImage2($image2)
    {
        $this->image2 = $image2;
    }

    /**
     * @return File
     */
    public function getImageFile2()
    {
        return $this->imageFile2;
    }

    /**
     * @param File $imageFile2
     */
    public function setImageFile2($imageFile2)
    {
        $this->imageFile2 = $imageFile2;
    }

    /**
     * @return string
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * @param string $image3
     */
    public function setImage3($image3)
    {
        $this->image3 = $image3;
    }

    /**
     * @return File
     */
    public function getImageFile3()
    {
        return $this->imageFile3;
    }

    /**
     * @param File $imageFile3
     */
    public function setImageFile3($imageFile3)
    {
        $this->imageFile3 = $imageFile3;
    }
}

