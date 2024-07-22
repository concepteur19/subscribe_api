<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DefaultSubscription;
use Illuminate\Support\Facades\Storage;

class DefaultSubscriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer les enregistrements existants dans la table
        // DefaultSubscription::truncate();

        // Créer les souscriptions par défaut
        $defaultSubscriptions = [
            [
                'name' => 'Netflix',
                'logo' => '/storage/logos/Netflix.png',
            ],
            [
                'name' => 'Jio Cinema',
                'logo' => '/storage/logos/jio.png',
            ],
            [
                'name' => 'Spotify',
                'logo' => '/storage/logos/spot.png',
            ],
            [
                'name' => 'Hotstar',
                'logo' => '/storage/logos/dysney.png',
            ],
            [
                'name' => 'Youtube',
                'logo' => '/storage/logos/ytb.png',
            ],
            [
                'name' => 'Prime',
                'logo' => '/storage/logos/prime.png',
            ],
            // [
            //     'name' => 'Deezer',
            //     'logo' => 'image/deezer.png',
            // ]

        ];

        foreach ($defaultSubscriptions as $subscription) {
            $defaultSubscription = new DefaultSubscription();
            $defaultSubscription->name = $subscription['name'];

            if ($subscription['logo']) {
                $logoPath = $this->copyLogo($subscription['logo']);
                $defaultSubscription->logo = $logoPath;
            }

            $defaultSubscription->save();
        }
    }

    /**
     * Copy the logo file to storage and return the file path.
     *
     * @param string $logoPath
     * @return string|null
     */
    private function copyLogo(string $logoPath): ?string
    {
        $relativePath = asset($logoPath);

        // Récupérer le contenu du fichier de logo
        $fileContent = file_get_contents(asset($relativePath));

        if ($fileContent) {
            // permet de générer un nom de fichier unique pour le logo
            $fileName = uniqid('logo_') . '.png';

            // permet d'enregistrer le fichier de logo dans le stockage
            Storage::disk('public')->put('logos/' . $fileName, $fileContent);

            return 'logos/' . $fileName;
        }

        return null;
    }
}
