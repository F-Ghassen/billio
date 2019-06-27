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
}

