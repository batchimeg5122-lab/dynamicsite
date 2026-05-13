<?php
$faqs=$conn->query("SELECT * FROM faqs WHERE is_active=1 ORDER BY order_num ASC");
$categories=$conn->query("SELECT DISTINCT category FROM faqs WHERE is_active=1");
?>
<div class="page-header bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Түгээмэл асуултууд</h1>
        <p class="lead">Таны асуултын хариу энд байж магадгүй</p>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <?php $i=0; while($f=$faqs->fetch_assoc()): $i++; ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?=$i>1?'collapsed':''?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?=$f['id']?>">
                                    <strong><?=htmlspecialchars($f['question'])?></strong>
                                    <?php if($f['category']): ?><span class="badge bg-primary ms-2"><?=$f['category']?></span><?php endif; ?>
                                </button>
                            </h2>
                            <div id="faq<?=$f['id']?>" class="accordion-collapse collapse <?=$i==1?'show':''?>" data-bs-parent="#faqAccordion">
                                <div class="accordion-body"><?=nl2br(htmlspecialchars($f['answer']))?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</section>