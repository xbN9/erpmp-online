<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity
 */
class Event
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
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="introduce", type="string", length=150, nullable=false)
     */
    private $introduce;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank_weight", type="smallint", nullable=false)
     */
    private $rankWeight;

    /**
     * @var boolean
     *
     * @ORM\Column(name="show_status", type="boolean", nullable=false)
     */
    private $showStatus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=30, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=35, nullable=false)
     */
    private $img;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="datetime", nullable=false)
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="datetime", nullable=false)
     */
    private $endTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="layout_style", type="boolean", nullable=false)
     */
    private $layoutStyle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="url_style", type="boolean", nullable=false)
     */
    private $urlStyle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="activity_status", type="boolean", nullable=false)
     */
    private $activityStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $activityPrice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;


}
