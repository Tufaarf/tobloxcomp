<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Community;
use App\Models\CompanyStats;
use App\Models\DetailedService;
use App\Models\Faq;
use App\Models\HeroSection;
use App\Models\Product;
use App\Models\Service;
use App\Models\TeamMember;
use App\Services\RobloxServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FrontController extends Controller
{
    public function __construct(RobloxServices $roblox)
    {
        $this->roblox = $roblox;
    }

    public function index()
{
    // Hero, About, dsb.
    $herosections     = HeroSection::latest()->get();
    $abouts           = About::latest()->get();
    $companyStats     = CompanyStats::orderBy('id')->get();

    // Jika tidak punya kolom is_active/sort_order, hapus saja filter/ordering-nya
    $services         = Service::when(Schema::hasColumn('services', 'is_active'), fn($q) => $q->where('is_active', true))
                               ->orderBy('id')
                               ->get();

    $detailedServices = DetailedService::when(Schema::hasColumn('detailed_services', 'is_active'), fn($q) => $q->where('is_active', true))
                                       ->orderBy('id')
                                       ->get();

    $products         = Product::when(Schema::hasColumn('products', 'is_active'), fn($q) => $q->where('is_active', true))
                               ->latest()
                               ->get();

    $teams            = TeamMember::when(Schema::hasColumn('team_members', 'is_active'), fn($q) => $q->where('is_active', true))
                                  ->when(Schema::hasColumn('team_members', 'sort_order'), fn($q) => $q->orderBy('sort_order'))
                                  ->orderBy('id')
                                  ->get();

    // === Community & FAQ (inti permintaan) ===
    $community = Community::where('is_active', true)
                          ->latest()
                          ->first(); // satu record untuk section

    $faqs = Faq::where('is_active', true)
               ->orderByRaw('sort_order IS NULL, sort_order ASC')
               ->orderBy('id')
               ->get();

    return view('front.index', compact(
        'herosections',
        'abouts',
        'companyStats',
        'services',
        'detailedServices',
        'products',
        'teams',
        'community',
        'faqs'
    ));
}

    public function services()
    {
        // Logic for the services page can be added here
        $herosections = HeroSection::all();
        $services = Service::all();
        return view('front.services.index', compact('services', 'herosections'));
    }

    public function products()
    {
        // Logic for the products page can be added here
        $products = Product::all();
        return view('front.products.index', compact('products'));
    }

    public function productDetail($id)
    {
        $product = Product::findOrFail($id);
        return view('front.detail-product', compact('product'));
    }

     public function robuxPage()
    {
        $pricePer50 = (int) config('topup.price_per_50', 7000);

        // siapkan array methods utk Blade
        $paymentMethods = collect(config('topup.methods', []))
            ->map(function ($m, $code) {
                return [
                    'code'   => $code,
                    'name'   => $m['name'],
                    'fee'    => (float) $m['fee'],
                    'type'   => $m['type'],     // text|image
                    'target' => $m['target'],   // nomor / path gambar
                ];
            })->values();

    return view('front.robux.topup', compact('pricePer50', 'paymentMethods'));
    }

    // cek username roblox
    public function checkUsername(Request $req)
{
    $username = trim($req->input('username'));
    $userId = $this->roblox->resolveUserId($username);

    if (!$userId) {
        return response()->json(['status' => false, 'message' => 'Username tidak ditemukan']);
    }

    // Panggil Thumbnails API (lebih stabil)
    $thumb = \Illuminate\Support\Facades\Http::get(
        'https://thumbnails.roblox.com/v1/users/avatar-headshot',
        [
            'userIds'    => $userId,
            'size'       => '150x150',
            'format'     => 'Png',
            'isCircular' => 'false',
        ]
    );

    $avatarUrl = null;
    if ($thumb->ok()) {
        $avatarUrl = data_get($thumb->json(), 'data.0.imageUrl');
    }

    // Fallback jika service di atas gagal (jarang)
    if (!$avatarUrl) {
        $avatarUrl = "https://www.roblox.com/headshot-thumbnail/image?userId={$userId}&width=150&height=150&format=png";
    }

    return response()->json([
        'status'   => true,
        'userId'   => $userId,
        'username' => $username,
        'avatar'   => $avatarUrl,
    ]);
    }

    // cek apakah user punya game
    public function checkExperience($userId)
    {
        $universes = $this->roblox->universesByUser($userId);

        if (count($universes) === 0) {
            return response()->json(['status' => false, 'message' => 'User ini tidak punya experience']);
        }

        return response()->json(['status' => true, 'universes' => $universes]);
    }


}
