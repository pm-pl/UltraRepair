# UltraRepair Plugin   <img src="icon.png" alt="UltraRepair Logo" width="64">

[![](https://poggit.pmmp.io/shield.state/Ultra-Repair)](https://poggit.pmmp.io/p/Ultra-Repair)
<a href="https://poggit.pmmp.io/p/Ultra-Repair"><img src="https://poggit.pmmp.io/shield.state/Ultra-Repair"></a> [![](https://poggit.pmmp.io/shield.api/Ultra-Repair)](https://poggit.pmmp.io/p/Ultra-Repair)
<a href="https://poggit.pmmp.io/p/Ultra-Repair"><img src="https://poggit.pmmp.io/shield.api/Ultra-Repair"></a>

UltraRepair is a powerful plugin for repairing items in-game on your Pocketmine-MP server. It provides players with the ability to repair their weapons and tools easily. The plugin supports repairing single items as well as repairing all items in a player's inventory.

## Features

- Repair items with a cooldown after initial use
- Repair all items in the player's inventory with a cooldown after initial use
- Customizable cooldown time
- Configurable messages for player feedback

## Installation

1. Download the latest release of UltraRepair from the [Releases](https://github.com/iLVOEWOCK/UltraRepair/releases) page.
2. Place the plugin `PHAR` file in the `plugins` folder of your Pocketmine-MP server.
3. Restart the server to enable the UltraRepair plugin.

## Usage

- Repair a single item:
  - Command: `/fix`
  - Permission: `ultrarepair.fix`
- Repair all items in the inventory:
  - Command: `/fix all`
  - Permission: `ultrarepair.fix.all`

Note: Players with the `ultrarepair.bypasscooldown` permission can bypass the repair cooldown.

## Configuration

The UltraRepair plugin can be customized through the `config.yml` file located in the `plugin_data/UltraRepair` directory. You can modify the following options:

- `cooldown`: The cooldown time in seconds. Default: 60.
- `messages`:
  - `no_item`: Message displayed when the player doesn't have an item in hand.
  - `cooldown`: Message displayed when the player is still in cooldown for repairing a single item.
  - `item_repaired`: Message displayed when the item in hand has been successfully repaired.
  - `invalid_item`: Message displayed when the item in hand cannot be repaired.
  - `cooldown_all`: Message displayed when the player is still in cooldown for repairing all items.
  - `all_items_repaired`: Message displayed when all items in the inventory have been successfully repaired.

## Contributing

Contributions are welcome! If you have any suggestions, bug reports, or feature requests, please open an issue on the [Issue Tracker](https://github.com/iLVOEWOCK/UltraRepair/issues).

## License

This project is licensed under the [MIT License](./LICENSE).

## Icon

<a href="https://www.flaticon.com/free-icons/wrench" title="wrench icons">Wrench icons created by Freepik - Flaticon</a>

## To-Do list

[ ] Add separate cooldowns for fix all
