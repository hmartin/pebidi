<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string", length="255")
     */
    protected $translation;

    /**
     * @ORM\ManyToOne(targetEntity="Dictionary", inversedBy="translations")
     * @ORM\JoinColumn(name="dictionary_id", referencedColumnName="id")
     */
    protected $dictionary;

    /**
     * @ORM\ManyToOne(targetEntity="Word", inversedBy="translations")
     * @ORM\JoinColumn(name="word_id", referencedColumnName="id")
     */
    protected $word;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;
}