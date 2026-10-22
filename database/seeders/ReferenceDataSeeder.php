<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds the reference hostel and complaint-category data.
 * Safe to run multiple times (uses updateOrInsert).
 */
class ReferenceDataSeeder extends Seeder
{
    // ---------------------------------------------------------------- Hostels
    private const HOSTELS = [
        'Alvan Ikoku Hall',
        'Eni-Njoku Hall',
        'Mbanefo Hostel',
        'Aja-Nwachukwu Hostel',
        'Akintola Hall',
        'Akpabio Hall',
        'Awolowo Hall',
        'Balewa Hall',
        'Bello Hall',
        'Eyo Ita Hostel',
        'Isa Kaita Hostel',
        'Kwame Nkrumah Hostel',
        'Mary Slessor Hostel',
        'Okeke Hall',
        'Okpara Hall',
        'Presidential Hostel',
        'TETFund Female Hostel',
        'New PG Hostel',
        'E-Block Hostel (Nursing Mothers)',
    ];

    // ---------------------------------------------------------------- Complaint categories
    private const CATEGORIES = [
        [
            'name'        => 'Theft',
            'description' => 'Stolen or missing belongings.',
        ],
        [
            'name'        => 'Facility Issue',
            'description' => 'Faulty water, electricity, doors, windows, fans, toilets and similar repairs.',
        ],
        [
            'name'        => 'Sexual Abuse',
            'description' => 'Sexual harassment or abuse. Handled in confidence by the porter and administrator.',
        ],
        [
            'name'        => 'Bullying',
            'description' => 'Harassment, intimidation or bullying.',
        ],
        [
            'name'        => 'Cult-related Issue',
            'description' => 'Suspected cult activity in or around the hostel.',
        ],
    ];

    public function run(): void
    {
        // ---- Hostels -------------------------------------------------------
        foreach (self::HOSTELS as $name) {
            DB::table('hostels')->updateOrInsert(
                ['name' => $name],
                [
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // ---- Complaint categories -------------------------------------------
        foreach (self::CATEGORIES as $cat) {
            DB::table('complaint_categories')->updateOrInsert(
                ['name' => $cat['name']],
                array_merge($cat, [
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
