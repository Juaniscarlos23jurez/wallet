<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GeneratePassbookIcons extends Command
{
    protected $signature = 'passbook:generate-icons';
    protected $description = 'Generate the necessary icons for Apple Wallet pass';

    public function handle()
    {
        $this->info('Generating Apple Wallet icons...');

        // Create directories if they don't exist
        $directories = [
            public_path('images'),
            resource_path('images'),
        ];

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
        }

        // Generate Apple Wallet icon
        $this->generateAppleWalletIcon();

        // Generate pass icon
        $this->generatePassIcon();

        $this->info('Icons generated successfully!');
    }

    protected function generateAppleWalletIcon()
    {
        $svg = <<<SVG
<svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect width="120" height="120" rx="24" fill="#000000"/>
    <path d="M60 30C43.4315 30 30 43.4315 30 60C30 76.5685 43.4315 90 60 90C76.5685 90 90 76.5685 90 60C90 43.4315 76.5685 30 60 30ZM60 84C46.7452 84 36 73.2548 36 60C36 46.7452 46.7452 36 60 36C73.2548 36 84 46.7452 84 60C84 73.2548 73.2548 84 60 84Z" fill="#FFFFFF"/>
    <path d="M60 42C50.0589 42 42 50.0589 42 60C42 69.9411 50.0589 78 60 78C69.9411 78 78 69.9411 78 60C78 50.0589 69.9411 42 60 42ZM60 72C53.3726 72 48 66.6274 48 60C48 53.3726 53.3726 48 60 48C66.6274 48 72 53.3726 72 60C72 66.6274 66.6274 72 60 72Z" fill="#FFFFFF"/>
</svg>
SVG;

        File::put(public_path('images/apple-wallet-icon.svg'), $svg);
        $this->info('Apple Wallet icon generated!');
    }

    protected function generatePassIcon()
    {
        $svg = <<<SVG
<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect width="29" height="29" rx="6" fill="#000000"/>
    <path d="M14.5 7C10.3579 7 7 10.3579 7 14.5C7 18.6421 10.3579 22 14.5 22C18.6421 22 22 18.6421 22 14.5C22 10.3579 18.6421 7 14.5 7ZM14.5 20C11.4624 20 9 17.5376 9 14.5C9 11.4624 11.4624 9 14.5 9C17.5376 9 20 11.4624 20 14.5C20 17.5376 17.5376 20 14.5 20Z" fill="#FFFFFF"/>
    <path d="M14.5 11C12.567 11 11 12.567 11 14.5C11 16.433 12.567 18 14.5 18C16.433 18 18 16.433 18 14.5C18 12.567 16.433 11 14.5 11Z" fill="#FFFFFF"/>
</svg>
SVG;

        File::put(resource_path('images/icon.svg'), $svg);
        $this->info('Pass icon generated!');
    }
} 