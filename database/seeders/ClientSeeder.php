<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [

            [
                'company_name' => '7-Ind Convenience Rtl Ltd',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Asianet News Network Private Limited',
                'category'     => 'OTHER',
            ],

            [
                'company_name' => 'Butterfly Innovations Private Limited',
                'category'     => 'OTHER',
            ],

            [
                'company_name' => 'Canali India Pvt. Ltd.',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Chemicals Petrochemicals Manufacturers Association',
                'category'     => 'OTHER',
            ],

            [
                'company_name' => 'Chennai Global Logistics Park Limited',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Eternalia Media Pvt. Ltd.',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Fino Payments Bank',
                'category'     => 'OTHER',
            ],

            [
                'company_name' => 'Himachal Futuristic Communications Ltd.',
                'category'     => 'OTHER',
            ],

            [
                'company_name' => 'Jio Platforms Limited',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Komorebi Tech Solutions Pvt Ltd',
                'category'     => 'OTHER',
            ],

            [
                'company_name' => 'Metro Cash & Carry India Private Limited',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Parksons Packaging Limited',
                'category'     => 'OTHER',
            ],

            [
                'company_name' => 'Qwik Supply Chain Private Limited.',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'RBML solution India Ltd.',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Rel Gas Lifestyle Ind PvL',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Bio Energy Ltd',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance BP Mobility Ltd.',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Brands Limited',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Consumer Product Limited',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Corp IT Park Ltd',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Ethane Pipeline Ltd.',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Fire Brigade Ser(P)Ltd',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Foundation',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Foundation Hospital Trust',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Foundation Institution Of Education And Research',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Group Support Service P. Ltd',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Ind Infras. Ltd.',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Industries Limited',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Jio Infocomm Ltd',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Life Sciences Nashik Private Limited',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Life Sciences Pvt. Ltd.',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Petro Marketing',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Projects and Property Management Services Ltd',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Retail Limited',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Retail Ventures Limited',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Sibur Elastomer P Ltd',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Reliance Syngas Limited',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Samarjit Enterprises LLP',
                'category'     => 'OTHER',
            ],

            [
                'company_name' => 'Seasaga Enterprises Private Limited',
                'category'     => 'OTHER',
            ],

            [
                'company_name' => 'Urban Ladder Home Decor',
                'category'     => 'RIL',
            ],

            [
                'company_name' => 'Viaante Business Solution Pvt Ltd.',
                'category'     => 'OTHER',
            ],

            [
                'company_name' => 'World Malayalee Council Mumbai Province',
                'category'     => 'OTHER',
            ],

        ];

        foreach ($clients as $index => $client) {

            Client::updateOrCreate(
                [
                    'client_code' => 'CLI' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                ],
                [
                    'category'     => $client['category'],
                    'company_name' => $client['company_name'],
                    'status'       => true,
                    'created_by'   => 1
                ]
            );
        }
    }
}