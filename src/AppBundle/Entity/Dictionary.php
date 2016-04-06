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
     * @ORM\OneToMany(targetEntity="DictionaryWord", mappedBy="dictionary")
     **/
    private $dictionaryWords;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     */
    protected $title;

    /**
     * @Gedmo\Slug(fields={"id", "title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     */
    protected $originLang;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
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
     * @ORM\Column(type="integer")
     */
    protected $disabled = 0;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    public function getJsonArray()
    {
        $a = ['id' => $this->getId(),
            'wids' => $this->getWids(),
            'uid' => $this->getUser()->getId(),
            'group' => $this->getGroupWord(),
            'author' => $this->getUser()->getUsername()
        ];

        if ($this->getGroupWord()) {
            $a['title'] = $this->getTitle();
            $a['description'] = $this->getDescription();
            $a['slug'] = $this->getSlug();
        }

        return $a;
    }
    
    public function transformToGroup($title, $description, $private)
    {
        $this->setGroupWord(1);
        $this->setMain(0);
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setPrivate($private);
    }

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
    
    public function getWids()
    {
        $a = array();
        if ($this->dictionaryWords) {
            foreach($this->dictionaryWords as $w) {
                $a[] = $w->getWord()->getId();
            }
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

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Dictionary
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add dictionaryWord
     *
     * @param \AppBundle\Entity\DictionaryWord $dictionaryWord
     *
     * @return Dictionary
     */
    public function addDictionaryWord(\AppBundle\Entity\DictionaryWord $dictionaryWord)
    {
        $this->dictionaryWords[] = $dictionaryWord;

        return $this;
    }

    /**
     * Remove dictionaryWord
     *
     * @param \AppBundle\Entity\DictionaryWord $dictionaryWord
     */
    public function removeDictionaryWord(\AppBundle\Entity\DictionaryWord $dictionaryWord)
    {
        $this->dictionaryWords->removeElement($dictionaryWord);
    }

    /**
     * Get dictionaryWords
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDictionaryWords()
    {
        return $this->dictionaryWords;
    }

    /**
     * Set disabled
     *
     * @param integer $disabled
     *
     * @return Dictionary
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
