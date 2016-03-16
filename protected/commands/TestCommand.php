<?php 

	class TestCommand extends CConsoleCommand {
		public function run($args){
			echo "Hello!";
			$run = new Run();
			if ($run -> save()) {
				echo "saved";
			} else {
				var_dump($run -> getErrors());
			}
			
		}
	}

?>