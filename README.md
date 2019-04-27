# EasyForms
Plugin for pmmp that allow you create GUI in a few clicks.

### Code samples

#### ModalForm
```php
$sender->sendForm(new ModalForm("A small question", "Our server is cool?",
	function(Player $player, bool $response) : void{
		$player->sendMessage($response ? "Thank you" : "We will try to become better");
	}
));
```
![modal](https://i.imgur.com/eI2xaBL.png)
#### MenuForm
```php
$sender->sendForm(new MenuForm(
	"Select server", "Choose server", [new Button("SkyWars #1", new Image("https://gamepedia.cursecdn.com/minecraft_gamepedia/1/19/Melon.png"))],
	function(Player $player, Button $selected) : void{
		$player->sendMessage("You selected: " . $selected->getText());
		$player->sendMessage("Index of button: " . $selected->getValue());
	}
));
```
![menu](https://i.imgur.com/QewDqkc.png)
#### CustomForm
```php
$sender->sendForm(new CustomForm("Enter data",
	[
		new Dropdown("Select product", ["beer", "cheese", "cola"]),
		new Input("Enter your name", "Bob"),
		new Label("I am label!"), //Note: get<Element>() does not work with label
		new Slider("Select count", 0.0, 100.0, 1.0, 50.0),
		new StepSlider("Select product", ["beer", "cheese", "cola"]),
		new Toggle("Creative", $sender->isCreative())
	],
	function(Player $player, CustomFormResponse $response) : void{
		$dropdown = $response->getDropdown();
		$player->sendMessage("You selected: {$dropdown->getSelectedOption()}");

		$input = $response->getInput();
		$player->sendMessage("Your name is {$input->getValue()}");

		$slider = $response->getSlider();
		$player->sendMessage("Count: {$slider->getValue()}");

		$stepSlider = $response->getStepSlider();
		$player->sendMessage("You selected: {$stepSlider->getSelectedOption()}");

		$toggle = $response->getToggle();
		$player->setGamemode($toggle->getValue() ? GameMode::CREATIVE() : GameMode::SURVIVAL());
	}
));
```
#### CustomForm with getValues() method
```php
$sender->sendForm(new CustomForm("Enter data",
	[
		new Dropdown("Select product", ["beer", "cheese", "cola"]),
		new Input("Enter your name", "Bob"),
		new Label("I am label!"), //Also ignored by getValues()
		new Slider("Select count", 0.0, 100.0, 1.0, 50.0),
		new StepSlider("Select product", ["beer", "cheese", "cola"]),
		new Toggle("Creative", $sender->isCreative())
	],
	function(Player $player, CustomFormResponse $response) : void{
		list($product1, $username, $count, $product2, $enableCreative) = $response->getValues();
		$player->sendMessage("You selected: $product1");
		$player->sendMessage("Your name is $username");
		$player->sendMessage("Count: $count");
		$player->sendMessage("You selected: $product2");
		$player->setGamemode($enableCreative ? GameMode::CREATIVE() : GameMode::SURVIVAL());
	}
));
```
![custom1](https://i.imgur.com/biAoc91.png)
![custom2](https://i.imgur.com/AFkpS7b.png)
#### ServerSettingsForm
```php
public function onServerSettingsRequest(ServerSettingsRequestEvent $e) : void{
	$player = $e->getPlayer();
	$e->setForm(new ServerSettingsForm("Server settings",
			[
				new Label("Some text"),
				new Toggle("Enable something", $player->isFlying())
			],
			new Image("http://icons.iconarchive.com/icons/double-j-design/diagram-free/128/settings-icon.png"),
			function(Player $player, CustomFormResponse $response) : void{
				$player->sendMessage($response->getToggle()->getValue() ? "enabled" : "disabled");
			}
		)
	);
}
```
![settings](https://i.imgur.com/Yic6LuA.png)
