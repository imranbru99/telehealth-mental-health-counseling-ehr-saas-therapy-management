<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Practitioner;
use App\Models\Review;
use App\Models\Service;
use App\Models\SubscriptionPlan;
use App\Services\ModuleRegistry;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $practitioners = Practitioner::with(['user', 'branches'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->take(6)
            ->get();

        $services = Service::where('is_active', true)->take(6)->get();

        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $reviews = Review::with('practitioner.user')
            ->where('is_published', true)
            ->take(6)
            ->get();

        $modules = collect(ModuleRegistry::all())->take(8);

        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->take(6)->get();

        $recentPosts = BlogPost::where('is_published', true)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('public.home', compact('practitioners', 'services', 'plans', 'reviews', 'modules', 'faqs', 'recentPosts'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function features()
    {
        return view('public.features');
    }

    public function modules()
    {
        $allModules = ModuleRegistry::all();
        $categories = collect($allModules)->groupBy('category');
        return view('public.modules', compact('allModules', 'categories'));
    }

    public function pricing()
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get();
        return view('public.pricing', compact('plans'));
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Thank you! Your message has been sent to our clinic support team. We will respond within 24 hours.');
    }

    public function requestDemo()
    {
        return view('public.demo-request');
    }

    public function blog(Request $request)
    {
        $locale = $request->query('lang', 'all');

        $query = BlogPost::where('is_published', true)->orderByDesc('published_at');

        if ($locale !== 'all') {
            $query->where('locale', $locale);
        }

        $posts = $query->paginate(9);

        return view('public.blog.index', compact('posts', 'locale'));
    }

    public function blogShow(string $slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();
        $related = BlogPost::where('id', '!=', $post->id)
            ->where('is_published', true)
            ->take(3)
            ->get();

        return view('public.blog.show', compact('post', 'related'));
    }

    public function faq()
    {
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get();
        $categories = $faqs->groupBy('category');
        return view('public.faq', compact('faqs', 'categories'));
    }

    // Legal & Compliance Pages
    public function privacyPolicy()
    {
        return view('public.legal.privacy');
    }

    public function termsConditions()
    {
        return view('public.legal.terms');
    }

    public function cookiePolicy()
    {
        return view('public.legal.cookies');
    }

    public function dataProtection()
    {
        return view('public.legal.data-protection');
    }

    public function securityInfo()
    {
        return view('public.legal.security');
    }

    public function accessibilityStatement()
    {
        return view('public.legal.accessibility');
    }
}
