<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://viktormorales.com
 * @since      1.0.0
 *
 * @package    Vhm_Usd2ars
 * @subpackage Vhm_Usd2ars/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="vhm-usd2ars" class="wrap">
<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

<form method="post" action="options.php">
<?php settings_fields( $this->plugin_name ); ?>
<textarea name="<?php echo $this->option_name?>_rates" style="display:none"><?php echo get_option($this->option_name . '_rates', true); ?></textarea>
<?php do_settings_sections( $this->plugin_name ); ?>

<h2 class="title"><?php _e('WooCommerce', $this->plugin_name); ?></h2>
<table class="form-table" role="presentation">
    <tbody>
        <tr>
            <th scope="row"><label for="<?php echo $this->option_name ?>_last_update"><?php _e('Last update', $this->plugin_name); ?></label></th>
            <td>
                <?php echo date('d/m/Y H:i:s', get_option($this->option_name . '_last_updated')); ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="<?php echo $this->option_name ?>_advice"><?php _e('Select rate exchange value', $this->plugin_name); ?></label></th>
            <td>
                <select name="<?php echo $this->option_name; ?>_selected_rate">
                    <option value=""><?php _e('Use base price', $this->plugin_name)?></option>
                    <?php
                        $rates = json_decode(get_option($this->option_name . '_rates', 
                        true), true);

                        foreach ($rates['dollar'] as $key => $dollar) {
                            ?>
                            <optgroup label="<?php echo $key?>">
                                <option value="<?php echo $key . '_buy'; ?>" <?php selected( get_option($this->option_name . '_selected_rate'), $key . '_buy' ); ?>><?php echo __('Buy', $this->plugin_name) . ': ' . $dollar['buy']; ?></option>
                                <option value="<?php echo $key . '_sell'; ?>" <?php selected( get_option($this->option_name . '_selected_rate'), $key . '_sell' ); ?>><?php echo __('Sell', $this->plugin_name) . ': ' . $dollar['sell']; ?></option>
                            </optgroup>
                            <?php
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="<?php echo $this->option_name ?>_advice"><?php _e('Select how you are displaying the price information', $this->plugin_name); ?></label></th>
            <td>
                <?php 
                    $display_price = get_option($this->option_name . '_display_price');
                    echo "<p> - " . $display_price . " - </p>";
                ?>
                <p><label><input type="radio" value="" name="<?php echo $this->option_name; ?>_display_price" <?php checked( $display_price, "", true ); ?>> <?php _e("Do not convert prices.", $this->plugin_name)?></label></p>

                <p><label><input type="radio" value="default_reference" name="<?php echo $this->option_name; ?>_display_price" <?php checked( $display_price, "default_reference", true ); ?>> <?php _e("Do not convert prices but show a reference in dollars.", $this->plugin_name)?></label></p>

                <p><label><input type="radio" value="ars" name="<?php echo $this->option_name; ?>_display_price" <?php checked( $display_price, "ars", true ); ?>> <?php _e("Convert prices to argentinian pesos.", $this->plugin_name)?></label></p>

                <p><label><input type="radio" value="ars_reference" name="<?php echo $this->option_name; ?>_display_price" <?php checked( $display_price, "ars_reference", true ); ?>> <?php _e("Convert prices to argentinian pesos and show a reference in dollars.", $this->plugin_name)?></label></p><br>
                
                <?php 
                    $usd_reference_text = get_option($this->option_name . '_usd_reference_text');
                ?>
                <p class="description"><?php _e('Reference in dollar text.', $this->plugin_name); ?></p>
                <textarea name="<?php echo $this->option_name; ?>_usd_reference_text" class="large-text"><?php echo $usd_reference_text; ?></textarea>
            </td>
        </tr>
    </tbody>
</table>

<h2 class="title"><?php _e('Help', $this->plugin_name); ?></h2>
<table class="form-table" role="presentation">
    <tbody>
        <tr>
            <th scope="row"><label><?php _e('Author', $this->plugin_name); ?></label></th>
            <td>
                <p><?php 
                    printf(
                        __('Developed and maintained by %s.', $this->plugin_name),
                        '<a href="//viktormorales.com">Victor H. Morales</a>'
                    )
                ?></p><br>
                <p class="description"><?php _e('Use the official contact information on the author website.', $this->plugin_name)?></p><br>
            </td>
        </tr>
    </tbody>
</table>

<?php submit_button(); ?>

</div>