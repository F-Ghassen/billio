<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductVariation
 *
 * @ORM\Table(name="product_variation")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ProductVariationRepository")
 */
class ProductVariation
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
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="s", type="string", length=255)
     */
    private $S;

    /**
     * @var string
     *
     * @ORM\Column(name="m", type="string", length=255)
     */
    private $M;

    /**
     * @var string
     *
     * @ORM\Column(name="L", type="string", length=255)
     */
    private $L;

    /**
     * @var string
     *
     * @ORM\Column(name="xl", type="string", length=255)
     */
    private $XL;

    /**
     * @var string
     *
     * @ORM\Column(name="xxl", type="string", length=255)
     */
    private $XXL;

    /**
     * @ORM\OneToMany(targetEntity="ProductBundle\Entity\ProductImage", mappedBy="productVariation", cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Product", inversedBy="variations")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function addImage(ProductImage $image)
    {
        $image->setProductVariation($this);
        $this->images[] = $image;
        return $this;
    }

    public function removeImage(ProductImage $image)
    {
        return$this->images->removeElement($image);
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return string
     */
    public function getS()
    {
        return $this->S;
    }

    /**
     * @param string $S
     */
    public function setS($S)
    {
        $this->S = $S;
    }

    /**
     * @return string
     */
    public function getM()
    {
        return $this->M;
    }

    /**
     * @param string $M
     */
    public function setM($M)
    {
        $this->M = $M;
    }

    /**
     * @return string
     */
    public function getL()
    {
        return $this->L;
    }

    /**
     * @param string $L
     */
    public function setL($L)
    {
        $this->L = $L;
    }

    /**
     * @return string
     */
    public function getXL()
    {
        return $this->XL;
    }

    /**
     * @param string $XL
     */
    public function setXL($XL)
    {
        $this->XL = $XL;
    }

    /**
     * @return string
     */
    public function getXXL()
    {
        return $this->XXL;
    }

    /**
     * @param string $XXL
     */
    public function setXXL($XXL)
    {
        $this->XXL = $XXL;
    }
}

