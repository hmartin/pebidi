<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"word", "local"})})
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
     * @ORM\Column(type="integer")
     */
    protected $disabled = 0;

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
     * Constructor
     */
    public function __construct()
    {
        $this->subWords = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dictionaries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->testsWords = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set word
     *
     * @param string $word
     *
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
     *
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
     *
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
     *
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
     * Add subWord
     *
     * @param \AppBundle\Entity\SubWord $subWord
     *
     * @return Word
     */
    public function addSubWord(\AppBundle\Entity\SubWord $subWord)
    {
        $this->subWords[] = $subWord;

        return $this;
    }

    /**
     * Remove subWord
     *
     * @param \AppBundle\Entity\SubWord $subWord
     */
    public function removeSubWord(\AppBundle\Entity\SubWord $subWord)
    {
        $this->subWords->removeElement($subWord);
    }

    /**
     * Get subWords
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubWords()
    {
        return $this->subWords;
    }

    /**
     * Add dictionary
     *
     * @param \AppBundle\Entity\Dictionary $dictionary
     *
     * @return Word
     */
    public function addDictionary(\AppBundle\Entity\Dictionary $dictionary)
    {
        $this->dictionaries[] = $dictionary;

        return $this;
    }

    /**
     * Remove dictionary
     *
     * @param \AppBundle\Entity\Dictionary $dictionary
     */
    public function removeDictionary(\AppBundle\Entity\Dictionary $dictionary)
    {
        $this->dictionaries->removeElement($dictionary);
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
     * Add testsWord
     *
     * @param \AppBundle\Entity\Test $testsWord
     *
     * @return Word
     */
    public function addTestsWord(\AppBundle\Entity\Test $testsWord)
    {
        $this->testsWords[] = $testsWord;

        return $this;
    }

    /**
     * Remove testsWord
     *
     * @param \AppBundle\Entity\Test $testsWord
     */
    public function removeTestsWord(\AppBundle\Entity\Test $testsWord)
    {
        $this->testsWords->removeElement($testsWord);
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
     * Add point
     *
     * @param \AppBundle\Entity\Point $point
     *
     * @return Word
     */
    public function addPoint(\AppBundle\Entity\Point $point)
    {
        $this->points[] = $point;

        return $this;
    }

    /**
     * Remove point
     *
     * @param \AppBundle\Entity\Point $point
     */
    public function removePoint(\AppBundle\Entity\Point $point)
    {
        $this->points->removeElement($point);
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
     * Set disabled
     *
     * @param integer $disabled
     *
     * @return Word
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled
     *
     * @return integer
     */
    public function getDisabled()
    {
        return $this->disabled;
    }
}
