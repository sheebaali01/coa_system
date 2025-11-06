<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SkuController;
use App\Http\Controllers\Admin\BatchController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\PngEncoder;
use SimpleSoftwareIO\QrCode\Generator;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-qr', function () {
    $qr = new Generator;
    $pngBinary = $qr->size(300)
                    ->encoding('UTF-8')
                    ->writer('png') 
                    ->generate('Hello GD!');
    $image = Image::read($pngBinary);

    // Encode and return
    $png = $image->encode(new PngEncoder());
    // Step 3: Return PNG response
    return response($png)->header('Content-Type', 'image/png');
        //     $img =Image::make($QR);

        // $now=Carbon::now();
        // //generating unique file name;
        // $fileName="waypoint_".$waypoint->organisation_id."_".$patrol->client_id."_".$patrol->site_id."_".$patrol->patrol_id."_".$waypoint->waypoint_id.".png";
        // //$fileName = $panicImage->getClientOriginalName() ;
        // $fileName = str_replace(' ', '_', $fileName);
        // //$imagePath = $destinationPath . "/vehicle/media/images/".$vehicle->vehicle_id."/".$fileName; // upload path
        // if (env('APP_CLOUDENGINE')==1) {
        //     $avatarStoragePath = storage_path('app').'/'.$fileName;
        // } else {
        //     $avatarStoragePath = storage_path('app/resources/assets/media/patrols').'/'.$fileName;
        // }
        // $avatar_path = $img->save($avatarStoragePath);
        // $uploadedFile = new \Symfony\Component\HttpFoundation\File\File($avatarStoragePath);
        // if (env('APP_CLOUDENGINE')==1) {
        //     upload_object("patrols/media/qr/".$waypoint->patrol_id."/" . $fileName, $uploadedFile);
        // }
        // //Storage::disk('gcs')->putFileAs("/patrols/media/qr/".$waypoint->patrol_id."/", $avatarStoragePath, $fileName, 'public');
        // $img->destroy();
});
Route::name('admin.')->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::prefix('skus')->name('skus.')->group(function () {
        Route::get('/', [SkuController::class, 'index'])->name('index');
        Route::any('/add', [SkuController::class, 'add'])->name('add');
        Route::any('/update/{id}', [SkuController::class, 'update'])->name('update');
        Route::any('/delete/{id}', [SkuController::class, 'delete'])->name('delete');
        Route::any('/view/{id}', [SkuController::class, 'view'])->name('view');
    });
    Route::prefix('batches')->name('batches.')->group(function () {
        Route::get('/', [BatchController::class, 'index'])->name('index');
        Route::any('/add', [BatchController::class, 'add'])->name('add');
        Route::any('/update/{id}', [BatchController::class, 'update'])->name('update');
        Route::any('/view/{id}', [BatchController::class, 'view'])->name('view');
    });
});