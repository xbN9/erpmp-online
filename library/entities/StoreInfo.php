<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * StoreInfo
 *
 * @ORM\Table(name="store_info")
 * @ORM\Entity
 */
class StoreInfo
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
     * @ORM\Column(name="name", type="string", length=35, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=50, nullable=true)
     */
    private $address;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="book_morning_start", type="datetime", nullable=false)
     */
    private $bookMorningStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="book_morning_stop", type="datetime", nullable=false)
     */
    private $bookMorningStop;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="book_noon_start", type="datetime", nullable=false)
     */
    private $bookNoonStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="book_noon_stop", type="datetime", nullable=false)
     */
    private $bookNoonStop;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="book_night_start", type="datetime", nullable=false)
     */
    private $bookNightStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="book_night_stop", type="datetime", nullable=false)
     */
    private $bookNightStop;


}
