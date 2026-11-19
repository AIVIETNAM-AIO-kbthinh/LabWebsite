<?php $lang = current_lang(); ?>
<section class="page-hero">
    <div class="container">
        <div class="crumbs">Home / Contact</div>
        <h1 data-i18n="contact.title">Contact Us</h1>
        <p class="subtitle" data-i18n="contact.subtitle">We'd love to hear from you</p>
    </div>
</section>

<section class="section section-contact" style="padding-top:50px">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info" data-aos="fade-right">
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h4><?= $lang==='vi' ? 'Địa chỉ' : 'Address' ?></h4>
                        <p><?= e(setting($lang==='vi' ? 'contact_address_vi' : 'contact_address_en')) ?></p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h4>Email</h4>
                        <p><a href="mailto:<?= e(setting('contact_email')) ?>"><?= e(setting('contact_email')) ?></a></p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <h4><?= $lang==='vi' ? 'Điện thoại' : 'Phone' ?></h4>
                        <p><?= e(setting('contact_phone')) ?></p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="far fa-clock"></i></div>
                    <div>
                        <h4><?= $lang==='vi' ? 'Giờ làm việc' : 'Working Hours' ?></h4>
                        <p><?= e(setting($lang==='vi' ? 'contact_hours_vi' : 'contact_hours_en')) ?></p>
                    </div>
                </div>
            </div>

            <div class="contact-form-wrapper" data-aos="fade-left">
                <form class="contact-form" method="post" action="<?= url('/contact') ?>">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <input type="text" id="contactName" name="name" required>
                        <label data-i18n="contact.name">Your Name</label>
                        <span class="form-line"></span>
                    </div>
                    <div class="form-group">
                        <input type="email" id="contactEmail" name="email" required>
                        <label data-i18n="contact.email">Your Email</label>
                        <span class="form-line"></span>
                    </div>
                    <div class="form-group">
                        <input type="text" id="contactSubject" name="subject" required>
                        <label data-i18n="contact.subject">Subject</label>
                        <span class="form-line"></span>
                    </div>
                    <div class="form-group">
                        <textarea id="contactMessage" name="message" rows="5" required></textarea>
                        <label data-i18n="contact.message">Your Message</label>
                        <span class="form-line"></span>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <span data-i18n="contact.send">Send Message</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
