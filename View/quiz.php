<?php 
require_once __DIR__ . '/../Model/db.php';
session_start();

$questions = [
    ["q" => "Kem thường được làm từ nguyên liệu chính nào?", "opts" => ["A. Bột mì", "B. Sữa", "C. Gạo", "D. Nước mắm"], "ans" => "B"],
    ["q" => "Kem ốc quế có đặc điểm gì nổi bật?", "opts" => ["A. Có hình dạng tròn", "B. Được đựng trong bánh ốc quế", "C. Ăn bằng thìa", "D. Không đường"], "ans" => "B"],
    ["q" => "Kem que khác kem ốc quế ở điểm nào?", "opts" => ["A. Hình dạng", "B. Cách ăn", "C. Có que cầm", "D. Tất cả các ý trên"], "ans" => "D"],
    ["q" => "Kem thường được ăn nhiều nhất vào mùa nào?", "opts" => ["A. Mùa xuân", "B. Mùa hạ", "C. Mùa thu", "D. Mùa đông"], "ans" => "B"],
    ["q" => "Loại kem nào thường được làm từ trái cây xay nhuyễn và nước?", "opts" => ["A. Kem sữa", "B. Kem que", "C. Kem sorbet", "D. Kem tươi"], "ans" => "C"]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_ans = $_POST['answers'] ?? [];
    $score = 0;
    foreach ($questions as $i => $q) {
        if (($user_ans[$i] ?? '') === $q['ans']) $score++;
    }

    $coupon_id = 0;
    $msg = "Bạn đã đúng $score/" . count($questions) . " câu.";

    if ($score == 5) $coupon_id = 4;
    elseif ($score == 4) $coupon_id = 3;
    elseif ($score == 3) $coupon_id = 2;
    elseif ($score == 2) $coupon_id = 1;

    if ($coupon_id > 0) {
        $sql = "SELECT code, discount_percent FROM coupons WHERE id = $coupon_id";
        $res = $conn->query($sql);
        $data = $res->fetch_assoc();
        if ($data) {
            $_SESSION['quiz_bonus'] = $data;
            $_SESSION['quiz_msg'] = "Chúc mừng! $msg";
        }
    } else {
        $_SESSION['quiz_fail'] = "$msg Chúc bạn may mắn lần sau!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        body { background: white; padding: 20px; overflow: hidden; }
        .btn-check:checked + .btn-outline-primary { background-color: #ff85a2 !important; border-color: #ff85a2 !important; color: white; }
        .btn-outline-primary { border-color: #ffb7b2; color: #ff85a2; }
    </style>
</head>
<body>
    <form method="POST" id="quizForm">
        <?php foreach ($questions as $i => $q): ?>
            <div class="step" id="step-<?= $i ?>" style="<?= $i > 0 ? 'display:none' : '' ?>">
                <h5 class="mb-4 text-secondary animate__animated animate__fadeInDown">Câu <?= $i+1 ?>: <?= $q['q'] ?></h5>
                <div class="row g-3">
                    <?php foreach ($q['opts'] as $opt): $v = substr($opt, 0, 1); ?>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="answers[<?= $i ?>]" id="i<?= $i.$v ?>" value="<?= $v ?>" onclick="goNext(<?= $i ?>)">
                            <label class="btn btn-outline-primary w-100 py-3" for="i<?= $i.$v ?>"><?= $opt ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </form>

    <script>
        function goNext(curr) {
            const currDiv = document.getElementById('step-' + curr);
            const nextDiv = document.getElementById('step-' + (curr + 1));
            currDiv.classList.add('animate__animated', 'animate__fadeOutLeft');
            setTimeout(() => {
                currDiv.style.display = 'none';
                if (nextDiv) {
                    nextDiv.style.display = 'block';
                    nextDiv.classList.add('animate__animated', 'animate__fadeInRight');
                } else {
                    Swal.fire({ title: 'Đang chấm điểm...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                    document.getElementById('quizForm').submit();
                }
            }, 400);
        }
    </script>

    <?php if (isset($_SESSION['quiz_bonus'])): ?>
    <script>
        confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });
        Swal.fire({
            title: 'THẮNG RỒI!',
            html: '<?= $_SESSION['quiz_msg'] ?><br><br>Mã quà tặng: <b style="font-size: 24px; color: #ff85a2;"><?= $_SESSION['quiz_bonus']['code'] ?></b><br>Giảm <?= $_SESSION['quiz_bonus']['discount_percent'] ?>%',
            icon: 'success'
        });
    </script>
    <?php unset($_SESSION['quiz_bonus'], $_SESSION['quiz_msg']); endif; ?>

    <?php if (isset($_SESSION['quiz_fail'])): ?>
    <script>
        Swal.fire({ title: 'Kết quả', text: '<?= $_SESSION['quiz_fail'] ?>', icon: 'info' });
    </script>
    <?php unset($_SESSION['quiz_fail']); endif; ?>
</body>
</html>