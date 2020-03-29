<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * OrderExpressExt
 *
 * @ORM\Table(name="order_express_ext")
 * @ORM\Entity
 */
class OrderExpressExt
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
     * @ORM\Column(name="order_sn", type="bigint", nullable=false)
     */
    private $orderSn;

    /**
     * @var string
     *
     * @ORM\Column(name="order_express_sn", type="string", length=25, nullable=false)
     */
    private $orderExpressSn;

    /**
     * @var string
     *
     * @ORM\Column(name="express_sn", type="string", length=40, nullable=false)
     */
    private $expressSn;

    /**
     * @var string
     *
     * @ORM\Column(name="express_name", type="string", length=15, nullable=false)
     */
    private $expressName;

    /**
     * @var string
     *
     * @ORM\Column(name="express_info", type="text", length=65535, nullable=false)
     */
    private $expressInfo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="state", type="boolean", nullable=false)
     */
    private $state;


}
