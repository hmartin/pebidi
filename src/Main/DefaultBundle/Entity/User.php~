<?php
namespace Main\DefaultBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $private = 0;

    /**
     * @ORM\OneToMany(targetEntity="Dictionary", mappedBy="user")
     */
    protected $dictionaries;

    /**
     * @ORM\OneToMany(targetEntity="DictionaryScore", mappedBy="user")
     */
    protected $dictionaryScores;

    /**
     * @ORM\OneToMany(targetEntity="Test", mappedBy="user")
     */
    protected $tests;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function getBitEmail() {
        $a = explode('@', $this->email);
        $b = explode('.', $a['1']);
        return $a['0'].'@....'.$b['1'];
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
     * Add dictionaries
     *
     * @param \Main\DefaultBundle\Entity\Dictionary $dictionaries
     * @return User
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
    
    public function getDefaultDictionary() {
        if (isset($this->dictionaries['0'])) {
            return $this->dictionaries['0'];
        }
        return false;
    }

    /**
     * Set private
     *
     * @param integer $private
     * @return User
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
     * @return User
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
     * Add dictionaryScores
     *
     * @param \Main\DefaultBundle\Entity\DictionaryScore $dictionaryScores
     * @return User
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
