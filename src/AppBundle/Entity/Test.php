<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TestRepository")
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
     * @ORM\ManyToMany(targetEntity="Word", inversedBy="testsWords")
     * @ORM\JoinTable(name="TestWord")
     **/
    private $words;

    /**
     * @ORM\ManyToOne(targetEntity="Dictionary", inversedBy="dictionaryScores",cascade={"persist"})
     * @ORM\JoinColumn(name="dictionary_id", referencedColumnName="id", nullable=true)
     */
    protected $dictionary;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tests")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $creator;

    /**
     * @ORM\OneToMany(targetEntity="Result", mappedBy="test")
     */
    protected $results;

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
        $this->words = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add words
     *
     * @param \AppBundle\Entity\Word $words
     * @return Test
     */
    public function addWord(\AppBundle\Entity\Word $words)
    {
        $this->words[] = $words;

        return $this;
    }


    /**
     * Add words
     *
     * @param \AppBundle\Entity\Word $words
     * @return Test
     */
    public function addWords(array $words)
    {
        $this->words = $words;

        return $this;
    }

    /**
     * Remove words
     *
     * @param \AppBundle\Entity\Word $words
     */
    public function removeWord(\AppBundle\Entity\Word $words)
    {
        $this->words->removeElement($words);
    }

    /**
     * Get words
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * Set creator
     *
     * @param \AppBundle\Entity\User $creator
     * @return Test
     */
    public function setCreator(\AppBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \AppBundle\Entity\User 
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Add results
     *
     * @param \AppBundle\Entity\Result $results
     * @return Test
     */
    public function addResult(\AppBundle\Entity\Result $results)
    {
        $this->results[] = $results;

        return $this;
    }

    /**
     * Remove results
     *
     * @param \AppBundle\Entity\Result $results
     */
    public function removeResult(\AppBundle\Entity\Result $results)
    {
        $this->results->removeElement($results);
    }

    /**
     * Get results
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set dictionary
     *
     * @param \AppBundle\Entity\Dictionary $dictionary
     *
     * @return Test
     */
    public function setDictionary(\AppBundle\Entity\Dictionary $dictionary = null)
    {
        $this->dictionary = $dictionary;

        return $this;
    }

    /**
     * Get dictionary
     *
     * @return \AppBundle\Entity\Dictionary
     */
    public function getDictionary()
    {
        return $this->dictionary;
    }
}
