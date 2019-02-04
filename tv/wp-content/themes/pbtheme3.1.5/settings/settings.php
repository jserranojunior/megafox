<?php global $pbtheme_data; ?>
<style>
.settings_button.activated {
	background:<?php echo $pbtheme_data['theme_color']; ?> !important;
}
</style>
<div id="pbtheme_settings">
	<div id="div_current_color" style="background:<?php echo $pbtheme_data['theme_color']; ?>"></div>
	<a id="show_hide_settings" href="#"><i class="fa fa-cog"></i></a>

		<h4 class="pbtheme_header_font">Pick a color</h4>
		<div class="settings_element pick_a_color">
			<div class="color_pick pbtheme_green_1" data-color="green_1"></div>
			<div class="color_pick pbtheme_green" data-color="green"></div>
			<div class="color_pick pbtheme_turquoise" data-color="turquoise"></div>
			<div class="color_pick pbtheme_yellow" data-color="yellow"></div>
			<div class="color_pick pbtheme_orange_1" data-color="orange_1"></div>
			<div class="color_pick pbtheme_orange" data-color="orange"></div>
			<div class="color_pick pbtheme_red" data-color="red"></div>
			<div class="color_pick pbtheme_magenta" data-color="magenta"></div>
			<div class="color_pick pbtheme_purple" data-color="purple"></div>
			<div class="color_pick pbtheme_pale" data-color="pale"></div>
			<div class="color_pick pbtheme_navy" data-color="navy"></div>
			<div class="color_pick pbtheme_blue_1" data-color="blue_1"></div>
			<div class="color_pick pbtheme_blue" data-color="blue"></div>
			<div class="color_pick pbtheme_blue_2" data-color="blue_2"></div>
			<div class="color_pick pbtheme_pink" data-color="pink"></div>
			<div class="clearfix"></div>
		</div>

		<h4 class="pbtheme_header_font">Boxed/Wide</h4>
		<div class="settings_element boxed_wide">
			<div id="settings_boxed" class="settings_button the_half">Boxed</div>
			<div id="settings_wide" class="settings_button the_half">Wide</div>
			<div class="clearfix"></div>
		</div>

		<h4 class="pbtheme_header_font">Content width</h4>
		<div class="settings_element current_width">
			<div id="width-1200" class="settings_button settings_button_width the_third">1200</div>
			<div id="width-1040" class="settings_button settings_button_width the_third">1040</div>
			<div id="width-960" class="settings_button settings_button_width the_third">960</div>
			<div class="clearfix"></div>
		</div>

		<h4 class="pbtheme_header_font">Select header style</h4>
		<div class="settings_element">
			<select id="settings-header-layout">
				<option>news-central</option>
				<option>small-left</option>
				<option selected>small-right</option>
			</select>
		</div>

		<h4 class="pbtheme_picker_logo">Logo size</h4>
		<div class="settings_element">
			<select id="settings-logo">
				<option value="div_logo_normal">Normal</option>
				<option value="div_logo_bigger">Bigger</option>
				<option value="div_logo_biggest">Biggest</option>
			</select>
		</div>

		<h4 class="pbtheme_header_font">Select headings font</h4>
		<div class="settings_element">
			<select id="settings-headings-font">
				<option>Sanchez</option>
				<option>PT Sans</option>
				<option>Open Sans</option>
				<option>Roboto</option>
				<option>Oswald</option>
				<option>Lato</option>
				<option>Droid Sans</option>
				<option>Ubuntu</option>
				<option>Montserrat</option>
				<option>Dosis</option>
				<option>Roboto Slab</option>
				<option>Josefin Sans</option>
			</select>
		</div>

		<h4 class="pbtheme_picker_background">Background (Boxed)</h4>
		<div class="settings_element">
			<select id="settings-background">
				<option>none.png</option>
				<option>black-Linen.png</option>
				<option>crissXcross.png</option>
				<option>diagonal-noise.png</option>
				<option>graphy.png</option>
				<option>noise_pattern_with_crosslines.png</option>
				<option>old_mathematics.png</option>
				<option>whitediamond.png</option>
			</select>
		</div>
</div>