<?php $lang = current_lang(); ?>
<section class="page-hero">
    <div class="container">
        <div class="crumbs">Home / About</div>
        <h1 data-i18n="about.title">Who We Are</h1>
        <p class="subtitle" data-i18n="about.subtitle"><?= $lang==='vi' ? 'Giới thiệu về ISI Lab' : 'Get to know ISI Lab' ?></p>
    </div>
</section>

<section class="section section-about" style="padding-top:60px">
    <div class="container">
        <div class="about-grid">
            <div class="about-text" data-aos="fade-right">
                <h3 data-i18n="about.heading">Driving the Future of Intelligent Systems</h3>
                <p data-i18n="about.p1">
                    ISI Lab (Intelligent System and IoT Laboratory) is a research lab at VNU International School,
                    focused on advancing the frontier of Artificial Intelligence, Internet of Things, robotics,
                    and unmanned aerial vehicles.
                </p>
                <p data-i18n="about.p2">
                    We bring together passionate students, dedicated lecturers, and industry experts to develop
                    innovative solutions that solve real-world problems through interdisciplinary research and
                    hands-on engineering.
                </p>

                <div class="about-features">
                    <div class="feature-item">
                        <i class="fas fa-brain"></i>
                        <div>
                            <h4 data-i18n="about.f1t">AI Research</h4>
                            <p data-i18n="about.f1d">Cutting-edge machine learning</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-robot"></i>
                        <div>
                            <h4 data-i18n="about.f2t">Robotics</h4>
                            <p data-i18n="about.f2d">Intelligent autonomous systems</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-network-wired"></i>
                        <div>
                            <h4 data-i18n="about.f3t">IoT Solutions</h4>
                            <p data-i18n="about.f3d">Connected smart devices</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-helicopter"></i>
                        <div>
                            <h4 data-i18n="about.f4t">UAV Systems</h4>
                            <p data-i18n="about.f4d">Drone control & navigation</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-visual" data-aos="fade-left">
                <div class="visual-card">
                    <div class="visual-grid"></div>
                    <div class="visual-circle"></div>
                    <div class="visual-icon"><i class="fas fa-microchip"></i></div>
                    <div class="visual-orbit orbit-1"><i class="fas fa-brain"></i></div>
                    <div class="visual-orbit orbit-2"><i class="fas fa-robot"></i></div>
                    <div class="visual-orbit orbit-3"><i class="fas fa-wifi"></i></div>
                    <div class="visual-orbit orbit-4"><i class="fas fa-code"></i></div>
                </div>
            </div>
        </div>
    </div>
</section>
