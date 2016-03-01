<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(fields={"word1","word2"})
 */
class Ww
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="SubWord",cascade={"persist"})
     * @ORM\JoinColumn()
     */
    protected $word1;

    /**
     * @ORM\ManyToOne(targetEntity="SubWord", cascade={"persist"})
     * @ORM\JoinColumn()
     */
    protected $word2;

    /**
     * @ORM\Column(type="integer")
     */
    protected $certified = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $additional = 0;

    /**
     * @ORM\Column(type="float")
     */
    protected $priority;


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
     * Set certified
     *
     * @param integer $certified
     *
     * @return Ww
     */
    public function setCertified($certified)
    {
        $this->certified = $certified;

        return $this;
    }

    /**
     * Get certified
     *
     * @return integer
     */
    public function getCertified()
    {
        return $this->certified;
    }

    /**
     * Set priority
     *
     * @param float $priority
     *
     * @return Ww
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return float
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set word1
     *
     * @param \AppBundle\Entity\SubWord $word1
     *
     * @return Ww
     */
    public function setWord1(\AppBundle\Entity\SubWord $word1 = null)
    {
        $this->word1 = $word1;

        return $this;
    }

    /**
     * Get word1
     *
     * @return \AppBundle\Entity\SubWord
     */
    public function getWord1()
    {
        return $this->word1;
    }

    /**
     * Set word2
     *
     * @param \AppBundle\Entity\SubWord $word2
     *
     * @return Ww
     */
    public function setWord2(\AppBundle\Entity\SubWord $word2 = null)
    {
        $this->word2 = $word2;

        return $this;
    }

    /**
     * Get word2
     *
     * @return \AppBundle\Entity\SubWord
     */
    public function getWord2()
    {
        return $this->word2;
    }

    /**
     * Set additional
     *
     * @param integer $additional
     *
     * @return Ww
     */
    public function setAdditional($additional)
    {
        $this->additional = $additional;

        return $this;
    }

    /**
     * Get additional
     *
     * @return integer
     */
    public function getAdditional()
    {
        return $this->additional;
    }
}
