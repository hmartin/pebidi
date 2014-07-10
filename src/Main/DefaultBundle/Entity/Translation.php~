<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Translation
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $translation;

    /**
     * @ORM\ManyToOne(targetEntity="Dictionary", inversedBy="translations",cascade={"persist"})
     * @ORM\JoinColumn(name="dictionary_id", referencedColumnName="id")
     */
    protected $dictionary;

    /**
     * @ORM\ManyToOne(targetEntity="Word", inversedBy="translations",cascade={"persist"})
     * @ORM\JoinColumn(name="word_id", referencedColumnName="id")
     */
    protected $word;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


    public function __toString() {
        return $this->translation;
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
     * Set translation
     *
     * @param string $translation
     * @return Translation
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * Get translation
     *
     * @return string 
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Translation
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
     * Set dictionary
     *
     * @param \Main\DefaultBundle\Entity\Dictionary $dictionary
     * @return Translation
     */
    public function setDictionary(\Main\DefaultBundle\Entity\Dictionary $dictionary = null)
    {
        $this->dictionary = $dictionary;

        return $this;
    }

    /**
     * Get dictionary
     *
     * @return \Main\DefaultBundle\Entity\Dictionary 
     */
    public function getDictionary()
    {
        return $this->dictionary;
    }

    /**
     * Set word
     *
     * @param \Main\DefaultBundle\Entity\Word $word
     * @return Translation
     */
    public function setWord(\Main\DefaultBundle\Entity\Word $word = null)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return \Main\DefaultBundle\Entity\Word 
     */
    public function getWord()
    {
        return $this->word;
    }
}
