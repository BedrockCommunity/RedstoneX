<?php

declare(strict_types=1);

namespace redstonex\block;

use pocketmine\block\Block;
use pocketmine\block\Transparent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use redstonex\RedstoneX;

/**
 * Class Redstone
 * @package redstonex\block
 */
class Redstone extends Transparent {

    /** @var int $id */
    protected $id = RedstoneX::REDSTONE_WIRE;

    /** @var $meta */
    public $meta = 0;

    protected static $id_incrementer = 0;

    protected $UID;

    /**
     * Redstone constructor.
     * @param int $meta
     */
    public function __construct($meta = 0) {
        parent::__construct($this->id, $meta, $this->getName(), RedstoneX::REDSTONE_ITEM);
        $this->UID = self::$id_incrementer;
        self::$id_incrementer++;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return "Redstone Wire";
    }

    /**
     * @param int $type
     * @return int
     */
    public function onUpdate(int $type) {
        //$this->activateRedstone();
        //$this->deactivateRedstone();

        $this->activateRedstone();

        return $type;
    }

    public function isRedstoneAble(){
      return \true;
    }
/*
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $facePos, Player $player = \null) : bool{
        RedstoneX::consoleDebug("Just placed redstone");
        $below = $this->getSide(Vector3::SIDE_DOWN);

        $foundRedstone = \false;
        for ($x = $this->getX() - 1; $x <= $this->getX() + 1; $x++) {
            for ($y = $this->getY() - 1; $y <= $this->getY() + 1; $y++) {
              $block = $this->getLevel()->getBlock(new Vector3($x, $y, $this->getZ()));

              if ($block instanceof Redstone) {
                $foundRedstone = \true;
              }
            }
        }

        if(!$foundRedstone){
          $this->meta = 0;
          $this->getLevel()->setBlock($blockReplace, $this, \true, \true);
        }elseif($blockClicked->isTransparent() === \false and $face !== Vector3::SIDE_DOWN){
            $faces = [
                Vector3::SIDE_UP => 5,
                Vector3::SIDE_NORTH => 4,
                Vector3::SIDE_SOUTH => 3,
                Vector3::SIDE_WEST => 2,
                Vector3::SIDE_EAST => 1
            ];
            $this->meta = $faces[$face];
            $this->getLevel()->setBlock($blockReplace, $this, \true, \true);

            return \true;
        }elseif($below->isTransparent() === \false or $below->getId() === self::FENCE or $below->getId() === self::COBBLESTONE_WALL or $below->getId() === self::REDSTONE_WIRE){
            $this->meta = 0;
            $this->getLevel()->setBlock($blockReplace, $this, \true, \true);

            return \true;
        }

        return \false;
    }*/

    public function activateRedstone($powerId = -1){
      RedstoneX::consoleDebug("§aUpdating neighbours...");

      $power = $this->meta;
      $babyStrength = $power-1;
      if(RedstoneX::$redstoneMaxPower)
        $babyStrength = 15;

      if($babyStrength < 0){
        $babyStrength = 0;
      }
      // We check each neighbouring block

      $faces = [
          0 => Vector3::SIDE_DOWN,
          1 => Vector3::SIDE_WEST,
          2 => Vector3::SIDE_EAST,
          3 => Vector3::SIDE_NORTH,
          4 => Vector3::SIDE_SOUTH,
      ];

      foreach ($faces as $face) {
        $block = $this->getLevel()->getBlock($this->getSide($face));
        if ($powerId != $this->UID and $block instanceof Redstone) {
            RedstoneX::consoleDebug("§aFound one! setting signal strength to $babyStrength ($power)");
            $block->getLevel()->setBlock($block->asVector3(), new Redstone($babyStrength));
            $block->activateRedstone($this->UID);
        }
      }

    }

    /**
    * @return int 0-15
    */
    public function getLightLevel() : int{
      return 10;//RedstoneX::getRedstoneActivity($this);
    }

    public function getHardness(): float {
        return 0.2;
    }
}
