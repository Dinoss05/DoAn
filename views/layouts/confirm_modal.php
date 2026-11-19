<!-- Modal Xác nhận kiểu Information -->
<div class="modal fade" id="infoConfirmModal" tabindex="-1" aria-labelledby="infoConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <div class="d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.082.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                    </svg>
                    <h5 class="modal-title mb-0" id="infoConfirmModalLabel">Thông báo xác nhận</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body py-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="text-primary flex-shrink-0 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                    </div>
                    <p class="mb-0 fs-6" id="infoConfirmMessage">Bạn có chắc chắn muốn thực hiện thao tác này?</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="infoConfirmBtn" class="btn btn-primary fw-semibold">Xác nhận</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('infoConfirmModal');
    if (!modalEl || typeof bootstrap === 'undefined') return;

    const infoModal = new bootstrap.Modal(modalEl);
    const msgEl = document.getElementById('infoConfirmMessage');
    const confirmBtn = document.getElementById('infoConfirmBtn');

    document.querySelectorAll('[data-info-confirm]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            const message = this.getAttribute('data-info-confirm') || 'Bạn có chắc chắn muốn tiếp tục?';
            const title = this.getAttribute('data-info-title') || 'Thông báo xác nhận';

            document.getElementById('infoConfirmModalLabel').textContent = title;
            msgEl.textContent = message;
            confirmBtn.setAttribute('href', href);
            infoModal.show();
        });
    });
});
</script>