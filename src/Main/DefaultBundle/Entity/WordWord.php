<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(fields={
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
     * @ORM\ManyToMany(targetEntity="Sense", mappedBy="wws")
     */
    protected $sense;


}
