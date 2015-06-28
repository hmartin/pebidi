<?php
namespace Main\DefaultBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Suck
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $page;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $url;


    /**
     * @ORM\Column(type="text")
     */
    protected $hmtl;


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
     * Set page
     *
     * @param integer $page
     *
     * @return Suck
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Suck
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set hmtl
     *
     * @param string $hmtl
     *
     * @return Suck
     */
    public function setHmtl($hmtl)
    {
        $this->hmtl = $hmtl;

        return $this;
    }

    /**
     * Get hmtl
     *
     * @return string
     */
    public function getHmtl()
    {
        return $this->hmtl;
    }
}
