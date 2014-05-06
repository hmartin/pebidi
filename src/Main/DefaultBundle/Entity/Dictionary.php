<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity
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
     * @ORM\OneToMany(targetEntity="Translation", mappedBy="dictionary")
     */
    protected $translations;

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
     * @ORM\Column(type="integer")
     */
    protected $private = 0;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


    public function getJsonArray() {
        return array( 'id' => $this->getConvertId(),
        'countWord' => count($this->getWords()),
        'bitEmail' => $this->getUser()->getBitEmail()
                    );
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->words = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add translations
     *
     * @param \Main\DefaultBundle\Entity\Translation $translations
     * @return Dictionary
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
}
