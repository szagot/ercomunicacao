<?php
/**
 * Produtos
 *
 * @author Daniel Bispo <szagot@gmail.com.br>
 * @since  2017-09-10
 */

namespace EP\Entities\Products;

/**
 * @Entity(repositoryClass="EP\Repositories\Products\ProductRepository")
 * @HasLifecycleCallbacks
 * @Table(name="Products")
 */
class Product
{
    /**
     * @Id
     * @Column(type="string", length=20)
     * @var string
     */
    protected $sku;

    /**
     * @Column(type="string", length=250)
     * @var string
     */
    protected $name;

    /**
     * @Column(type="integer",options={"unsigned":true, "default":0}, nullable=true)
     * @var null|int
     */
    protected $stock;

    /**
     * @Column(type="decimal", precision=10, scale=2, options={"unsigned":true, "default":0}, nullable=true)
     * @var null|float
     */
    protected $value;

    /**
     * @ManyToOne(targetEntity="Brand")
     * @JoinColumn(name="brand", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @var null|Brand
     */
    protected $brand;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     *
     * @return Product
     */
    public function setSku(string $sku): Product
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param int|null $stock
     *
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param float|null $value
     *
     * @return Product
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return null|Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     *
     * @return Product
     */
    public function setBrand(Brand $brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Faz a atualização das datas
     *
     * @PrePersist
     * @PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

}