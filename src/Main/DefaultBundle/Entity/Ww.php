<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(fields={"word1","word2"})
 */
class Ww
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Word",cascade={"persist"})
     * @ORM\JoinColumn()
     */
    protected $word1;

    /**
     * @ORM\ManyToOne(targetEntity="Word", cascade={"persist"})
     * @ORM\JoinColumn()
     */
    protected $word2;

    /**
     * @ORM\ManyToMany(targetEntity="Sense")
     * @ORM\JoinTable(name="WwSenses")
     */
    protected $senses;
}
