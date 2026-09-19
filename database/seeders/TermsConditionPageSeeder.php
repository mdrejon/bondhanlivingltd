<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\Schema;

class TermsConditionPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $termsData = [
            'terms_page_hero' => [
                'title' => 'Terms and Condition',
                'bg_image' => 'assets/img/bg/breadcumb-bg.jpg',
            ],
            'terms_page_items' => [
                [
                    'title' => 'Application',
                    'description' => 'Interested purchasers should submit application in prescribed form duly signed by him /her along with booking money. The company generally reserves the right to accept or reject an application without assigning any reason.'
                ],
                [
                    'title' => 'Payment',
                    'description' => 'All payments should be made by A/c payee cheque or Bank Draft or Pay-order in favor of the company, Bondhan Living Ltd. The Bangladeshi residing abroad may remit payments by TT/DD/direct bank to bank transfer.'
                ],
                [
                    'title' => 'Cancellation',
                    'description' => 'If the payment is delayed beyond two installments, the company has the right to cancel the allotment. In the event of cancellation of allotment or surrender of allotted apartment, refund would be made deducting 10% service charges only after reselling the apartment.'
                ],
                [
                    'title' => 'Agreement',
                    'description' => 'The Company and the allottee will be required to execute an agreement to safeguard the interest of allottee as well as the company.'
                ],
                [
                    'title' => 'Company Right',
                    'description' => 'Company reserves the right to make minor changes in the specification and facilities of the project for overall interest or due to unavoidable circumstances.'
                ],
                [
                    'title' => 'Refund',
                    'description' => 'If for any reason beyond the control of the company, the project is abandoned, the company will refund to the buyers the initial deposit and all installment money within 90 days. However, buyers shall not entitle to any claims of damage whatsoever.'
                ],
                [
                    'title' => 'Extra Area',
                    'description' => 'Size (Area) of apartments mentioned in the brochure includes net area, area of wall, area of column & proportionate common area. It may be increase or decrease due to technical reasons. Any addition or deduction in the apartment size will be adjusted during final settlement of total price.'
                ],
                [
                    'title' => 'Modification Cost',
                    'description' => 'Any charges in external construction & which is seen from outside is not allowed. Buyers are earnestly advised not to suggest major internal modification. Modification will not be accepted if it goes against the beauty of the building. Buyers are required to inform the details of their desired changes/modifications in writing immediately after booking the apartment. On getting detail of changes the company will assess & communicate the cost involved if there is any. Both the company & buyers will sign an additional agreement in this regard. No changes/modifications shall be accepted after the deadline fixed by the company.'
                ],
                [
                    'title' => 'Cost of Registration /Transfer Fee',
                    'description' => 'All VAT, Tax, cost of stamp, cost of registration of sale deed will be proportionately undivided, undemarked share of land and apartment as per value and all other incidental charges in items of the value of the apartment and proportionate land shall be paid by the allottee as per government rate at the time of the execution of the sale deed before as and when required. Registration and related formalities may be maid by the allottee directly or through the company on due payment in advance by the allottee to the company.'
                ],
                [
                    'title' => 'Utility Connection charges and incidental costs',
                    'description' => 'The utility connection fees, security deposited, other incidental charges and cost payable of water supply and sewerage connection, gas and electric connection and meters are not included in the price of the apartment .All such payment shall be made by allottee directly or through the company to the concerned authorities and if any expenditure in these respect is incurred by the company in advance, the same shall be reimbursed by the allottee.'
                ],
                [
                    'title' => 'Hand Over',
                    'description' => 'The possession of each apartment and parking space shall be duly handed over to the allottee on completion and on full payment of installments and other charges and dues. Prior to this, possession of the apartment will remain with the Company.'
                ],
                [
                    'title' => 'Owner’s Co-operative Society',
                    'description' => 'The buyer must become the number of the Owner’s Co-operative Society which will be formed by the buyers of the apartment. The maintenance of common facilities and management of general affairs of the project will be looked after by the Co-operative Society. Member will abide by the rules framed by the Co-operative Society and pay an initial fee/deposit of Tk.25, 000/-(Twenty Five Thousand) only to the Co-operative Fund. Thereafter, they must pay a monthly deposit as decided by the society.'
                ]
            ],
            'terms_page_seo' => [
                'meta_title' => 'Terms and Conditions - Bondhan Living Ltd',
                'meta_description' => 'Terms and conditions for purchasing and interacting with Bondhan Living Ltd properties.',
                'meta_keywords' => 'terms, conditions, bondhan living, policy',
                'meta_author' => 'Bondhan Living Ltd'
            ]
        ];

        foreach ($termsData as $key => $value) {
            GlobalSetting::set($key, json_encode($value));
        }

        Schema::enableForeignKeyConstraints();
    }
}
