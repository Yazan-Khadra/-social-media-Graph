<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'company_name' => 'TechCorp Solutions',
            'description' => 'Leading technology solutions provider specializing in web development and mobile applications.',
            'email' => 'contact@techcorp.com',
            'phone' => '+1-555-0123',
            'website' => 'https://techcorp.com',
            'logo_url' => 'https://example.com/logos/techcorp.png'
        ]);

        Company::create([
            'company_name' => 'InnovateSoft',
            'description' => 'Innovative software development company focused on AI and machine learning solutions.',
            'email' => 'info@innovatesoft.com',
            'phone' => '+1-555-0456',
            'website' => 'https://innovatesoft.com',
            'logo_url' => 'https://example.com/logos/innovatesoft.png'
        ]);

        Company::create([
            'company_name' => 'Digital Dynamics',
            'description' => 'Digital marketing and web development agency helping businesses grow online.',
            'email' => 'hello@digitaldynamics.com',
            'phone' => '+1-555-0789',
            'website' => 'https://digitaldynamics.com',
            'logo_url' => 'https://example.com/logos/digitaldynamics.png'
        ]);
    }
}
