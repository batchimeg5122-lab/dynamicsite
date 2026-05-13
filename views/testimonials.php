<?php
$testimonials=$conn->query("SELECT * FROM testimonials WHERE 1=1 ORDER BY is_featured DESC, id DESC");
?>
<div class="page-header bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Хэрэглэгчийн сэтгэгдэл</h1>
        <p class="lead">Манай үйлчлүүлэгчид юу хэлдэг вэ</p>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <?php while($t=$testimonials->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm <?=$t['is_featured']?'border-warning':''?>">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <?php if($t['client_photo']): ?>
                                    <img src="<?=UPLOAD_URL.$t['client_photo']?>" class="rounded-circle me-3" style="width:60px;height:60px;object-fit:cover">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width:60px;height:60px">
                                        <i class="bi bi-person fs-3"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h6 class="mb-0"><?=htmlspecialchars($t['client_name'])?></h6>
                                    <small class="text-muted"><?=$t['client_position']?><br><?=$t['client_company']?></small>
                                </div>
                            </div>
                            <div class="text-warning mb-2">
                                <?php for($i=0;$i<$t['rating'];$i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                            </div>
                            <p class="text-muted"><i class="bi bi-quote"></i> <?=htmlspecialchars($t['testimonial'])?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
