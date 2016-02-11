<?php

namespace Cordial;

class BetaBandit {

    /**
     * getVariant accepts a list of variants, their respective impressions and 
     * conversions, and returns the "best" variant
     * 
     * @static
     * @access public
     * @param array Two dimensional array of possible variants
     * @return mixed Key from $options that should be used for variant
     */
    public static function getVariant($options)
    {
        // Variant with highest beta distribution sample
        $highestVariant = null;

        // Highest beta distribution sample we've seen
        $highestBeta = 0;

        // Look at each variant and get a beta distribution sample for that variant
        foreach ($options as $key => $data) {
            if (!isset($data['prior'])) {
                $data['prior'] = 1;
            }
            stats_rand_setall(mt_rand(1,2147483562),mt_rand(1,2147483398));
            $sample = stats_rand_gen_beta(
                $data['prior'] + $data['conversions'],
                $data['prior'] + $data['impressions'] - $data['conversions']
            );

            // Record this sample if it's the first or highest
            if (is_null($highestVariant) || $sample > $highestBeta) {
                $highestVariant = $key;
                $highestBeta = $sample;
            }
        }

        // Return the "best" variant
        return $highestVariant;
    }
}
