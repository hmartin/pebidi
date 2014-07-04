<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class WordWord
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Word", inversedBy="translations",cascade={"persist"})
     * @ORM\JoinColumn(name="word1_id", referencedColumnName="id")
     */
    protected $word1;

    /**
     * @ORM\ManyToOne(targetEntity="Word", inversedBy="translations",cascade={"persist"})
     * @ORM\JoinColumn(name="word2_id", referencedColumnName="id")
     */
    protected $word2;


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
     * Set word1
     *
     * @param \Main\DefaultBundle\Entity\Word $word1
     * @return WordWord
     */
    public function setWord1(\Main\DefaultBundle\Entity\Word $word1 = null)
    {
        $this->word1 = $word1;

        return $this;
    }

    /**
     * Get word1
     *
     * @return \Main\DefaultBundle\Entity\Word 
     */
    public function getWord1()
    {
        return $this->word1;
    }

    /**
     * Set word2
     *
     * @param \Main\DefaultBundle\Entity\Word $word2
     * @return WordWord
     */
    public function setWord2(\Main\DefaultBundle\Entity\Word $word2 = null)
    {
        $this->word2 = $word2;

        return $this;
    }

    /**
     * Get word2
     *
     * @return \Main\DefaultBundle\Entity\Word 
     */
    public function getWord2()
    {
        return $this->word2;
    }
}
