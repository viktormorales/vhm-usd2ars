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
<textarea name="<?php echo $this->option_name?>_rate_exchange" style="display:none"><?php echo get_option($this->option_name . '_rate_exchange', true); ?></textarea>
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
                <select name="<?php echo $this->option_name; ?>_default">
                    <option value=""><?php _e('Use base price', $this->plugin_name)?></option>
                    <?php
                        $rate_exchange = json_decode(get_option($this->option_name . '_rate_exchange', 
                        true), true);

                        foreach ($rate_exchange['dollar'] as $key => $dollar) {
                            ?>
                            <optgroup label="<?php echo $key?>">
                                <option value="<?php echo $key . '_buy'; ?>" <?php selected( get_option($this->option_name . '_default'), $key . '_buy' ); ?>><?php echo __('Buy', $this->plugin_name) . ': ' . $dollar['buy']; ?></option>
                                <option value="<?php echo $key . '_sell'; ?>" <?php selected( get_option($this->option_name . '_default'), $key . '_sell' ); ?>><?php echo __('Sell', $this->plugin_name) . ': ' . $dollar['sell']; ?></option>
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
                <input type="radio" value="value1" name="display"> I have prices in dollars and i want to show them in pesos.
                <input type="radio" value="value2" name="display"> I have prices in pesos and I want to show a reference in dollars.
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