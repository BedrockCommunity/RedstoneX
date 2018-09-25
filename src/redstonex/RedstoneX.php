<?php

declare(strict_types=1);

namespace redstonex;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\plugin\PluginBase;
use redstonex\block\Lever;
use redstonex\block\Redstone;
use redstonex\block\RedstoneLamp;
use redstonex\block\RedstoneLampUnlit;
use redstonex\block\RedstoneTorch;
use redstonex\block\RedstoneTorchUnlit;
use redstonex\event\EventListener;
use redstonex\commands\RedstonePowaa;
use pocketmine\Server as PMServer;

/**
 * Class RedstoneX
 * @package redstonex
 * @author VixikCZ
 */
class RedstoneX extends PluginBase implements RedstoneData {

    /** @var  RedstoneX $instance */
    private static $instance;

    /** @var  EventListener $listener */
    private $listener;

    /** @var bool $debug */
    private static $debug = true;

    /** @var bool $redstoneMaxPower */
    public static $redstoneMaxPower = false;

    public function onEnable() {
        self::$instance = $this;
        $this->registerBlocks();
        $this->registerEvents();
        $this->registerCommands();
    }

    public function registerEvents() {
        $this->getServer()->getPluginManager()->registerEvents($this->listener = new EventListener, $this);
    }

    public function registerBlocks() {

        /** @var Block[] $blocks */
        $blocks = [
            new Redstone(0),
            new RedstoneTorch(0),
            new RedstoneTorchUnlit(0),
            new RedstoneLamp(0),
            new RedstoneLampUnlit(0),
            new Lever(0)
        ];

        // OLD API SUPPORT
        try {
            if(class_exists(BlockFactory::class)) {
                foreach ($blocks as $block) {
                    BlockFactory::registerBlock($block, true);
                }
            }
            else {
                goto e;
            }
        }
        catch (\Exception $exception) {
            $this->getLogger()->critical("§cCloud not register blocks!");
        }

        return;
        e:
        foreach ($blocks as $block) {
            Block::registerBlock($block, true);
        }
    }

    public function registerCommands(){
        RedstoneX::consoleDebug("Registering Commands...");
  		  $cmds = [
    			new RedstonePowaa("redstonePowaa"),
    		];

    		PMServer::getInstance()->getCommandMap()->registerAll("redstonex", $cmds);
    }

    /**
     * @param string $debug
     */
    public static function consoleDebug(string $debug) {
        if(self::$debug) self::getInstance()->getLogger()->info($debug);
    }

    /**
     * @param Block $block
     * @return bool
     */
    public static function isRedstone(Block $block) {
        return in_array(intval($block->getId()), self::ALL_IDS);
    }

    /**
     * @return RedstoneX $instance
     */
    public static function getInstance():RedstoneX {
        return self::$instance;
    }

    /**
     * @param Block $block
     */
    public static function setInactive(Block $block) {
        if($block->getId() == self::REDSTONE_WIRE || $block instanceof Redstone) {
            $block->getLevel()->setBlock($block->asVector3(), new Redstone);
        }
        else {
            $block->getLevel()->setBlockIdAt($block->getY(), $block->getY(), $block->getZ(), $block->getId());
            $block->getLevel()->setBlockDataAt($block->getY(), $block->getY(), $block->getZ(), 0);
        }
    }

    /**
     * @param Block $block
     * @param int $active
     */
    public static function setRedstoneActivity(Block $block, int $active = 15) {
        switch ($block->getId()) {
            case self::REDSTONE_WIRE:
                $block->getLevel()->setBlock($block->asVector3(), new Redstone(RedstoneX::REDSTONE_WIRE, $active, "Redstone Wire", RedstoneX::REDSTONE_ITEM));
                return;
            default:
                #if($block->getDamage() < $active) {
                    $block->getLevel()->setBlock($block->asVector3(), $block, true, true);
                #}
                return;
        }
    }

    /**
     * @param Block $block
     * @return bool
     */
    public static function getRedstoneActivity(Block $block): int {
        switch ($block->getId()) {
            case self::REDSTONE_WIRE:
                return $block->meta;
            case self::REDSTONE_TORCH_ACTIVE:
                return 15;
            default:
                return 0;
        }
    }

}
