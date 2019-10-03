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
     * @ORM\Column(name="s", type="string", length=255, nullable=true)
     */
    private $S;

    /**
     * @var string
     *
     * @ORM\Column(name="m", type="string", length=255, nullable=true)
     */
    private $M;

    /**
     * @var string
     *
     * @ORM\Column(name="L", type="string", length=255, nullable=true)
     */
    private $L;

    /**
     * @var string
     *
     * @ORM\Column(name="xl", type="string", length=255, nullable=true)
     */
    private $XL;

    /**
     * @var string
     *
     * @ORM\Column(name="xxl", type="string", length=255, nullable=true)
     */
    private $XXL;

    /**
     * @ORM\Column(name="size_jean_29", type="string", length=255, nullable=true)
     */
    private $size_jean_29;

    /**
     * @ORM\Column(name="size_jean_30", type="string", length=255, nullable=true)
     */
    private $size_jean_30;

    /**
     * @ORM\Column(name="size_jean_31", type="string", length=255, nullable=true)
     */
    private $size_jean_31;

    /**
     * @ORM\Column(name="size_jean_32", type="string", length=255, nullable=true)
     */
    private $size_jean_32;

    /**
     * @ORM\Column(name="size_jean_33", type="string", length=255, nullable=true)
     */
    private $size_jean_33;

    /**
     * @ORM\Column(name="size_jean_34", type="string", length=255, nullable=true)
     */
    private $size_jean_34;

    /**
     * @ORM\Column(name="size_jean_36", type="string", length=255, nullable=true)
     */
    private $size_jean_36;

    /**
     * @ORM\Column(name="size_jean_38", type="string", length=255, nullable=true)
     */
    private $size_jean_38;

    /**
     * @ORM\Column(name="size_moc_40", type="string", length=255, nullable=true)
     */
    private $size_moc_40;

    /**
     * @ORM\Column(name="size_moc_41", type="string", length=255, nullable=true)
     */
    private $size_moc_41;

    /**
     * @ORM\Column(name="size_moc_42", type="string", length=255, nullable=true)
     */
    private $size_moc_42;

    /**
     * @ORM\Column(name="size_moc_43", type="string", length=255, nullable=true)
     */
    private $size_moc_43;

    /**
     * @ORM\Column(name="size_moc_44", type="string", length=255, nullable=true)
     */
    private $size_moc_44;

    /**
     * @ORM\Column(name="size_moc_45", type="string", length=255, nullable=true)
     */
    private $size_moc_45;

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
        $this->images = new ArrayCollection();
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

    /**
     * @return mixed
     */
    public function getSizeJean29()
    {
        return $this->size_jean_29;
    }

    /**
     * @param mixed $size_jean_29
     */
    public function setSizeJean29($size_jean_29)
    {
        $this->size_jean_29 = $size_jean_29;
    }

    /**
     * @return mixed
     */
    public function getSizeJean30()
    {
        return $this->size_jean_30;
    }

    /**
     * @param mixed $size_jean_30
     */
    public function setSizeJean30($size_jean_30)
    {
        $this->size_jean_30 = $size_jean_30;
    }

    /**
     * @return mixed
     */
    public function getSizeJean31()
    {
        return $this->size_jean_31;
    }

    /**
     * @param mixed $size_jean_31
     */
    public function setSizeJean31($size_jean_31)
    {
        $this->size_jean_31 = $size_jean_31;
    }

    /**
     * @return mixed
     */
    public function getSizeJean32()
    {
        return $this->size_jean_32;
    }

    /**
     * @param mixed $size_jean_32
     */
    public function setSizeJean32($size_jean_32)
    {
        $this->size_jean_32 = $size_jean_32;
    }

    /**
     * @return mixed
     */
    public function getSizeJean33()
    {
        return $this->size_jean_33;
    }

    /**
     * @param mixed $size_jean_33
     */
    public function setSizeJean33($size_jean_33)
    {
        $this->size_jean_33 = $size_jean_33;
    }

    /**
     * @return mixed
     */
    public function getSizeJean34()
    {
        return $this->size_jean_34;
    }

    /**
     * @param mixed $size_jean_34
     */
    public function setSizeJean34($size_jean_34)
    {
        $this->size_jean_34 = $size_jean_34;
    }

    /**
     * @return mixed
     */
    public function getSizeJean36()
    {
        return $this->size_jean_36;
    }

    /**
     * @param mixed $size_jean_36
     */
    public function setSizeJean36($size_jean_36)
    {
        $this->size_jean_36 = $size_jean_36;
    }

    /**
     * @return mixed
     */
    public function getSizeJean38()
    {
        return $this->size_jean_38;
    }

    /**
     * @param mixed $size_jean_38
     */
    public function setSizeJean38($size_jean_38)
    {
        $this->size_jean_38 = $size_jean_38;
    }

    /**
     * @return mixed
     */
    public function getSizeMoc40()
    {
        return $this->size_moc_40;
    }

    /**
     * @param mixed $size_moc_40
     */
    public function setSizeMoc40($size_moc_40)
    {
        $this->size_moc_40 = $size_moc_40;
    }

    /**
     * @return mixed
     */
    public function getSizeMoc41()
    {
        return $this->size_moc_41;
    }

    /**
     * @param mixed $size_moc_41
     */
    public function setSizeMoc41($size_moc_41)
    {
        $this->size_moc_41 = $size_moc_41;
    }

    /**
     * @return mixed
     */
    public function getSizeMoc42()
    {
        return $this->size_moc_42;
    }

    /**
     * @param mixed $size_moc_42
     */
    public function setSizeMoc42($size_moc_42)
    {
        $this->size_moc_42 = $size_moc_42;
    }

    /**
     * @return mixed
     */
    public function getSizeMoc43()
    {
        return $this->size_moc_43;
    }

    /**
     * @param mixed $size_moc_43
     */
    public function setSizeMoc43($size_moc_43)
    {
        $this->size_moc_43 = $size_moc_43;
    }

    /**
     * @return mixed
     */
    public function getSizeMoc44()
    {
        return $this->size_moc_44;
    }

    /**
     * @param mixed $size_moc_44
     */
    public function setSizeMoc44($size_moc_44)
    {
        $this->size_moc_44 = $size_moc_44;
    }

    /**
     * @return mixed
     */
    public function getSizeMoc45()
    {
        return $this->size_moc_45;
    }

    /**
     * @param mixed $size_moc_45
     */
    public function setSizeMoc45($size_moc_45)
    {
        $this->size_moc_45 = $size_moc_45;
    }
}

