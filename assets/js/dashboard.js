// Pastikan data sudah tersedia
const data = window.dashboardData || {
    tesSelesai: 0,
    tesBerlangsung: 0,
    pembayaranLunas: 0,
    pembayaranPending: 0
};

// Warna tema
const warna = {
    selesai: '#10B981',
    berlangsung: '#3B82F6',
    lunas: '#6366F1',
    pending: '#F59E0B'
};

// Cek apakah elemen canvas ada
const canvas = document.getElementById('statusChart');
if (canvas) {
    const ctx = canvas.getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Tes Selesai', 'Tes Berlangsung', 'Pembayaran Lunas', 'Pembayaran Pending'],
            datasets: [{
                label: 'Jumlah',
                data: [
                    data.tesSelesai,
                    data.tesBerlangsung,
                    data.pembayaranLunas,
                    data.pembayaranPending
                ],
                backgroundColor: [
                    `${warna.selesai}CC`,
                    `${warna.berlangsung}CC`,
                    `${warna.lunas}CC`,
                    `${warna.pending}CC`
                ],
                borderColor: [warna.selesai, warna.berlangsung, warna.lunas, warna.pending],
                borderWidth: 2,
                borderRadius: 8,
                barThickness: 45
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1F2937',
                    titleColor: '#FFFFFF',
                    bodyColor: '#E5E7EB',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#F3F4F6',
                        borderDash: [4, 4]
                    },
                    ticks: {
                        precision: 0,
                        color: '#6B7280'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#4B5563' }
                }
            },
            animation: {
                duration: 1200,
                easing: 'easeOutCubic'
            }
        }
    });
}