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
     * @ORM\OneToMany(targetEntity="Test", mappedBy="dictionary")
     */
    protected $tests;

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
     * Set lang
     *
     * @param string $lang
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
     * Set created
     *
     * @param \DateTime $created
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
     * Add words
     *
     * @param \Main\DefaultBundle\Entity\Word $words
     * @return Dictionary
     */
    public function addWord(\Main\DefaultBundle\Entity\Word $words)
    {
        $this->words[] = $words;

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
     * Set private
     *
     * @param integer $private
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
     * Add tests
     *
     * @param \Main\DefaultBundle\Entity\Test $tests
     * @return Dictionary
     */
    public function addTest(\Main\DefaultBundle\Entity\Test $tests)
    {
        $this->tests[] = $tests;

        return $this;
    }

    /**
     * Remove tests
     *
     * @param \Main\DefaultBundle\Entity\Test $tests
     */
    public function removeTest(\Main\DefaultBundle\Entity\Test $tests)
    {
        $this->tests->removeElement($tests);
    }

    /**
     * Get tests
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * Set originLang
     *
     * @param string $originLang
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
     * Set score
     *
     * @param integer $score
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
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    public function setUserScore($userScore)
    {
        $this->userScore = $userScore;

        return $this;
    }

    public function getUserScore()
    {
        return $this->userScore;
    }

    /**
     * Add dictionaryScores
     *
     * @param \Main\DefaultBundle\Entity\DictionaryScore $dictionaryScores
     * @return Dictionary
     */
    public function addDictionaryScore(\Main\DefaultBundle\Entity\DictionaryScore $dictionaryScores)
    {
        $this->dictionaryScores[] = $dictionaryScores;

        return $this;
    }

    /**
     * Remove dictionaryScores
     *
     * @param \Main\DefaultBundle\Entity\DictionaryScore $dictionaryScores
     */
    public function removeDictionaryScore(\Main\DefaultBundle\Entity\DictionaryScore $dictionaryScores)
    {
        $this->dictionaryScores->removeElement($dictionaryScores);
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
