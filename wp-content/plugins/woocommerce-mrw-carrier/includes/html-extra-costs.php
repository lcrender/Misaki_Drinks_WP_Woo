<?php
// Admin View: Extra costs table

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;
$range_table = $wpdb->prefix . "mrw_ranges";
$states_table = $wpdb->prefix . "mrw_cities";
$taxes_table = $wpdb->prefix . "mrw_taxes";

$ranges = $wpdb->get_results( "SELECT * FROM $range_table", ARRAY_A );
$states = $wpdb->get_results( "SELECT * FROM $states_table", ARRAY_A );
$taxes  = $wpdb->get_results( "SELECT * FROM $taxes_table", ARRAY_A );

$ranges_count = $wpdb->get_var( "SELECT COUNT(*) FROM $range_table" );

function include_mrw_scripts2() {
	
	wp_register_script( 'mrw-tablerates-script', plugins_url() . '/woocommerce-mrw-carrier/js/mrw-carrier-tablerates.js', array(), '1.0.0', true );
	wp_enqueue_script( 'mrw-tablerates-script');
	
	wp_localize_script( 'ajax-script', 'ajax_object',
		array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
}
add_action( 'admin_enqueue_scripts', 'include_mrw_scripts' );

?>

<!-- Ajax load -->
<div id="div_loader">
<img id="load" src="../wp-content/plugins/woocommerce-mrw-carrier/img/ajax-loader2.gif"/>
</div>

<!--Ajax loader-->
<th scope="row" class="titledesc"><?php echo __( 'Tablerate', 'woocommerce-mrw-carrier' ); ?></th>
<table id="mrw_table" class="table" style="max-width:100%">
	<tbody>
		<!-- Show ranges -->
		<tr id="range_inf">
			<td class="range_type"></td>
			<td class="border_left border_bottom range_sign">&gt;=</td>
			<?php
			foreach ($ranges as $range){
			$range_id = $range['range_id'];
			$min = $range['min'];
			?>
			<td class="border_bottom">
				<div id="range_inf[<?php echo $range_id;?>]">
					<input class="ranges_inf" name="range_inf[<?php echo $range_id; ?>]" type="text" value="<?php echo $min; ?>" />
					<span class="weight_unit">Kg</span>
					<span class="price_unit">€</span>
				</div>
			</td>

			<?php }
			//If ranges count < 5 add new range hidden. Show it when click in new range
			if($ranges_count <= 20)
			{
			?>
			<td class="border_bottom new_range">
				<div id="range_inf[<?php echo $range_id + 1;?>]">
					<input class="new_inf ranges_inf_new" name="range_inf[<?php echo $ranges_count + 1; ?>]" type="text" value="0" />
					<span class="weight_unit">Kg</span>
					<span class="price_unit">€</span>
				</div>
			</td>
			<?php
			}
			?>
		</tr>
		<tr id="range_sup" class="range_sup">
			<td class="range_type"></td>
			<td class="border_left range_sign">&lt;</td>
			<?php
			foreach ($ranges as $range){
			$range_id = $range['range_id'];
			$max = $range['max'];
			?>
			<td class="range_data">
				<div id="range_sup[<?php echo $range_id;?>]">
					<input class="ranges_sup" name="range_sup[<?php echo $range_id; ?>]" type="text" value="<?php echo $max; ?>" autocomplete="off"/>
					<span class="weight_unit">Kg</span>
					<span class="price_unit">€</span>
				</div>
			</td>
			<?php }
			//If ranges count < 5 add new range hidden. Show it when click in new range
			if($ranges_count <= 20)
			{
			?>
			<td class="range_data new_range">
				<div id="range_sup[<?php echo $range_id + 1;?>]">
					<input class="new_sup ranges_sup_new" name="range_sup[<?php echo $range_id + 1; ?>]" type="text" value="0" autocomplete="off"/>
					<span class="weight_unit">Kg</span>
					<span class="price_unit">€</span>
				</div>
			</td>
			<?php
			}
			?>
		</tr>

		<!-- Show cities. -->

		<tr class="fees_all">
		<td class="all">
			<span class="fees_all"><?php echo __( 'All', 'woocommerce-mrw-carrier' ); ?></span>
		</td>
		<td style="">
			<input id="All" type="checkbox" />
		</td>
		<?php
			foreach ($states as $state){
			$city_id = $state['city_id'];
			$country_id = $state['country_id'];
			$city_name = $state['city'];
			$city_available = $state['available'];
			?>
		<tr id="fees_id" class="fees" data-zoneid="<?php echo $city_id; ?>" data-countryid="<?php echo $country_id; ?>">
			<td>
				<label for="zone_<?php echo $country_id; ?>" id="zone_name_<?php echo $city_id; ?>"><?php echo $city_name; ?></label>
			</td>
			<td class="zone">
				<input type="checkbox" class="input_zone fees1" id="<?php echo $city_id; ?>" name="zone_<?php echo $city_id; ?>" value="<?php echo $country_id; ?>"  <?php if($city_available != 0){ ?>checked="checked"><?php } ?>
			</td>
			<?php
				foreach ($ranges as $range){
				$range_id = $range['range_id'];
				$min = $range['min'];
				?>
			<td class="mrw-range">
				<div class="input-group fixed-width-md">

					<!-- Check if there is any price assigned to the tupla (city, range).-->

					<input class="fees2" id=<?php echo $country_id;?> name="fees[<?php echo $city_id; ?>][<?php echo $range_id; ?>]" type="text"
					<?php
						$price  = $wpdb->get_var( "SELECT price FROM $taxes_table WHERE country_id = '$country_id' AND city_id = '$city_id' AND range_id = '$range_id'");

						if($price == NULL){ 
					?> value="0"
					<?php }
						if ($city_available == 0){ 
					?> disabled="disabled" value="<?php echo $price; ?>"/>
					<?php }
						else {
					?> value="<?php echo $price; ?>" />
					<?php } 
					?>
					<span class="price_un">€</span>
				</div>
			</td>
			<?php
			}

			if($ranges_count <= 20)
			{
			?>
			<td class="new_range">
				<div>
					<!-- Check if there is any price assigned to the tupla (city, range).-->
					<input class="new_fees fees2_new" id=<?php echo $country_id;?> name="fees[<?php echo $city_id; ?>][<?php echo $ranges_count + 1; ?>]" type="text" value="0" />
					<span class="price_un">€</span>
				</div>
			</td>
			<?php
			}
			?>
		</tr>

		<?php } ?>

		<td id="tablerate_buttons">
		<!-- Button to delete range -->
		<div id="delete_range">
			<?php
				if (count($ranges) > 1)
				{?>
						<input id="btn_delete_range" type="button" method="POST" name="<?php echo count($ranges) ?>" class="button-primary" value="<?php echo __('Delete range', 'woocommerce-mrw-carrier');?>"/>
				<?php }
				else{
				?>
						<input id="btn_delete_range" type="button" method="POST" name="<?php echo count($ranges) ?>" class="button-primary" value="<?php echo __('Delete range', 'woocommerce-mrw-carrier');?>" style="display:none"/>
				<?php
				}
			?>
		</div>

		<!-- Button to add range -->
		<div id="add_range">
			<?php
				if (count($ranges) < 20)
				{?>
						<input id="btn_add_range" type="button" method="POST" name="btn_add_range" class="button-primary" value="<?php echo __('Add range', 'woocommerce-mrw-carrier');?>"/>
				<?php }
				else{
				{?>
						<input id="btn_add_range" type="button" method="POST" name="btn_add_range" class="button-primary" value="<?php echo __('Add range', 'woocommerce-mrw-carrier');?>" style="display:none"/>
				<?php
				}
			}?>
		</div>

		<!-- Button to save taxes -->
		<div id="save_taxes">
				<input id="btn_save" type="button" method="POST" name="btn_save" class="button-primary" value="<?php echo __('Save taxes', 'woocommerce-mrw-carrier');?>"/>
		</div>
	</td>

	</tbody>
</table>


