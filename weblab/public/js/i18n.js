// ============================================
// SONG NGỮ VIỆT - ANH
// ============================================
const translations = {
    en: {
        'nav.home': 'Home',
        'nav.about': 'About',
        'nav.research': 'Research',
        'nav.members': 'Members',
        'nav.projects': 'Projects',
        'nav.publications': 'Publications',
        'nav.news': 'News',
        'nav.gallery': 'Gallery',
        'nav.contact': 'Contact',


        'hero.title1': 'Intelligent System',
        'hero.title2': '& IoT Laboratory',
        'hero.description': 'Pioneering research in Artificial Intelligence, Internet of Things, Robotics, and Unmanned Aerial Vehicles. Where innovation meets education at VNU International School.',
        'hero.btn1': 'Explore More',
        'hero.btn2': 'Join Us',
        'hero.stat1': 'Members',
        'hero.stat2': 'Projects',
        'hero.stat3': 'Publications',
        'hero.stat4': 'Years',

        'about.tag': 'About Us',
        'about.title': 'Who We Are',
        'about.heading': 'Driving the Future of Intelligent Systems',
        'about.p1': 'ISI Lab (Intelligent System and IoT Laboratory) is a research lab at VNU International School, focused on advancing the frontier of Artificial Intelligence, Internet of Things, robotics, and unmanned aerial vehicles.',
        'about.p2': 'We bring together passionate students, dedicated lecturers, and industry experts to develop innovative solutions that solve real-world problems through interdisciplinary research and hands-on engineering.',
        'about.f1t': 'AI Research',
        'about.f1d': 'Cutting-edge machine learning',
        'about.f2t': 'Robotics',
        'about.f2d': 'Intelligent autonomous systems',
        'about.f3t': 'IoT Solutions',
        'about.f3d': 'Connected smart devices',
        'about.f4t': 'UAV Systems',
        'about.f4d': 'Drone control & navigation',

        'research.tag': 'Our Research',
        'research.title': 'Research Areas',
        'research.subtitle': 'Exploring the boundaries of technology and innovation',

        'members.tag': 'Our Team',
        'members.title': 'Meet the Team',
        'members.subtitle': 'Brilliant minds working together',
        'members.tab1': 'All',
        'members.tab2': 'Lab Leaders',
        'members.tab3': 'Lecturers',
        'members.tab4': 'Students',
        'members.tab5': 'Alumni',

        'projects.tag': 'Our Work',
        'projects.title': 'Featured Projects',
        'projects.subtitle': 'Innovation in action',

        'pub.tag': 'Research Output',
        'pub.title': 'Publications',
        'pub.subtitle': 'Our contributions to the scientific community',

        'news.tag': 'Latest Updates',
        'news.title': 'News & Activities',
        'news.subtitle': 'Stay updated with our latest activities',

        'gallery.tag': 'Photo Gallery',
        'gallery.title': 'Lab Moments',
        'gallery.subtitle': 'Capturing memorable moments',

        'contact.tag': 'Get In Touch',
        'contact.title': 'Contact Us',
        'contact.subtitle': "We'd love to hear from you",
        'contact.name': 'Your Name',
        'contact.email': 'Your Email',
        'contact.subject': 'Subject',
        'contact.message': 'Your Message',
        'contact.send': 'Send Message',

        'footer.about': 'Intelligent System and IoT Laboratory - Pioneering research in AI, IoT, Robotics and UAV systems.',
        'footer.links': 'Quick Links',
        'footer.explore': 'Explore',
        'footer.contact': 'Contact',
        'footer.rights': 'All rights reserved.',
        'footer.admin': 'Admin'
    },
    vi: {
        'nav.home': 'Trang chủ',
        'nav.about': 'Giới thiệu',
        'nav.research': 'Nghiên cứu',
        'nav.members': 'Thành viên',
        'nav.projects': 'Dự án',
        'nav.publications': 'Công bố',
        'nav.news': 'Tin tức',
        'nav.gallery': 'Thư viện',
        'nav.contact': 'Liên hệ',

        'hero.title1': 'Phòng thí nghiệm',
        'hero.title2': 'Hệ thống thông minh & IoT',
        'hero.description': 'Tiên phong nghiên cứu Trí tuệ nhân tạo, Internet vạn vật, Robot và Máy bay không người lái. Nơi đổi mới gặp gỡ giáo dục tại Khoa Quốc tế - ĐHQGHN.',
        'hero.btn1': 'Khám phá thêm',
        'hero.btn2': 'Tham gia',
        'hero.stat1': 'Thành viên',
        'hero.stat2': 'Dự án',
        'hero.stat3': 'Công bố',
        'hero.stat4': 'Năm',

        'about.tag': 'Về chúng tôi',
        'about.title': 'Chúng tôi là ai',
        'about.heading': 'Dẫn dắt tương lai của hệ thống thông minh',
        'about.p1': 'ISI Lab (Phòng thí nghiệm Hệ thống thông minh và IoT) là một phòng nghiên cứu tại Khoa Quốc tế - ĐHQGHN, tập trung vào việc đẩy mạnh ranh giới của Trí tuệ nhân tạo, IoT, robot và UAV.',
        'about.p2': 'Chúng tôi tập hợp những sinh viên đam mê, giảng viên tận tâm và các chuyên gia trong ngành để phát triển các giải pháp sáng tạo giải quyết các vấn đề thực tế.',
        'about.f1t': 'Nghiên cứu AI',
        'about.f1d': 'Học máy hiện đại',
        'about.f2t': 'Robot',
        'about.f2d': 'Hệ thống tự hành thông minh',
        'about.f3t': 'Giải pháp IoT',
        'about.f3d': 'Thiết bị thông minh kết nối',
        'about.f4t': 'Hệ thống UAV',
        'about.f4d': 'Điều khiển & dẫn đường drone',

        'research.tag': 'Nghiên cứu',
        'research.title': 'Lĩnh vực nghiên cứu',
        'research.subtitle': 'Khám phá ranh giới của công nghệ và đổi mới',

        'members.tag': 'Đội ngũ',
        'members.title': 'Gặp gỡ thành viên',
        'members.subtitle': 'Những bộ óc tài năng cùng làm việc',
        'members.tab1': 'Tất cả',
        'members.tab2': 'Trưởng lab',
        'members.tab3': 'Giảng viên',
        'members.tab4': 'Sinh viên',
        'members.tab5': 'Cựu thành viên',

        'projects.tag': 'Công việc',
        'projects.title': 'Dự án nổi bật',
        'projects.subtitle': 'Đổi mới trong hành động',

        'pub.tag': 'Kết quả nghiên cứu',
        'pub.title': 'Công bố khoa học',
        'pub.subtitle': 'Đóng góp của chúng tôi cho cộng đồng khoa học',

        'news.tag': 'Cập nhật mới nhất',
        'news.title': 'Tin tức & Hoạt động',
        'news.subtitle': 'Cập nhật các hoạt động mới nhất',

        'gallery.tag': 'Thư viện ảnh',
        'gallery.title': 'Khoảnh khắc tại Lab',
        'gallery.subtitle': 'Ghi lại những khoảnh khắc đáng nhớ',

        'contact.tag': 'Kết nối',
        'contact.title': 'Liên hệ',
        'contact.subtitle': 'Chúng tôi rất mong nhận được tin từ bạn',
        'contact.name': 'Họ và tên',
        'contact.email': 'Email',
        'contact.subject': 'Tiêu đề',
        'contact.message': 'Nội dung',
        'contact.send': 'Gửi tin nhắn',

        'footer.about': 'Phòng thí nghiệm Hệ thống thông minh và IoT - Tiên phong nghiên cứu AI, IoT, Robot và UAV.',
        'footer.links': 'Liên kết nhanh',
        'footer.explore': 'Khám phá',
        'footer.contact': 'Liên hệ',
        'footer.rights': 'Bảo lưu mọi quyền.',
        'footer.admin': 'Quản trị'
    }
};

