<?php

	class extension_backend_javascriptr extends Extension {

		public function about(){
			return array(
				'name' => 'Backend JavaScriptr',
				'version' => '0.1',
				'release-date' => '2011-04-18',
				'author' => array(
					'name' => 'João Barbosa',
					'website' => 'http://www.joaootavio.com.br',
					'email' => 'falecom@joaootavio.com.br'
				)
			);
		}

		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/backend/',
					'delegate' => 'AdminPagePreGenerate',
					'callback' => 'appendMyCode'
				),
				array(
					'page' => '/backend/',
					'delegate' => 'InitaliseAdminPageHead',
					'callback' => 'appendCodeMirror'
				),
				array(
					'page' => '/system/preferences/',
					'delegate' => 'AddCustomPreferenceFieldsets',
					'callback' => 'appendCodeBox'
				),
			);
		}

		public function appendMyCode($context){
			
			// Add the custom code to the head of the page
			Administration::instance()->Page->addScriptToHead(URL . '/extensions/backend_javascriptr/assets/custom.js', 100, false);
			
		}
		
		public function appendCodeBox($context){
		
			if(isset($_POST['action']['custom_js_save'])){
				# Path to our file
				$custom_file = getcwd() . '/extensions/backend_javascriptr/assets/custom.js';
				# Open the file and reset it, to recieve the new code
				$open_file = fopen($custom_file, 'w');
				# Write it, then close
				fwrite($open_file, $_POST['custom_backend_js']);
				fclose($open_file);
				
			}

			$group = new XMLElement('fieldset');
			$group->setAttribute('class', 'settings');
			$group->appendChild(new XMLElement('legend', __('Backend JavaScriptr')));


			$div = new XMLElement('div', NULL, array('class' => 'label'));
			$span = new XMLElement('span', NULL, array('class' => 'frame'));

			$span->appendChild(new XMLElement('button', __('Save'), array('name' => 'action[custom_js_save]', 'type' => 'submit')));
			
			$label = Widget::Label(__('Your JavaScript goes here:'));
			
			# Retrieve the stored code to put it inside the textarea
			$custom_js_content = file_get_contents(getcwd() . '/extensions/backend_javascriptr/assets/custom.js');

			$label->appendChild(Widget::Textarea('custom_backend_js', 10, 50, $custom_js_content, array('id' => 'backend_javascriptr_field') ));
			
			$div->appendChild($label);
			$div->appendChild($span);

			$div->appendChild(new XMLElement('p', __('Remember: The "extensions/backend_javascriptr/assets/custom.js" has to have all permissions ( chmod 777 ).'), array('class' => 'help')));

			$group->appendChild($div);
			$context['wrapper']->appendChild($group);
		}
		
		public function appendCodeMirror($context){
			#Future...
		}
		
	}
