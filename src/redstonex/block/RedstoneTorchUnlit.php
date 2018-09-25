<?php

declare(strict_types=1);

namespace redstonex\block;

use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use redstonex\RedstoneX;

/**
 * Class RedstoneTorch
 * @package redstonex\block
 */
class RedstoneTorchUnlit extends RedstoneTorch {

    /**
     * @var int
     */
    protected $id = RedstoneX::REDSTONE_TORCH_INACTIVE;

    public function activateRedstone($power = 15){
      parent::activateRedstone(0);
    }

}
