<div class="d-flex justify-content-center mt-4">
    <button type="button" class="btn btn-warning" id="liveAlertBtn">
    Jadwal
    </button>
</div>

<div class="d-flex justify-content-center mt-3">
    <div id="liveAlertPlaceholder" style="width: 50%;"></div>
</div>

<script>
    document.getElementById('liveAlertBtn').addEventListener('click', function () {
        document.getElementById('liveAlertPlaceholder').innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                Toko buka setiap hari dari jam 08:00 - 22:00.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    });
</script>
