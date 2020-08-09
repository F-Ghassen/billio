<?php

namespace OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OrderInfo
 *
 * @ORM\Table(name="order_info")
 * @ORM\Entity(repositoryClass="OrderBundle\Repository\OrderInfoRepository")
 */
class OrderInfo
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
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z\sأ-ي]*$/",
     *     message="Invalid Name",
     *     )
     *
     * @ORM\Column(name="customerFirstName", type="string", length=255)
     */
    private $customerFirstName;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z\sأ-ي]*$/",
     *     message="Invalid Last Name",
     * )
     *
     * @ORM\Column(name="customerLastName", type="string", length=255)
     */
    private $customerLastName;

    /**
     * @var int
     *
     * @Assert\Regex(
     *     pattern="/\d{8}/",
     *     htmlPattern="/\d{8}/",
     *     message="Invalid Phone Number",
     * )
     *
     * @ORM\Column(name="customerPhone", type="integer")
     */
    private $customerPhone;

    /**
     * @var string
     *
     * @Assert\Email(
     *     message = "'{{ value }}' is not a valid email.",
     * )
     *
     * @ORM\Column(name="customerEmail", type="string", length=255, nullable=true)
     */
    private $customerEmail;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="City can't be blank",
     * )
     *
     * @ORM\Column(name="customer_city", type="string", length=255, nullable=true)
     */
    private $customerCity;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Address can't be blank",
     * )
     *
     * @ORM\Column(name="customer_address", type="string", length=255, nullable=true)
     */
    private $customerAddress;

    /**
     * @var string
     *
     *
     * @Assert\Regex(
     *     pattern="/\d{4}/",
     *     message="Invalid Code",
     * )
     *
     * @ORM\Column(name="customer_postal_code", type="string", length=255, nullable=true)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="promo", type="string", length=255, nullable=true)
     */
    private $promo;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255, nullable=true)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_method", type="string", length=255, nullable=true)
     */
    private $payment_method;

    /**
     * @ORM\Column(type="boolean", length=255, nullable=true)
     * @var bool
     */
    private $enabled;

    public $NumSite;
    public $Password;
    public $orderID;

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
     * Set customerFirstName
     *
     * @param string $customerFirstName
     *
     * @return OrderInfo
     */
    public function setCustomerFirstName($customerFirstName)
    {
        $this->customerFirstName = $customerFirstName;

        return $this;
    }

    /**
     * Get customerFirstName
     *
     * @return string
     */
    public function getCustomerFirstName()
    {
        return $this->customerFirstName;
    }

    /**
     * Set customerLastName
     *
     * @param string $customerLastName
     *
     * @return OrderInfo
     */
    public function setCustomerLastName($customerLastName)
    {
        $this->customerLastName = $customerLastName;

        return $this;
    }

    /**
     * Get customerLastName
     *
     * @return string
     */
    public function getCustomerLastName()
    {
        return $this->customerLastName;
    }

    /**
     * Set customerPhone
     *
     * @param integer $customerPhone
     *
     * @return OrderInfo
     */
    public function setCustomerPhone($customerPhone)
    {
        $this->customerPhone = $customerPhone;

        return $this;
    }

    /**
     * Get customerPhone
     *
     * @return int
     */
    public function getCustomerPhone()
    {
        return $this->customerPhone;
    }

    /**
     * Set customerEmail
     *
     * @param string $customerEmail
     *
     * @return OrderInfo
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    /**
     * Get customerEmail
     *
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * @return string
     */
    public function getCustomerCity()
    {
        return $this->customerCity;
    }

    /**
     * @param string $customerCity
     */
    public function setCustomerCity($customerCity)
    {
        $this->customerCity = $customerCity;
    }

    /**
     * @return string
     */
    public function getCustomerAddress()
    {
        return $this->customerAddress;
    }

    /**
     * @param string $customerAddress
     */
    public function setCustomerAddress($customerAddress)
    {
        $this->customerAddress = $customerAddress;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * @param string $promo
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;
    }

    /**
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * @param string $pays
     */
    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    /**
     * @param string $payment_method
     */
    public function setPaymentMethod($payment_method)
    {
        $this->payment_method = $payment_method;
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
}

