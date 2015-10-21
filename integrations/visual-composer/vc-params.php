<?php
/**
 * Mm Components Visual Composer Custom Param Types.
 *
 * @since 1.0.0
 *
 * @package mm-components
 */

/**
 * Visual Composer Custom Date Param.
 *
 * @since  1.0.0
 *
 * @param   array   $settings  The param settings.
 * @param   string  $value     The param value.
 *
 * @return  string             The param HTML.
 */
function mm_vc_date_param( $settings, $value ) {

	$output = sprintf( '<input type="date" class="countdown-date wpb_vc_param_value" value="%s" name="%s" />',
		$value,
		esc_attr( $settings['param_name'] )
	);

	return $output;
}

/**
 * Visual Composer Custom Timezone Param.
 *
 * @since   1.0.0
 *
 * @param   array   $settings  The param settings.
 * @param   string  $value     The param value.
 *
 * @return  string             The param HTML.
 */
function mm_vc_timezone_param( $settings, $value ) {

	$output = '<select name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value">
	<option value="GMT-1200" ' . selected( $value, 'GMT-1200' ) . '>(GMT -12:00) Eniwetok, Kwajalein</option>
	<option value="GMT-1100" ' . selected( $value, 'GMT-1100' ) . '>(GMT -11:00) Midway Island, Samoa</option>
	<option value="GMT-1000" ' . selected( $value, 'GMT-1000' ) . '>(GMT -10:00) Hawaii</option>
	<option value="GMT-0900" ' . selected( $value, 'GMT-0900' ) . '>(GMT -9:00) Alaska</option>
	<option value="GMT-0800" ' . selected( $value, 'GMT-0800' ) . '>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
	<option value="GMT-0700" ' . selected( $value, 'GMT-0700' ) . '>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
	<option value="GMT-0600" ' . selected( $value, 'GMT-0600' ) . '>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
	<option value="GMT-0500" ' . selected( $value, 'GMT-0500' ) . '>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
	<option value="GMT-0430" ' . selected( $value, 'GMT-0430' ) . '>(GMT -4:30) Caracas</option>
	<option value="GMT-0400" ' . selected( $value, 'GMT-0400' ) . '>(GMT -4:00) Atlantic Time (Canada), La Paz, Santiago</option>
	<option value="GMT-0330" ' . selected( $value, 'GMT-0330' ) . '>(GMT -3:30) Newfoundland</option>
	<option value="GMT-0300" ' . selected( $value, 'GMT-0300' ) . '>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
	<option value="GMT-0200" ' . selected( $value, 'GMT-0200' ) . '>(GMT -2:00) Mid-Atlantic</option>
	<option value="GMT-0100" ' . selected( $value, 'GMT-0100' ) . '>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
	<option value="GMT" ' . selected( $value, 'GMT' ) . '>(GMT) Western Europe Time, London, Lisbon, Casablanca, Greenwich</option>
	<option value="GMT+0100" ' . selected( $value, 'GMT+0100' ) . '>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
	<option value="GMT+0200" ' . selected( $value, 'GMT+0200' ) . '>(GMT +2:00) Kaliningrad, South Africa, Cairo</option>
	<option value="GMT+0300" ' . selected( $value, 'GMT+0300' ) . '>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
	<option value="GMT+0330" ' . selected( $value, 'GMT+0330' ) . '>(GMT +3:30) Tehran</option>
	<option value="GMT+0400" ' . selected( $value, 'GMT+0400' ) . '>(GMT +4:00) Abu Dhabi, Muscat, Yerevan, Baku, Tbilisi</option>
	<option value="GMT+0430" ' . selected( $value, 'GMT+0430' ) . '>(GMT +4:30) Kabul</option>
	<option value="GMT+0500" ' . selected( $value, 'GMT+0500' ) . '>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
	<option value="GMT+0530" ' . selected( $value, 'GMT+0530' ) . '>(GMT +5:30) Mumbai, Kolkata, Chennai, New Delhi</option>
	<option value="GMT+0545" ' . selected( $value, 'GMT+0545' ) . '>(GMT +5:45) Kathmandu</option>
	<option value="GMT+0600" ' . selected( $value, 'GMT+0600' ) . '>(GMT +6:00) Almaty, Dhaka, Colombo</option>
	<option value="GMT+0630" ' . selected( $value, 'GMT+0630' ) . '>(GMT +6:30) Yangon, Cocos Islands</option>
	<option value="GMT+0700" ' . selected( $value, 'GMT+0700' ) . '>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
	<option value="GMT+0800" ' . selected( $value, 'GMT+0800' ) . '>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
	<option value="GMT+0900" ' . selected( $value, 'GMT+0900' ) . '>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
	<option value="GMT+0930" ' . selected( $value, 'GMT+0930' ) . '>(GMT +9:30) Adelaide, Darwin</option>
	<option value="GMT+1000" ' . selected( $value, 'GMT+1000' ) . '>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
	<option value="GMT+1100" ' . selected( $value, 'GMT+1100' ) . '>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
	<option value="GMT+1200" ' . selected( $value, 'GMT+1200' ) . '>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
	</select>';

	return $output;
}