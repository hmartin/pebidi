<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Point
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Result", inversedBy="points")
     * @ORM\JoinColumn(name="result_id", referencedColumnName="id")
     */
    protected $result;

    /**
     * @ORM\ManyToOne(targetEntity="Word", inversedBy="points",cascade={"persist"})
     * @ORM\JoinColumn(name="word_id", referencedColumnName="id")
     */
    protected $word;

    /**
     * @ORM\Column(type="integer")
     */
    protected $point;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

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
     * Set point
     *
     * @param integer $point
     * @return Point
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return integer 
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Point
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set result
     *
     * @param \AppBundle\Entity\Result $result
     * @return Point
     */
    public function setResult(\AppBundle\Entity\Result $result = null)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return \AppBundle\Entity\Result 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set word
     *
     * @param \AppBundle\Entity\Word $word
     * @return Point
     */
    public function setWord(\AppBundle\Entity\Word $word = null)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return \AppBundle\Entity\Word 
     */
    public function getWord()
    {
        return $this->word;
    }
}
