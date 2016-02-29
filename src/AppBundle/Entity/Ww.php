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
     * @ORM\ManyToOne(targetEntity="WordType",cascade={"persist"})
     * @ORM\JoinColumn()
     */
    protected $word1;

    /**
     * @ORM\ManyToOne(targetEntity="WordType", cascade={"persist"})
     * @ORM\JoinColumn()
     */
    protected $word2;

    /**
     * @ORM\Column(type="integer")
     */
    protected $certified = 0;

    /**
     * @ORM\Column(type="float")
     */
    protected $priority;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->senses = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Add senses
     *
     * @param \AppBundle\Entity\Sense $senses
     * @return Ww
     */
    public function addSense(\AppBundle\Entity\Sense $senses)
    {
        $this->senses[] = $senses;

        return $this;
    }

    /**
     * Remove senses
     *
     * @param \AppBundle\Entity\Sense $senses
     */
    public function removeSense(\AppBundle\Entity\Sense $senses)
    {
        $this->senses->removeElement($senses);
    }

    /**
     * Get senses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSenses()
    {
        return $this->senses;
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
     * @param integer $priority
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
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set word1
     *
     * @param \AppBundle\Entity\WordType $word1
     *
     * @return Ww
     */
    public function setWord1(\AppBundle\Entity\WordType $word1 = null)
    {
        $this->word1 = $word1;

        return $this;
    }

    /**
     * Get word1
     *
     * @return \AppBundle\Entity\WordType
     */
    public function getWord1()
    {
        return $this->word1;
    }

    /**
     * Set word2
     *
     * @param \AppBundle\Entity\WordType $word2
     *
     * @return Ww
     */
    public function setWord2(\AppBundle\Entity\WordType $word2 = null)
    {
        $this->word2 = $word2;

        return $this;
    }

    /**
     * Get word2
     *
     * @return \AppBundle\Entity\WordType
     */
    public function getWord2()
    {
        return $this->word2;
    }
}
