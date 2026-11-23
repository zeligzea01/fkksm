// ── FKKSM script.js ─────────────────────────────────────────

document.addEventListener("DOMContentLoaded", () => {

  /* ── Mobile nav toggle ─────────────────────────────────── */
  const toggle = document.querySelector(".menu-toggle");
  const nav = document.getElementById("site-menu");
  const menuWrap = toggle?.closest(".nav-menu");

  function closeMenu() {
    if (!toggle || !nav || !menuWrap) return;
    nav.hidden = true;
    menuWrap.classList.remove("is-open");
    toggle.setAttribute("aria-expanded", "false");
  }

  if (toggle && nav && menuWrap) {
    toggle.addEventListener("click", () => {
      const isOpen = nav.hidden;
      nav.hidden = !isOpen;
      menuWrap.classList.toggle("is-open", isOpen);
      toggle.setAttribute("aria-expanded", String(isOpen));
    });
    nav.querySelectorAll("a").forEach(link => link.addEventListener("click", closeMenu));
    document.addEventListener("click", event => {
      if (!menuWrap.contains(event.target)) closeMenu();
    });
    document.addEventListener("keydown", event => {
      if (event.key === "Escape") {
        closeMenu();
        toggle.focus();
      }
    });
  }

  /* Language and appearance controls */
  const languageToggle = document.getElementById("languageToggle");
  const themeToggle = document.getElementById("themeToggle");
  const englishCopy = {
    ".topline-inner span:first-child": '<i class="status-dot"></i> FOR PEOPLE BEHIND INDUSTRY',
    ".topline-inner span:last-child": "CONNECT · SHARE · GROW",
    ".main-nav a": ["HOME", "ABOUT US", "PROGRAMS", "NEWS", "EVENTS", "CONTACT"],
    ".header-brand-text small": "community",
    ".member-button": "MEMBERS",
    ".hero-card-head > span:first-child": "UPCOMING SUMMIT 2026",
    ".ticker-label": "● LATEST",
    ".ticker-content": "New Member Registration Is Open — Join 1,200+ industry professionals by May 31 &nbsp;|&nbsp; MediaByte: Smart Factory Advertising Trends, Q2 2026 &nbsp;|&nbsp; Regional Industry Collaboration Forum: Jakarta, June 20, 2026 &nbsp;|&nbsp; Next → FKKSM Summit 2026 at JIExpo, October 14–16 &nbsp;|&nbsp; New Member Registration Is Open — Join 1,200+ industry professionals by May 31 &nbsp;|&nbsp; MediaByte: Smart Factory Advertising Trends, Q2 2026 &nbsp;|&nbsp; Regional Industry Collaboration Forum: Jakarta, June 20, 2026 &nbsp;|&nbsp; Next → FKKSM Summit 2026 at JIExpo, October 14–16",
    ".hero-tag": '<span class="tag-dot"></span> INDONESIAN INDUSTRY TRANSFORMATION &amp; COLLABORATION',
    ".hero h1": "Information and synergy,<br>all in <span>one place.</span>",
    ".hero-description": "A home for industry representatives, experts, educational institutions, and policymakers to advance technology and national competitiveness with FKKSM.",
    ".hero-actions .btn-primary": "Explore FKKSM Programs →",
    ".hero-actions .btn-secondary": "View Member Directory",
    ".hero-meta span": ["Recognized by the Ministry of Industry", "National-standard data management"],
    ".pill-muted": "Limited seats",
    ".event-location": "<span>📍</span> Jakarta International Expo (JIExpo)",
    ".hero-card h3": "FKKSM National Conference &amp; Summit 2026",
    ".hero-card > p": "Our annual gathering brings together more than 1,200 delegates across manufacturing, technology, and industrial policy.",
    ".event-footer small": "Event dates",
    ".event-footer strong": "October 14–16, 2026",
    ".event-footer .btn-small": "Register as a delegate →",
    ".stat-card:nth-child(1) p": "Active Corporate<br>Member Companies",
    ".stat-card:nth-child(2) p": "Programs<br>Underway in 2026",
    ".stat-card:nth-child(3) p": "University<br>Consortium Partners",
    ".stat-card:nth-child(4) p": "Regional Offices<br>Across Indonesia",
    ".donut-chart small": "SYNERGY",
    ".legend-title": "ECOSYSTEM MIX",
    ".legend li": [
      '<span class="swatch purple"></span> Manufacturing <strong>40%</strong>',
      '<span class="swatch teal"></span> Technology &amp; AI <strong>30%</strong>',
      '<span class="swatch aqua"></span> Academia / Research <strong>20%</strong>',
      '<span class="swatch gray"></span> Logistics &amp; Partners <strong>10%</strong>'
    ],
    ".activity-badge-title": "FKKSM COMMUNITY",
    ".activity-badge-sub": "MM2100 OFFICE",
    ".activity-title": "FKKSM 2026<br>ACTIVITY HIGHLIGHTS",
    ".quote-inner blockquote": "&ldquo;The industries of tomorrow are built through a connected, trusted ecosystem—not by any one organization alone.&rdquo;",
    ".author-text small": "Chair of the Industrial Communication &amp; Collaboration Forum (FKKSM)",
    ".program-header .eyebrow": '<span class="eyebrow-line"></span> OUR PROGRAM PILLARS',
    ".program-title": "Programs shaping<br>the future of collaboration",
    ".program-desc": "Our strategies create meaningful impact and strengthen people’s capabilities across Indonesia’s industrial ecosystem.",
    ".program-see-all": "View all programs →",
    ".program-card-tag": ["DIGITAL ENABLEMENT", "PEOPLE &amp; COMPETENCY", "AUTOMATION REVIEW", "STRATEGIC PARTNERSHIPS", "POLICY &amp; GOVERNANCE", "INNOVATION ECOSYSTEM"],
    ".program-card h3": ["Digital Enablement &amp; Industrial AI", "People &amp; Competency Development", "Automation &amp; Smart Systems", "Best Practice Sharing &amp; Benchmarking", "Standards, Policy &amp; Compliance", "Technology Leaders Community"],
    ".program-card p": [
      "Accelerating digital technology and AI adoption across manufacturing operations.",
      "Building workforce capabilities for industry needs and professional certification.",
      "Supporting benchmarking and automation across Industry 4.0 environments.",
      "Sharing best practices from international institutions, researchers, and industry leaders.",
      "Supporting fair, sustainable labor regulation and industrial policy.",
      "Connecting technology leaders across Indonesia to accelerate innovation."
    ],
    ".program-list li": [
      "AI workshops for supervisors", "Factory digitalization roadmaps",
      "Certified internship programs", "Cross-sector mentoring forums",
      "Smart factory visits", "Industrial automation panels",
      "Monthly discussion forums", "Annual industry reports",
      "HR regulation briefings", "Workforce policy advocacy",
      "CTO/CIO roundtable series", "Annual innovation challenge"
    ],
    ".program-more": "50+ more projects →",
    ".events-section .eyebrow": '<span class="eyebrow-line"></span> COMMUNITY EVENTS &amp; SEMINARS',
    ".events-section .content-title": "Meet us at an upcoming event",
    ".section-link": ["View all dates ↗", "Browse all stories ↗"],
    ".event-type": ["National", "Community", "Workshop"],
    ".event-date": ["<b>07</b> NOV", "<b>21</b> NOV", "<b>02</b> DEC"],
    ".event-card h3": ["FKKSM Tech Industry Expo 2026", "Industrial AI Regulation &amp; Cybersecurity", "Accelerating Manufacturing Productivity"],
    ".event-card p": [
      "A technology and industry forum bringing together innovators, practitioners, and manufacturing leaders.",
      "A practical discussion on trust and security standards for industrial AI adoption.",
      "Explore lean and automation approaches that put people at the center of productivity."
    ],
    ".event-card-meta": ["09:00–16:00 · Jakarta, Indonesia", "13:00–15:30 · Online &amp; Bekasi", "09:00–12:00 · MM2100, Cikarang"],
    ".event-register": ["Register now →", "Register now →", "Register now →"],
    ".stories-section .eyebrow": '<span class="eyebrow-line"></span> PUBLICATIONS &amp; INSIGHTS',
    ".stories-section .content-title": "Notes from the industry",
    ".story-category": ["INDUSTRY COLLABORATION", "LEADERSHIP", "DIGITAL TRANSFORMATION"],
    ".story-card h3": ["FKKSM signs MoU to strengthen the industry ecosystem", "Manufacturing Industry Leaders Dialogue", "National Industry Readiness Report, Q3 2026"],
    ".story-card p": [
      "A new partnership expands opportunities for industry knowledge-sharing and collaboration.",
      "Practitioners share strategies for productive, resilient workplaces.",
      "Recent findings on technology adoption and future skills needs."
    ],
    ".story-card a": ["Read the story →", "Read the story →", "Read the story →"],
    ".join-card .eyebrow": "JOIN THE COMMUNITY",
    ".join-card h2": "Be part of the transformation<br>of Indonesia’s industry.",
    ".join-card > p:not(.eyebrow)": "Grow your network, share proven practices, and connect with Indonesia’s industrial leaders.",
    ".join-actions .btn-light": "Apply for membership ↗",
    ".join-actions .btn-glass": "Contact the Secretariat",
    ".partners-label": "BUILDING INDONESIA’S INDUSTRIAL ECOSYSTEM TOGETHER",
    ".contact-copy .eyebrow": '<span class="eyebrow-line"></span> CENTRAL SECRETARIAT LOCATION',
    ".contact-copy h2": "Visit the FKKSM Secretariat",
    ".contact-intro": "Our central hub for partnerships, membership support, and national industry programs.",
    ".contact-detail:nth-of-type(1) b": "Office address",
    ".contact-detail:nth-of-type(1) div > span": "Kemitraan Industri Building, Floor 4,<br>Jl. Jend. Gatot Subroto Kav. 52,<br>South Jakarta 12950",
    ".contact-detail:nth-of-type(2) b": "Secretariat hours",
    ".contact-detail:nth-of-type(2) div > span": "Monday–Friday: 8:30 AM–5:00 PM WIB",
    ".contact-actions .contact-button": ["Open in Google Maps ↗", "Get directions"],
    ".map-open": "Open in Maps ↗",
    ".footer-description": "A home for industry collaboration, digital transformation, and stronger national workforce capabilities.",
    ".footer-column h2": ["About FKKSM", "Programs &amp; Services", "Membership"],
    ".footer-brand-name small": "INDUSTRY COMMUNICATION<br>&amp; COLLABORATION FORUM",
    ".footer-column:nth-child(2) a": ["Vision &amp; Mission", "Our History", "Leadership", "Governance"],
    ".footer-column:nth-child(3) a": ["Professional Certification", "Industry Transformation", "Research &amp; Advocacy", "Industry Dialogues"],
    ".footer-column:nth-child(4) a": ["Member Directory", "How to Join", "Member Benefits", "Member Login"],
    ".footer-bottom span": "© 2026 FKKSM. All rights reserved.",
    ".footer-bottom a": "Terms &amp; Conditions",
    ".org-chart-overline": "ORGANIZATION STRUCTURE",
    ".org-chart-period": "TERM 2026–2031",
    ".org-title-patron": "PATRON",
    ".org-title-advisor": "ADVISORS",
    ".org-title-board": "EXECUTIVE BOARD",
    ".org-title-experts": "EXPERT TEAM",
    ".org-title-vice1": "VICE CHAIR I",
    ".org-title-vice2": "VICE CHAIR II",
    ".org-title-vice3": "VICE CHAIR III",
    ".org-title-public": "PUBLIC RELATIONS",
    ".org-title-legal": "LEGAL",
    ".org-title-industrial": "INDUSTRIAL RELATIONS",
    ".org-title-training": "TRAINING",
    ".org-title-activities": "ANNUAL ACTIVITIES",
    ".org-title-comdev": "COMMUNITY DEVELOPMENT",
    ".org-title-organization": "ORGANIZATION",
    ".org-focus-legal-hi": "Legal &amp; Industrial Relations",
    ".org-focus-training-activities": "Training &amp; Activities",
    ".org-focus-comdev-organization": "Community Development &amp; Organization",
    ".org-label-chair": "Chair",
    ".org-label-members": "Members",
    ".org-label-secretary": "Secretary",
    ".org-label-treasurer": "Treasurer",
    ".org-label-admin": "Administrator",
    ".directory-eyebrow": '<span class="eyebrow-line"></span> MEMBER DIRECTORY',
    ".directory-title": "FKKSM Member Directory",
    ".directory-description": "Meet the leadership structure and teams driving industry collaboration at FKKSM MM2100.",
    ".directory-section-label": "Organization Structure",
    ".directory-period": "Term 2026–2031",
    ".directory-caption": "FKKSM MM2100 organization structure · Term 2026–2031",
    ".directory-back-label": "Back to Home",
  };



  const indonesianCopy = {
    ".topline-inner span:first-child": '<i class="status-dot"></i> UNTUK MEREKA YANG MENGGERAKKAN INDUSTRI',
    ".topline-inner span:last-child": "TERHUBUNG · BERBAGI · BERTUMBUH",
    ".main-nav a": ["BERANDA", "TENTANG KAMI", "PROGRAM", "BERITA", "ACARA", "KONTAK"],
    ".header-brand-text small": "komunitas",
    ".member-button": "ANGGOTA",
    ".ticker-label": "● TERBARU",
    ".hero-card-head > span:first-child": "SUMMIT MENDATANG 2026",
    ".event-location": "<span>📍</span> Jakarta International Expo (JIExpo)",
    ".hero-card h3": "Konferensi Nasional &amp; Summit FKKSM 2026",
    ".activity-badge-title": "KOMUNITAS FKKSM",
    ".activity-badge-sub": "KANTOR MM2100",
    ".activity-title": "RANGKAIAN<br>KEGIATAN<br>FKKSM 2026",
    ".donut-chart small": "SINERGI",
    ".program-card-tag": ["PEMBERDAYAAN DIGITAL", "SDM &amp; KOMPETENSI", "TINJAUAN OTOMASI", "KEMITRAAN STRATEGIS", "KEBIJAKAN &amp; TATA KELOLA", "EKOSISTEM INOVASI"],
    ".program-card h3": ["Pemberdayaan Digital &amp; AI Industri", "Pengembangan SDM &amp; Kompetensi", "Otomasi &amp; Sistem Cerdas", "Berbagi Praktik Terbaik &amp; Benchmarking", "Standar, Kebijakan &amp; Kepatuhan", "Komunitas Pemimpin Teknologi"],
    ".program-card p": [
      "Mendorong penerapan teknologi digital dan AI di lini produksi manufaktur.",
      "Mengembangkan kompetensi SDM sesuai kebutuhan industri dan sertifikasi profesional.",
      "Mendukung benchmarking dan penerapan otomasi di lingkungan Industri 4.0.",
      "Berbagi praktik terbaik dari lembaga internasional, peneliti, dan pelaku industri.",
      "Mendukung regulasi ketenagakerjaan dan kebijakan industri yang adil dan berkelanjutan.",
      "Menghubungkan pemimpin teknologi di Indonesia untuk mempercepat inovasi."
    ],
    ".program-list li": [
      "Lokakarya AI untuk supervisor", "Peta jalan digitalisasi pabrik",
      "Program magang bersertifikat", "Forum mentoring lintas sektor",
      "Kunjungan ke pabrik pintar", "Panel otomasi industri",
      "Forum diskusi bulanan", "Laporan industri tahunan",
      "Sosialisasi peraturan HRD", "Advokasi kebijakan ketenagakerjaan",
      "Forum CTO/CIO", "Tantangan inovasi tahunan"
    ],
    ".program-more": "50+ Program lainnya →",
    ".partners-label": "BERSAMA MEMBANGUN EKOSISTEM INDUSTRI INDONESIA",
    ".org-chart-overline": "STRUKTUR KEPENGURUSAN",
    ".org-chart-period": "PERIODE 2026–2031",
    ".org-title-patron": "PEMBINA",
    ".org-title-advisor": "PENASIHAT",
    ".org-title-board": "PENGURUS HARIAN",
    ".org-title-experts": "TIM PAKAR",
    ".org-title-vice1": "WAKIL KETUA I",
    ".org-title-vice2": "WAKIL KETUA II",
    ".org-title-vice3": "WAKIL KETUA III",
    ".org-title-public": "HUBUNGAN MASYARAKAT",
    ".org-title-legal": "HUKUM",
    ".org-title-industrial": "HUBUNGAN INDUSTRIAL",
    ".org-title-training": "PELATIHAN",
    ".org-title-activities": "KEGIATAN TAHUNAN",
    ".org-title-comdev": "PENGEMBANGAN KOMUNITAS",
    ".org-title-organization": "ORGANISASI",
    ".org-focus-legal-hi": "Bidang Hukum &amp; Hubungan Industrial",
    ".org-focus-training-activities": "Bidang Pelatihan &amp; Kegiatan",
    ".org-focus-comdev-organization": "Bidang Pengembangan Komunitas &amp; Organisasi",
    ".org-label-chair": "Ketua",
    ".org-label-members": "Anggota",
    ".org-label-secretary": "Sekretaris",
    ".org-label-treasurer": "Bendahara",
    ".org-label-admin": "Administrator",
    ".directory-eyebrow": '<span class="eyebrow-line"></span> DIREKTORI ANGGOTA',
    ".directory-title": "Direktori Anggota FKKSM",
    ".directory-description": "Kenali struktur kepengurusan dan tim yang menggerakkan kolaborasi industri FKKSM MM2100.",
    ".directory-section-label": "Struktur Kepengurusan",
    ".directory-period": "Periode 2026–2031",
    ".directory-caption": "Struktur kepengurusan FKKSM MM2100 · Periode 2026–2031",
    ".directory-back-label": "Kembali ke Beranda",
  };
  const japaneseCopy = {
    ".topline-inner span:first-child": '<i class="status-dot"></i> 産業を支える人々のために',
    ".topline-inner span:last-child": "つながる · 共有する · 成長する",
    ".main-nav a": ["ホーム", "FKKSMについて", "プログラム", "ニュース", "イベント", "お問い合わせ"],
    ".header-brand-text small": "コミュニティ",
    ".hero-card-head > span:first-child": "開催予定サミット 2026",
    ".ticker-label": "● 最新情報",
    ".member-button": "会員ページ",
    ".ticker-content": "新規会員登録受付中 — 1,200社以上の産業コミュニティに参加しませんか &nbsp;|&nbsp; MediaByte：スマートファクトリー広告の最新動向 2026年第2四半期 &nbsp;|&nbsp; 地域産業連携フォーラム：ジャカルタ、2026年6月20日 &nbsp;|&nbsp; 次回 → FKKSMサミット2026、JIExpo、10月14〜16日 &nbsp;|&nbsp; 新規会員登録受付中 — 1,200社以上の産業コミュニティに参加しませんか &nbsp;|&nbsp; MediaByte：スマートファクトリー広告の最新動向 2026年第2四半期 &nbsp;|&nbsp; 地域産業連携フォーラム：ジャカルタ、2026年6月20日 &nbsp;|&nbsp; 次回 → FKKSMサミット2026、JIExpo、10月14〜16日",
    ".hero-tag": '<span class="tag-dot"></span> インドネシア産業の変革と連携',
    ".hero h1": "情報と連携を<br><span>ひとつの場所に。</span>",
    ".hero-description": "産業界の代表者、専門家、教育機関、政策担当者が集まり、FKKSMとともに技術の進歩と国際競争力の向上を目指すプラットフォームです。",
    ".hero-actions .btn-primary": "FKKSMのプログラムを見る →",
    ".hero-actions .btn-secondary": "会員一覧を見る",
    ".hero-meta span": ["産業省に認定", "国家基準に準拠したデータ管理"],
    ".pill-muted": "定員に限りがあります",
    ".event-location": "<span>📍</span> ジャカルタ国際展示場（JIExpo）",
    ".hero-card h3": "FKKSM全国会議・サミット 2026",
    ".hero-card > p": "製造、テクノロジー、産業政策の分野から1,200名以上の代表者が集う年次イベントです。",
    ".event-footer small": "開催日",
    ".event-footer strong": "2026年10月14〜16日",
    ".event-footer .btn-small": "参加登録 →",
    ".stat-card:nth-child(1) p": "法人会員企業<br>活動中",
    ".stat-card:nth-child(2) p": "2026年の<br>実施プログラム",
    ".stat-card:nth-child(3) p": "大学コンソーシアム<br>パートナー",
    ".stat-card:nth-child(4) p": "インドネシア各地の<br>地域拠点",
    ".donut-chart small": "連携",
    ".legend-title": "エコシステム構成",
    ".legend li": [
      '<span class="swatch purple"></span> 製造業 <strong>40%</strong>',
      '<span class="swatch teal"></span> テクノロジー・AI <strong>30%</strong>',
      '<span class="swatch aqua"></span> 学術・研究 <strong>20%</strong>',
      '<span class="swatch gray"></span> 物流・パートナー <strong>10%</strong>'
    ],
    ".activity-badge-title": "FKKSMコミュニティ",
    ".activity-badge-sub": "MM2100オフィス",
    ".activity-title": "FKKSM 2026<br>活動ハイライト",
    ".quote-inner blockquote": "「明日の産業は、一つの組織だけでなく、つながりと信頼に支えられたエコシステムによって築かれます。」",
    ".author-text small": "産業コミュニケーション・連携フォーラム（FKKSM）会長",
    ".program-header .eyebrow": '<span class="eyebrow-line"></span> 主要プログラム',
    ".program-title": "連携の未来をつくる<br>プログラム",
    ".program-desc": "インドネシアの産業エコシステム全体で人材の力を高め、確かな成果につなげる取り組みです。",
    ".program-see-all": "すべてのプログラム →",
    ".program-card-tag": ["デジタル活用", "人材・能力開発", "自動化レビュー", "戦略的パートナーシップ", "政策・ガバナンス", "イノベーション"],
    ".program-card h3": ["デジタル活用と産業AI", "人材・能力開発", "自動化とスマートシステム", "ベストプラクティス共有・ベンチマーク", "基準・政策・コンプライアンス", "テクノロジーリーダーコミュニティ"],
    ".program-card p": [
      "製造現場におけるデジタル技術とAIの導入を促進します。",
      "産業界のニーズに応える人材育成と専門資格取得を支援します。",
      "インダストリー4.0に向けたベンチマークと自動化を支援します。",
      "海外機関、研究者、産業界の先進事例を共有します。",
      "公正で持続可能な労働規制と産業政策を支援します。",
      "インドネシア全土の技術リーダーをつなぎ、イノベーションを加速します。"
    ],
    ".program-list li": [
      "管理者向けAIワークショップ", "工場デジタル化ロードマップ",
      "認定インターンシップ", "業界横断メンタリング",
      "スマートファクトリー見学", "産業自動化パネル",
      "月例ディスカッション", "年次産業レポート",
      "人事規則の説明会", "労働政策への提言",
      "CTO/CIOラウンドテーブル", "年次イノベーション企画"
    ],
    ".program-more": "その他50以上のプロジェクト →",
    ".events-section .eyebrow": '<span class="eyebrow-line"></span> コミュニティイベント・セミナー',
    ".events-section .content-title": "次回イベントでお会いしましょう",
    ".section-link": ["日程一覧 ↗", "記事一覧 ↗"],
    ".event-type": ["全国", "コミュニティ", "ワークショップ"],
    ".event-date": ["<b>07</b> 11月", "<b>21</b> 11月", "<b>02</b> 12月"],
    ".event-card h3": ["FKKSMテック産業エキスポ 2026", "産業AI規制とサイバーセキュリティ", "製造業の生産性向上"],
    ".event-card p": [
      "イノベーター、実務家、製造業のリーダーが集う産業技術フォーラムです。",
      "産業分野でのAI導入に必要な信頼とセキュリティ基準を議論します。",
      "人を中心に据えたリーン生産と自動化の手法を紹介します。"
    ],
    ".event-card-meta": ["09:00〜16:00 · ジャカルタ", "13:00〜15:30 · オンライン・ブカシ", "09:00〜12:00 · MM2100、チカラン"],
    ".event-register": ["参加登録 →", "参加登録 →", "参加登録 →"],
    ".stories-section .eyebrow": '<span class="eyebrow-line"></span> 出版物・インサイト',
    ".stories-section .content-title": "産業現場からのレポート",
    ".story-category": ["産業連携", "リーダーシップ", "デジタル変革"],
    ".story-card h3": ["産業エコシステム強化に向けたMoUを締結", "製造業リーダー対話フォーラム", "国家産業の準備状況レポート 2026年第3四半期"],
    ".story-card p": [
      "新たな連携により、産業界の知識共有と協働の機会が広がります。",
      "生産的でしなやかな職場づくりの戦略を共有します。",
      "技術導入と将来必要となるスキルに関する最新の調査結果です。"
    ],
    ".story-card a": ["記事を読む →", "記事を読む →", "記事を読む →"],
    ".join-card .eyebrow": "コミュニティに参加",
    ".join-card h2": "インドネシア産業の変革を<br>ともに進めましょう。",
    ".join-card > p:not(.eyebrow)": "ネットワークを広げ、実践知を共有し、インドネシアの産業リーダーとつながりましょう。",
    ".join-actions .btn-light": "会員に申し込む ↗",
    ".join-actions .btn-glass": "事務局に問い合わせる",
    ".partners-label": "ともに築くインドネシアの産業エコシステム",
    ".contact-copy .eyebrow": '<span class="eyebrow-line"></span> 中央事務局所在地',
    ".contact-copy h2": "FKKSM事務局へのアクセス",
    ".contact-intro": "パートナーシップ、会員相談、産業プログラムを支える中央拠点です。",
    ".contact-detail:nth-of-type(1) b": "事務局住所",
    ".contact-detail:nth-of-type(1) div > span": "Kemitraan Industri Building 4階,<br>Jl. Jend. Gatot Subroto Kav. 52,<br>南ジャカルタ 12950",
    ".contact-detail:nth-of-type(2) b": "事務局営業時間",
    ".contact-detail:nth-of-type(2) div > span": "月〜金 8:30〜17:00（WIB）",
    ".contact-actions .contact-button": ["Googleマップで開く ↗", "ルートを検索"],
    ".map-open": "地図で開く ↗",
    ".footer-description": "産業連携、デジタル変革、そして国内人材の能力向上を支えるコミュニティです。",
    ".footer-column h2": ["FKKSMについて", "プログラム・サービス", "会員案内"],
    ".footer-brand-name small": "産業コミュニケーション<br>・協働フォーラム",
    ".footer-column:nth-child(2) a": ["理念・使命", "沿革", "役員紹介", "組織ガバナンス"],
    ".footer-column:nth-child(3) a": ["専門資格研修", "産業変革", "調査・提言", "産業対話フォーラム"],
    ".footer-column:nth-child(4) a": ["会員一覧", "入会方法", "会員特典", "会員ログイン"],
    ".footer-bottom span": "© 2026 FKKSM. 無断転載を禁じます。",
    ".footer-bottom a": "利用規約",
    ".org-chart-overline": "組織体制",
    ".org-chart-period": "任期：2026～2031年",
    ".org-title-patron": "顧問",
    ".org-title-advisor": "相談役",
    ".org-title-board": "執行部",
    ".org-title-experts": "専門家チーム",
    ".org-title-vice1": "副会長Ⅰ",
    ".org-title-vice2": "副会長Ⅱ",
    ".org-title-vice3": "副会長Ⅲ",
    ".org-title-public": "広報",
    ".org-title-legal": "法務",
    ".org-title-industrial": "労使関係",
    ".org-title-training": "研修",
    ".org-title-activities": "月次・年次活動",
    ".org-title-comdev": "コミュニティ開発",
    ".org-title-organization": "組織",
    ".org-focus-legal-hi": "法務・労使関係",
    ".org-focus-training-activities": "研修・諸活動",
    ".org-focus-comdev-organization": "コミュニティ開発・組織運営",
    ".org-label-chair": "会長",
    ".org-label-members": "メンバー",
    ".org-label-secretary": "事務局長",
    ".org-label-treasurer": "会計",
    ".org-label-admin": "管理担当",
    ".directory-eyebrow": '<span class="eyebrow-line"></span> 役員・チーム紹介',
    ".directory-title": "FKKSMの組織・メンバー紹介",
    ".directory-description": "FKKSM MM2100の運営体制と、産業連携を支える各チームをご紹介します。",
    ".directory-section-label": "組織体制",
    ".directory-period": "任期：2026～2031年",
    ".directory-caption": "FKKSM MM2100の組織体制（任期：2026～2031年）",
    ".directory-back-label": "トップページへ戻る",
  };
  const originalMarkup = new Map();
  const allLanguageSelectors = new Set([
    ...Object.keys(englishCopy),
    ...Object.keys(indonesianCopy),
    ...Object.keys(japaneseCopy)
  ]);
  allLanguageSelectors.forEach(selector => {
    document.querySelectorAll(selector).forEach(element => {
      if (!originalMarkup.has(element)) originalMarkup.set(element, element.innerHTML);
    });
  });

  function applyLanguage(language) {
    originalMarkup.forEach((markup, element) => { element.innerHTML = markup; });
    const selectedCopy = language === "en" ? englishCopy : language === "ja" ? japaneseCopy : indonesianCopy;
    Object.entries(selectedCopy).forEach(([selector, value]) => {
      const elements = [...document.querySelectorAll(selector)];
      elements.forEach((element, index) => {
        const translation = Array.isArray(value) ? value[index] : value;
        if (translation !== undefined) element.innerHTML = translation;
      });
    });
    document.documentElement.lang = language;
    document.title = language === "en"
      ? "FKKSM MM2100 | Industrial HR Community"
      : language === "ja"
        ? "FKKSM MM2100 | 産業人材コミュニティ"
        : "FKKSM MM2100 | Komunitas Industri";
    const description = document.querySelector('meta[name="description"]');
    if (description) description.content = language === "en"
      ? "An industrial human resources community connecting people and organizations."
      : language === "ja"
        ? "産業界の人材と組織をつなぐコミュニティです。"
        : "Komunitas sumber daya manusia industri yang menghubungkan orang dan organisasi.";
    const languageNames = { id: "Indonesia", en: "English", ja: "日本語" };
    if (languageToggle) {
      languageToggle.textContent = language === "id" ? "ID" : language === "en" ? "EN" : "日本語";
      const nextLabel = language === "ja"
        ? "インドネシア語に切り替え"
        : language === "en"
          ? "Switch to Japanese"
          : "Ganti ke bahasa Inggris";
      languageToggle.setAttribute("aria-label", nextLabel);
      languageToggle.title = languageNames[language] + " · " + nextLabel;
    }
    const mainNav = document.querySelector(".main-nav");
    if (mainNav) mainNav.setAttribute("aria-label", language === "ja" ? "メインナビゲーション" : language === "en" ? "Main navigation" : "Navigasi utama");
    const headerBrand = document.querySelector(".header-brand");
    if (headerBrand) headerBrand.setAttribute("aria-label", language === "ja" ? "FKKSM MM2100のホーム" : language === "en" ? "FKKSM MM2100 home" : "Beranda FKKSM MM2100");
    const headerLogo = document.querySelector(".header-logo");
    if (headerLogo) headerLogo.alt = language === "ja" ? "FKKSMのロゴ" : language === "en" ? "FKKSM logo" : "Logo FKKSM";
    const lmsLink = document.querySelector(".lms-button");
    if (lmsLink) lmsLink.setAttribute("aria-label", language === "ja" ? "FKKSM LMSを開く" : language === "en" ? "Open FKKSM LMS" : "Buka LMS FKKSM");
    const menuToggle = document.querySelector(".menu-toggle");
    if (menuToggle) menuToggle.setAttribute("aria-label", language === "ja" ? "ナビゲーションメニューを開く" : language === "en" ? "Open navigation menu" : "Buka menu navigasi");
    localStorage.setItem("fkksm-language", language);
    updateThemeLabel();
  }

  function updateThemeLabel() {
    if (!themeToggle) return;
    const dark = document.body.classList.contains("dark-mode");
    const language = document.documentElement.lang;
    themeToggle.textContent = language === "ja"
      ? (dark ? "ライトモード" : "ダークモード")
      : language === "en"
        ? (dark ? "Light mode" : "Dark mode")
        : (dark ? "Mode terang" : "Mode gelap");
    themeToggle.setAttribute("aria-pressed", String(dark));
  }

  function applyTheme(theme) {
    document.body.classList.toggle("dark-mode", theme === "dark");
    localStorage.setItem("fkksm-theme", theme);
    updateThemeLabel();
  }

  if (languageToggle) languageToggle.addEventListener("click", () => {
    const languages = ["id", "en", "ja"];
    const current = languages.indexOf(document.documentElement.lang);
    applyLanguage(languages[(current + 1) % languages.length]);
  });
  if (themeToggle) themeToggle.addEventListener("click", () => {
    applyTheme(document.body.classList.contains("dark-mode") ? "light" : "dark");
  });
  applyLanguage(localStorage.getItem("fkksm-language") || "id");
  applyTheme(localStorage.getItem("fkksm-theme") || "light");

  /* ── Hero Card Slideshow ───────────────────────────────── */
  let heroIndex = 0;
  const heroSlidesEl = document.getElementById("heroSlides");
  const heroDots = document.querySelectorAll("#heroSliderDots .dot");
  const heroTotal = heroSlidesEl ? heroSlidesEl.querySelectorAll(".hero-slide").length : 0;
  const heroPrev = document.querySelector(".hero-slide-btn.prev");
  const heroNext = document.querySelector(".hero-slide-btn.next");

  function updateHero() {
    if (!heroSlidesEl || !heroTotal) return;
    heroSlidesEl.dataset.activeSlide = String(heroIndex);
    heroDots.forEach((dot, index) => dot.classList.toggle("active", index === heroIndex));
  }

  function advanceHero(direction) {
    if (heroTotal < 2) return;
    heroIndex = (heroIndex + direction + heroTotal) % heroTotal;
    updateHero();
    resetHeroAuto();
  }

  function selectHero(index) {
    if (!heroTotal) return;
    heroIndex = (index + heroTotal) % heroTotal;
    updateHero();
    resetHeroAuto();
  }

  let heroTimer = null;
  function resetHeroAuto() {
    clearInterval(heroTimer);
    heroTimer = heroTotal > 1 ? setInterval(() => advanceHero(1), 4000) : null;
  }

  if (heroPrev) heroPrev.addEventListener("click", () => advanceHero(-1));
  if (heroNext) heroNext.addEventListener("click", () => advanceHero(1));
  heroDots.forEach((dot, index) => dot.addEventListener("click", () => selectHero(index)));
  updateHero();
  resetHeroAuto();

  /* ── Activity Band: Thumbnail + Main Slideshow ─────────── */
  let actIdx = 0;
  const mainSlides = document.querySelectorAll(".activity-main-slide");
  const thumbs = document.querySelectorAll(".act-thumb");
  const counterEl = document.getElementById("actCounter");
  const actTotal = mainSlides.length;

  function updateActivity(idx) {
    actIdx = (idx + actTotal) % actTotal;

    // swap main slide (opacity fade — NO page scroll triggered)
    mainSlides.forEach((s, i) => s.classList.toggle("active", i === actIdx));

    // highlight active thumb
    thumbs.forEach((t, i) => t.classList.toggle("active", i === actIdx));

    // update counter
    if (counterEl) counterEl.textContent = `${actIdx + 1} / ${actTotal}`;

    // scroll ONLY within .act-thumbs container, never the page
    const activeThumb = thumbs[actIdx];
    if (activeThumb) {
      const container = activeThumb.closest(".act-thumbs");
      if (container) {
        const thumbLeft = activeThumb.offsetLeft;
        const thumbRight = thumbLeft + activeThumb.offsetWidth;
        const contLeft = container.scrollLeft;
        const contRight = contLeft + container.offsetWidth;
        if (thumbLeft < contLeft) {
          container.scrollLeft = thumbLeft - 8;
        } else if (thumbRight > contRight) {
          container.scrollLeft = thumbRight - container.offsetWidth + 8;
        }
      }
    }
  }

  let actTimer = null;

  function resetActivityAuto() {
    clearInterval(actTimer);
    actTimer = actTotal > 1 ? setInterval(() => updateActivity(actIdx + 1), 3500) : null;
  }

  thumbs.forEach((thumb, index) => {
    thumb.addEventListener("click", () => {
      updateActivity(index);
      resetActivityAuto();
    });
  });
  const activityPrev = document.querySelector(".act-nav-prev");
  const activityNext = document.querySelector(".act-nav-next");
  if (activityPrev) activityPrev.addEventListener("click", () => {
    updateActivity(actIdx - 1);
    resetActivityAuto();
  });
  if (activityNext) activityNext.addEventListener("click", () => {
    updateActivity(actIdx + 1);
    resetActivityAuto();
  });

  // Auto-advance every 3.5 s
  resetActivityAuto();
  updateActivity(0);

});
