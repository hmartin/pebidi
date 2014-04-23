<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(indexes={@ORM\Index(name="lemma_idx", columns={"lemma"})})
 * @ORM\Entity
 */
class DefinitionEn
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="WordEn", inversedBy="definitions",cascade={"persist"})
     * @ORM\JoinColumn(name="wordEn_id", referencedColumnName="id")
     */
    protected $wordEn;

    /**
     * @ORM\Column(type="integer")
     */
    protected $synsetid;

    /**
     * @ORM\Column(type="integer")
     */
    protected $wordid;

    /**
     * @ORM\Column(type="integer")
     */
    protected $casedwordid;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $lemma;

    /**
     * @ORM\Column(type="integer")
     */
    protected $senseid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $sensenum;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $lexid;

    /**
     * @ORM\Column(type="integer")
     */
    protected $tagcount;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $sensekey;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $cased;

    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $pos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $lexdomainid;

    /**
     * @ORM\Column(type="text")
     */
    protected $definition;

    /**
     * @ORM\Column(type="text")
     */
    protected $sampleset;
}
