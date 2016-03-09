<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DictionaryRepository")
 */
class Dictionary
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="dictionaries")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToMany(targetEntity="Word", inversedBy="dictionaries")
     * @ORM\JoinTable(name="DictionariesWord")
     **/
    private $words;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="DictionaryScore", mappedBy="dictionary")
     */
    protected $dictionaryScores;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $originLang;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $lang;

    /**
     * @ORM\Column(type="float")
     */
    protected $score = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $private = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $main = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $groupWord = 0;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    public function getJsonArray()
    {
        $a = array('id' => $this->getId(),
            'wids' => $this->getWids(),
            'bitEmail' => $this->getUser()->getBitEmail(),
            'uid' => $this->getUser()->getId()
        );

        if ($this->getGroupWord()) {
            $a['title'] = $this->getTitle();
            $a['description'] = $this->getDescription();
            $a['author'] = $this->getUser()->getUsername();
        }

        return $a;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->words = new \Doctrine\Common\Collections\ArrayCollection();
        $this->main = 1;
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
     * Set title
     *
     * @param string $title
     *
     * @return Dictionary
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Dictionary
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set originLang
     *
     * @param string $originLang
     *
     * @return Dictionary
     */
    public function setOriginLang($originLang)
    {
        $this->originLang = $originLang;

        return $this;
    }

    /**
     * Get originLang
     *
     * @return string
     */
    public function getOriginLang()
    {
        return $this->originLang;
    }

    /**
     * Set lang
     *
     * @param string $lang
     *
     * @return Dictionary
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
     * Set score
     *
     * @param float $score
     *
     * @return Dictionary
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set private
     *
     * @param integer $private
     *
     * @return Dictionary
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return integer
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set groupWord
     *
     * @param integer $groupWord
     *
     * @return Dictionary
     */
    public function setGroupWord($groupWord)
    {
        $this->groupWord = $groupWord;

        return $this;
    }

    /**
     * Get groupWord
     *
     * @return integer
     */
    public function getGroupWord()
    {
        return $this->groupWord;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Dictionary
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
     * @param \AppBundle\Entity\User $user
     *
     * @return Dictionary
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add word
     *
     * @param \AppBundle\Entity\Word $word
     *
     * @return Dictionary
     */
    public function addWord(\AppBundle\Entity\Word $word)
    {
        $this->words[] = $word;

        return $this;
    }

    /**
     * Remove word
     *
     * @param \AppBundle\Entity\Word $word
     */
    public function removeWord(\AppBundle\Entity\Word $word)
    {
        $this->words->removeElement($word);
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
    
    public function getWids()
    {
        $a = array();
        foreach($this->words as $w) {
            $a[] = $w->getId();
        }
        
        return $a;
    }

    /**
     * Add dictionaryScore
     *
     * @param \AppBundle\Entity\DictionaryScore $dictionaryScore
     *
     * @return Dictionary
     */
    public function addDictionaryScore(\AppBundle\Entity\DictionaryScore $dictionaryScore)
    {
        $this->dictionaryScores[] = $dictionaryScore;

        return $this;
    }

    /**
     * Remove dictionaryScore
     *
     * @param \AppBundle\Entity\DictionaryScore $dictionaryScore
     */
    public function removeDictionaryScore(\AppBundle\Entity\DictionaryScore $dictionaryScore)
    {
        $this->dictionaryScores->removeElement($dictionaryScore);
    }

    /**
     * Get dictionaryScores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDictionaryScores()
    {
        return $this->dictionaryScores;
    }

    /**
     * Set main
     *
     * @param integer $main
     *
     * @return Dictionary
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return integer
     */
    public function getMain()
    {
        return $this->main;
    }
}
