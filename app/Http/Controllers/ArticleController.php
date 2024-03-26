<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articles = Article::all();
            $imagesArticle = [];

            for ($i = 0; $i < count($articles); $i++) {
                // $sections = Section::where("article_id", $articles[$i]->id)->get();
                $sectionsWithImage = Section::where("article_id", $articles[$i]->id)->whereNotNull("image")->get();
                $image = $sectionsWithImage[0]->image;
                array_push($imagesArticle, $image);
            }

            return response()->json([
                'status' => true,
                'posts' => [$articles, $imagesArticle],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createArticle(Request $request)
    {
        try {

            // Vérification des informations du post
            $validateArticle = Validator::make(
                $request->all(),
                [
                    'title' => 'required|string',
                    'sections' => 'required|array'
                ]
            );

            if ($validateArticle->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Remplissez correctement le formulaire',
                    'errors' => $validateArticle->errors()
                ], 401);
            }

            // Crée l'article
            $article = Article::create([
                'title' => $request->title
            ]);

            $sections = $request['sections'];
            $sections0 = [];

            for ($i = 0; $i < count($sections); $i++) {
                $sectionValidator = Validator::make($sections[$i], [
                    'title' => 'nullable|string',
                    'contain' => 'required|string',
                    'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048'
                ]);

                if ($sectionValidator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Remplissez correctement le formulaire dans la partie section',
                        'errors' => $sectionValidator->errors()
                    ], 401);
                }

                $imageFile = $sections[$i]['image'];
                $imageUrl = $imageFile->store('articles');
                // Générez un nom de fichier unique pour éviter les conflits
                // $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();

                // Enregistrez l'image dans le stockage
                // Storage::disk('public/images')->put($filename, file_get_contents($imageFile->getRealPath()));

                // $imageFile->storeAs('public/images', $filename);

                // Obtenir l'URL complète de l'image
                // $imageUrl = Storage::url('images/' . $filename);

                $section = Section::create([
                    'title' => $sections[$i]['title'],
                    'image' => $imageUrl, //$filename,
                    'contain' => $sections[$i]['contain'],
                    'article_id' => $article->id
                ]);

                array_push($sections0, $section);
            }

            // Retourne la réponse en JSON
            return response()->json([
                'success' => true,
                'message' => 'l\'article a été crée avec succès',
                'article' => ['articles' => $article, 'sections' => $sections0],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function getOneArticle($id)
    {
        try {
        } catch (\Throwable $th) {
        }
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}




// foreach ($sections as $section) {
                // $sectionValidator = Validator::make($section, [
                //     'title' => 'nullable|string',
                //     'contain' => 'required|string',
                //     'image' => 'nullable|image'
                // ]);

                // if ($sectionValidator->fails()) {
                //     return response()->json([
                //         'status' => false,
                //         'message' => 'Remplissez correctement le formulaire dans la partie section',
                //         'errors' => $sectionValidator->errors()
                //     ], 401);
                // }

                // if (isset($section['image'])) {
                //     $imageFile = $section['image'];
            
                //     // Générez un nom de fichier unique pour éviter les conflits
                //     $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
            
                //     // Enregistrez l'image dans le stockage
                //     Storage::disk('public')->put($filename, file_get_contents($imageFile->getRealPath()));
            
                //     Section::create([
                //         'title' => $section['title'],
                //         'image' => $filename,
                //         'contain' => $section['contain']
                //     ]);
                // } else {
                //     Section::create([
                //         'title' => $section['title'],
                //         'contain' => $section['contain']
                //     ]);
                // }

                // $imageFile = $section['image'];

                // // Générez un nom de fichier unique pour éviter les conflits
                // $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();

                // // Enregistrez l'image dans le stockage
                // Storage::disk('public')->put($filename, file_get_contents($imageFile->getRealPath()));
                // // if (is_a($imageFile, 'Illuminate\Http\UploadedFile')) {
                // //     $image = $imageFile->store('articles', 'public');

                // //     Section::create([
                // //         'title' => $section['title'],
                // //         'image' => $image,
                // //         'contain' => $section['contain']
                // //     ]);
                // // } else {
                // //     dd($imageFile);
                // //     // Gérer le cas où $imageFile n'est pas un objet File valide
                // //     // Par exemple, afficher un message d'erreur ou effectuer une autre action appropriée.
                // // }
                // // dd($imageFile);
                // // $image = $imageFile->store('articles', 'public');

                // Section::create([
                //     'title' => $section['title'],
                //     'image' => $filename,
                //     'contain' => $section['contain']
                // ]);
            // }