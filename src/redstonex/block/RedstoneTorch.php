<?php

declare(strict_types=1);

namespace redstonex\block;

use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use redstonex\RedstoneX;
use pocketmine\block\{
	Air, Block, Transparent
};
use pocketmine\item\Item;
use pocketmine\Player;

/**
 * Class RedstoneTorch
 * @package redstonex\block
 */
class RedstoneTorch extends \pocketmine\block\RedstoneTorch {

    /**
     * @var int
     */
    protected $id = RedstoneX::REDSTONE_TORCH_ACTIVE;

    /**
     * @return string
     */
    public function getName():string {
        return "Redstone Torch";
    }

    /**
     * @param bool $activated
     */
    public function setActivated(bool $activated = true) {
        $activated ? $this->id = RedstoneX::REDSTONE_TORCH_ACTIVE : $this->id = RedstoneX::REDSTONE_TORCH_INACTIVE;
    }

    /**
     * @return bool
     */
    public function isActivated(): bool {
        return $this->id === RedstoneX::REDSTONE_TORCH_ACTIVE;
    }

    public function toggleTorch(){
        $this->setActivated(!$this->isActivated());
    }

    /**
     * @return float
     */
    public function getHardness(): float {
        return 0.1;
    }

    /**
     * @param int $type
     * @return int
     */
    public function onUpdate(int $type) {
        if($type === Level::BLOCK_UPDATE_NORMAL){
        $below = $this->getSide(Vector3::SIDE_DOWN);
        $side = $this->getDamage();
        $faces = [
            0 => Vector3::SIDE_DOWN,
            1 => Vector3::SIDE_WEST,
            2 => Vector3::SIDE_EAST,
            3 => Vector3::SIDE_NORTH,
            4 => Vector3::SIDE_SOUTH,
        ];

        if($this->getSide($faces[$side])->isTransparent() === \true and !($side === Vector3::SIDE_DOWN and ($below->getId() === self::FENCE or $below->getId() === self::COBBLESTONE_WALL or $below->getId() === self::REDSTONE_WIRE))){
            $this->getLevel()->useBreakOn($this);

            return Level::BLOCK_UPDATE_NORMAL;
        }
    }
        $this->activateRedstone();
        return $type;
    }

    public function activateRedstone($power = 15){
      $_un_ = ($power === 0) ? "un" : "";
      RedstoneX::consoleDebug("§aRedstone ".$_un_."torching...");


      $faces = [
          0 => Vector3::SIDE_DOWN,
          1 => Vector3::SIDE_WEST,
          2 => Vector3::SIDE_EAST,
          3 => Vector3::SIDE_NORTH,
          4 => Vector3::SIDE_SOUTH,
      ];

      foreach($faces as $face){
        $block = $this->getLevel()->getBlock($this->getSide($face));
        if ($block instanceof Redstone) {
            RedstoneX::consoleDebug("§aFound one! setting s. strength to ".($power));
            RedstoneX::setRedstoneActivity($block, $power);
            $block->activateRedstone();
        } elseif ($block instanceof RedstoneTorch) {
            RedstoneX::consoleDebug("§aFound another torch ! Toggling it");
            $block->toggleTorch();
        }
      }

    }

      /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onBreak(Item $item, Player $player = null): bool{
    	$this->activateRedstone(0);
      $this->getLevel()->setBlock($this, new Air(), true, true);

    	return true;
    }

    public function isRedstoneAble(){
      return \true;
    }

}
