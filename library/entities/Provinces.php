<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Provinces
 *
 * @ORM\Table(name="provinces")
 * @ORM\Entity
 */
class Provinces
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
     * @ORM\Column(name="provinceid", type="string", length=20, nullable=false)
     */
    private $provinceid;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", length=50, nullable=false)
     */
    private $province;


}
