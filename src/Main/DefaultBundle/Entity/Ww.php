<?php
namespace Main\DefaultBundle\Entity;

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
     * @ORM\ManyToOne(targetEntity="Word",cascade={"persist"})
     * @ORM\JoinColumn()
     */
    protected $word1;

    /**
     * @ORM\ManyToOne(targetEntity="Word", cascade={"persist"})
     * @ORM\JoinColumn()
     */
    protected $word2;

    /**
     * @ORM\ManyToMany(targetEntity="Sense")
     * @ORM\JoinTable(name="WwSenses")
     */
    protected $senses;

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
     * Set word1
     *
     * @param \Main\DefaultBundle\Entity\Word $word1
     * @return Ww
     */
    public function setWord1(\Main\DefaultBundle\Entity\Word $word1 = null)
    {
        $this->word1 = $word1;

        return $this;
    }

    /**
     * Get word1
     *
     * @return \Main\DefaultBundle\Entity\Word 
     */
    public function getWord1()
    {
        return $this->word1;
    }

    /**
     * Set word2
     *
     * @param \Main\DefaultBundle\Entity\Word $word2
     * @return Ww
     */
    public function setWord2(\Main\DefaultBundle\Entity\Word $word2 = null)
    {
        $this->word2 = $word2;

        return $this;
    }

    /**
     * Get word2
     *
     * @return \Main\DefaultBundle\Entity\Word 
     */
    public function getWord2()
    {
        return $this->word2;
    }

    /**
     * Add senses
     *
     * @param \Main\DefaultBundle\Entity\Sense $senses
     * @return Ww
     */
    public function addSense(\Main\DefaultBundle\Entity\Sense $senses)
    {
        $this->senses[] = $senses;

        return $this;
    }

    /**
     * Remove senses
     *
     * @param \Main\DefaultBundle\Entity\Sense $senses
     */
    public function removeSense(\Main\DefaultBundle\Entity\Sense $senses)
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
}
