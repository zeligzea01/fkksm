<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\LandingSection;
use App\Support\SecurityHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LandingCmsController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $sections = LandingSection::getAllForHome();
        $activeKey = $request->query('section', 'hero_slides');
        if (!isset($sections[$activeKey])) {
            $activeKey = 'hero_slides';
        }

        $skillBadgeCatalog = Course::availableSkillBadgeIcons();

        return view('lms.landing-cms.index', compact('sections', 'activeKey', 'skillBadgeCatalog'));
    }

    public function update(Request $request, string $sectionKey): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $defaults = LandingSection::defaults();
        abort_unless(isset($defaults[$sectionKey]), 404);

        $imageRule = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp', 'max:4096'];
        $request->validate([
            'section_title' => ['nullable', 'string', 'max:160'],
            'image_file' => $imageRule,
            'slides.*.image_file' => $imageRule,
            'webinars.*.image_file' => $imageRule,
            'programs.*.image_file' => $imageRule,
            'items.*.image_file' => $imageRule,
        ], [
            '*.mimes' => 'Gambar harus berformat JPG, PNG, atau WEBP yang aman.',
            '*.mimetypes' => 'Berkas gambar tidak valid.',
            '*.max' => 'Ukuran gambar maksimal 4 MB.',
        ]);

        $all = LandingSection::getAllForHome();
        $current = $all[$sectionKey];

        $title = mb_substr(trim((string) $request->input('section_title', $current['title'])), 0, 160);
        $isActive = $request->boolean('is_active');
        $content = $current['content'];

        switch ($sectionKey) {
            case 'hero_slides':
                $slides = [];
                $rawSlides = (array) $request->input('slides', []);
                foreach ($rawSlides as $idx => $s) {
                    if (trim((string) ($s['headline'] ?? '')) === '') {
                        continue;
                    }
                    $image = $this->handleUploadOrFallback($request, "slides.{$idx}.image_file", (string) ($s['image'] ?? 'fkksm/assets/images/activity-01.jpg'));
                    $slides[] = [
                        'badge' => trim((string) ($s['badge'] ?? '')),
                        'headline' => trim((string) ($s['headline'] ?? '')),
                        'subtitle' => trim((string) ($s['subtitle'] ?? '')),
                        'cta_label' => trim((string) ($s['cta_label'] ?? 'Jelajahi Kelas')),
                        'cta_url' => SecurityHelper::sanitizeUrl((string) ($s['cta_url'] ?? '#katalog-kursus'), '#katalog-kursus', true),
                        'secondary_label' => trim((string) ($s['secondary_label'] ?? '')),
                        'secondary_url' => SecurityHelper::sanitizeUrl((string) ($s['secondary_url'] ?? ''), '', true),
                        'image' => $image,
                    ];
                }
                if (!empty($slides)) {
                    $content['slides'] = $slides;
                }
                break;

            case 'onboarding_banner':
                $content = [
                    'headline' => trim((string) $request->input('headline', $content['headline'])),
                    'subtitle' => trim((string) $request->input('subtitle', $content['subtitle'])),
                    'cta_guest' => trim((string) $request->input('cta_guest', $content['cta_guest'])),
                    'cta_auth' => trim((string) $request->input('cta_auth', $content['cta_auth'])),
                    'image' => $this->handleUploadOrFallback($request, 'image_file', (string) $request->input('image', $content['image'])),
                ];
                break;

            case 'what_you_learn':
                $content['heading'] = trim((string) $request->input('heading', $content['heading']));
                $content['subtitle'] = trim((string) $request->input('subtitle', $content['subtitle']));
                $webinars = [];
                foreach ($request->input('webinars', []) as $idx => $w) {
                    if (trim((string) ($w['title'] ?? '')) === '') {
                        continue;
                    }
                    $webinars[] = [
                        'schedule' => trim((string) ($w['schedule'] ?? '')),
                        'discount_badge' => trim((string) ($w['discount_badge'] ?? 'DISKON 100%')),
                        'category' => trim((string) ($w['category'] ?? 'Bahasa • Live Webinar')),
                        'title' => trim((string) ($w['title'] ?? '')),
                        'tutor' => trim((string) ($w['tutor'] ?? 'Instruktur Ahli')),
                        'tutor_initials' => strtoupper(substr(trim((string) ($w['tutor'] ?? 'IA')), 0, 2)),
                        'original_price' => trim((string) ($w['original_price'] ?? 'Rp 259.000')),
                        'price_label' => trim((string) ($w['price_label'] ?? 'GRATIS')),
                        'cta_label' => trim((string) ($w['cta_label'] ?? 'Ikut Gratis')),
                        'image' => $this->handleUploadOrFallback($request, "webinars.{$idx}.image_file", (string) ($w['image'] ?? 'assets/fkksm/_1795405504399_1756706515.png')),
                        'is_live_soon' => !empty($w['is_live_soon']),
                    ];
                }
                if (!empty($webinars)) {
                    $content['webinars'] = $webinars;
                }
                break;

            case 'course_categories':
                $content['heading'] = trim((string) $request->input('heading', $content['heading']));
                $content['subtitle'] = trim((string) $request->input('subtitle', $content['subtitle']));
                $items = [];
                foreach ($request->input('items', []) as $item) {
                    if (trim((string) ($item['name'] ?? '')) === '') {
                        continue;
                    }
                    $items[] = [
                        'name' => trim((string) $item['name']),
                        'category_param' => trim((string) ($item['category_param'] ?? $item['name'])),
                        'theme' => trim((string) ($item['theme'] ?? 'sky')),
                        'icon_key' => trim((string) ($item['icon_key'] ?? 'language')),
                    ];
                }
                if (!empty($items)) {
                    $content['items'] = $items;
                }
                break;

            case 'kids_teens_programs':
                $content['heading'] = trim((string) $request->input('heading', $content['heading']));
                $content['subtitle'] = trim((string) $request->input('subtitle', $content['subtitle']));
                $progs = [];
                foreach ($request->input('programs', []) as $idx => $p) {
                    if (trim((string) ($p['title'] ?? '')) === '') {
                        continue;
                    }
                    $progs[] = [
                        'title' => trim((string) $p['title']),
                        'mode_badge' => trim((string) ($p['mode_badge'] ?? 'Online')),
                        'mode_theme' => trim((string) ($p['mode_theme'] ?? 'blue')),
                        'meta' => trim((string) ($p['meta'] ?? '')),
                        'description' => trim((string) ($p['description'] ?? '')),
                        'image' => $this->handleUploadOrFallback($request, "programs.{$idx}.image_file", (string) ($p['image'] ?? 'assets/fkksm/lang-program-thumb-cnext.webp')),
                        'cta_label' => trim((string) ($p['cta_label'] ?? 'Lihat Detail')),
                        'cta_url' => SecurityHelper::sanitizeUrl((string) ($p['cta_url'] ?? '/lms/courses'), '/lms/courses', true),
                    ];
                }
                if (!empty($progs)) {
                    $content['programs'] = $progs;
                }
                break;

            case 'voucher_banner':
                $content = [
                    'heading' => trim((string) $request->input('heading', $content['heading'])),
                    'description' => trim((string) $request->input('description', $content['description'])),
                    'cta_label' => trim((string) $request->input('cta_label', $content['cta_label'])),
                    'image' => $this->handleUploadOrFallback($request, 'image_file', (string) $request->input('image', $content['image'])),
                ];
                break;

            case 'bestseller_courses':
            case 'prakerja_section':
                $content['heading'] = trim((string) $request->input('heading', $content['heading']));
                $content['subtitle'] = trim((string) $request->input('subtitle', $content['subtitle']));
                if ($sectionKey === 'prakerja_section') {
                    $content['banner_heading'] = trim((string) $request->input('banner_heading', $content['banner_heading']));
                    $content['banner_description'] = trim((string) $request->input('banner_description', $content['banner_description']));
                }
                $items = [];
                foreach ($request->input('items', []) as $idx => $it) {
                    if (trim((string) ($it['title'] ?? '')) === '') {
                        continue;
                    }
                    $badges = [];
                    $allowedIcons = array_column(Course::availableSkillBadgeIcons(), 'icon');
                    foreach (($it['badges'] ?? []) as $b) {
                        $iconName = basename(trim((string) ($b['icon'] ?? '1765179223375_166389665.png')));
                        $badgeTitle = trim((string) ($b['name'] ?? ''));
                        if ($badgeTitle !== '' && in_array($iconName, $allowedIcons, true)) {
                            $badges[] = [
                                'icon' => $iconName,
                                'name' => mb_substr($badgeTitle, 0, 120),
                            ];
                        }
                    }
                    $items[] = [
                        'title' => trim((string) $it['title']),
                        'category' => trim((string) ($it['category'] ?? 'Umum')),
                        'rating' => trim((string) ($it['rating'] ?? '4.9')),
                        'reviews' => trim((string) ($it['reviews'] ?? '(100)')),
                        'price' => trim((string) ($it['price'] ?? 'Rp 500.000')),
                        'thumbnail' => $this->handleUploadOrFallback($request, "items.{$idx}.image_file", (string) ($it['thumbnail'] ?? 'assets/fkksm/_1795405504399_1756706515.png')),
                        'badges' => $badges,
                    ];
                }
                if (!empty($items)) {
                    $content['items'] = $items;
                }
                break;

            case 'job_vacancies':
                $content['heading'] = trim((string) $request->input('heading', $content['heading']));
                $content['subtitle'] = trim((string) $request->input('subtitle', $content['subtitle']));
                $jobs = [];
                foreach ($request->input('jobs', []) as $j) {
                    if (trim((string) ($j['title'] ?? '')) === '') {
                        continue;
                    }
                    $comp = trim((string) ($j['company'] ?? 'Perusahaan MM2100'));
                    $jobs[] = [
                        'title' => trim((string) $j['title']),
                        'company' => $comp,
                        'initial' => strtoupper(substr(trim((string) ($j['initial'] ?? $comp)), 0, 2)),
                        'type' => trim((string) ($j['type'] ?? 'Full-Time')),
                        'location' => trim((string) ($j['location'] ?? 'Bekasi, Indonesia')),
                        'time' => trim((string) ($j['time'] ?? 'Baru saja')),
                    ];
                }
                if (!empty($jobs)) {
                    $content['jobs'] = $jobs;
                }
                break;

            case 'b2b_corporate':
                $content = [
                    'badge' => trim((string) $request->input('badge', $content['badge'])),
                    'headline' => trim((string) $request->input('headline', $content['headline'])),
                    'description' => trim((string) $request->input('description', $content['description'])),
                    'cta_label' => trim((string) $request->input('cta_label', $content['cta_label'])),
                    'phone' => trim((string) $request->input('phone', $content['phone'])),
                    'email' => trim((string) $request->input('email', $content['email'])),
                ];
                break;

            case 'testimonials':
                $content['badge'] = trim((string) $request->input('badge', $content['badge']));
                $content['heading'] = trim((string) $request->input('heading', $content['heading']));
                $content['subtitle'] = trim((string) $request->input('subtitle', $content['subtitle']));
                $items = [];
                foreach ($request->input('items', []) as $t) {
                    if (trim((string) ($t['quote'] ?? '')) === '') {
                        continue;
                    }
                    $name = trim((string) ($t['name'] ?? 'Alumni FKKSM'));
                    $items[] = [
                        'quote' => trim((string) $t['quote']),
                        'name' => $name,
                        'role' => trim((string) ($t['role'] ?? 'Praktisi Industri MM2100')),
                        'initials' => strtoupper(substr(trim((string) ($t['initials'] ?? $name)), 0, 2)),
                    ];
                }
                if (!empty($items)) {
                    $content['items'] = $items;
                }
                break;

            case 'faq_items':
                $content['badge'] = trim((string) $request->input('badge', $content['badge']));
                $content['heading'] = trim((string) $request->input('heading', $content['heading']));
                $content['subtitle'] = trim((string) $request->input('subtitle', $content['subtitle']));
                $items = [];
                foreach ($request->input('items', []) as $f) {
                    if (trim((string) ($f['question'] ?? '')) === '') {
                        continue;
                    }
                    $items[] = [
                        'question' => trim((string) $f['question']),
                        'answer' => trim((string) ($f['answer'] ?? '')),
                    ];
                }
                if (!empty($items)) {
                    $content['items'] = $items;
                }
                break;

            case 'seo_content':
                $content = [
                    'heading' => trim((string) $request->input('heading', $content['heading'])),
                    'paragraph_1' => trim((string) $request->input('paragraph_1', $content['paragraph_1'])),
                    'paragraph_2' => trim((string) $request->input('paragraph_2', $content['paragraph_2'])),
                ];
                break;
        }

        LandingSection::saveSection($sectionKey, $title, $isActive, $content, Auth::id());

        ActivityLog::record(Auth::id(), 'landing_cms_updated', "Memperbarui konten Landing Page section '{$sectionKey}'.");

        return redirect()->route('lms.landing-cms.index', ['section' => $sectionKey])
            ->with('success', "Konten halaman Home section '{$title}' berhasil diperbarui dan langsung aktif di website utama.");
    }

    public function reset(string $sectionKey): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $defaults = LandingSection::defaults();
        abort_unless(isset($defaults[$sectionKey]), 404);

        $def = $defaults[$sectionKey];
        LandingSection::saveSection($sectionKey, $def['title'], $def['is_active'], $def['content'], Auth::id());

        ActivityLog::record(Auth::id(), 'landing_cms_reset', "Mereset konten Landing Page section '{$sectionKey}' ke setelan bawaan.");

        return redirect()->route('lms.landing-cms.index', ['section' => $sectionKey])
            ->with('success', "Section '{$def['title']}' berhasil dikembalikan ke konten default.");
    }

    private function handleUploadOrFallback(Request $request, string $fileField, string $fallback): string
    {
        if ($request->hasFile($fileField)) {
            $stored = SecurityHelper::storeSafeImage($request->file($fileField), 'landing');
            if ($stored) {
                return $stored;
            }
        }
        return SecurityHelper::sanitizeUrl($fallback, 'assets/fkksm/_1795405504399_1756706515.png', true);
    }
}

