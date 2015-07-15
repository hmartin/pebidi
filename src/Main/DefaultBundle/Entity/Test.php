<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Main\DefaultBundle\Repository\TestRepository")
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
     * @param \Main\DefaultBundle\Entity\Word $words
     * @return Test
     */
    public function addWord(\Main\DefaultBundle\Entity\Word $words)
    {
        $this->words[] = $words;

        return $this;
    }


    /**
     * Add words
     *
     * @param \Main\DefaultBundle\Entity\Word $words
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
     * @param \Main\DefaultBundle\Entity\Word $words
     */
    public function removeWord(\Main\DefaultBundle\Entity\Word $words)
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
     * @param \Main\DefaultBundle\Entity\User $creator
     * @return Test
     */
    public function setCreator(\Main\DefaultBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \Main\DefaultBundle\Entity\User 
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Add results
     *
     * @param \Main\DefaultBundle\Entity\Result $results
     * @return Test
     */
    public function addResult(\Main\DefaultBundle\Entity\Result $results)
    {
        $this->results[] = $results;

        return $this;
    }

    /**
     * Remove results
     *
     * @param \Main\DefaultBundle\Entity\Result $results
     */
    public function removeResult(\Main\DefaultBundle\Entity\Result $results)
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
     * @param \Main\DefaultBundle\Entity\Dictionary $dictionary
     *
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
}
