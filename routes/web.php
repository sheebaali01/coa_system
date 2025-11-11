<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SkuController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\VialController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthController;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
// use Intervention\Image\Laravel\Facades\Image;
// use Intervention\Image\ImageManager;
// use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Hash;

Route::get('/test-hash', function () {
    return Hash::make('12345678');
});

Route::get('/test-qr', function () {

    $svg = QrCode::format('png')->size(300)->generate('Hello GD!');

    // 2️⃣ Save to public disk
    $fileName = 'qrcodes/hello.svg';
    Storage::disk('public')->put($fileName, $svg);

    // 3️⃣ Make it accessible
    $url = Storage::url($fileName); // returns /storage/qrcodes/hello.svg

    // 4️⃣ Return URL or display image
    return "<img src='{$url}' alt='QR Code'>";

    // $svg = QrCode::size(300)->generate('Hello GD!');
    // $image = Image::read($svg);

    // Encode and return
    // $png = $image->encode(new PngEncoder());
    // Step 3: Return PNG response
    // return $svg;
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
Route::get('/scan/{code}', [VialController::class, 'scanVial']);


// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/scan/redirect/{vial}', [VialController::class, 'instantRedirect'])
    ->name('vial.instant-redirect');

 Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isUser()) {
        return redirect()->route('user.dashboard');
    }

    // Default fallback (optional)
    return redirect()->route('login');
});
// Admin Routes
Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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

    Route::prefix('vials')->name('vials.')->group(function () {
        Route::get('/{batchId}', [VialController::class, 'index'])->name('index');
        Route::any('/add', [VialController::class, 'add'])->name('add');
        Route::any('/update/{id}', [VialController::class, 'update'])->name('update');
        Route::any('/view/{id}', [VialController::class, 'view'])->name('view');
        Route::get('/export/pdf', [VialController::class, 'exportPdf'])
        ->name('export.pdf');
        Route::get('/export/excel', [VialController::class, 'exportExcel'])
        ->name('export.excel');
        Route::post('/resetAll/{batchId}', [VialController::class, 'resetAllQrCodes']);
        Route::post('/reset/{vialId}', [VialController::class, 'resetQrCode']);
    });
});

// User Routes
Route::middleware(['auth', 'user'])->name('user.')->prefix('user')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');
    // other user routes...
});