<?php
/**
 * Marcas
 *
 * @author Daniel Bispo <szagot@gmail.com.br>
 * @since  10/09/2017
 */

namespace EP\Entities\Products;

/**
 * @Entity(repositoryClass="EP\Repositories\Products\BrandRepository")
 * @Table(name="Brands")
 */
class Brand
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @Column(type="string", length=50)
     * @var string
     */
    protected $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Brand
     */
    public function setId(int $id): Brand
    {
        $this->id = $id;

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
     * @return Brand
     */
    public function setName(string $name): Brand
    {
        $this->name = $name;

        return $this;
    }

}