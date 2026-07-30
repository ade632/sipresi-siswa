/**
 * Menghasilkan fingerprint device yang cukup stabil untuk keperluan
 * whitelist perangkat piket (bukan untuk identifikasi individu pengguna).
 * Kombinasi: user agent, resolusi layar, timezone, dan canvas rendering hash.
 *
 * PENTING: fingerprint ini disimpan di localStorage supaya tetap sama
 * antar kunjungan pada device yang sama -- device baru akan menghasilkan
 * fingerprint baru dan HARUS didaftarkan ulang oleh Admin.
 */
export async function getDeviceFingerprint() {
    const cached = localStorage.getItem('sipresi_device_fingerprint');
    if (cached) return cached;

    const raw = [
        navigator.userAgent,
        navigator.language,
        screen.width + 'x' + screen.height,
        Intl.DateTimeFormat().resolvedOptions().timeZone,
        canvasHash(),
    ].join('|');

    const encoded = new TextEncoder().encode(raw);
    const hashBuffer = await crypto.subtle.digest('SHA-256', encoded);
    const hashHex = Array.from(new Uint8Array(hashBuffer))
        .map((b) => b.toString(16).padStart(2, '0'))
        .join('');

    localStorage.setItem('sipresi_device_fingerprint', hashHex);

    return hashHex;
}

function canvasHash() {
    try {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        ctx.textBaseline = 'top';
        ctx.font = '14px Arial';
        ctx.fillText('SIPRESI-SISWA-fingerprint', 2, 2);

        return canvas.toDataURL();
    } catch (e) {
        return 'no-canvas';
    }
}
