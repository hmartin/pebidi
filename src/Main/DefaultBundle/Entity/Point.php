<?php
namespace Main\DefaultBundle\Entity;

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
     * @ORM\ManyToOne(targetEntity="Test", inversedBy="points")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id")
     */
    protected $test;

    /**
     * @ORM\ManyToOne(targetEntity="Word", inversedBy="translations",cascade={"persist"})
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
     * Set test
     *
     * @param \Main\DefaultBundle\Entity\Test $test
     * @return Point
     */
    public function setTest(\Main\DefaultBundle\Entity\Test $test = null)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * Get test
     *
     * @return \Main\DefaultBundle\Entity\Test 
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Set word
     *
     * @param \Main\DefaultBundle\Entity\Word $word
     * @return Point
     */
    public function setWord(\Main\DefaultBundle\Entity\Word $word = null)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return \Main\DefaultBundle\Entity\Word 
     */
    public function getWord()
    {
        return $this->word;
    }
}
