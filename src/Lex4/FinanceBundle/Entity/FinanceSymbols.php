<?php

namespace Lex4\FinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FinanceSymbols
 *
 * @ORM\Table(name="finance_symbols")
 * @ORM\Entity
 */
class FinanceSymbols
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Lex4\UserBundle\Entity\User", inversedBy="finance_symbols")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Поле не может быть пустым")
     * @ORM\Column(name="symbol", type="string", length=255, nullable=false)
     */
    private $symbol;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Поле не может быть пустым")
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text", nullable=true)
     */
    private $data;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set symbol
     *
     * @param string $symbol
     * @return FinanceSymbols
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get symbol
     *
     * @return string 
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return FinanceSymbols
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
     * Set data
     *
     * @param string $data
     * @return FinanceSymbols
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set user
     *
     * @param \Lex4\UserBundle\Entity\User $user
     * @return FinanceSymbols
     */
    public function setUser(\Lex4\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Lex4\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
