<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Areas
 *
 * @ORM\Table(name="areas", indexes={@ORM\Index(name="cityid", columns={"cityid"})})
 * @ORM\Entity
 */
class Areas
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
     * @ORM\Column(name="areaid", type="string", length=20, nullable=false)
     */
    private $areaid;

    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=50, nullable=false)
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(name="cityid", type="string", length=20, nullable=false)
     */
    private $cityid;


}
