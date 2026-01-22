<div class="container mt-4">
    <div class="card comment-box">
        <div class="card-body">

            <!-- تعليق -->
            <div class="d-flex mb-4 comment-item">
                <img src="https://i.pravatar.cc/50"
                     class="rounded-circle me-3 comment-avatar">

                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0 fw-bold">Ahmed Ali</h6>
                        <small class="text-muted">
                            <i class="fa-regular fa-clock"></i> قبل 5 دقائق
                        </small>
                    </div>
                    <p class="mb-0 text-muted">
                        هذا مثال على تعليق المستخدم داخل صندوق التعليقات.
                    </p>
                </div>
            </div>

            <!-- تعليق آخر -->
            <div class="d-flex mb-4 comment-item">
                <img src="https://i.pravatar.cc/51"
                     class="rounded-circle me-3 comment-avatar">

                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0 fw-bold">Sara Mohamed</h6>
                        <small class="text-muted">
                            <i class="fa-regular fa-clock"></i> منذ ساعة
                        </small>
                    </div>
                    <p class="mb-0 text-muted">
                        تعليق آخر لتجربة التصميم باستخدام Bootstrap.
                    </p>
                </div>
            </div>

            <!-- إضافة تعليق -->
            <div class="border-top pt-3">
                <div class="d-flex">
                    <img src="https://i.pravatar.cc/52"
                         class="rounded-circle me-3 comment-avatar">

                    <textarea class="form-control"
                              rows="2"
                              placeholder="اكتب تعليقك..."></textarea>
                </div>

                <div class="text-end mt-2">
                    <button class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-paper-plane"></i> إرسال
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
<style>
    .comment-box {
        max-width: 700px;
        margin: auto;
    }

    .comment-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
    }

    .comment-item:not(:last-child) {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }

</style>
