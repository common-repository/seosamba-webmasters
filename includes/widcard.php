<?php

/**
 * Created by Oleg Malii
 * Date: 04.08.17 12:51
 */
class SeosfwmWidcard {
    /**
     * List of available Widcard options
     *
     * Address1
     * Address2
     * City
     * CountryState
     * StateName
     * Zip
     * Email
     * FbAccount
     * GplusAccount
     * LinkedInAccount
     * TwitAccount
     * YoutubeChannel
     * RssChannel
     * OrganizationDescription
     * OrganizationName
     * OrganizationCountry
     * OrganizationCountryName
     * IndustryName
     * IndustryType
     * Phone
     * MSA
     *
     * Short-code example: [widcard option="OrganizationName"]
     *
     * @param Array $atts
     * @return String
     */
    public function get_widcard_option($atts) {
        $fieldValue = get_option( SeosambaWebmasters::WIDCARD_PREFIX . ucfirst( $atts[ 'option' ] ) );
        $fieldValueJson = json_decode($fieldValue, true);

        if( !empty( $fieldValueJson ) && is_array( $fieldValueJson ) ) {
            $fieldValue = implode(',', $fieldValueJson);
        }

        return $fieldValue;
    }

}