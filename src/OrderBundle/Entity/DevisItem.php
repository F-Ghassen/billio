<?php

namespace OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DevisItem
 *
 * @ORM\Table(name="devis_item")
 * @ORM\Entity(repositoryClass="OrderBundle\Repository\DevisItemRepository")
 */
class DevisItem
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     *
     * @ORM\Column(name="size", type="text")
     */
    private $size;

    /**
     *
     * @ORM\Column(name="promo", type="text", nullable=true)
     */
    private $promo;

    /**
     * @ORM\ManyToOne(targetEntity="OrderBundle\Entity\Devis", inversedBy="items")
     */
    private $devis;

    /**
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Product", cascade={"persist"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\ProductVariation")
     */
    private $variation;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="price_dollar", type="integer", nullable=true)
     */
    private $price_dollar;

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
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
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
     * @return mixed
     */
    public function getDevis()
    {
        return $this->devis;
    }

    /**
     * @param mixed $devis
     */
    public function setDevis($devis)
    {
        $this->devis = $devis;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getVariation()
    {
        return $this->variation;
    }

    /**
     * @param mixed $variation
     */
    public function setVariation($variation)
    {
        $this->variation = $variation;
    }

    /**
     * @return mixed
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * @param mixed $promo
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getPriceDollar()
    {
        return $this->price_dollar;
    }

    /**
     * @param string $price_dollar
     */
    public function setPriceDollar($price_dollar)
    {
        $this->price_dollar = $price_dollar;
    }
}

