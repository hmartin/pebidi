<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Main\DefaultBundle\Repository\DictionaryRepository")
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
     * @ORM\OneToMany(targetEntity="DictionaryScore", mappedBy="dictionary")
     */
    protected $dictionaryScores;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $originLang;

    /**
     * @ORM\Column(type="string", length=255)
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
    
    protected $userScore = 0;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    public function getJsonArray() {
        return array( 'id' => $this->getConvertId(),
        'countWord' => count($this->getWords()),
        'bitEmail' => $this->getUser()->getBitEmail(),
        'score' => $this->getUserScore()
                    );
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
    
    public function getConvertId() {
        return base_convert($this->id, 10, 23);
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
     * @param \Main\DefaultBundle\Entity\User $user
     *
     * @return Dictionary
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
     * Add word
     *
     * @param \Main\DefaultBundle\Entity\Word $word
     *
     * @return Dictionary
     */
    public function addWord(\Main\DefaultBundle\Entity\Word $word)
    {
        $this->words[] = $word;

        return $this;
    }

    /**
     * Remove word
     *
     * @param \Main\DefaultBundle\Entity\Word $word
     */
    public function removeWord(\Main\DefaultBundle\Entity\Word $word)
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

    /**
     * Add dictionaryScore
     *
     * @param \Main\DefaultBundle\Entity\DictionaryScore $dictionaryScore
     *
     * @return Dictionary
     */
    public function addDictionaryScore(\Main\DefaultBundle\Entity\DictionaryScore $dictionaryScore)
    {
        $this->dictionaryScores[] = $dictionaryScore;

        return $this;
    }

    /**
     * Remove dictionaryScore
     *
     * @param \Main\DefaultBundle\Entity\DictionaryScore $dictionaryScore
     */
    public function removeDictionaryScore(\Main\DefaultBundle\Entity\DictionaryScore $dictionaryScore)
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
}
