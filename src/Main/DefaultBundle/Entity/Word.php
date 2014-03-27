<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Word
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
    protected $word;

    /**
     * @ORM\ManyToMany(targetEntity="Dictionary", inversedBy="words")
     * @ORM\JoinTable(name="DictionariesWord")
     **/
    private $dictionaries;
    
    /**
     * @ORM\OneToMany(targetEntity="Translation", mappedBy="dictionary")
     */
    protected $translations;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;
}