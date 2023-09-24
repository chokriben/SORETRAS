<?php






namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presentation;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB; // Add this import statement at the top of the file
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


use App\Models\Pages;

class PresentationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 100)
    {

        $Presentations = Pages::paginate($perPage);


        if ($request->page) {
            $Presentations = new LengthAwarePaginator($Presentations, count($Presentations), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'Presentations' => $Presentations,
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $langages = app(Locales::class)->all();
        $presentation = Presentation::first();

        if (!$presentation) {
            $presentation = new Presentation();
        }

        foreach ($langages as $language) {
            $validator = Validator::make($request->all(), [
                'name_' . $language => 'max:255|required',
                'description_' . $language => 'max:255|required',
            ]);



            $presentation->translateOrNew($language)->name = $request->input('name_' . $language);
            $presentation->translateOrNew($language)->description = $request->input('description_' . $language);
        }
        $presentation->save();
        if ($request->hasFile('file')) {
            $allowedExtensions = ['jpg', 'png', 'jpeg', 'csv', 'txt', 'xls', 'xlsx', 'pdf'];
            $maxFileSize = 2048; // 2048 kilobytes (2 megabytes)

            $fileValidator = Validator::make($request->all(), [
                'file' => 'mimes:' . implode(',', $allowedExtensions) . '|max:' . $maxFileSize,
                // Ajoutez d'autres règles de validation pour les champs de votre formulaire si nécessaire
            ]);

            if ($fileValidator->fails()) {
                return response()->json([
                    "success" => false,
                    "errorsValidation" => $fileValidator->messages()
                ], 400);
            }



            if ($request->hasFile('file')) {
                // Handle file upload and media saving
                $file = $request->file('file');
                $fileExtension = $file->getClientOriginalExtension();
                $fileSrc = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileSrc, 'public');

                DB::beginTransaction();

                try {
                    // Create and save the media instance
                    $media = new Media([
                        'legende' => 'presentations',
                        'type' => $fileExtension,
                        'src' => $fileSrc,
                        'path' => '/uploads/' . $filePath
                    ]);
                    $media->save();

                    // Associate the media with the presentation
                    $presentation->medias()->save($media);

                    DB::commit();

                    // Return a response indicating successful file upload
                    return response()->json(['message' => 'File uploaded successfully']);
                } catch (\Exception $e) {
                    DB::rollback();

                    return response()->json([
                        "success" => false,
                        "message" => "Error occurred while saving the media.",
                    ], 500);
                }
            }
        }
        $presentation->save();
        return response()->json([
            "success" => true,
            "message" => "Insertion est effectuée avec succès",
        ], 200);
    }

    public function update(Request $request)
    {
        $langages = app(Locales::class)->all();

        foreach ($langages as $language) {
            $validator = Validator::make($request->all(), [
                'name_' . $language => 'max:255|required',
                'description_' . $language => 'max:255|required',
            ]);

            // Validate the file
            if ($request->hasFile('file')) {
                $allowedFileExtensions = ['jpg', 'png', 'jpeg', 'csv', 'txt', 'xls', 'xlsx', 'pdf'];
                $validator = Validator::make($request->all(), [
                    'file' => 'required|mimes:' . implode(',', $allowedFileExtensions) . '|max:2048'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "errorsValidation" => $validator->messages()
                    ], 400);
                }
            }
        }

        $presentation = Presentation::findOrFail($request->id);

        // Update translated fields
        foreach ($langages as $language) {
            $presentation->translateOrNew($language)->name = $request->input('name_' . $language);
            $presentation->translateOrNew($language)->description = $request->input('description_' . $language);
        }

        // Update other fields
        $presentation->active = $request->input('active', false);

        // Handle file upload and media saving
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Get the file extension
            $fileExtension = $file->getClientOriginalExtension();
            // Generate a custom file name with a timestamp to avoid filename collisions
            $fileSrc = time() . '_' . $file->getClientOriginalName();

            // Validate the file
            $allowedPhotoExtensions = ['jpg', 'png', 'jpeg', 'gif'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array($extension, $allowedPhotoExtensions)) {
                return response()->json([
                    "success" => false,
                    "message" => "Invalid file format. Only jpg, png, jpeg, and gif files are allowed.",
                ], 400);
            }

            // Store the file in the storage/app/public/Presentation/photos directory
            $filePath = $file->storeAs('uploads', $fileSrc, 'public');

            // Use a database transaction to ensure data integrity
            DB::beginTransaction();

            try {
                // Create and save the media instance
                $media = new Media([
                    'legende' => 'presentations',
                    'type' => $fileExtension,
                    'src' => $fileSrc, // Save the custom file name in the 'src' column
                    'path' => '/storage/' . $filePath // Save the public URL to access the file in the 'path' column
                ]);
                $media->save();

                // Detach existing media from presentation (if any)
                $presentation->medias()->detach();

                // Associate the new media with the presentation
                $presentation->medias()->attach($media);

                // Commit the transaction if everything is successful
                DB::commit();
            } catch (\Exception $e) {
                // Rollback the transaction if an error occurs
                DB::rollback();

                return response()->json([
                    "success" => false,
                    "message" => "Error occurred while saving the media.",
                ], 500);
            }
        }

        $presentation->save();

        return response()->json([
            "success" => true,
            "message" => "Modification est effectuée avec succès",
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
}
