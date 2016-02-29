<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DictionaryScoreRepository")
 */
class DictionaryScore
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="dictionaryScores")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Dictionary", inversedBy="dictionaryScores",cascade={"persist"})
     * @ORM\JoinColumn(name="dictionary_id", referencedColumnName="id")
     */
    protected $dictionary;

    /**
     * @ORM\Column(type="float")
     */
    protected $score = 0;

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
     * Set score
     *
     * @param float $score
     * @return DictionaryScore
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return DictionaryScore
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
     * Set dictionary
     *
     * @param \AppBundle\Entity\Dictionary $dictionary
     * @return DictionaryScore
     */
    public function setDictionary(\AppBundle\Entity\Dictionary $dictionary = null)
    {
        $this->dictionary = $dictionary;

        return $this;
    }

    /**
     * Get dictionary
     *
     * @return \AppBundle\Entity\Dictionary 
     */
    public function getDictionary()
    {
        return $this->dictionary;
    }
}
