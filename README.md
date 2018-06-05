# EasyForms
Plugin for pmmp that allow you create GUI in a few clicks.
### Code samples
#### ModalForm
```php
EasyForms::sendForm($sender, new class("A small question", "Our server is cool?") extends ModalForm{
	public function onSubmit(Player $player, $response) : void{
		$player->sendMessage($response ? "Thank you" : "We will try to become better");
	}
});
```
![modal](https://i.imgur.com/eI2xaBL.png)
#### MenuForm
```php
EasyForms::sendForm($sender, new MenuForm("Select server", "Choose server", [
	new class("SkyWars #1", "https://d1u5p3l4wpay3k.cloudfront.net/minecraft_gamepedia/1/19/Melon.png") extends Button{
		public function handle(Player $player, $value) : void{
			$player->sendMessage("You selected: " . $this->text);
		}
	}
]));
```
![menu](https://i.imgur.com/QewDqkc.png)
#### CustomForm
```php
EasyForms::sendForm($sender, new CustomForm("Enter data", [
	new class("Select product", ["beer", "cheese", "cola"]) extends Dropdown{
		public function handle(Player $player, $value) : void{
			$player->sendMessage("You selected: " . $this->options[$value]);
		}
	},
	new class("Enter your name", "Bob") extends Input{
		public function handle(Player $player, $value) : void{
			$player->sendMessage("Your name is $value");
		}
	},
	new Label("I am label!"), //it is useless to process, $value will be null
	new class("Select count", 0.0, 100.0, 1.0, 50.0) extends Slider{
		public function handle(Player $player, $value) : void{
			$player->sendMessage("Count: $value");
		}
	},
	new class("Select product", ["beer", "cheese", "cola"]) extends StepSlider{ //like dropdown, but it is slider
		public function handle(Player $player, $value) : void{
			$player->sendMessage("You selected: " . $this->options[$value]);
		}
	},
	new class("Creative") extends Toggle{
		public function handle(Player $player, $value) : void{
			$player->setGamemode($value ? 1 : 0);
		}
	}
]));
```
![custom1](https://i.imgur.com/biAoc91.png)
![custom2](https://i.imgur.com/AFkpS7b.png)
