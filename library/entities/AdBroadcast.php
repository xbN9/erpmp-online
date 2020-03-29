<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * AdBroadcast
 *
 * @ORM\Table(name="ad_broadcast")
 * @ORM\Entity
 */
class AdBroadcast
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
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=50, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=50, nullable=false)
     */
    private $url;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="related_Id", type="integer", nullable=false)
     */
    private $relatedId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="position", type="boolean", nullable=false)
     */
    private $position;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="link_id", type="integer", nullable=false)
     */
    private $linkId;


}
