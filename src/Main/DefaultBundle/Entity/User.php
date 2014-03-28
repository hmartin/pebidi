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

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    
    /**
     * @ORM\OneToMany(targetEntity="Dictionary", mappedBy="user")
     */
    protected $dictionaries;

    public function getTruncateEmail() {
        $a = explode('@', $this->email);
        return $a['0'].'@...';
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
}
