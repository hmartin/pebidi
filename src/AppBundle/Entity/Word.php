<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WordRepository")
 */
class Word
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $word;

    /**
     * @ORM\Column(type="string", length=5)
     */
    protected $local;

    /**
     * @ORM\Column(type="integer")
     */
    protected $certified = 0;

    /**
     * @ORM\OneToMany(targetEntity="SubWord", mappedBy="word",cascade={"persist"})
     */
    protected $subWords;


    /**
     * @ORM\ManyToMany(targetEntity="Dictionary", mappedBy="words")
     **/
    private $dictionaries;

    /**
     * @ORM\ManyToMany(targetEntity="Test", mappedBy="words")
     **/
    private $testsWords;

    /**
     * @ORM\OneToMany(targetEntity="Point", mappedBy="word",cascade={"persist"})
     */
    protected $points;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    public function __toString() {
        return $this->word;
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
     * Set word
     *
     * @param string $word
     * @return Word
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string 
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set local
     *
     * @param string $local
     * @return Word
     */
    public function setLocal($local)
    {
        $this->local = $local;

        return $this;
    }

    /**
     * Get local
     *
     * @return string 
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Set certified
     *
     * @param integer $certified
     * @return Word
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
     * Set created
     *
     * @param \DateTime $created
     * @return Word
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
     * Add dictionaries
     *
     * @param \AppBundle\Entity\Dictionary $dictionaries
     * @return Word
     */
    public function addDictionary(\AppBundle\Entity\Dictionary $dictionaries)
    {
        $this->dictionaries[] = $dictionaries;

        return $this;
    }

    /**
     * Remove dictionaries
     *
     * @param \AppBundle\Entity\Dictionary $dictionaries
     */
    public function removeDictionary(\AppBundle\Entity\Dictionary $dictionaries)
    {
        $this->dictionaries->removeElement($dictionaries);
    }

    /**
     * Get dictionaries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDictionaries()
    {
        return $this->dictionaries;
    }

    /**
     * Add points
     *
     * @param \AppBundle\Entity\Point $points
     * @return Word
     */
    public function addPoint(\AppBundle\Entity\Point $points)
    {
        $this->points[] = $points;

        return $this;
    }

    /**
     * Remove points
     *
     * @param \AppBundle\Entity\Point $points
     */
    public function removePoint(\AppBundle\Entity\Point $points)
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

    /**
     * Add testsWords
     *
     * @param \AppBundle\Entity\Test $testsWords
     * @return Word
     */
    public function addTestsWord(\AppBundle\Entity\Test $testsWords)
    {
        $this->testsWords[] = $testsWords;

        return $this;
    }

    /**
     * Remove testsWords
     *
     * @param \AppBundle\Entity\Test $testsWords
     */
    public function removeTestsWord(\AppBundle\Entity\Test $testsWords)
    {
        $this->testsWords->removeElement($testsWords);
    }

    /**
     * Get testsWords
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTestsWords()
    {
        return $this->testsWords;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dictionaries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->testsWords = new \Doctrine\Common\Collections\ArrayCollection();
        $this->points = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Word
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add wordType
     *
     * @param \AppBundle\Entity\WordType $wordType
     *
     * @return Word
     */
    public function addWordType(\AppBundle\Entity\WordType $wordType)
    {
        $this->wordTypes[] = $wordType;

        return $this;
    }

    /**
     * Remove wordType
     *
     * @param \AppBundle\Entity\WordType $wordType
     */
    public function removeWordType(\AppBundle\Entity\WordType $wordType)
    {
        $this->wordTypes->removeElement($wordType);
    }

    /**
     * Get wordTypes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWordTypes()
    {
        return $this->wordTypes;
    }
}