function getCookie(name) {
    const cookies = document.cookie.split(';');

    for (const cookie of cookies) {
        const parts = cookie.trim().split('=');
        const key = parts.shift();

        if (key === name) {
            return decodeURIComponent(parts.join('='));
        }
    }

    return null;
}

function saveLanguage(lang) {
    localStorage.setItem('isilab-lang', lang);

    document.cookie =
        'isilab-lang=' + encodeURIComponent(lang) +
        '; path=/' +
        '; max-age=' + (60 * 60 * 24 * 365) +
        '; SameSite=Lax';
}

function validLanguage(lang) {
    return lang === 'en' || lang === 'vi';
}

const serverLanguage = validLanguage(window.ISILAB_LANG)
    ? window.ISILAB_LANG
    : document.documentElement.lang;

let currentLang = validLanguage(serverLanguage)
    ? serverLanguage
    : (validLanguage(getCookie('isilab-lang'))
        ? getCookie('isilab-lang')
        : (validLanguage(localStorage.getItem('isilab-lang'))
            ? localStorage.getItem('isilab-lang')
            : 'en'));

function applyTranslations() {
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');

        if (
            translations[currentLang] &&
            translations[currentLang][key]
        ) {
            el.textContent = translations[currentLang][key];
        }
    });

    const langBtn = document.querySelector('.lang-current');

    if (langBtn) {
        langBtn.textContent = currentLang === 'en' ? 'EN' : 'VI';
    }

    document.documentElement.lang = currentLang;

    window.dispatchEvent(
        new CustomEvent('langChanged', {
            detail: {
                lang: currentLang
            }
        })
    );
}

function toggleLanguage() {
    currentLang = currentLang === 'en' ? 'vi' : 'en';

    saveLanguage(currentLang);

    // Send the selected language in the next HTTP request so PHP can render
    // database content (title_vi, desc_vi, etc.) in the same language.
    const nextUrl = new URL(window.location.href);
    nextUrl.searchParams.set('lang', currentLang);
    window.location.href = nextUrl.toString();
}

window.getLang = () => currentLang;
window.toggleLanguage = toggleLanguage;
window.applyTranslations = applyTranslations;

document.addEventListener('DOMContentLoaded', () => {
    // PHP has already selected the authoritative language for this page.
    // Mirror it to browser storage without triggering another reload.
    if (
        getCookie('isilab-lang') !== currentLang ||
        localStorage.getItem('isilab-lang') !== currentLang
    ) {
        saveLanguage(currentLang);
    }

    applyTranslations();

    // Compatibility for installations that still use the old button markup.
    const btn = document.getElementById('langToggle');
    if (btn) btn.addEventListener('click', toggleLanguage);
});
