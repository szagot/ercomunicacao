<?php
/**
 * Entidade Users
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

namespace EP\Entities\Users;

/**
 * @Entity(repositoryClass="EP\Repositories\Users\UserRepository")
 * @HasLifecycleCallbacks
 * @Table(name="Users")
 */
class User
{
    /**
     * @Id
     * @Column(type="string", length=20)
     * @var string
     */
    protected $code;

    /**
     * @Column(length=100)
     * @var string
     */
    protected $name;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $pass;

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
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return User
     */
    public function setCode(string $code): User
    {
        $this->code = $code;

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
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     *
     * @return User
     */
    public function setPass(string $pass): User
    {
        $this->pass = passHash($pass);

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