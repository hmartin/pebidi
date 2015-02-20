<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Sense
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    protected $local;


    /**
     * @ORM\Column(type="text")
     */
    protected $definition;

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
     * Set synsetid
     *
     * @param integer $synsetid
     * @return DefinitionEn
     */
    public function setSynsetid($synsetid)
    {
        $this->synsetid = $synsetid;

        return $this;
    }

    /**
     * Get synsetid
     *
     * @return integer 
     */
    public function getSynsetid()
    {
        return $this->synsetid;
    }

    /**
     * Set wordid
     *
     * @param integer $wordid
     * @return DefinitionEn
     */
    public function setWordid($wordid)
    {
        $this->wordid = $wordid;

        return $this;
    }

    /**
     * Get wordid
     *
     * @return integer 
     */
    public function getWordid()
    {
        return $this->wordid;
    }

    /**
     * Set casedwordid
     *
     * @param integer $casedwordid
     * @return DefinitionEn
     */
    public function setCasedwordid($casedwordid)
    {
        $this->casedwordid = $casedwordid;

        return $this;
    }

    /**
     * Get casedwordid
     *
     * @return integer 
     */
    public function getCasedwordid()
    {
        return $this->casedwordid;
    }

    /**
     * Set lemma
     *
     * @param string $lemma
     * @return DefinitionEn
     */
    public function setLemma($lemma)
    {
        $this->lemma = $lemma;

        return $this;
    }

    /**
     * Get lemma
     *
     * @return string 
     */
    public function getLemma()
    {
        return $this->lemma;
    }

    /**
     * Set senseid
     *
     * @param integer $senseid
     * @return DefinitionEn
     */
    public function setSenseid($senseid)
    {
        $this->senseid = $senseid;

        return $this;
    }

    /**
     * Get senseid
     *
     * @return integer 
     */
    public function getSenseid()
    {
        return $this->senseid;
    }

    /**
     * Set sensenum
     *
     * @param integer $sensenum
     * @return DefinitionEn
     */
    public function setSensenum($sensenum)
    {
        $this->sensenum = $sensenum;

        return $this;
    }

    /**
     * Get sensenum
     *
     * @return integer 
     */
    public function getSensenum()
    {
        return $this->sensenum;
    }

    /**
     * Set lexid
     *
     * @param integer $lexid
     * @return DefinitionEn
     */
    public function setLexid($lexid)
    {
        $this->lexid = $lexid;

        return $this;
    }

    /**
     * Get lexid
     *
     * @return integer 
     */
    public function getLexid()
    {
        return $this->lexid;
    }

    /**
     * Set tagcount
     *
     * @param integer $tagcount
     * @return DefinitionEn
     */
    public function setTagcount($tagcount)
    {
        $this->tagcount = $tagcount;

        return $this;
    }

    /**
     * Get tagcount
     *
     * @return integer 
     */
    public function getTagcount()
    {
        return $this->tagcount;
    }

    /**
     * Set sensekey
     *
     * @param string $sensekey
     * @return DefinitionEn
     */
    public function setSensekey($sensekey)
    {
        $this->sensekey = $sensekey;

        return $this;
    }

    /**
     * Get sensekey
     *
     * @return string 
     */
    public function getSensekey()
    {
        return $this->sensekey;
    }

    /**
     * Set cased
     *
     * @param string $cased
     * @return DefinitionEn
     */
    public function setCased($cased)
    {
        $this->cased = $cased;

        return $this;
    }

    /**
     * Get cased
     *
     * @return string 
     */
    public function getCased()
    {
        return $this->cased;
    }

    /**
     * Set pos
     *
     * @param string $pos
     * @return DefinitionEn
     */
    public function setPos($pos)
    {
        $this->pos = $pos;

        return $this;
    }

    /**
     * Get pos
     *
     * @return string 
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * Set lexdomainid
     *
     * @param integer $lexdomainid
     * @return DefinitionEn
     */
    public function setLexdomainid($lexdomainid)
    {
        $this->lexdomainid = $lexdomainid;

        return $this;
    }

    /**
     * Get lexdomainid
     *
     * @return integer 
     */
    public function getLexdomainid()
    {
        return $this->lexdomainid;
    }

    /**
     * Set definition
     *
     * @param string $definition
     * @return DefinitionEn
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * Get definition
     *
     * @return string 
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Set sampleset
     *
     * @param string $sampleset
     * @return DefinitionEn
     */
    public function setSampleset($sampleset)
    {
        $this->sampleset = $sampleset;

        return $this;
    }

    /**
     * Get sampleset
     *
     * @return string 
     */
    public function getSampleset()
    {
        return $this->sampleset;
    }


    /**
     * Set word
     *
     * @param \Main\DefaultBundle\Entity\Word $word
     * @return DefinitionEn
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
