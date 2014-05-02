<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Test
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Dictionary", inversedBy="tests",cascade={"persist"})
     * @ORM\JoinColumn(name="dictionary_id", referencedColumnName="id")
     */
    protected $dictionary;

    /**
     * @ORM\OneToMany(targetEntity="Point", mappedBy="test")
     */
    protected $points;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

//type, score, nbQuestion
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
     * Set created
     *
     * @param \DateTime $created
     * @return Test
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
     * Set dictionary
     *
     * @param \Main\DefaultBundle\Entity\Dictionary $dictionary
     * @return Test
     */
    public function setDictionary(\Main\DefaultBundle\Entity\Dictionary $dictionary = null)
    {
        $this->dictionary = $dictionary;

        return $this;
    }

    /**
     * Get dictionary
     *
     * @return \Main\DefaultBundle\Entity\Dictionary 
     */
    public function getDictionary()
    {
        return $this->dictionary;
    }

    /**
     * Add points
     *
     * @param \Main\DefaultBundle\Entity\Point $points
     * @return Test
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
