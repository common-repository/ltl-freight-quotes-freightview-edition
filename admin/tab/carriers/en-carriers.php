<?php

/**
 * Carriers show.
 */

namespace EnFreightviewCarriers;

/**
 * Show and update carriers.
 * Class EnFreightviewCarriers
 * @package EnFreightviewCarriers
 */
if (!class_exists('EnFreightviewCarriers')) {

    class EnFreightviewCarriers {

        /**
         * Show Carriers
         */
        static public function en_load() {
            self::en_save_carriers();
            $en_freightview_carriers = EnFreightviewCarriers::en_freightview_carriers();
            echo '<div class="en_freightview_carriers">';
            echo '<form method="post">';
            echo force_balance_tags('<p>Identifies which carriers are included in the quote response, not what is displayed in the shopping cart. Identify what displays in the shopping cart in the Quote Settings. For example, you may include quote responses from all carriers, but elect to only show the cheapest three in the shopping cart.  <br> <br> Not all carriers service all origin and destination points. If a carrier doesn`t service the ship to address, it is automatically omitted from the quote response. Consider conferring with your Worldwide Express representative if you`d like to narrow the number of carrier responses.</p>');
            echo '<table>';
            echo '<tr>';
            echo '<th>Carrier Name</th>';
            echo '<th>Logo</th>';
            echo '<th> <input type="checkbox" id="en_freightview_total_carriers"> </th>';
            echo '</tr>';
            $en_checked_carriers = get_option('en_freightview_carriers');
            $en_checked_carriers = (isset($en_checked_carriers) && strlen($en_checked_carriers) > 0) ? json_decode($en_checked_carriers, true) : [];

            foreach ($en_freightview_carriers as $key => $value) {

                $en_freightview_carrier = in_array($value['en_standard_carrier_alpha_code'], $en_checked_carriers) ? "checked='checked'" : '';

                echo '<tr>';
                echo '<td> ' . esc_attr($value['en_freightview_carrier_name']) . ' </td>';
                echo '<td> <img alt="carriers"  src="' . esc_attr(EN_FREIGHTVIEW_DIR_FILE) . '/admin/tab/carriers/assets/' . esc_attr($value['en_freightview_carrier_logo']) . '"> </td>';
                echo '<td> <input type="checkbox" class="en_freightview_carrier" name="en_freightview_carrier[]" value="' . esc_attr($value['en_standard_carrier_alpha_code']) . '" ' . $en_freightview_carrier . '> </td>';
                echo '</tr>';
            }

            echo '</form>';
            echo '</table>';
            echo '</div>';
        }

        /**
         * Carriers Save Data
         */
        static public function en_save_carriers() {
            if (isset($_POST['en_freightview_carrier']) && (!empty($_POST['en_freightview_carrier']))) {
                $en_freightview_carrier = array_map('sanitize_text_field', $_POST['en_freightview_carrier']);;
                update_option('en_freightview_carriers', wp_json_encode($en_freightview_carrier));

                echo "<script type='text/javascript'>
                window.location=document.location.href;
            </script>";
            }
        }

        /**
         * Carriers Data
         */
        static public function en_freightview_carriers() {
            $carrier = [
                [
                    'en_standard_carrier_alpha_code' => 'pyle',
                    'en_freightview_carrier_name' => 'A. Duie Pyle',
                    'en_freightview_carrier_logo' => 'pyle.jpg'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'aatj',
                    'en_freightview_carrier_name' => 'Access America Transport',
                    'en_freightview_carrier_logo' => 'aatj.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'AACT',
                    'en_freightview_carrier_name' => 'AAA COOPER',
                    'en_freightview_carrier_logo' => 'AACT.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'ABFS',
                    'en_freightview_carrier_name' => 'ABF FREIGHT SYSTEM, INC.',
                    'en_freightview_carrier_logo' => 'ABFS.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'avrt',
                    'en_freightview_carrier_name' => 'Averitt Express',
                    'en_freightview_carrier_logo' => 'avrt.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'beav',
                    'en_freightview_carrier_name' => 'Beaver Express Service',
                    'en_freightview_carrier_logo' => 'beav.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'btnb',
                    'en_freightview_carrier_name' => 'Benton Global',
                    'en_freightview_carrier_logo' => 'btnb.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'btvp',
                    'en_freightview_carrier_name' => 'Best Overnite Express',
                    'en_freightview_carrier_logo' => 'btvp.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'bglf',
                    'en_freightview_carrier_name' => 'Blue-Grace Logistics',
                    'en_freightview_carrier_logo' => 'bglf.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'bxpe',
                    'en_freightview_carrier_name' => 'Bolt Express',
                    'en_freightview_carrier_logo' => 'bxpe.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'bahf',
                    'en_freightview_carrier_name' => 'B&H Freight',
                    'en_freightview_carrier_logo' => 'bahf.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'cenf',
                    'en_freightview_carrier_name' => 'Central Freight Lines',
                    'en_freightview_carrier_logo' => 'cenf.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'ctii',
                    'en_freightview_carrier_name' => 'Central Transport',
                    'en_freightview_carrier_logo' => 'ctii.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'rblt',
                    'en_freightview_carrier_name' => 'CH Robinson',
                    'en_freightview_carrier_logo' => 'rblt.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'clni',
                    'en_freightview_carrier_name' => 'Clear Lane Freight Systems',
                    'en_freightview_carrier_logo' => 'clni.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'cllq',
                    'en_freightview_carrier_name' => 'Coyote Logistics',
                    'en_freightview_carrier_logo' => 'cllq.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'ctbv',
                    'en_freightview_carrier_name' => 'The Custom Companies',
                    'en_freightview_carrier_logo' => 'ctbv.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'dylt',
                    'en_freightview_carrier_name' => 'Daylight Transport',
                    'en_freightview_carrier_logo' => 'dylt.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'DAFG',
                    'en_freightview_carrier_name' => 'DAYTON FREIGHT LINES, INC.',
                    'en_freightview_carrier_logo' => 'DAFG.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'DHRN',
                    'en_freightview_carrier_name' => 'DOHRN TRANSFER COMPANY',
                    'en_freightview_carrier_logo' => 'DHRN.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'dphe',
                    'en_freightview_carrier_name' => 'Dependable Highway Express',
                    'en_freightview_carrier_logo' => 'dphe.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'dolr',
                    'en_freightview_carrier_name' => 'DotLine Transportation',
                    'en_freightview_carrier_logo' => 'dolr.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'dubl',
                    'en_freightview_carrier_name' => 'Dugan Truck Line',
                    'en_freightview_carrier_logo' => 'dubl.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'echo',
                    'en_freightview_carrier_name' => 'Echo Global Logistics',
                    'en_freightview_carrier_logo' => 'echo.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'edxi',
                    'en_freightview_carrier_name' => 'EDI Express',
                    'en_freightview_carrier_logo' => 'edxi.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'exla',
                    'en_freightview_carrier_name' => 'Estes Express Lines',
                    'en_freightview_carrier_logo' => 'exla.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'fxfe',
                    'en_freightview_carrier_name' => 'FedEx Freight',
                    'en_freightview_carrier_logo' => 'fxfe.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'EQXT',
                    'en_freightview_carrier_name' => 'EXPRESS 2000 INC',
                    'en_freightview_carrier_logo' => 'EQXT.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'flan',
                    'en_freightview_carrier_name' => 'Flo Trans',
                    'en_freightview_carrier_logo' => 'flan.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'FDXG',
                    'en_freightview_carrier_name' => 'FED-EX GROUND',
                    'en_freightview_carrier_logo' => 'FDXG.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'fwdn',
                    'en_freightview_carrier_name' => 'Forward Air',
                    'en_freightview_carrier_logo' => 'fwdn.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'frcd',
                    'en_freightview_carrier_name' => 'Freightcenter',
                    'en_freightview_carrier_logo' => 'frcd.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'frci',
                    'en_freightview_carrier_name' => 'Freightquote',
                    'en_freightview_carrier_logo' => 'frci.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'fcsy',
                    'en_freightview_carrier_name' => 'Frontline Freight',
                    'en_freightview_carrier_logo' => 'fcsy.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'gelj',
                    'en_freightview_carrier_name' => 'GlobalTranz',
                    'en_freightview_carrier_logo' => 'gelj.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'hmes',
                    'en_freightview_carrier_name' => 'USF Holland',
                    'en_freightview_carrier_logo' => 'hmes.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'bkhd',
                    'en_freightview_carrier_name' => 'HomeDirect',
                    'en_freightview_carrier_logo' => 'bkhd.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'laxv',
                    'en_freightview_carrier_name' => 'Land Air Express of New England',
                    'en_freightview_carrier_logo' => 'laxv.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'lkvl',
                    'en_freightview_carrier_name' => 'Lakeville Motor Express',
                    'en_freightview_carrier_logo' => 'lkvl.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'midw',
                    'en_freightview_carrier_name' => 'Midwest Motor Express',
                    'en_freightview_carrier_logo' => 'midw.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'nebt',
                    'en_freightview_carrier_name' => 'Nebraska Transport',
                    'en_freightview_carrier_logo' => 'nebt.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'ncta',
                    'en_freightview_carrier_name' => 'New Century Transportation',
                    'en_freightview_carrier_logo' => 'ncta.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'nemf',
                    'en_freightview_carrier_name' => 'New England Motor Freight',
                    'en_freightview_carrier_logo' => 'nemf.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'npme',
                    'en_freightview_carrier_name' => 'New Penn Motor Express',
                    'en_freightview_carrier_logo' => 'npme.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'oakh',
                    'en_freightview_carrier_name' => 'Oak Harbor Freight Lines',
                    'en_freightview_carrier_logo' => 'oakh.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'ODFL',
                    'en_freightview_carrier_name' => 'OLD DOMINION FREIGHT LINE, INC.',
                    'en_freightview_carrier_logo' => 'ODFL.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'pana',
                    'en_freightview_carrier_name' => 'Panama Transfer',
                    'en_freightview_carrier_logo' => 'pana.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'ptwt',
                    'en_freightview_carrier_name' => 'Panther Premium Logistics',
                    'en_freightview_carrier_logo' => 'ptwt.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'paaf',
                    'en_freightview_carrier_name' => 'Pilot Freight Services',
                    'en_freightview_carrier_logo' => 'paaf.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'pitd',
                    'en_freightview_carrier_name' => 'Pitt Ohio',
                    'en_freightview_carrier_logo' => 'pitd.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'rlca',
                    'en_freightview_carrier_name' => 'R+L Carriers',
                    'en_freightview_carrier_logo' => 'rlca.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'rjwi',
                    'en_freightview_carrier_name' => 'RJW Transport',
                    'en_freightview_carrier_logo' => 'rjwi.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'retl',
                    'en_freightview_carrier_name' => 'Reddaway',
                    'en_freightview_carrier_logo' => 'retl.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'RDFS',
                    'en_freightview_carrier_name' => 'ROADRUNNER TRANSPORTATION SERVICES',
                    'en_freightview_carrier_logo' => 'RDFS.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'saia',
                    'en_freightview_carrier_name' => 'SAIA MOTOR FREIGHT LINE, INC.',
                    'en_freightview_carrier_logo' => 'SAIA.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'SEFL',
                    'en_freightview_carrier_name' => 'SOUTHEASTERN FREIGHT LINES, INC.',
                    'en_freightview_carrier_logo' => 'SEFL.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'smtl',
                    'en_freightview_carrier_name' => 'Southwestern Motor Transport',
                    'en_freightview_carrier_logo' => 'smtl.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'stdf',
                    'en_freightview_carrier_name' => 'Standard Forwarding',
                    'en_freightview_carrier_logo' => 'stdf.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'sfpa',
                    'en_freightview_carrier_name' => 'Sunset Pacific Transportation',
                    'en_freightview_carrier_logo' => 'sfpa.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'suon',
                    'en_freightview_carrier_name' => 'Sutton Transport',
                    'en_freightview_carrier_logo' => 'suon.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'tqyl',
                    'en_freightview_carrier_name' => 'Total Quality Logistics',
                    'en_freightview_carrier_logo' => 'tqyl.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'towe',
                    'en_freightview_carrier_name' => 'Towne Air Freight',
                    'en_freightview_carrier_logo' => 'towe.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'ulrd',
                    'en_freightview_carrier_name' => 'Unishippers',
                    'en_freightview_carrier_logo' => 'ulrd.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'UPSN',
                    'en_freightview_carrier_name' => 'UPS GROUND',
                    'en_freightview_carrier_logo' => 'UPSN.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'uvln',
                    'en_freightview_carrier_name' => 'United Van Lines',
                    'en_freightview_carrier_logo' => 'uvln.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'upgf',
                    'en_freightview_carrier_name' => 'TForce Freight',
                    'en_freightview_carrier_logo' => 'tforce.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'ward',
                    'en_freightview_carrier_name' => 'Ward Transport',
                    'en_freightview_carrier_logo' => 'ward.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'xgsi',
                    'en_freightview_carrier_name' => 'Xpress Global Systems',
                    'en_freightview_carrier_logo' => 'xgsi.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'usrd',
                    'en_freightview_carrier_name' => 'U.S. Road',
                    'en_freightview_carrier_logo' => 'usrd.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'rdwy',
                    'en_freightview_carrier_name' => 'YRC Freight',
                    'en_freightview_carrier_logo' => 'rdwy.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'cnwy',
                    'en_freightview_carrier_name' => 'XPO LOGISTICS FREIGHT, INC. (LTL)',
                    'en_freightview_carrier_logo' => 'xpo-logistics.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'RDTC',
                    'en_freightview_carrier_name' => 'YRC FREIGHT - TIME CRITICAL',
                    'en_freightview_carrier_logo' => 'RDTC.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'YRCA',
                    'en_freightview_carrier_name' => 'YRC FREIGHT ACCELERATED',
                    'en_freightview_carrier_logo' => 'YRCA.png'
                ],
                [
                    'en_standard_carrier_alpha_code' => 'zpxs',
                    'en_freightview_carrier_name' => 'Zip Xpress Inc.',
                    'en_freightview_carrier_logo' => 'zip-xpress.png'
                ]
            ];

            return $carrier;
        }

    }

}
