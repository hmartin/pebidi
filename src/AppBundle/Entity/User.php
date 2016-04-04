<?php
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
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
     * @ORM\OneToMany(targetEntity="Result", mappedBy="user")
     */
    protected $results;


    /**
     * @ORM\OneToMany(targetEntity="Test", mappedBy="creator")
     */
    protected $tests;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function getBitEmail() 
    {
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
     * @param \AppBundle\Entity\Dictionary $dictionaries
     * @return User
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
    
    public function getDefaultDictionary() 
    {
        foreach($this->dictionaries as $d) {
            if ($d->getMain()) {
                return $d;
            }
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
     * @param \AppBundle\Entity\Test $tests
     * @return User
     */
    public function addTest(\AppBundle\Entity\Test $tests)
    {
        $this->tests[] = $tests;

        return $this;
    }

    /**
     * Remove tests
     *
     * @param \AppBundle\Entity\Test $tests
     */
    public function removeTest(\AppBundle\Entity\Test $tests)
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
     * Add result
     *
     * @param \AppBundle\Entity\Result $result
     *
     * @return User
     */
    public function addResult(\AppBundle\Entity\Result $result)
    {
        $this->results[] = $result;

        return $this;
    }

    /**
     * Remove result
     *
     * @param \AppBundle\Entity\Result $result
     */
    public function removeResult(\AppBundle\Entity\Result $result)
    {
        $this->results->removeElement($result);
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
     * Set petok
     *
     * @param string $petok
     *
     * @return User
     */
    public function setPetok($petok)
    {
        $this->petok = $petok;

        return $this;
    }

    /**
     * Get petok
     *
     * @return string
     */
    public function getPetok()
    {
        return $this->petok;
    }
}
