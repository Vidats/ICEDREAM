<?php
include 'header.php';
if (!isset($_GET['order_id'])) {
    echo "<script>window.location='my_order.php';</script>";
    exit();
}
$order_id = $_GET['order_id'];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-white" style="background-color: #ff85a2;">
                    <h4 class="mb-0">Đánh giá đơn hàng #<?= $order_id ?></h4>
                </div>
                <div class="card-body">
                    <form action="../Controller/Feedback.php" method="POST">
                        <input type="hidden" name="order_id" value="<?= $order_id ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mức độ hài lòng:</label>
                            <div class="rating-css">
                                <div class="star-icon">
                                    <input type="radio" name="rating" value="1" id="rating1">
                                    <label for="rating1" class="fa fa-star"></label>
                                    <input type="radio" name="rating" value="2" id="rating2">
                                    <label for="rating2" class="fa fa-star"></label>
                                    <input type="radio" name="rating" value="3" id="rating3">
                                    <label for="rating3" class="fa fa-star"></label>
                                    <input type="radio" name="rating" value="4" id="rating4">
                                    <label for="rating4" class="fa fa-star"></label>
                                    <input type="radio" name="rating" value="5" id="rating5" checked>
                                    <label for="rating5" class="fa fa-star"></label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label fw-bold">Nhận xét của bạn:</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Hãy chia sẻ cảm nhận về sản phẩm và dịch vụ..." required></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn text-white" style="background-color: #ff85a2;">Gửi đánh giá</button>
                            <a href="my_order.php" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rating-css div { color: #ffe400; font-size: 30px; font-family: sans-serif; font-weight: 800; text-align: center; text-transform: uppercase; padding: 20px 0; }
    .rating-css input { display: none; }
    .rating-css input + label { font-size: 40px; text-shadow: 1px 1px 0 #8f8420; cursor: pointer; }
    .rating-css input:checked + label ~ label { color: #b4b4b4; }
    .rating-css label:active { transform: scale(0.8); transition: 0.3s all; }
    /* Logic đảo ngược để ngôi sao sáng từ trái qua phải khi hover/check cần CSS phức tạp hơn, 
       đây là bản đơn giản: chọn số sao. */
</style>

<?php include 'footer.php'; ?>