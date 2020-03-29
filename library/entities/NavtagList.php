<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * NavtagList
 *
 * @ORM\Table(name="navtag_list", indexes={@ORM\Index(name="nav_id", columns={"nav_id"})})
 * @ORM\Entity
 */
class NavtagList
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
     * @var integer
     *
     * @ORM\Column(name="nav_id", type="integer", nullable=false)
     */
    private $navId;

    /**
     * @var integer
     *
     * @ORM\Column(name="event_id", type="integer", nullable=false)
     */
    private $eventId;

    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer", nullable=false)
     */
    private $categoryId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;


}
