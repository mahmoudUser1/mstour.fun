// Manus File Transfer - Main Scripts

document.addEventListener('DOMContentLoaded', function() {
    console.log('Project Initialized');
    
    // إغلاق التنبيهات تلقائياً
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    });
});

// وظيفة نسخ الروابط
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('تم نسخ الرابط بنجاح');
    });
}
