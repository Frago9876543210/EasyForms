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
	new Label("I am label!"), //popElement() does not work with label
	new Slider("Select count", 0.0, 100.0, 1.0, 50.0),
	new StepSlider("Select product", ["beer", "cheese", "cola"]),
	new Toggle("Creative", $sender->isCreative())
]) extends CustomForm{
	public function onSubmit(Player $player, $response) : void{
		parent::onSubmit($player, $response);
		/** @var Dropdown $dropdown */
		$dropdown = $this->popElement();
		$player->sendMessage("You selected: {$dropdown->getSelectedOption()}");

		/** @var Input $input */
		$input = $this->popElement();
		$player->sendMessage("Your name is {$input->getValue()}");

		/** @var Slider $slider */
		$slider = $this->popElement();
		$player->sendMessage("Count: {$slider->getValue()}");

		/** @var StepSlider $stepSlider */
		$stepSlider = $this->popElement();
		$player->sendMessage("You selected: {$stepSlider->getSelectedOption()}");

		/** @var Toggle $toggle */
		$toggle = $this->popElement();
		$player->setGamemode($toggle->getValue() ? 1 : 0);
	}
});
```
![custom1](https://i.imgur.com/biAoc91.png)
![custom2](https://i.imgur.com/AFkpS7b.png)
#### ServerSettingsForm
```php
public function onServerSettingsRequest(ServerSettingsRequestEvent $e) : void{
	$player = $e->getPlayer();
	EasyForms::sendForm($player, new class("Server settings", [
		new Label("Some text"),
		new Toggle("Fly", $player->isFlying())
	], "http://icons.iconarchive.com/icons/double-j-design/diagram-free/128/settings-icon.png") extends ServerSettingsForm{
		public function onSubmit(Player $player, $response) : void{
			parent::onSubmit($player, $response);
			/** @var Toggle $toggle */
			$toggle = $this->popElement();
			$player->setAllowFlight((bool) $toggle->getValue());
		}
	});
}
```
![settings](https://i.imgur.com/Yic6LuA.png)
