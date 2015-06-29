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
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $sense;

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
     * Set local
     *
     * @param string $local
     *
     * @return Sense
     */
    public function setLocal($local)
    {
        $this->local = $local;

        return $this;
    }

    /**
     * Get local
     *
     * @return string
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Set sense
     *
     * @param string $sense
     *
     * @return Sense
     */
    public function setSense($sense)
    {
        $this->sense = $sense;

        return $this;
    }

    /**
     * Get sense
     *
     * @return string
     */
    public function getSense()
    {
        return $this->sense;
    }

    /**
     * Get sense
     *
     * @return string
     */
    public function __toString()
    {
        var_dump($this->sense);
        if (is_null($this->sense)) {
            return '';
        }
        return $this->sense;
    }
}
