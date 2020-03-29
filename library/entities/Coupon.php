<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Coupon
 *
 * @ORM\Table(name="coupon")
 * @ORM\Entity
 */
class Coupon
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
     * @ORM\Column(name="coupon_id", type="bigint", nullable=false)
     */
    private $couponId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="threshold_val", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $thresholdVal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="coupon_content", type="boolean", nullable=false)
     */
    private $couponContent;

    /**
     * @var string
     *
     * @ORM\Column(name="coupon_content_val", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $couponContentVal;

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
     * @var string
     *
     * @ORM\Column(name="category_id", type="string", length=35, nullable=false)
     */
    private $categoryId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="disable_time", type="datetime", nullable=false)
     */
    private $disableTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="target_population", type="boolean", nullable=true)
     */
    private $targetPopulation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="issue_way", type="boolean", nullable=true)
     */
    private $issueWay;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="issue_time", type="datetime", nullable=false)
     */
    private $issueTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer", nullable=false)
     */
    private $number;


}
