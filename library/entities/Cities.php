<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Cities
 *
 * @ORM\Table(name="cities", indexes={@ORM\Index(name="provinceid", columns={"provinceid"})})
 * @ORM\Entity
 */
class Cities
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cityid", type="string", length=20, nullable=false)
     */
    private $cityid;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=50, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="provinceid", type="string", length=20, nullable=false)
     */
    private $provinceid;


}
