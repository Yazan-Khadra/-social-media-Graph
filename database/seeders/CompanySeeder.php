<?php

namespace Database\Seeders;

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
            'company_name' => 'Tech Innovators Inc.',
            'description' => 'A leading company in tech innovation and software solutions.',
            'email' => 'contact@techinnovators.com',
            'mobile_number' => '123-456-7890',
            'logo_url' => 'logos/techinnovators.png',
            'social_links' => json_encode(['linkedin' => 'https://linkedin.com/company/techinnovators'])
        ]);
        Company::create([
            'company_name' => 'Green Energy Solutions',
            'description' => 'Providing sustainable and renewable energy solutions worldwide.',
            'email' => 'info@greenenergy.com',
            'mobile_number' => '987-654-3210',
            'logo_url' => 'logos/greenenergy.png',
            'social_links' => json_encode(['twitter' => 'https://twitter.com/greenenergy'])
        ]);
        Company::create([
            'company_name' => 'EduFuture',
            'description' => 'Innovating the future of education with technology.',
            'email' => 'hello@edufuture.com',
            'mobile_number' => '555-123-4567',
            'logo_url' => 'logos/edufuture.png',
            'social_links' => json_encode(['facebook' => 'https://facebook.com/edufuture'])
        ]);
    }
}
