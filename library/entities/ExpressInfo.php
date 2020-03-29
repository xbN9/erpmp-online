<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * ExpressInfo
 *
 * @ORM\Table(name="express_info")
 * @ORM\Entity
 */
class ExpressInfo
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
     * @ORM\Column(name="express_name", type="string", length=50, nullable=false)
     */
    private $expressName;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     */
    private $name;


}
