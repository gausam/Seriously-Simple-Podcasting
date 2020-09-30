<?php

namespace SeriouslySimplePodcasting\Controllers\Integrations\Elementor\Widgets;

use SeriouslySimplePodcasting\Controllers\Players_Controller;
use SeriouslySimplePodcasting\Renderers\Renderer;

class Elementor_Html_Player_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'HTML Player';
	}

	public function get_title() {
		return __( 'HTML Player', 'seriously-simple-podcasting' );
	}

	public function get_icon() {
		return 'fa fa-html5';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_episodes() {
		$args = array(
			'fields'          => array('post_title, id'),
			'posts_per_page'  => -1,
			'post_type' => 'podcast'
		);

		$episodes = get_posts($args);
		$episodeOptions = [];
		foreach($episodes as $episode) {
			$episodeOptions[$episode->ID] = $episode->post_title;
		}

		return $episodeOptions;
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'seriously-simple-podcasting' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$episodeOptions = $this->get_episodes();

		$this->add_control(
			'show_elements',
			[
				'label' => __( 'Show Elements', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => $episodeOptions,
				'default' => array_shift(array_values($episodeOptions))
		]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$player = new Players_Controller(__FILE__, SSP_VERSION);
		$render = new Renderer();

		$html_player_data = $player->html_player($settings['show_elements']);
		$html_player = $render->render($html_player_data, 'players/html-player');

		echo $html_player;
	}

	protected function _content_template() {
		?>
		<# _.each( settings.show_elements, function( element ) { #>
		<div>{{{ element }}}</div>
		<# } ) #>
		<?php
	}
}