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
                'logo' => 'images/Netflix.png',
            ],
            [
                'name' => 'Jio Cinema',
                'logo' => 'images/jio.png',
            ],
            [
                'name' => 'Spotify',
                'logo' => 'images/spot.png',
            ],
            [
                'name' => 'Hotstar',
                'logo' => 'images/dysney.png',
            ],
            [
                'name' => 'Youtube',
                'logo' => 'images/ytb.png',
            ],
            [
                'name' => 'Prime',
                'logo' => 'images/prime.png',
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
        // $absolutePath = base_path($logoPath);

        // Récupérer le contenu du fichier de logo
        $fileContent = file_get_contents($logoPath);

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
