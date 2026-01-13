<?php include 'header.php'; ?>

<main class="contact-page">
    <section class="contact-hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Kết Nối Với ICEDREAM</h1>
            <p class="lead">Chúng tôi luôn sẵn sàng lắng nghe những ngọt ngào từ bạn!</p>
        </div>
    </section>

    <section class="container py-5">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="contact-form-wrapper p-4 p-md-5 shadow rounded-4 bg-white">
                    <h2 class="section-title mb-4">Gửi tin nhắn cho chúng tôi</h2>
<form action="../Controller/process_contact.php" method="POST">                        <div class="mb-3">
                            <label class="form-label fw-600">Họ và tên</label>
                            <input type="text" name="name" class="form-control" placeholder="Nhập tên của bạn" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600">Lời nhắn</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="Bạn muốn nói gì với ICEDREAM?" required></textarea>
                        </div>
<button type="submit" name="send_contact" class="btn btn-hero w-100">Gửi Tin Nhắn Ngay</button>                 
   </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="contact-info mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-map-marker-alt contact-icon"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">Địa chỉ</h5>
                            <p class="mb-0 text-muted">123 Đường Kem Bơ, Thành phố Long Xuyên, An Giang</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-phone-alt contact-icon"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">Số điện thoại</h5>
                            <p class="mb-0 text-muted">0123 456 789</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-envelope contact-icon"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">Email</h5>
                            <p class="mb-0 text-muted">hello@icedream.com</p>
                        </div>
                    </div>
                </div>

                <div class="map-container shadow rounded-4 overflow-hidden">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3928.841454308143!2d105.7684266147424!3d10.029933692830634!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0895a51d60719%3A0x9d76b0035f6d5d3c!2zQ8O0bmcgVmnDqm4gTmluaCBLaeG7gXU!5e0!3m2!1svi!2svn!4v1650000000000!5m2!1svi!2svn" 
                        width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.querySelector('form').onsubmit = function() {
    const btn = this.querySelector('button[type="submit"]');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang gửi...';
    btn.disabled = true;
};
</script>


<?php include 'footer.php'; ?>