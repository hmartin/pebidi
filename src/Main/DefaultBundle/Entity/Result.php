<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Result
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="results")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Test", inversedBy="results")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id")
     */
    protected $test;

    /**
     * @ORM\OneToMany(targetEntity="Point", mappedBy="result")
     */
    protected $points;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $score;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->points = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set score
     *
     * @param integer $score
     * @return Result
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Result
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
     * Set user
     *
     * @param \Main\DefaultBundle\Entity\User $user
     * @return Result
     */
    public function setUser(\Main\DefaultBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Main\DefaultBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set test
     *
     * @param \Main\DefaultBundle\Entity\Test $test
     * @return Result
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
     * Add points
     *
     * @param \Main\DefaultBundle\Entity\Point $points
     * @return Result
     */
    public function addPoint(\Main\DefaultBundle\Entity\Point $points)
    {
        $this->points[] = $points;

        return $this;
    }

    /**
     * Remove points
     *
     * @param \Main\DefaultBundle\Entity\Point $points
     */
    public function removePoint(\Main\DefaultBundle\Entity\Point $points)
    {
        $this->points->removeElement($points);
    }

    /**
     * Get points
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPoints()
    {
        return $this->points;
    }
}
