<?php
namespace DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Game
 *
 * @ORM\Entity
 * @ORM\Table(name="dashboard_game")
 */
class Game
{
    const GAME_START_TYPE_CONSOLE = 0;

    const GAME_START_TYPE_KNOCK = 1;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     * )
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 500,
     * )
     * @var string
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     * )
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(type="string")
     */
    protected $startCommand;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     * )
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(type="string")
     */
    protected $shutdownCommand;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $runningCommand;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $logo;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $maxPlayer;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $isRunning = false;

    /**
     * @Assert\Choice({0, 1})
     * @var string
     * @ORM\Column(type="integer")
     */
    protected $startType;

    /**
     * @var array
     */
    protected $startTypes = [ self::GAME_START_TYPE_CONSOLE, self::GAME_START_TYPE_KNOCK];

    /**
     * Get Id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Game
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get Name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Description.
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get StartTypes.
     *
     * @return array
     */
    public function getStartTypes()
    {
        return $this->startTypes;
    }

    /**
     * @param mixed $description
     *
     * @return Game
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get StartCommand.
     *
     * @return string
     */
    public function getStartCommand()
    {
        return $this->startCommand;
    }

    /**
     * @param string $startCommand
     *
     * @return Game
     */
    public function setStartCommand($startCommand)
    {
        $this->startCommand = $startCommand;

        return $this;
    }

    /**
     * Get ShutdownCommand.
     *
     * @return string
     */
    public function getShutdownCommand()
    {
        return $this->shutdownCommand;
    }

    /**
     * @param string $shutdownCommand
     *
     * @return Game
     */
    public function setShutdownCommand($shutdownCommand)
    {
        $this->shutdownCommand = $shutdownCommand;

        return $this;
    }

    /**
     * Get RunningCommand.
     *
     * @return string
     */
    public function getRunningCommand()
    {
        return $this->runningCommand;
    }

    /**
     * @param string $runningCommand
     *
     * @return Game
     */
    public function setRunningCommand($runningCommand)
    {
        $this->runningCommand = $runningCommand;

        return $this;
    }

    /**
     * Get Logo.
     *
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     *
     * @return Game
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get MaxPlayer.
     *
     * @return int
     */
    public function getMaxPlayer()
    {
        return $this->maxPlayer;
    }

    /**
     * Get IsRunning.
     *
     * @return bool
     */
    public function isRunning()
    {
        return $this->isRunning;
    }

    /**
     * @param bool $isRunning
     *
     * @return Game
     */
    public function setIsRunning($isRunning)
    {
        $this->isRunning = $isRunning;

        return $this;
    }

    /**
     * @param int $maxPlayer
     *
     * @return Game
     */
    public function setMaxPlayer($maxPlayer)
    {
        $this->maxPlayer = $maxPlayer;

        return $this;
    }

    /**
     * Get StartType.
     *
     * @return string
     */
    public function getStartType()
    {
        return $this->startType;
    }

    /**
     * @param string $startType
     *
     * @return Game
     */
    public function setStartType($startType)
    {
        if (!in_array($startType, $this->startTypes)) {
            throw new \InvalidArgumentException("Invalid Start Type" . $startType);
        }
        $this->startType = $startType;

        return $this;
    }
}
