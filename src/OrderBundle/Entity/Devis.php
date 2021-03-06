<?php

namespace OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Devis
 *
 * @ORM\Table(name="devis")
 * @ORM\Entity(repositoryClass="OrderBundle\Repository\DevisRepository")
 */
class Devis
{
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="OrderBundle\Entity\DevisItem", mappedBy="devis", cascade={"persist", "remove"})
     */
    private $items;

    /**
     * @ORM\OneToOne(targetEntity="OrderBundle\Entity\OrderInfo", cascade={"persist", "remove"})
     */
    private $orderInfo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="saleDate", type="datetime")
     */
    private $saleDate;

    /**
     * @ORM\Column(type="boolean", length=255, nullable=true)
     * @var bool
     */
    private $enabled;

    /**
     * @ORM\Column(type="boolean", length=255, options={"default":"0"})
     * @var bool
     */
    private $archived;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $delivery_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $remarque;


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
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    public function addItem(DevisItem $item)
    {
        $item->setDevis($this);
        $this->items[] = $item;
        return $this;
    }

    public function removeItem(DevisItem $item)
    {
        return $this->items->removeElement($item);
    }

    /**
     * @return mixed
     */
    public function getOrderInfo()
    {
        return $this->orderInfo;
    }

    /**
     * @param mixed $orderInfo
     */
    public function setOrderInfo($orderInfo)
    {
        $this->orderInfo = $orderInfo;
    }

    /**
     * @return \DateTime
     */
    public function getSaleDate()
    {
        return $this->saleDate;
    }

    /**
     * @param \DateTime $saleDate
     */
    public function setSaleDate($saleDate)
    {
        $this->saleDate = $saleDate;
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
     * @return bool
     */
    public function isArchived()
    {
        return $this->archived;
    }

    /**
     * @param bool $archived
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getDeliveryDate()
    {
        return $this->delivery_date;
    }

    /**
     * @param string $delivery_date
     */
    public function setDeliveryDate($delivery_date)
    {
        $this->delivery_date = $delivery_date;
    }

    /**
     * @return string
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**
     * @param string $remarque
     */
    public function setRemarque($remarque)
    {
        $this->remarque = $remarque;
    }
}

