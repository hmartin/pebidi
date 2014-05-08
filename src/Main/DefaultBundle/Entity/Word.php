<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(indexes={@ORM\Index(name="word_idx", columns={"word"})})
 * @ORM\Entity(repositoryClass="Main\DefaultBundle\Repository\WordRepository")
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
    protected $lang;

    /**
     * @ORM\Column(type="integer")
     */
    protected $certified = 0;

    /**
     * @ORM\ManyToMany(targetEntity="Dictionary", mappedBy="words")
     **/
    private $dictionaries;

    /**
     * @ORM\OneToMany(targetEntity="Translation", mappedBy="word",cascade={"persist"})
     */
    protected $translations;

    /**
     * @ORM\OneToMany(targetEntity="Definition", mappedBy="word",cascade={"persist"})
     */
    protected $definitions;

    /**
     * @ORM\OneToMany(targetEntity="Point", mappedBy="word",cascade={"persist"})
     */
    protected $points;

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
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Add translations
     *
     * @param \Main\DefaultBundle\Entity\Translation $translations
     * @return Word
     */
    public function addTranslation(\Main\DefaultBundle\Entity\Translation $translations)
    {
        $this->translations[] = $translations;

        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Main\DefaultBundle\Entity\Translation $translations
     */
    public function removeTranslation(\Main\DefaultBundle\Entity\Translation $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Add dictionaries
     *
     * @param \Main\DefaultBundle\Entity\Dictionary $dictionaries
     * @return Word
     */
    public function addDictionary(\Main\DefaultBundle\Entity\Dictionary $dictionaries)
    {
        $this->dictionaries[] = $dictionaries;

        return $this;
    }

    /**
     * Remove dictionaries
     *
     * @param \Main\DefaultBundle\Entity\Dictionary $dictionaries
     */
    public function removeDictionary(\Main\DefaultBundle\Entity\Dictionary $dictionaries)
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
     * Set lang
     *
     * @param string $lang
     * @return Word
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string 
     */
    public function getLang()
    {
        return $this->lang;
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
     * Set id
     *
     * @param integer $id
     * @return Word
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Add definitions
     *
     * @param \Main\DefaultBundle\Entity\Definition $definitions
     * @return Word
     */
    public function addDefinition(\Main\DefaultBundle\Entity\Definition $definitions)
    {
        $this->definitions[] = $definitions;

        return $this;
    }

    /**
     * Remove definitions
     *
     * @param \Main\DefaultBundle\Entity\Definition $definitions
     */
    public function removeDefinition(\Main\DefaultBundle\Entity\Definition $definitions)
    {
        $this->definitions->removeElement($definitions);
    }

    /**
     * Get definitions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * Add points
     *
     * @param \Main\DefaultBundle\Entity\Point $points
     * @return Word
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
