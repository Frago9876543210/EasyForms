# EasyForms
[![](https://poggit.pmmp.io/shield.state/EasyForms)](https://poggit.pmmp.io/p/EasyForms)

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
EasyForms::sendForm($sender, new class("Select server", "Choose server", [
	new Button("SkyWars #1", "https://d1u5p3l4wpay3k.cloudfront.net/minecraft_gamepedia/1/19/Melon.png")
]) extends MenuForm{
	public function onSubmit(Player $player, $response) : void{
		parent::onSubmit($player, $response);
		$player->sendMessage("You selected: " . $this->buttons[$response]->getText());
	}
});
```
![menu](https://i.imgur.com/QewDqkc.png)
#### CustomForm
```php
EasyForms::sendForm($sender, new class("Enter data", [
	new Dropdown("Select product", ["beer", "cheese", "cola"]),
	new Input("Enter your name", "Bob"),
	new Label("I am label!"),
	new Slider("Select count", 0.0, 100.0, 1.0, 50.0),
	new StepSlider("Select product", ["beer", "cheese", "cola"]),
	new Toggle("Creative", $sender->isCreative())
]) extends CustomForm{
	public function onSubmit(Player $player, $response) : void{
		parent::onSubmit($player, $response);
		/** @var Dropdown $dropdown */
		$dropdown = $this->elements[0];
		$player->sendMessage("You selected: {$dropdown->getSelectedOption()}");

		/** @var Input $input */
		$input = $this->elements[1];
		$player->sendMessage("Your name is {$input->getValue()}");

		/** @var Slider $slider */
		$slider = $this->elements[3];
		$player->sendMessage("Count: {$slider->getValue()}");

		/** @var StepSlider $stepSlider */
		$stepSlider = $this->elements[4];
		$player->sendMessage("You selected: {$stepSlider->getSelectedOption()}");

		/** @var Toggle $toggle */
		$toggle = $this->elements[5];
		$player->setGamemode($toggle->getValue() ? 1 : 0);
	}
});
```
![custom1](https://i.imgur.com/biAoc91.png)
![custom2](https://i.imgur.com/AFkpS7b.png)
#### ServerSettingsForm
```php
EasyForms::$settings = new ServerSettingsForm("Server settings", [
		new Label("Some text"),
		new class("Mute chat") extends Toggle{
			public function handle(Player $player, $value) : void{
				$player->sendMessage($value ? "enabled" : "disabled");
			}
		}
	]
);
```
![settigs](https://i.imgur.com/Ab0IaTl.png)
__But this form can only be used one time__
