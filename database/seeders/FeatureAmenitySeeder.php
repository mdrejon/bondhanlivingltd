<?php

namespace Database\Seeders;

use App\Models\GlobalSetting;
use Illuminate\Database\Seeder;

class FeatureAmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            "Quality defined by Bondhan Living Clients.",
            "Quality materials use in projects.",
            "Bondhan Living has individual quality policy.",
            "Renowned architects design Bondhan Living projects.",
            "Bondhan Living is committed to handover in time.",
            "Bondhan Living tries to create an eco- friendly environment.",
            "Bondhan Living also planned layout, so as to provide sufficient fresh air and light or ultimate residential living experience.",
            "Bondhan Living ensures the balance of aesthetics and functionality.",
            "Bondhan Living giving PABX connection (if client want).",
            "Bondhan Living providing deep tube well in each project.",
            "Bondhan Living providing house emergency power supply.",
            "Bondhan Living establishes electricity sub station for project.",
            "Bondhan Living emphasis on community facility.",
            "Bondhan Living giving additional values to their clients.",
            "Security facilities are always available.",
            "Rest room and toilet facilities for the drivers.(Depend on Project)",
            "Bondhan Living LAN if clients wants this."
        ];

        $amenities = [
            [
                "category" => "DOOR",
                "items" => [
                    "Door Chain",
                    "Check Viewer.",
                    "Calling Bell Switch of Good Quality.",
                    "Solid Brass Door Knocker",
                    "Apartment Number in Brass.",
                    "Heavy duty door lock of foreign origin.",
                    "Internal Doors of Strong and Durable veneer Flush door Shutters with French polish.",
                    "All internal door frames are made of sill Koral/ Jarul/ Gutia.",
                    "All bathroom doors with inner side water proof laminated plastic door.",
                    "All internal doors with good quality round lock",
                    "To maintain the highest standards in developing commercial properties.",
                    "To maintain the highest standards in developing homes for individuals.",
                    "To create safe homes.",
                    "To provide feeling of living in a home with ultimate comfort.",
                    "To provide good customer service.",
                    "To provide professional and personalized services of the highest integrity.",
                    "To become an international referral for buyers and investors.",
                    "To provide high quality Customer Relationship Management.",
                    "To develop a 'brand name'."
                ]
            ],
            [
                "category" => "WINDOWS",
                "items" => [
                    "Sliding aluminum windows as per architectural design of the building.",
                    "5 mm thick glass with mohair fining.",
                    "Safety grills in all outer windows.",
                    "Rain water barrier in 4\" aluminum section (As per architectural design of the building).",
                    "Mosquito Net provision in All Windows"
                ]
            ],
            [
                "category" => "WALLS",
                "items" => [
                    "Good Quality bricks.",
                    "Smooth finished walls.",
                    "Exterior wall thickness will be 10\" and internal wall thickness will be 5\""
                ]
            ],
            [
                "category" => "FLOOR FINISH",
                "items" => [
                    "Floors in Homogenous Tiles (RAK /Great walls /equivalent)",
                    "Safety grills in verandahs (Except front Verandahs)",
                    "All verandahs in floor tiles (RAK / Great walls or Equivalent).",
                    "Suitable light points provision."
                ]
            ],
            [
                "category" => "PAINTING & POLISHING",
                "items" => [
                    "Plastic Paint in all internal walls and ceilings in soft colors (Berger, Elite or equivalent) Enamel paint on grills and for bathroom ceilings.",
                    "Exterior wall will be weather coat paint (Berger, Elite or equivalent).",
                    "French polished doorframes & shutters.",
                    "Synthetic enamel paint applied on M.S. railing and grilles. (Berger, Elite or equivalent)"
                ]
            ],
            [
                "category" => "ELECTRICAL",
                "items" => [
                    "Imported goods standard electrical switches, plug points and other fittings.",
                    "A/c Power Outlets with earthling connection.",
                    "Provision of Air Condition in Master beds, Child Bed & Living room.",
                    "Fancy light fixtures in all rooms.",
                    "Electrical Distribution Box with Main Switch.",
                    "Standard quality concealed electrical wiring",
                    "Security Eights in the compound, car parking space and common spaces."
                ]
            ],
            [
                "category" => "BATH ROOM FEATURES",
                "items" => [
                    "Uniform Floor slope towards Water Outlet.",
                    "Good Quality Sanitary Wares in all bathrooms except servant Toilet",
                    "Marble Granite top finished Cabinet Basin in Master Bath.",
                    "Other bathroom will have standard pedestal basin.",
                    "Good Quality Glazed Tiles in all Bathrooms except Maid bath.",
                    "Matching wall tiles in alt bathrooms up to false ceiling (except servant's toilet)",
                    "All Mirrors with overhead lamps (Except servant's toilet)",
                    "Good Quality Chrome plated fittings in master bathroom including towel, rail, soap case, paper holder etc. (Except servant's toilet)",
                    "Tiles on floor and wall up to 5 feet in maid's bathroom with long pan, shower lowdown Exhaustion",
                    "Concealed Hot and Cold Water lines provision in master bathroom & Child -1"
                ]
            ],
            [
                "category" => "KITCHEN FEATURES",
                "items" => [
                    "Impressively designed platform with Granite Marble Worktop.",
                    "Double Burner Gas Outlet.",
                    "Good Quality glazed/ ceramic wall Tiles up to cabinet height (RAK/equivalent).",
                    "Matching Floor Tiles (RAK Homogenous Type).",
                    "Space provision for Gas Oven.",
                    "Concealed Hot and Cold Water Lines.",
                    "One tiled washing area in kitchen verandah.",
                    "One Stainless Counter-top Steel Sink with Mixer Cabinet Aluminum.",
                    "Suitably Located Exhaust Fan."
                ]
            ],
            [
                "category" => "UTILITY LINES",
                "items" => [
                    "Gas & Electricity supply will be individual apartment- wise Meter and connection and water supply will have common meter connection for the project.",
                    "All apartments will have independent Gas Connection for two burners.",
                    "All apartments will have independent Electricity Meter.",
                    "A common WASA meter for total Complex. Connection cost facility landowners apartments will be borne by the developer."
                ]
            ],
            [
                "category" => "GENERAL AMENITIES OF THE COMPLEX",
                "items" => [
                    "Heavy secured gate with decorative lamps and logo.",
                    "Reserved car parking in Covered if Protected Basement and Ground floor for residents with comfortable driveway.",
                    "Driver's waiting room & toilet.",
                    "Impressive main lobby, reception area and staircase in secured premises.",
                    "Lift from reputed international manufacturer to be: a) With enough capacity to serve resident at every floor, b) With adequate lighting, c) With well-finished and attractive door and cabin.",
                    "Tiles lift lobbies and main staircase with easy to climb steps, adequate lighting and fire protection features.",
                    "Stand-by emergency generator for the following: a. The lift, b. Water pump, c. Lighting in common space and stair, d. Emergency points in each apartment.",
                    "Electricity Supply approx 220V/440V from PDB source with separate main cable and LT Panel/Distribution Board.",
                    "Water Supply Connection from WASA sufficient as per Total calculated Consumption.",
                    "Underground Water Reservoir with one Main Lifting Pump and Standby pump.",
                    "Sewerage System planned for long-term requirement.",
                    "Gas Pipeline Connection from BAKHRABAD Distribution System as per total Calculated Consumption, Adequate Safety Measures incorporated.",
                    "Termite Protection Treatment of Ground.",
                    "A fire extinguisher on each floor.",
                    "Preparation of By-laws and committee formation of Apartment Owners Association. 6 (six) months free service for supervision, repair and rectification of defects."
                ]
            ]
        ];

        GlobalSetting::set('features_list', json_encode($features));
        GlobalSetting::set('amenities_list', json_encode($amenities));
    }
}
